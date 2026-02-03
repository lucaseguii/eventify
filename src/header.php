<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="header">
    <div class="container header-content">
        <a href="../src/index.php" class="logo">
            <span class="logo-mark">E</span>
            <span class="logo-text">Eventify</span>
        </a>

        <nav class="nav">
            <a href="../src/index.php#com-funciona">Com funciona</a>
            <a href="../src/index.php#esdeveniments">Esdeveniments</a>
            <a href="../views/mis_entradas.php">Mis entradas</a>
            <a href="../views/contacte.php">Contacte</a>
        </nav>

        <div class="auth-buttons">

            <?php if (!isset($_SESSION["usuario_id"])): ?>

                <a href="../views/login.php" class="btn btn-outline">Inicia sessió</a>
                <a href="../views/register.php" class="btn btn-primary">Registra’t</a>

            <?php else: ?>

                <span class="user-hello">
                    Hola, <?php echo htmlspecialchars($_SESSION["usuario_nombre"]); ?>
                </span>

                <?php if (in_array(($_SESSION["usuario_rol"] ?? ""), ["organizador", "admin"], true)): ?>
                    <a href="../views/panel_organizador.php" class="btn btn-outline">
                        Panel organitzador
                    </a>
                <?php endif; ?>

                <?php if (($_SESSION["usuario_rol"] ?? "") === "admin"): ?>
                    <a href="../views/panel_admin.php" class="btn btn-outline">
                        Panel admin
                    </a>
                <?php endif; ?>

                <a href="../views/logout.php" class="btn btn-primary">
                    Tancar sessió
                </a>

            <?php endif; ?>

        </div>
    </div>
</header>
