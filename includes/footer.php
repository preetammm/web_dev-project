    </div><!-- /.container -->

    <footer class="py-5 mt-5 glass">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5 class="text-white"><i class="fas fa-comments me-2"></i>Grievance Redressal</h5>
                    <p class="text-white-50">A streamlined solution for startups to manage customer grievances efficiently and build stronger relationships with their users.</p>
                    <div class="d-flex gap-3 fs-4">
                        <a href="#" class="text-white-50 hover-text-white"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white-50 hover-text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white-50 hover-text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white-50 hover-text-white"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <h5 class="text-white">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-white-50 text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="about.php" class="text-white-50 text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="contact.php" class="text-white-50 text-decoration-none">Contact Us</a></li>
                        <li class="mb-2"><a href="login.php" class="text-white-50 text-decoration-none">Login</a></li>
                        <li class="mb-2"><a href="register.php" class="text-white-50 text-decoration-none">Register</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5 class="text-white">Resources</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Blog</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Privacy Policy</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Terms of Service</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Support</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="text-white">Newsletter</h5>
                    <p class="text-white-50">Subscribe to our newsletter for the latest updates and news.</p>
                    <form class="d-flex">
                        <input type="email" class="form-control bg-transparent text-white" placeholder="Your Email" required>
                        <button type="submit" class="btn btn-glass ms-2">Subscribe</button>
                    </form>
                </div>
            </div>
            <hr class="mt-4 border-light">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0 text-white-50">&copy; <?php echo date('Y'); ?> Grievance Redressal Platform for Startups. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
    // Add hover effect to glass elements
    document.addEventListener('DOMContentLoaded', function() {
        const glassElements = document.querySelectorAll('.glass');
        glassElements.forEach(element => {
            element.addEventListener('mousemove', function(e) {
                const rect = element.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                element.style.background = `radial-gradient(circle at ${x}px ${y}px, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.15))`;
            });

            element.addEventListener('mouseleave', function() {
                element.style.background = 'rgba(255, 255, 255, 0.15)';
            });
        });
    });
    </script>
</body>
</html>
