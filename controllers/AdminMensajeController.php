<?php
session_start();

require_once '../config/database.php';

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../html/login.php");
    exit;
}

if ($_SESSION["usuario_rol"] !== "admin") {
    header("Location: ../html/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../html/admin/mensajes.php");
    exit;
}

$id = $_POST["id"] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: ../html/admin/mensajes.php?error=id_invalido");
    exit;
}

try {
    $sql = "DELETE FROM contactos WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: ../html/admin/mensajes.php?success=eliminado");
    exit;

} catch (PDOException $e) {
    header("Location: ../html/admin/mensajes.php?error=db_error");
    exit;
}