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
        <div class="card login-form glass">
            <div class="card-header bg-transparent text-white">
                <h4 class="mb-0 text-center"><i class="fas fa-sign-in-alt me-2"></i>Login</h4>
            </div>
            <div class="card-body">
                <form action="login_process.php" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label text-white">Username or Email</label>
                        <input type="text" class="form-control bg-transparent text-white" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-white">Password</label>
                        <input type="password" class="form-control bg-transparent text-white" id="password" name="password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-glass btn-submit">Login</button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-transparent text-center">
                <p class="mb-0 text-white">Don't have an account? <a href="register.php" class="text-white"><u>Register here</u></a></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
