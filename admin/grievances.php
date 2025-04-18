<?php
require_once '../config/db_config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Require admin
require_admin();

// Handle grievance deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $grievance_id = clean_input($_GET['id']);

    // Check if grievance exists
    $sql = "SELECT id FROM grievances WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $grievance_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        $_SESSION['error'] = "Grievance not found";
        redirect("grievances.php");
    }

    // Delete comments first
    $sql = "DELETE FROM comments WHERE grievance_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $grievance_id);
    mysqli_stmt_execute($stmt);

    // Delete grievance
    $sql = "DELETE FROM grievances WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $grievance_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Grievance deleted successfully";
    } else {
        $_SESSION['error'] = "Failed to delete grievance";
    }

    redirect("grievances.php");
}

// Handle bulk actions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bulk_action']) && isset($_POST['grievance_ids'])) {
    $bulk_action = clean_input($_POST['bulk_action']);
    $grievance_ids = $_POST['grievance_ids'];

    if (empty($grievance_ids)) {
        $_SESSION['error'] = "No grievances selected";
        redirect("grievances.php");
    }

    if ($bulk_action == 'delete') {
        // Delete comments first
        $ids_string = implode(',', array_map('intval', $grievance_ids));
        $sql = "DELETE FROM comments WHERE grievance_id IN ($ids_string)";
        mysqli_query($conn, $sql);

        // Delete grievances
        $sql = "DELETE FROM grievances WHERE id IN ($ids_string)";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success'] = count($grievance_ids) . " grievances deleted successfully";
        } else {
            $_SESSION['error'] = "Failed to delete grievances";
        }
    } elseif (in_array($bulk_action, ['pending', 'in_progress', 'resolved', 'rejected'])) {
        $status = $bulk_action;
        $user_id = $_SESSION["user_id"];
        $success_count = 0;

        foreach ($grievance_ids as $grievance_id) {
            $grievance_id = intval($grievance_id);

            // Update status
            $sql = "UPDATE grievances SET status = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $status, $grievance_id);

            if (mysqli_stmt_execute($stmt)) {
                $success_count++;

                // Add a comment about the status change
                $comment = "Status changed to " . get_formatted_status($status) . " by admin (bulk action).";
                $sql = "INSERT INTO comments (grievance_id, user_id, comment) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "iis", $grievance_id, $user_id, $comment);
                mysqli_stmt_execute($stmt);
            }
        }

        if ($success_count > 0) {
            $_SESSION['success'] = "$success_count grievances updated to " . get_formatted_status($status);
        } else {
            $_SESSION['error'] = "Failed to update grievances";
        }
    } else {
        $_SESSION['error'] = "Invalid bulk action";
    }

    redirect("grievances.php");
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? clean_input($_GET['status']) : '';
$category_filter = isset($_GET['category']) ? clean_input($_GET['category']) : '';
$search = isset($_GET['search']) ? clean_input($_GET['search']) : '';

// Build query
$sql = "SELECT g.*, c.name as category_name, u.username as submitted_by
        FROM grievances g
        JOIN categories c ON g.category_id = c.id
        JOIN users u ON g.user_id = u.id
        WHERE 1=1";

$params = [];
$types = "";

if (!empty($status_filter)) {
    $sql .= " AND g.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

if (!empty($category_filter)) {
    $sql .= " AND g.category_id = ?";
    $params[] = $category_filter;
    $types .= "i";
}

if (!empty($search)) {
    $sql .= " AND (g.title LIKE ? OR g.description LIKE ? OR u.username LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

$sql .= " ORDER BY g.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Get all categories for filter
$categories = get_all_categories();

// Get grievance counts by status
$status_counts = [
    'pending' => 0,
    'in_progress' => 0,
    'resolved' => 0,
    'rejected' => 0
];

$sql_count = "SELECT status, COUNT(*) as count FROM grievances GROUP BY status";
$result_count = mysqli_query($conn, $sql_count);

while ($row = mysqli_fetch_assoc($result_count)) {
    $status_counts[$row['status']] = $row['count'];
}

$total_grievances = array_sum($status_counts);

include 'admin_header.php';
?>

<div class="admin-page-header">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1><i class="fas fa-list me-2"></i>Grievance Management</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="dashboard.php" class="btn btn-glass"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card dashboard-stats admin-glass">
            <div class="stats-number"><?php echo $total_grievances; ?></div>
            <div class="stats-label">Total Grievances</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-stats admin-glass">
            <div class="stats-number text-warning"><?php echo $status_counts['pending']; ?></div>
            <div class="stats-label">Pending</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-stats admin-glass">
            <div class="stats-number text-info"><?php echo $status_counts['in_progress']; ?></div>
            <div class="stats-label">In Progress</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-stats admin-glass">
            <div class="stats-number text-success"><?php echo $status_counts['resolved']; ?></div>
            <div class="stats-label">Resolved</div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4 admin-glass">
    <div class="card-header bg-transparent text-white">
        <h5 class="mb-0">Filter Grievances</h5>
    </div>
    <div class="card-body">
        <form action="grievances.php" method="get" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="in_progress" <?php echo $status_filter == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="resolved" <?php echo $status_filter == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                    <option value="rejected" <?php echo $status_filter == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="category" class="form-label">Category</label>
                <select name="category" id="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $category_filter == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Search by title, description or username" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="grievances.php" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<form action="grievances.php" method="post">
    <div class="card mb-4 admin-glass">
        <div class="card-header bg-transparent text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">All Grievances</h4>
            <div class="bulk-actions">
                <select name="bulk_action" class="form-select form-select-sm d-inline-block w-auto me-2">
                    <option value="">Bulk Actions</option>
                    <option value="pending">Mark as Pending</option>
                    <option value="in_progress">Mark as In Progress</option>
                    <option value="resolved">Mark as Resolved</option>
                    <option value="rejected">Mark as Rejected</option>
                    <option value="delete">Delete</option>
                </select>
                <button type="submit" class="btn btn-sm btn-light" onclick="return confirm('Are you sure you want to perform this action on the selected grievances?')">Apply</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Submitted By</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><input type="checkbox" name="grievance_ids[]" value="<?php echo $row['id']; ?>" class="grievance-checkbox"></td>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['submitted_by']); ?></td>
                                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                    <td><span class="<?php echo get_status_badge_class($row['status']); ?>"><?php echo get_formatted_status($row['status']); ?></span></td>
                                    <td><?php echo format_date($row['created_at']); ?></td>
                                    <td>
                                        <a href="../grievance_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">View</a>
                                        <a href="grievances.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this grievance? This will also delete all comments.')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No grievances found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>

<script>
// Select all checkboxes
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.grievance-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});
</script>

<?php include 'admin_footer.php'; ?>
