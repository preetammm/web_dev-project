<?php
require_once 'config/db_config.php';
require_once 'includes/functions.php';

start_session_if_not_started();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Grievance Redressal Platform for Startups</h1>
        <p>A streamlined solution for startups to manage customer grievances efficiently and build stronger relationships with their users.</p>
        <div class="d-flex justify-content-center gap-3">
            <?php if (!is_logged_in()): ?>
                <a href="register.php" class="btn btn-glass"><i class="fas fa-user-plus me-2"></i>Get Started</a>
                <a href="login.php" class="btn btn-glass"><i class="fas fa-sign-in-alt me-2"></i>Login</a>
            <?php else: ?>
                <a href="dashboard.php" class="btn btn-glass"><i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard</a>
                <a href="submit_grievance.php" class="btn btn-glass"><i class="fas fa-file-alt me-2"></i>Submit Grievance</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 text-white">Key Features</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Efficient Communication</h3>
                    <p>Direct communication channel between users and administrators for quick resolution of grievances.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3>Status Tracking</h3>
                    <p>Real-time tracking of grievance status from submission to resolution, keeping users informed at every step.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Analytics Dashboard</h3>
                    <p>Comprehensive dashboard for administrators to monitor and analyze grievance patterns and resolution times.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">How It Works</h2>
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" class="img-fluid rounded shadow" alt="How it works">
            </div>
            <div class="col-md-6">
                <div class="ps-md-4">
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">1</div>
                        </div>
                        <div>
                            <h4>Register an Account</h4>
                            <p>Create your account to access the platform's features.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">2</div>
                        </div>
                        <div>
                            <h4>Submit Your Grievance</h4>
                            <p>Fill out the grievance form with all relevant details.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">3</div>
                        </div>
                        <div>
                            <h4>Track Resolution Progress</h4>
                            <p>Monitor the status of your grievance through your dashboard.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">4</div>
                        </div>
                        <div>
                            <h4>Get Resolution</h4>
                            <p>Receive updates and resolution for your grievance.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 text-white">What Our Users Say</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="card-text">"This platform has revolutionized how we handle customer grievances. The streamlined process has improved our resolution time by 60%."</p>
                        <div class="d-flex align-items-center mt-3">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80" class="rounded-circle me-3" width="50" height="50" alt="User">
                            <div>
                                <h6 class="mb-0">John Smith</h6>
                                <small class="text-muted">CEO, TechStart Inc.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="card-text">"As a user, I appreciate how transparent the grievance process is. I can track my complaint status in real-time and communicate directly with the team."</p>
                        <div class="d-flex align-items-center mt-3">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80" class="rounded-circle me-3" width="50" height="50" alt="User">
                            <div>
                                <h6 class="mb-0">Sarah Johnson</h6>
                                <small class="text-muted">Regular User</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="card-text">"The analytics dashboard has given us valuable insights into common customer issues, helping us improve our products proactively."</p>
                        <div class="d-flex align-items-center mt-3">
                            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80" class="rounded-circle me-3" width="50" height="50" alt="User">
                            <div>
                                <h6 class="mb-0">Michael Chen</h6>
                                <small class="text-muted">Product Manager, InnovateCo</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 text-center text-white">
    <div class="container">
        <h2 class="mb-4">Ready to Streamline Your Grievance Management?</h2>
        <p class="lead mb-4">Join thousands of startups already using our platform to improve customer satisfaction.</p>
        <a href="register.php" class="btn btn-glass btn-lg">Get Started Today</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
