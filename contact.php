<?php
require_once 'config/db_config.php';
require_once 'includes/functions.php';

start_session_if_not_started();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero" style="padding: 80px 0;">
    <div class="container text-center">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you. Reach out to us with any questions or feedback.</p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="contact-info">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4>Our Location</h4>
                    <p>123 Innovation Street<br>Tech Hub, Silicon Valley<br>CA 94025, USA</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-info">
                    <div class="contact-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h4>Call Us</h4>
                    <p>+1 (555) 123-4567<br>Monday - Friday: 9am - 5pm</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-info">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4>Email Us</h4>
                    <p>info@grievanceplatform.com<br>support@grievanceplatform.com</p>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-6">
                <div class="contact-form">
                    <h3 class="mb-4">Send Us a Message</h3>
                    <form id="contactForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="h-100 d-flex flex-column">
                    <h3 class="mb-4">Connect With Us</h3>
                    <div class="mb-4">
                        <p>Follow us on social media to stay updated with the latest news and updates.</p>
                        <div class="d-flex gap-3 fs-3">
                            <a href="#" class="text-primary"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="text-info"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-danger"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-primary"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-3">Our Location</h4>
                        <!-- Embed Google Map -->
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3168.639290621064!2d-122.08374688469212!3d37.42199997982367!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808fba02425dad8f%3A0x29cdf01a44fc687f!2sGoogle%20Building%2040%2C%20Mountain%20View%2C%20CA%2094043%2C%20USA!5e0!3m2!1sen!2sin!4v1625147354944!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Frequently Asked Questions</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                How do I submit a grievance?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                To submit a grievance, you need to register an account first. Once logged in, navigate to the "Submit Grievance" page from your dashboard, fill out the form with all relevant details, and submit it. You'll receive a confirmation and can track the status of your grievance from your dashboard.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                How long does it take to resolve a grievance?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                The resolution time varies depending on the complexity of the grievance. Simple issues are typically resolved within 24-48 hours, while more complex issues may take 3-5 business days. You can always check the status of your grievance in real-time through your dashboard.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Is my data secure on your platform?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, we take data security very seriously. All data transmitted through our platform is encrypted using industry-standard protocols. We also have strict access controls in place to ensure that only authorized personnel can access your information.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Can I integrate your platform with my existing systems?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, our platform offers API integration capabilities that allow you to connect with your existing CRM, helpdesk, or other customer service systems. Contact our support team for detailed integration documentation and assistance.
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
        <h2 class="mb-4">Ready to Get Started?</h2>
        <p class="lead mb-4">Join thousands of startups already using our platform to improve customer satisfaction.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="register.php" class="btn btn-glass btn-lg">Sign Up Now</a>
            <a href="about.php" class="btn btn-outline-light btn-lg">Learn More</a>
        </div>
    </div>
</section>

<!-- JavaScript for Contact Form -->
<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Thank you for your message! We will get back to you soon.');
    this.reset();
});
</script>

<?php include 'includes/footer.php'; ?>
