<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="d-flex flex-column collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'homepage.php') ? 'active' : ''; ?>" href="/pages/homepage.php">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'veicolo.php') ? 'active' : ''; ?>" href="/pages/veicolo.php">
                        <i class="bi bi-car-front"></i> Veicoli
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'targa.php') ? 'active' : ''; ?>" href="/pages/targa.php">
                        <i class="bi bi-upc-scan"></i> Targhe
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'targa_restituita.php') ? 'active' : ''; ?>" href="/pages/targa_restituita.php">
                        <i class="bi bi-people"></i> Targhe restituite
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'targa_attiva.php') ? 'active' : ''; ?>" href="/pages/targa_attiva.php">
                        <i class="bi bi-bar-chart"></i> Targhe attive
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'revisione.php') ? 'active' : ''; ?>" href="/pages/revisione.php">
                        <i class="bi bi-bar-chart"></i> Revisioni
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
