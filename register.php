<?php
require_once 'config/db_config.php';
require_once 'includes/functions.php';

start_session_if_not_started();

// Check if user is already logged in
if (is_logged_in()) {
    redirect("dashboard.php");
}

include 'includes/header.php';
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card register-form glass">
            <div class="card-header bg-transparent text-white">
                <h4 class="mb-0 text-center"><i class="fas fa-user-plus me-2"></i>Register</h4>
            </div>
            <div class="card-body">
                <form action="register_process.php" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label text-white">Username</label>
                        <input type="text" class="form-control bg-transparent text-white" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label text-white">Email</label>
                        <input type="email" class="form-control bg-transparent text-white" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-white">Password</label>
                        <input type="password" class="form-control bg-transparent text-white" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label text-white">Confirm Password</label>
                        <input type="password" class="form-control bg-transparent text-white" id="confirm_password" name="confirm_password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-glass btn-submit">Register</button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-transparent text-center">
                <p class="mb-0 text-white">Already have an account? <a href="login.php" class="text-white"><u>Login here</u></a></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
