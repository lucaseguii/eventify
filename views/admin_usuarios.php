<?php
require_once __DIR__ . "/../config/seguridad.php";
require_once __DIR__ . "/../config/conexion.php";

require_rol("admin");

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "cambiar_rol") {
    $id = (int)($_POST["id"] ?? 0);
    $rol = $_POST["rol"] ?? "";

    if ($id <= 0 || !in_array($rol, ["asistente", "organizador", "admin"], true)) {
        $error = "Datos inválidos.";
    } else {
        if ($id === (int)$_SESSION["usuario_id"] && $rol !== "admin") {
            $error = "No pots treure’t el rol d’admin a tu mateix.";
        } else {
            $stmt = $conexion->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
            $stmt->bind_param("si", $rol, $id);

            if ($stmt->execute()) {
                $mensaje = "Rol actualitzat correctament";
            } else {
                $error = "Error en actualitzar el rol.";
            }
            $stmt->close();
        }
    }
}

/* --- Borrar usuario --- */
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "borrar") {
    $id = (int)($_POST["id"] ?? 0);

    if ($id <= 0) {
        $error = "ID inválid.";
    } else {
        if ($id === (int)$_SESSION["usuario_id"]) {
            $error = "No pots eliminar el teu propi compte d’admin.";
        } else {
            $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $mensaje = "Usuari eliminat";
            } else {
                $error = "Error en eliminar l’usuari.";
            }
            $stmt->close();
        }
    }
}

$res = $conexion->query("SELECT id, nombre, correo, rol, fecha_creacion FROM usuarios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eventify | Administrar usuaris</title>

    <link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/footer.css" />
    <link rel="stylesheet" href="../css/admin_usuarios.css" />
</head>
<body>

<?php include_once __DIR__ . "/../src/header.php"; ?>

<main class="section">
    <div class="container">
        <div class="admin-top">
            <div>
                <h2>Gestió d’usuaris</h2>
                <p class="section-subtitle">Llistat, canvi de rol i eliminació d’usuaris.</p>
            </div>
            <a href="panel_admin.php" class="btn btn-outline">Tornar al panel</a>
        </div>

        <?php if ($mensaje !== ""): ?>
            <div class="alert-ok"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="tabla tabla-usuarios">
                <div class="tabla-head">
                    <div>ID</div>
                    <div>Nom</div>
                    <div>Correu</div>
                    <div>Rol</div>
                    <div>Creat</div>
                    <div>Accions</div>
                </div>

                <?php if (!$res || $res->num_rows === 0): ?>
                    <div class="tabla-row">
                        <div class="muted">No hi ha usuaris.</div>
                    </div>
                <?php else: ?>
                    <?php while ($u = $res->fetch_assoc()): ?>
                        <div class="tabla-row">
                            <div><?php echo (int)$u["id"]; ?></div>
                            <div><?php echo htmlspecialchars($u["nombre"]); ?></div>
                            <div><?php echo htmlspecialchars($u["correo"]); ?></div>

                            <div>
                                <form method="POST" class="rol-form">
                                    <input type="hidden" name="accion" value="cambiar_rol" />
                                    <input type="hidden" name="id" value="<?php echo (int)$u["id"]; ?>" />

                                    <select name="rol">
                                        <option value="asistente"   <?php echo $u["rol"] === "asistente" ? "selected" : ""; ?>>asistent</option>
                                        <option value="organizador" <?php echo $u["rol"] === "organizador" ? "selected" : ""; ?>>organitzador</option>
                                        <option value="admin"       <?php echo $u["rol"] === "admin" ? "selected" : ""; ?>>admin</option>
                                    </select>

                                    <button type="submit" class="btn btn-small btn-primary">Guardar</button>
                                </form>
                            </div>

                            <div><?php echo htmlspecialchars($u["fecha_creacion"]); ?></div>

                            <div>
                                <form method="POST" onsubmit="return confirm('Segur que vols eliminar aquest usuari?');">
                                    <input type="hidden" name="accion" value="borrar" />
                                    <input type="hidden" name="id" value="<?php echo (int)$u["id"]; ?>" />
                                    <button type="submit" class="btn btn-small btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</main>

<?php include_once __DIR__ . "/../src/footer.php"; ?>

</body>
</html>
