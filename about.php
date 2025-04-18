<?php
require_once 'config/db_config.php';
require_once 'includes/functions.php';

start_session_if_not_started();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero" style="padding: 80px 0;">
    <div class="container text-center">
        <h1>About Us</h1>
        <p>Learn more about our mission to revolutionize grievance management for startups</p>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="about-card h-100">
                    <div class="about-img" style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80');"></div>
                    <div class="p-4">
                        <h3>Our Story</h3>
                        <p>The Grievance Redressal Platform was born out of a simple observation: startups often struggle with managing customer complaints efficiently, which can lead to customer dissatisfaction and churn.</p>
                        <p>Founded in 2023, our platform aims to bridge this gap by providing a streamlined solution that helps startups manage grievances effectively while maintaining strong customer relationships.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="about-card h-100">
                    <div class="p-4">
                        <h3>Our Mission</h3>
                        <p>We believe that every customer voice matters. Our mission is to empower startups with the tools they need to listen, respond, and resolve customer grievances efficiently.</p>
                        <p>By providing a transparent and efficient platform, we help startups turn customer complaints into opportunities for improvement and relationship building.</p>
                        <h3 class="mt-4">Our Vision</h3>
                        <p>To create a world where customer grievances are seen not as problems, but as valuable feedback that drives innovation and customer satisfaction.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Values Section -->
        <h2 class="text-center mb-4 text-white">Our Core Values</h2>
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h4 class="mt-3">Transparency</h4>
                        <p>We believe in complete transparency in the grievance resolution process.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h4 class="mt-3">Efficiency</h4>
                        <p>We strive to make the grievance resolution process as efficient as possible.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h4 class="mt-3">Customer-Centric</h4>
                        <p>We put customers at the center of everything we do.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h4 class="mt-3">Data-Driven</h4>
                        <p>We use data to continuously improve our platform and services.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        <h2 class="text-center mb-4 text-white">Meet Our Team</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="team-member">
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=80" class="team-img" alt="Team Member">
                    <h5>Alex Johnson</h5>
                    <p class="text-white">Founder & CEO</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="team-member">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=80" class="team-img" alt="Team Member">
                    <h5>Sarah Williams</h5>
                    <p class="text-white">CTO</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="team-member">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=80" class="team-img" alt="Team Member">
                    <h5>David Chen</h5>
                    <p class="text-white">Head of Product</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="team-member">
                    <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=80" class="team-img" alt="Team Member">
                    <h5>Emily Rodriguez</h5>
                    <p class="text-white">Customer Success Manager</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Achievements Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Our Achievements</h2>
        <div class="row text-center">
            <div class="col-md-3">
                <div class="stats-number text-primary">500+</div>
                <div class="stats-label">Startups Served</div>
            </div>
            <div class="col-md-3">
                <div class="stats-number text-primary">50,000+</div>
                <div class="stats-label">Grievances Resolved</div>
            </div>
            <div class="col-md-3">
                <div class="stats-number text-primary">98%</div>
                <div class="stats-label">Customer Satisfaction</div>
            </div>
            <div class="col-md-3">
                <div class="stats-number text-primary">15+</div>
                <div class="stats-label">Countries Reached</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 text-center text-white">
    <div class="container">
        <h2 class="mb-4">Ready to Transform Your Grievance Management?</h2>
        <p class="lead mb-4">Join our platform today and see the difference in customer satisfaction.</p>
        <a href="register.php" class="btn btn-glass btn-lg">Get Started Today</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
