<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$ok = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"] ?? "");
    $correu = trim($_POST["correu"] ?? "");
    $missatge = trim($_POST["missatge"] ?? "");

    if ($nom === "" || $correu === "" || $missatge === "") {
        $error = "Omple tots els camps, si us plau.";
    } elseif (!filter_var($correu, FILTER_VALIDATE_EMAIL)) {
        $error = "El correu no és vàlid.";
    } else {

        $ok = "Missatge enviat correctament ✅ Ens posarem en contacte amb tu aviat.";

      
        $nom = $correu = $missatge = "";
    }
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eventify | Contacte</title>

    <link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/footer.css" />
    <link rel="stylesheet" href="../css/contacte.css" />
</head>

<body>

<?php include_once __DIR__ . "/../src/header.php"; ?>

<main class="section">
    <div class="container">
        <h2>Contacte</h2>
        <p class="section-subtitle">
            Tens dubtes o necessites ajuda? Escriu-nos i et respondrem el més prest possible.
        </p>

        <div class="contact-grid">
            <div class="card">
                <h3>Envia’ns un missatge</h3>

                <?php if ($error !== ""): ?>
                    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($ok !== ""): ?>
                    <div class="alert-ok"><?php echo htmlspecialchars($ok); ?></div>
                <?php endif; ?>

                <form method="POST" class="contact-form">
                    <label>
                        Nom
                        <input type="text" name="nom" value="<?php echo htmlspecialchars($nom ?? ""); ?>" required />
                    </label>

                    <label>
                        Correu
                        <input type="email" name="correu" value="<?php echo htmlspecialchars($correu ?? ""); ?>" required />
                    </label>

                    <label>
                        Missatge
                        <textarea name="missatge" rows="5" required><?php echo htmlspecialchars($missatge ?? ""); ?></textarea>
                    </label>

                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>

            <div class="card">
                <h3>Informació</h3>
                <p class="muted">També ens pots contactar per aqui:</p>

                <ul class="info-list">
                    <li><strong>Email:</strong> eventify@eventify.com</li>
                    <li><strong>Telèfon:</strong> +34 650 32 47 57</li>
                    <li><strong>Horari:</strong> Dilluns a Divendres · 9:00–18:00</li>
                    <li><strong>Ubicació:</strong> Alaior, Menorca</li>
                </ul>
            </div>
        </div>

    </div>
</main>

<?php include_once __DIR__ . "/../src/footer.php"; ?>

</body>
</html>
