    </div><!-- /.container -->

    <footer class="py-4 mt-5 admin-glass">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0 text-white-50">&copy; <?php echo date('Y'); ?> Grievance Redressal Platform - Admin Panel</p>
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
        const glassElements = document.querySelectorAll('.admin-glass, .glass');
        glassElements.forEach(element => {
            element.addEventListener('mousemove', function(e) {
                const rect = element.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                element.style.background = `radial-gradient(circle at ${x}px ${y}px, rgba(31, 38, 135, 0.2), rgba(31, 38, 135, 0.15))`;
            });
            
            element.addEventListener('mouseleave', function() {
                element.style.background = 'rgba(31, 38, 135, 0.15)';
            });
        });
    });
    </script>
</body>
</html>
