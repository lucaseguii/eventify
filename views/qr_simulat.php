<?php
require_once __DIR__ . "/../config/seguridad.php";
require_once __DIR__ . "/../config/conexion.php";

require_login();

$cantidad = (int)($_GET["cantidad"] ?? 0);
if ($cantidad < 0) $cantidad = 0;
?>
<!DOCTYPE html>
<html lang="ca">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  

  <link rel="stylesheet" href="../css/base.css" />
  <link rel="stylesheet" href="../css/header.css" />
  <link rel="stylesheet" href="../css/footer.css" />

  <style>
    .qr-card {
      max-width: 560px;
      margin: 0 auto;
      text-align: center;
    }
    .qr-img {
      width: 220px;
      height: 220px;
      object-fit: contain;
      margin: 1rem auto;
      display: block;
      border-radius: 12px;
      border: 1px solid #e5e7eb;
      background: #fff;
      padding: 12px;
    }
    .muted {
      color: #6b7280;
      font-size: 0.95rem;
    }
  </style>
</head>
<body>

<?php include_once __DIR__ . "/../src/header.php"; ?>

<main class="section">
  <div class="container">
    <div class="card qr-card">
      <h2>Compra confirmada</h2>

      <?php if ($cantidad > 0): ?>
        <p class="muted">Has comprat <strong><?php echo $cantidad; ?></strong> entrades.</p>
      <?php else: ?>
        <p class="muted">La teva compra s’ha registrat correctament.</p>
      <?php endif; ?>

      <img
        src="../assets/img/qr_simulat.png"
        alt="QR simulat"
        class="qr-img"
      />



      <div style="margin-top:1rem; display:flex; gap:.6rem; justify-content:center; flex-wrap:wrap;">
        <a href="mis_entradas.php" class="btn btn-primary">Anar a les meves entrades</a>
        <a href="../src/index.php#esdeveniments" class="btn btn-outline">Tornar als esdeveniments</a>
      </div>
    </div>
  </div>
</main>

<?php include_once __DIR__ . "/../src/footer.php"; ?>

</body>
</html>
