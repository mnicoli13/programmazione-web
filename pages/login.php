
<?php include("../template-parts/header.php") ?>

<div class="login-container container">
    <div class="row w-100">
        <!-- Left side: App features -->
        <div class="col-md-4 d-none d-md-block">
            <?php include("../template-parts/login/features.php") ?>
        </div>
        
        <!-- Right side: Login/Register form -->
        <div class="col-md-8">
            <div class="login-form-container">
                <div class="login-header">
                    <h2 class="text-center mb-3"><i class="bi bi-person-circle"></i> Accedi all'Area Riservata</h2>
                    <p class="text-center mb-4">Accedi o registrati per gestire i tuoi veicoli</p>
                    
                    <!-- Login/Register Tabs -->     
                    <div class="d-flex justify-content-center">
                        <ul class="nav nav-tabs login-tabs justify-content-center" id="loginTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-panel" type="button" role="tab" aria-controls="login-panel" aria-selected="true">
                                    <i class="bi bi-box-arrow-in-right"></i> Accedi
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-panel" type="button" role="tab" aria-controls="register-panel" aria-selected="false">
                                    <i class="bi bi-person-plus"></i> Registrati
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Notification container -->
                <div id="login-notification" class="m-3" style="display: none;"></div>
                
                <!-- Login/Register Content -->
                <div class="tab-content">
                    <!-- Login Form -->
                    <div class="tab-pane fade show active" id="login-panel" role="tabpanel" aria-labelledby="login-tab">
                        <div class="login-form">
                            <?php include("../template-parts/login/login-form.php") ?>
                        </div>

                        <div class="text-center mb-4">
                            <a href="#" class="text-decoration-none text-muted small help-link" data-bs-toggle="modal" data-bs-target="#loginHelpModal">Aiuto</a>
                        </div>
                    </div>

                    <!-- Register Form -->
                    <div class="tab-pane fade" id="register-panel" role="tabpanel" aria-labelledby="register-tab">
                        <div class="login-form">
                            <?php include("../template-parts/login/register-form.php") ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("../template-parts/login/help-modal.php") ?>

<?php include("../template-parts/footer.php") ?>