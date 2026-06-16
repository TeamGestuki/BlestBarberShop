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
    header("Location: ../html/admin/servicios.php");
    exit;
}

$accion = $_POST["accion"] ?? "";

if ($accion === "crear") {

    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $precio = $_POST["precio"] ?? "";
    $activo = 1;

    if (empty($nombre) || $precio === "") {
        header("Location: ../html/admin/servicios.php?error=campos_vacios");
        exit;
    }

    if (!is_numeric($precio) || $precio < 0) {
        header("Location: ../html/admin/servicios.php?error=datos_invalidos");
        exit;
    }

    try {
        $sql = "INSERT INTO servicios (nombre, descripcion, precio, activo)
                VALUES (:nombre, :descripcion, :precio, :activo)";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":activo", $activo, PDO::PARAM_INT);

        $stmt->execute();

        header("Location: ../html/admin/servicios.php?success=creado");
        exit;

    } catch (PDOException $e) {
        header("Location: ../html/admin/servicios.php?error=db_error");
        exit;
    }
}

if ($accion === "editar") {

    $id = $_POST["id"] ?? "";
    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $precio = $_POST["precio"] ?? "";
    $activo = isset($_POST["activo"]) ? 1 : 0;

    if (empty($id) || empty($nombre) || empty($precio)) {
        header("Location: ../html/admin/servicios.php?error=campos_vacios");
        exit;
    }

    if (!is_numeric($id) || !is_numeric($precio) || $precio < 0) {
        header("Location: ../html/admin/servicios.php?error=datos_invalidos");
        exit;
    }

    try {
        $sql = "UPDATE servicios
                SET nombre = :nombre,
                    descripcion = :descripcion,
                    precio = :precio
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        header("Location: ../html/admin/servicios.php?success=editado");
        exit;

    } catch (PDOException $e) {
        header("Location: ../html/admin/servicios.php?error=db_error");
        exit;
    }
}

if ($accion === "desactivar") {

    $id = $_POST["id"] ?? "";

    if (empty($id) || !is_numeric($id)) {
        header("Location: ../html/admin/servicios.php?error=datos_invalidos");
        exit;
    }

    try {
        $sql = "UPDATE servicios
                SET activo = 0
                WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ../html/admin/servicios.php?success=desactivado");
        exit;

    } catch (PDOException $e) {
        header("Location: ../html/admin/servicios.php?error=db_error");
        exit;
    }
}

if ($accion === "reactivar") {

    $id = $_POST["id"] ?? "";

    if (empty($id) || !is_numeric($id)) {
        header("Location: ../html/admin/servicios.php?error=datos_invalidos");
        exit;
    }

    try {
        $sql = "UPDATE servicios
                SET activo = 1
                WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ../html/admin/servicios.php?success=reactivado");
        exit;

    } catch (PDOException $e) {
        header("Location: ../html/admin/servicios.php?error=db_error");
        exit;
    }
}

header("Location: ../html/admin/servicios.php");
exit;