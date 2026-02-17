<?php
require_once __DIR__ . "/../config/seguridad.php";
require_once __DIR__ . "/../config/conexion.php";

require_login();

$id_evento = (int)($_GET["id"] ?? 0);
if ($id_evento <= 0) {
    header("Location: ../src/index.php#esdeveniments");
    exit;
}

$stmt = $conexion->prepare(
    "SELECT id, titulo, descripcion, lugar, fecha_evento, aforo, precio
     FROM eventos
     WHERE id = ?
     LIMIT 1"
);
$stmt->bind_param("i", $id_evento);
$stmt->execute();
$evento = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$evento) {
    header("Location: ../src/index.php#esdeveniments");
    exit;
}

$stmt = $conexion->prepare(
    "SELECT COALESCE(SUM(cantidad), 0) AS vendidas
     FROM entradas
     WHERE id_evento = ?"
);
$stmt->bind_param("i", $id_evento);
$stmt->execute();
$vendidas = (int)($stmt->get_result()->fetch_assoc()["vendidas"] ?? 0);
$stmt->close();

$aforo = (int)$evento["aforo"];
$disponibles = max(0, $aforo - $vendidas);

$precio = (float)($evento["precio"] ?? 0);

$error = "";
$cantidad = 1;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cantidad = (int)($_POST["cantidad"] ?? 0);
    $id_usuario = (int)$_SESSION["usuario_id"];

    if ($cantidad <= 0) {
        $error = "Selecciona una quantitat vàlida.";
    } elseif ($cantidad > $disponibles) {
        $error = "No hi ha prou entrades disponibles. Disponibles: " . $disponibles;
    } else {
        $stmt = $conexion->prepare(
            "INSERT INTO entradas (id_evento, id_usuario, cantidad)
             VALUES (?, ?, ?)"
        );
        $stmt->bind_param("iii", $id_evento, $id_usuario, $cantidad);

        if ($stmt->execute()) {
            $stmt->close();
header("Location: qr_simulat.php?cantidad=" . $cantidad);
exit;

        } else {
            $error = "Error al registrar la compra.";
        }

        $stmt->close();
    }
}

$total = ($precio <= 0) ? 0 : ($precio * $cantidad);
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eventify | Comprar entrades</title>

    <link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/footer.css" />
    <link rel="stylesheet" href="../css/comprar.css" />
</head>

<body>

<?php include_once __DIR__ . "/../src/header.php"; ?>

<main class="section">
    <div class="container">

        <div class="card comprar-card">
            <h2><?php echo htmlspecialchars($evento["titulo"]); ?></h2>

            <p class="section-subtitle">
                <?php
                    $dt = new DateTime($evento["fecha_evento"]);
                    echo htmlspecialchars(
                        ($evento["lugar"] ?: "Sense ubicació") . " · " . $dt->format("d/m/Y H:i")
                    );
                ?>
            </p>

            <div class="info-grid">
                <div>
                    <p class="label">Aforament total</p>
                    <p class="value"><?php echo $aforo; ?></p>
                </div>
                <div>
                    <p class="label">Entrades venudes</p>
                    <p class="value"><?php echo $vendidas; ?></p>
                </div>
                <div>
                    <p class="label">Disponibles</p>
                    <p class="value"><?php echo $disponibles; ?></p>
                </div>
                <div>
                    <p class="label">Preu per entrada</p>
                    <p class="value">
                        <?php echo ($precio <= 0) ? "Gratuït" : number_format($precio, 2) . " €"; ?>
                    </p>
                </div>
            </div>

            <?php if (trim($evento["descripcion"] ?? "") !== ""): ?>
                <div class="desc">
                    <?php echo nl2br(htmlspecialchars($evento["descripcion"])); ?>
                </div>
            <?php endif; ?>

            <?php if ($error !== ""): ?>
                <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($disponibles <= 0): ?>
                <div class="alert-error">No queden entrades disponibles.</div>
                <a href="../src/index.php#esdeveniments" class="btn btn-outline">Tornar</a>
            <?php else: ?>
                <form method="POST" class="comprar-form">
                    <label>
                        Quantitat d’entrades
                        <input
                            id="cantidad"
                            type="number"
                            name="cantidad"
                            min="1"
                            max="<?php echo $disponibles; ?>"
                            value="<?php echo (int)$cantidad; ?>"
                            required
                        />
                    </label>

                    <p class="total-line">
                        <strong>Total:</strong>
                        <span id="totalText" data-precio="<?php echo htmlspecialchars((float)$precio); ?>">
                            <?php echo ($precio <= 0) ? "Gratuït" : number_format($total, 2) . " €"; ?>
                        </span>
                    </p>

                    <div class="actions">
                        <button type="submit" class="btn btn-primary">Confirmar compra</button>
                        <a href="../src/index.php#esdeveniments" class="btn btn-outline">Cancel·lar</a>
                    </div>
                </form>
            <?php endif; ?>

        </div>

    </div>
</main>

<?php include_once __DIR__ . "/../src/footer.php"; ?>

<script src="../js/comprar.js"></script>

</body>
</html>
