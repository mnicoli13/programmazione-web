        </div> <!-- End of main-container -->
        
        <footer class="footer py-4 bg-light border-top mt-auto">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 text-lg-start text-center mb-3 mb-lg-0">
                        <span class="text-muted">&copy; <?php echo date('Y'); ?> Sistema Gestione Veicoli</span>
                    </div>
                    <div class="col-lg-6 text-lg-end text-center">
                        <a href="https://github.com/mnicoli13/programmazione-web" target="_blank" class="text-decoration-none me-3 text-secondary">
                            <i class="bi bi-github"></i> GitHub
                        </a>
                        <a href="#" class="text-decoration-none text-secondary" data-bs-toggle="modal" data-bs-target="#helpModal">
                            <i class="bi bi-question-circle"></i> Aiuto
                        </a>
                    </div>
                </div>
            </div>
        </footer>

        <?php include("../template-parts/modals/user-guide.php") ?>


        <!-- Back to top button -->
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
            <i class="bi bi-arrow-up-short"></i>
        </a>

        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Custom scripts -->
        <script src="<?php echo PATH_ROOT . 'assets/js/main.js'; ?>"></script>
        <script src="<?php echo PATH_ROOT . 'assets/js/crud.js'; ?>"></script>
        <script src="<?php echo PATH_ROOT . 'assets/js/filter.js'; ?>"></script>
        <script src="<?php echo PATH_ROOT . 'assets/js/auth.js'; ?>"></script>
    </body>
</html>
