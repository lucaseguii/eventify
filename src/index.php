<?php
require_once __DIR__ . "/../config/conexion.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$href_crear = "../views/login.php";
if (isset($_SESSION["usuario_id"])) {
    $rol = $_SESSION["usuario_rol"] ?? "";
    if (in_array($rol, ["organizador", "admin"], true)) {
        $href_crear = "../views/crear_evento.php";
    } else {
        $href_crear = "#esdeveniments";
    }
}

$sql = "SELECT id, titulo, lugar, fecha_evento, descripcion, precio
        FROM eventos
        ORDER BY fecha_evento DESC
        LIMIT 9";
$resultado_eventos = $conexion->query($sql);

$sql_destacados = "SELECT id, titulo, lugar, fecha_evento, precio
                   FROM eventos
                   WHERE fecha_evento >= NOW()
                   ORDER BY fecha_evento ASC
                   LIMIT 3";
$resultado_destacados = $conexion->query($sql_destacados);
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventify | Gestió d’entrades</title>

    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/index.css">
</head>

<body>

<?php include_once __DIR__ . "/header.php"; ?>

<main>

    <section class="hero">
        <div class="container hero-content">

            <div class="hero-text">
                <h1>La plataforma per gestionar i vendre entrades</h1>
                <p>
                    Eventify et permet crear esdeveniments, gestionar aforaments i vendre entrades
                    de manera ràpida i segura.
                </p>

                <div class="hero-actions">
                    <a href="<?php echo $href_crear; ?>" class="btn btn-primary">Crea un esdeveniment</a>
                    <a href="#esdeveniments" class="btn btn-outline">Veure esdeveniments</a>
                </div>
            </div>

            <div class="hero-card">
                <h2>Esdeveniments destacats</h2>

                <?php if (!$resultado_destacados || $resultado_destacados->num_rows === 0): ?>
                    <p class="section-subtitle" style="margin-bottom:0;">
                        Encara no hi ha esdeveniments destacats.
                    </p>
                <?php else: ?>
                    <ul class="event-list">
                        <?php while ($d = $resultado_destacados->fetch_assoc()): ?>
                            <li class="event-item">
                                <span class="event-name"><?php echo htmlspecialchars($d["titulo"]); ?></span>
                                <span class="event-meta">
                                    <?php
                                        $lugar = $d["lugar"] ?: "Sense ubicació";
                                        $dt = new DateTime($d["fecha_evento"]);
                                        echo htmlspecialchars($lugar . " · " . $dt->format("d/m/Y H:i"));
                                    ?>
                                </span>

                                <span class="event-price">
                                    <?php
                                        $precio = (float)($d["precio"] ?? 0);
                                        echo ($precio <= 0) ? "Gratuït" : number_format($precio, 2) . " €";
                                    ?>
                                </span>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>

                <a href="#esdeveniments" class="link">Veure tots →</a>
            </div>

        </div>
    </section>

    <section id="com-funciona" class="section">
        <div class="container">
            <h2>Com funciona Eventify?</h2>
            <p class="section-subtitle">En tres passos ja tens el teu esdeveniment en marxa.</p>

            <div class="grid-3">
                <div class="card step-card">
                    <span class="step-number">1</span>
                    <h3>Crea el teu esdeveniment</h3>
                    <p>Introdueix nom, data, aforament i tipus d’entrades.</p>
                </div>

                <div class="card step-card">
                    <span class="step-number">2</span>
                    <h3>Comparteix l’enllaç</h3>
                    <p>Publica les teves entrades en xarxes, web o email.</p>
                </div>

                <div class="card step-card">
                    <span class="step-number">3</span>
                    <h3>Controla accessos</h3>
                    <p>Valida els QR el dia de l’esdeveniment.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="esdeveniments" class="section section-alt">
        <div class="container">
            <h2>Esdeveniments en curs</h2>
            <p class="section-subtitle">Aquests esdeveniments es carreguen des de la base de dades.</p>

            <?php if (!$resultado_eventos || $resultado_eventos->num_rows === 0): ?>
                <div class="card">
                    <p>No hi ha esdeveniments disponibles encara.</p>
                    <p class="section-subtitle">Crea’n un des del Panel organitzador.</p>
                </div>
            <?php else: ?>
                <div class="grid-3">
                    <?php while ($ev = $resultado_eventos->fetch_assoc()): ?>
                        <article class="card event-card">
                            <h3><?php echo htmlspecialchars($ev["titulo"]); ?></h3>

                            <p class="event-location">
                                <?php echo htmlspecialchars($ev["lugar"] ?: "Sense ubicació"); ?>
                            </p>

                            <p class="event-date">
                                <?php
                                    $dt = new DateTime($ev["fecha_evento"]);
                                    echo $dt->format("d/m/Y H:i");
                                ?>
                            </p>

                            <p class="event-desc">
                                <?php
                                    $desc = trim($ev["descripcion"] ?? "");
                                    echo htmlspecialchars($desc !== "" ? $desc : "Sense descripció.");
                                ?>
                            </p>

                            <p class="event-price">
                                <?php
                                    $precio = (float)($ev["precio"] ?? 0);
                                    echo ($precio <= 0) ? "Gratuït" : number_format($precio, 2) . " €";
                                ?>
                            </p>

                            <a href="../views/comprar_entrada.php?id=<?php echo (int)$ev["id"]; ?>"
                               class="btn btn-small btn-primary">
                               Comprar entrades
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php include_once __DIR__ . "/footer.php"; ?>

</body>
</html>
