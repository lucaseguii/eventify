<?php
require_once __DIR__ . "/../config/seguridad.php";
require_once __DIR__ . "/../config/conexion.php";

require_login();

$id_usuario = (int)$_SESSION["usuario_id"];

$sql = "
    SELECT 
        e.titulo,
        e.lugar,
        e.fecha_evento,
        e.precio,
        en.cantidad,
        en.fecha_compra
    FROM entradas en
    JOIN eventos e ON e.id = en.id_evento
    WHERE en.id_usuario = ?
    ORDER BY en.fecha_compra DESC
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventify | Les meves entrades</title>

    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/mis_entrades.css">
</head>
<body>

<?php include_once __DIR__ . "/../src/header.php"; ?>

<main class="section">
    <div class="container">
        <h2>Les meves entrades</h2>
        <p class="section-subtitle">Aquí pots veure totes les entrades que has comprat.</p>

        <?php if ($resultado->num_rows === 0): ?>
            <div class="card">
                <p>Encara no has comprat cap entrada.</p>
            </div>
        <?php else: ?>
            <div class="grid-3">
                <?php while ($e = $resultado->fetch_assoc()): ?>
                    <?php
                        $precio = (float)($e["precio"] ?? 0);
                        $cantidad = (int)($e["cantidad"] ?? 0);
                        $total = ($precio <= 0) ? 0 : ($precio * $cantidad);
                    ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($e["titulo"]); ?></h3>

                        <p class="event-location">
                            <?php echo htmlspecialchars($e["lugar"] ?: "Sense ubicació"); ?>
                        </p>

                        <p class="event-date">
                            <?php
                                $dt = new DateTime($e["fecha_evento"]);
                                echo $dt->format("d/m/Y H:i");
                            ?>
                        </p>

                        <p><strong>Entrades:</strong> <?php echo $cantidad; ?></p>

                        <p><strong>Preu per entrada:</strong>
                            <?php echo ($precio <= 0) ? "Gratuït" : number_format($precio, 2) . " €"; ?>
                        </p>

                        <p><strong>Total:</strong>
                            <?php echo ($precio <= 0) ? "Gratuït" : number_format($total, 2) . " €"; ?>
                        </p>

                        <p class="section-subtitle" style="margin-bottom:0;">
                            Comprades el <?php echo htmlspecialchars($e["fecha_compra"]); ?>
                        </p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <div style="margin-top:1.2rem;">
            <a href="../src/index.php#esdeveniments" class="btn btn-outline">Tornar als esdeveniments</a>
        </div>
    </div>
</main>

<?php include_once __DIR__ . "/../src/footer.php"; ?>

</body>
</html>
