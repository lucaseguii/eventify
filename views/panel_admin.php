<?php
session_start();
require_once __DIR__ . "/../config/conexion.php";


if (!isset($_SESSION["usuario_id"]) || ($_SESSION["usuario_rol"] ?? "") !== "admin") {
    header("Location: login.php");
    exit;
}

$total_usuarios = 0;
$total_eventos = 0;

$res = $conexion->query("SELECT COUNT(*) AS total FROM usuarios");
if ($res) {
    $fila = $res->fetch_assoc();
    $total_usuarios = (int)$fila["total"];
}

$res = $conexion->query("SELECT COUNT(*) AS total FROM eventos");
if ($res) {
    $fila = $res->fetch_assoc();
    $total_eventos = (int)$fila["total"];
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eventify | Panel Admin</title>

    <link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/footer.css" />
    <link rel="stylesheet" href="../css/admin.css" />
</head>

<body>

    <?php include_once __DIR__ . "/../src/header.php"; ?>

    <main class="section">
        <div class="container">
            <div class="admin-top">
                <div>
                    <h2>Panel d’admin</h2>
                    <p class="section-subtitle">
                        Benvingut, <?php echo htmlspecialchars($_SESSION["usuario_nombre"] ?? ""); ?>.
                    </p>
                </div>

                <a class="btn btn-outline" href="logout.php">Tancar sessió</a>
            </div>

            <div class="grid-3">
                <div class="card">
                    <h3>Usuaris registrats</h3>
                    <p class="big-number"><?php echo $total_usuarios; ?></p>
                </div>

                <div class="card">
                    <h3>Esdeveniments creats</h3>
                    <p class="big-number"><?php echo $total_eventos; ?></p>
                </div>

                <div class="card">
                    <h3>Accions ràpides</h3>
                    <div class="admin-actions">
                        <a class="btn btn-primary btn-full" href="admin_usuarios.php">Gestionar usuaris</a>
<a href="admin_esdeveniments.php" class="btn btn-outline">Gestionar esdeveniments</a>
                    </div>
                </div>
            </div>

            <div class="card admin-card">
                <h3>Notes</h3>
                <p>
                    Aquest panell és una base per afegir funcions: llistar usuaris, eliminar comptes,
                    moderar esdeveniments, etc.
                </p>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . "/../src/footer.php"; ?>

</body>
</html>
