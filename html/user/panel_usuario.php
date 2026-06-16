<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["usuario_rol"] !== "general") {
    header("Location: login.php");
    exit;
}
?>

<h1>Panel Usuario</h1>
<p>Hola, <?php echo $_SESSION["usuario_nombre"]; ?>.</p>
<p>Login correcto como usuario general.</p>