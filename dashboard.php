<?php
require_once 'config/db_config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Require login
require_login();

// Get user grievances
$user_id = $_SESSION['user_id'];
$sql = "SELECT g.*, c.name as category_name 
        FROM grievances g 
        JOIN categories c ON g.category_id = c.id 
        WHERE g.user_id = ? 
        ORDER BY g.created_at DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Get grievance counts by status
$status_counts = [
    'pending' => 0,
    'in_progress' => 0,
    'resolved' => 0,
    'rejected' => 0
];

$sql_count = "SELECT status, COUNT(*) as count FROM grievances WHERE user_id = ? GROUP BY status";
$stmt_count = mysqli_prepare($conn, $sql_count);
mysqli_stmt_bind_param($stmt_count, "i", $user_id);
mysqli_stmt_execute($stmt_count);
$result_count = mysqli_stmt_get_result($stmt_count);

while ($row = mysqli_fetch_assoc($result_count)) {
    $status_counts[$row['status']] = $row['count'];
}

$total_grievances = array_sum($status_counts);

include 'includes/header.php';
?>

<h1 class="mb-4">My Dashboard</h1>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card dashboard-stats">
            <div class="stats-number"><?php echo $total_grievances; ?></div>
            <div class="stats-label">Total Grievances</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-stats">
            <div class="stats-number text-warning"><?php echo $status_counts['pending']; ?></div>
            <div class="stats-label">Pending</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-stats">
            <div class="stats-number text-info"><?php echo $status_counts['in_progress']; ?></div>
            <div class="stats-label">In Progress</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-stats">
            <div class="stats-number text-success"><?php echo $status_counts['resolved']; ?></div>
            <div class="stats-label">Resolved</div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>My Grievances</h2>
            <a href="submit_grievance.php" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Submit New Grievance
            </a>
        </div>
    </div>
</div>

<?php if (mysqli_num_rows($result) > 0): ?>
    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-6">
                <div class="card grievance-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="grievance-title"><?php echo htmlspecialchars($row['title']); ?></span>
                        <span class="<?php echo get_status_badge_class($row['status']); ?>"><?php echo get_formatted_status($row['status']); ?></span>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?php echo nl2br(htmlspecialchars(substr($row['description'], 0, 150)) . (strlen($row['description']) > 150 ? '...' : '')); ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-secondary"><?php echo htmlspecialchars($row['category_name']); ?></span>
                            <small class="text-muted"><?php echo format_date($row['created_at']); ?></small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="grievance_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        <p>You haven't submitted any grievances yet. <a href="submit_grievance.php">Submit your first grievance</a>.</p>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
