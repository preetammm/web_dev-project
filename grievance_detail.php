<?php
require_once 'config/db_config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Require login
require_login();

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid grievance ID";
    redirect("dashboard.php");
}

$grievance_id = clean_input($_GET['id']);
$user_id = $_SESSION['user_id'];
$is_admin = is_admin();

// Get grievance details
$sql = "SELECT g.*, c.name as category_name, u.username as submitted_by
        FROM grievances g
        JOIN categories c ON g.category_id = c.id
        JOIN users u ON g.user_id = u.id
        WHERE g.id = ?";

if (!$is_admin) {
    // If not admin, only show user's own grievances
    $sql .= " AND g.user_id = ?";
}

$stmt = mysqli_prepare($conn, $sql);

if (!$is_admin) {
    mysqli_stmt_bind_param($stmt, "ii", $grievance_id, $user_id);
} else {
    mysqli_stmt_bind_param($stmt, "i", $grievance_id);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Grievance not found or you don't have permission to view it";
    redirect("dashboard.php");
}

$grievance = mysqli_fetch_assoc($result);

// Get comments
$sql = "SELECT c.*, u.username, u.role
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.grievance_id = ?
        ORDER BY c.created_at ASC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $grievance_id);
mysqli_stmt_execute($stmt);
$comments_result = mysqli_stmt_get_result($stmt);

include 'includes/header.php';
?>

<div class="row mb-3">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Grievance Details</li>
            </ol>
        </nav>
    </div>
</div>

<div class="grievance-detail-header">
    <div class="row">
        <div class="col-md-8">
            <h1><?php echo htmlspecialchars($grievance['title']); ?></h1>
            <div class="grievance-meta">
                <span class="me-3"><i class="fas fa-user me-1"></i> <?php echo htmlspecialchars($grievance['submitted_by']); ?></span>
                <span class="me-3"><i class="fas fa-calendar me-1"></i> <?php echo format_date($grievance['created_at']); ?></span>
                <span class="me-3"><i class="fas fa-tag me-1"></i> <?php echo htmlspecialchars($grievance['category_name']); ?></span>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <span class="<?php echo get_status_badge_class($grievance['status']); ?> p-2">
                <?php echo get_formatted_status($grievance['status']); ?>
            </span>
        </div>
    </div>
</div>

<?php if ($is_admin): ?>
<div class="admin-controls mb-4 glass">
    <h5 class="mb-3 text-white">Admin Controls</h5>
    <form action="admin/update_status.php" method="post" class="row g-3 mb-3">
        <input type="hidden" name="grievance_id" value="<?php echo $grievance_id; ?>">
        <div class="col-md-8">
            <select name="status" class="form-select">
                <option value="pending" <?php echo $grievance['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="in_progress" <?php echo $grievance['status'] == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                <option value="resolved" <?php echo $grievance['status'] == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                <option value="rejected" <?php echo $grievance['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">Update Status</button>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <a href="admin/delete_grievance.php?id=<?php echo $grievance_id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this grievance? This action cannot be undone.')"><i class="fas fa-trash me-2"></i>Delete Grievance</a>
            <a href="admin/grievances.php" class="btn btn-secondary ms-2"><i class="fas fa-list me-2"></i>All Grievances</a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Description</h5>
    </div>
    <div class="card-body">
        <p><?php echo nl2br(htmlspecialchars($grievance['description'])); ?></p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Comments</h5>
    </div>
    <div class="card-body">
        <?php if (mysqli_num_rows($comments_result) > 0): ?>
            <div class="comment-section">
                <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
                    <div class="comment">
                        <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                        <div class="comment-meta">
                            <span class="me-2">
                                <i class="fas fa-user me-1"></i>
                                <?php echo htmlspecialchars($comment['username']); ?>
                                <?php if ($comment['role'] == 'admin'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php endif; ?>
                            </span>
                            <span><i class="fas fa-clock me-1"></i> <?php echo format_date($comment['created_at']); ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">No comments yet.</p>
        <?php endif; ?>

        <form action="add_comment.php" method="post" class="mt-4">
            <input type="hidden" name="grievance_id" value="<?php echo $grievance_id; ?>">
            <div class="mb-3">
                <label for="comment" class="form-label">Add a Comment</label>
                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Comment</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
