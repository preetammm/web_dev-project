<?php
require_once '../config/db_config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Require admin
require_admin();

// Handle user deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $user_id = clean_input($_GET['id']);

    // Don't allow admin to delete themselves
    if ($user_id == $_SESSION['user_id']) {
        $_SESSION['error'] = "You cannot delete your own account";
        redirect("users.php");
    }

    // Check if user exists
    $sql = "SELECT id, role FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        $_SESSION['error'] = "User not found";
        redirect("users.php");
    }

    $user = mysqli_fetch_assoc($result);

    // Don't allow deleting other admins
    if ($user['role'] == 'admin' && $user_id != $_SESSION['user_id']) {
        $_SESSION['error'] = "You cannot delete other admin accounts";
        redirect("users.php");
    }

    // Delete user's grievances and comments first
    $sql = "DELETE FROM comments WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    $sql = "DELETE FROM grievances WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    // Delete user
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "User deleted successfully";
    } else {
        $_SESSION['error'] = "Failed to delete user";
    }

    redirect("users.php");
}

// Handle role change
if (isset($_GET['action']) && $_GET['action'] == 'role' && isset($_GET['id']) && isset($_GET['role'])) {
    $user_id = clean_input($_GET['id']);
    $role = clean_input($_GET['role']);

    // Validate role
    if ($role != 'user' && $role != 'admin') {
        $_SESSION['error'] = "Invalid role";
        redirect("users.php");
    }

    // Don't allow admin to change their own role
    if ($user_id == $_SESSION['user_id']) {
        $_SESSION['error'] = "You cannot change your own role";
        redirect("users.php");
    }

    // Check if user exists
    $sql = "SELECT id FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_num_rows($stmt) == 0) {
        $_SESSION['error'] = "User not found";
        redirect("users.php");
    }

    // Update user role
    $sql = "UPDATE users SET role = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $role, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "User role updated successfully";
    } else {
        $_SESSION['error'] = "Failed to update user role";
    }

    redirect("users.php");
}

// Get all users
$sql = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

// Get user counts by role
$sql_admin_count = "SELECT COUNT(*) as count FROM users WHERE role = 'admin'";
$result_admin_count = mysqli_query($conn, $sql_admin_count);
$admin_count = mysqli_fetch_assoc($result_admin_count)['count'];

$sql_user_count = "SELECT COUNT(*) as count FROM users WHERE role = 'user'";
$result_user_count = mysqli_query($conn, $sql_user_count);
$user_count = mysqli_fetch_assoc($result_user_count)['count'];

include 'admin_header.php';
?>

<div class="admin-page-header">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1><i class="fas fa-users me-2"></i>User Management</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="dashboard.php" class="btn btn-glass"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card dashboard-stats admin-glass">
            <div class="stats-number"><?php echo $admin_count + $user_count; ?></div>
            <div class="stats-label">Total Users</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-stats admin-glass">
            <div class="stats-number text-primary"><?php echo $admin_count; ?></div>
            <div class="stats-label">Admins</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-stats admin-glass">
            <div class="stats-number text-success"><?php echo $user_count; ?></div>
            <div class="stats-label">Regular Users</div>
        </div>
    </div>
</div>

<div class="card mb-4 admin-glass">
    <div class="card-header bg-transparent text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">All Users</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registered On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <?php if ($row['role'] == 'admin'): ?>
                                        <span class="badge bg-danger">Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">User</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo format_date($row['created_at']); ?></td>
                                <td>
                                    <?php if ($row['id'] != $_SESSION['user_id']): ?>
                                        <?php if ($row['role'] == 'user'): ?>
                                            <a href="users.php?action=role&id=<?php echo $row['id']; ?>&role=admin" class="btn btn-sm btn-outline-primary" onclick="return confirm('Are you sure you want to make this user an admin?')">Make Admin</a>
                                        <?php else: ?>
                                            <a href="users.php?action=role&id=<?php echo $row['id']; ?>&role=user" class="btn btn-sm btn-outline-warning" onclick="return confirm('Are you sure you want to remove admin privileges from this user?')">Remove Admin</a>
                                        <?php endif; ?>
                                        <a href="users.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this user? This will also delete all their grievances and comments.')">Delete</a>
                                    <?php else: ?>
                                        <span class="text-muted">Current User</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
