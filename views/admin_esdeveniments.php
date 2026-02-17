<?php
require_once __DIR__ . "/../config/seguridad.php";
require_once __DIR__ . "/../config/conexion.php";

require_roles(["admin"]);

$error = "";
$ok = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "eliminar") {
    $id = (int)($_POST["id"] ?? 0);

    if ($id > 0) {
        $stmt = $conexion->prepare("DELETE FROM eventos WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $ok = "Esdeveniment eliminat correctament.";
        } else {
            $error = "No s'ha pogut eliminar l'esdeveniment.";
        }
        $stmt->close();
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "editar") {
    $id = (int)($_POST["id"] ?? 0);

    $titulo = trim($_POST["titulo"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $lugar = trim($_POST["lugar"] ?? "");
    $fecha_evento = trim($_POST["fecha_evento"] ?? "");
    $aforo = (int)($_POST["aforo"] ?? 0);
    $precio = (float)($_POST["precio"] ?? 0);

    if ($id <= 0 || $titulo === "" || $fecha_evento === "" || $aforo <= 0) {
        $error = "Revisa les dades: títol, data i aforament són obligatoris (aforament > 0).";
    } else {
        $stmt = $conexion->prepare("
            UPDATE eventos
            SET titulo = ?, descripcion = ?, lugar = ?, fecha_evento = ?, aforo = ?, precio = ?
            WHERE id = ?
            LIMIT 1
        ");
        $stmt->bind_param("ssssid i", $titulo, $descripcion, $lugar, $fecha_evento, $aforo, $precio, $id);
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "editar" && $error === "") {
    $id = (int)($_POST["id"] ?? 0);

    $titulo = trim($_POST["titulo"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $lugar = trim($_POST["lugar"] ?? "");
    $fecha_evento = trim($_POST["fecha_evento"] ?? "");
    $aforo = (int)($_POST["aforo"] ?? 0);
    $precio = (float)($_POST["precio"] ?? 0);

    $stmt = $conexion->prepare("
        UPDATE eventos
        SET titulo = ?, descripcion = ?, lugar = ?, fecha_evento = ?, aforo = ?, precio = ?
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->bind_param("ssssidi", $titulo, $descripcion, $lugar, $fecha_evento, $aforo, $precio, $id);

    if ($stmt->execute()) {
        $ok = "Esdeveniment actualitzat correctament.";
    } else {
        $error = "No s'ha pogut actualitzar l'esdeveniment.";
    }
    $stmt->close();
}

$sql = "SELECT id, titulo, lugar, fecha_evento, aforo, precio FROM eventos ORDER BY fecha_evento DESC";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="ca">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eventify | Admin - Esdeveniments</title>

  <link rel="stylesheet" href="../css/base.css" />
  <link rel="stylesheet" href="../css/header.css" />
  <link rel="stylesheet" href="../css/footer.css" />

  <style>
    .admin-card { overflow-x:auto; }
    table { width:100%; border-collapse:collapse; min-width: 880px; }
    th, td { padding:.7rem .6rem; border-bottom:1px solid #e5e7eb; vertical-align:top; }
    th { text-align:left; font-size:.9rem; color:#374151; background:#f9fafb; }
    input, textarea { width:100%; padding:.45rem .5rem; border:1px solid #d1d5db; border-radius:.6rem; font:inherit; }
    textarea { resize:vertical; }
    .row-actions { display:flex; gap:.5rem; flex-wrap:wrap; }
    .btn-mini { padding:.4rem .8rem; font-size:.85rem; }
    .price { white-space:nowrap; }
  </style>
</head>
<body>

<?php include_once __DIR__ . "/../src/header.php"; ?>

<main class="section">
  <div class="container">
    <h2>Gestió d’esdeveniments</h2>
    <p class="section-subtitle">Editar i eliminar esdeveniments.</p>

    <?php if ($error !== ""): ?>
      <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($ok !== ""): ?>
      <div class="alert-ok"><?php echo htmlspecialchars($ok); ?></div>
    <?php endif; ?>

    <div class="card admin-card">
      <?php if (!$resultado || $resultado->num_rows === 0): ?>
        <p>No hi ha esdeveniments.</p>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th style="width:70px;">ID</th>
              <th style="width:240px;">Títol</th>
              <th style="width:160px;">Lloc</th>
              <th style="width:190px;">Data i hora</th>
              <th style="width:110px;">Aforament</th>
              <th style="width:120px;">Preu</th>
              <th style="width:210px;">Accions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($ev = $resultado->fetch_assoc()): ?>
              <tr>
                <form method="POST">
                  <td>
                    <?php echo (int)$ev["id"]; ?>
                    <input type="hidden" name="id" value="<?php echo (int)$ev["id"]; ?>">
                    <input type="hidden" name="accion" value="editar">
                  </td>
                  <td><input type="text" name="titulo" value="<?php echo htmlspecialchars($ev["titulo"]); ?>" required></td>
                  <td><input type="text" name="lugar" value="<?php echo htmlspecialchars($ev["lugar"]); ?>"></td>
                  <td>
                    <input type="datetime-local" name="fecha_evento"
                      value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($ev['fecha_evento']))); ?>"
                      required>
                  </td>
                  <td><input type="number" name="aforo" min="1" value="<?php echo (int)$ev["aforo"]; ?>" required></td>
                  <td class="price">
                    <input type="number" name="precio" step="0.01" min="0" value="<?php echo htmlspecialchars((string)$ev["precio"]); ?>">
                  </td>
                  <td>
                    <div class="row-actions">
                      <button class="btn btn-primary btn-mini" type="submit">Guardar</button>
                    </div>
                </form>

                <form method="POST" onsubmit="return confirm('Segur que vols eliminar aquest esdeveniment?');" style="margin-top:.4rem;">
                  <input type="hidden" name="id" value="<?php echo (int)$ev["id"]; ?>">
                  <input type="hidden" name="accion" value="eliminar">
                  <button class="btn btn-outline btn-mini" type="submit">Eliminar</button>
                </form>

                  </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

    <div style="margin-top:1.2rem;">
      <a href="panel_admin.php" class="btn btn-outline">Tornar al panell</a>
    </div>
  </div>
</main>

<?php include_once __DIR__ . "/../src/footer.php"; ?>

</body>
</html>
