<?php

session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../html/login.php");
    exit;
}

$action = $_POST["action"] ?? "";

if ($action === "register") {
    register($conn);
} elseif ($action === "login") {
    login($conn);
} elseif ($action === "logout") {
    logout();
} else {
    header("Location: ../html/login.php?error=accion_invalida");
    exit;
}

function register($conn) {
    $nombre = trim($_POST["nombre"] ?? "");
    $apellido = trim($_POST["apellido"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $password_confirm = $_POST["password_confirm"] ?? "";
    $terminos = isset($_POST["terminos"]);

    if (
        empty($nombre) ||
        empty($apellido) ||
        empty($email) ||
        empty($password) ||
        empty($password_confirm)
    ) {
        header("Location: ../html/registro.php?error=campos_vacios");
        exit;
    }

    if (!$terminos) {
    header("Location: ../html/registro.php?error=terminos_no_aceptados");
    exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../html/registro.php?error=email_invalido");
        exit;
    }

    if ($password !== $password_confirm) {
        header("Location: ../html/registro.php?error=password_no_coincide");
        exit;
    }

    if (strlen($password) < 8) {
        header("Location: ../html/registro.php?error=password_corta");
        exit;
    }

    $checkSql = "SELECT id FROM usuarios WHERE email = :email";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bindParam(":email", $email);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        header("Location: ../html/registro.php?error=email_existente");
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios 
            (nombre, apellido, telefono, email, password, rol)
            VALUES
            (:nombre, :apellido, :telefono, :email, :password, 'general')";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":apellido", $apellido);
    $stmt->bindParam(":telefono", $telefono);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $hashedPassword);

    $stmt->execute();

    header("Location: ../html/login.php?registro=exitoso");
    exit;
}

function login($conn) {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($email) || empty($password)) {
        header("Location: ../html/login.php?error=campos_vacios");
        exit;
    }

    $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario || !password_verify($password, $usuario["password"])) {
        header("Location: ../html/login.php?error=credenciales_invalidas");
        exit;
    }

    $_SESSION["usuario_id"] = $usuario["id"];
    $_SESSION["usuario_nombre"] = $usuario["nombre"];
    $_SESSION["usuario_email"] = $usuario["email"];
    $_SESSION["usuario_rol"] = $usuario["rol"];

    if ($usuario["rol"] === "admin") {
        header("Location: ../html/panel_admin.php");
        exit;
    }

    header("Location: ../html/panel_usuario.php");
    exit;
}

function logout() {
    session_start();
    session_unset();
    session_destroy();

    header("Location: ../html/login.php?logout=exitoso");
    exit;
}