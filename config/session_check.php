<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';

if (
    !isset($_SESSION["usuario_id"]) &&
    isset($_COOKIE["remember_user"]) &&
    isset($_COOKIE["remember_token"])
) {

    $usuarioId = $_COOKIE["remember_user"];
    $token = $_COOKIE["remember_token"];
    $tokenHash = hash("sha256", $token);

    try {

        $sql = "SELECT *
                FROM usuarios
                WHERE id = :id
                AND remember_token_hash = :token_hash
                AND remember_token_expira > NOW()
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $usuarioId);
        $stmt->bindParam(":token_hash", $tokenHash);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {

            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nombre"] = $usuario["nombre"];
            $_SESSION["usuario_email"] = $usuario["email"];
            $_SESSION["usuario_rol"] = $usuario["rol"];
        }

    } catch (PDOException $e) {

        setcookie("remember_token", "", time() - 3600, "/");
        setcookie("remember_user", "", time() - 3600, "/");
    }
}