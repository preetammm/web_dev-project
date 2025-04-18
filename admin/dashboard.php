<?php
require_once '../config/db_config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Require admin
require_admin();

// Get all grievances
$sql = "SELECT g.*, c.name as category_name, u.username as submitted_by
        FROM grievances g
        JOIN categories c ON g.category_id = c.id
        JOIN users u ON g.user_id = u.id
        ORDER BY g.created_at DESC";
$result = mysqli_query($conn, $sql);

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

// Get user count
$sql_users = "SELECT COUNT(*) as count FROM users WHERE role = 'user'";
$result_users = mysqli_query($conn, $sql_users);
$user_count = mysqli_fetch_assoc($result_users)['count'];

include 'admin_header.php';
?>

<div class="admin-page-header">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="users.php" class="btn btn-glass me-2"><i class="fas fa-users me-2"></i>Manage Users</a>
            <a href="grievances.php" class="btn btn-glass me-2"><i class="fas fa-list me-2"></i>Manage Grievances</a>
            <a href="categories.php" class="btn btn-glass"><i class="fas fa-tags me-2"></i>Manage Categories</a>
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
            <div class="stats-number text-light"><?php echo $user_count; ?></div>
            <div class="stats-label">Registered Users</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4 admin-glass">
            <div class="card-header bg-transparent text-white">
                <h4 class="mb-0">Recent Grievances</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 0;
                            if (mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)):
                                    if ($count < 5): // Show only 5 recent grievances
                                        $count++;
                            ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><span class="<?php echo get_status_badge_class($row['status']); ?>"><?php echo get_formatted_status($row['status']); ?></span></td>
                                    <td><?php echo format_date($row['created_at']); ?></td>
                                    <td>
                                        <a href="../grievance_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">View</a>
                                        <a href="delete_grievance.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this grievance?')">Delete</a>
                                    </td>
                                </tr>
                            <?php
                                    endif;
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="5" class="text-center">No grievances found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="text-center mt-3">
                        <a href="grievances.php" class="btn btn-primary">View All Grievances</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4 admin-glass">
            <div class="card-header bg-transparent text-white">
                <h4 class="mb-0">Quick Actions</h4>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="feature-icon mx-auto mb-3">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h5>User Management</h5>
                                <p>View, edit, and manage user accounts</p>
                                <a href="users.php" class="btn btn-primary">Manage Users</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="feature-icon mx-auto mb-3">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <h5>Categories</h5>
                                <p>Add, edit, or remove grievance categories</p>
                                <a href="categories.php" class="btn btn-primary">Manage Categories</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="feature-icon mx-auto mb-3">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <h5>Statistics</h5>
                                <p>View detailed grievance statistics</p>
                                <a href="grievances.php" class="btn btn-primary">View Statistics</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="feature-icon mx-auto mb-3">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <h5>Settings</h5>
                                <p>Configure system settings</p>
                                <a href="#" class="btn btn-primary">System Settings</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
