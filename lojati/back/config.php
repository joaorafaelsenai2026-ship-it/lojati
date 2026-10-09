
<?php

$host = "localhost";
$banco = "loja_ti";
$usuario = "root";
$senha = "";

try {
    $con = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );

    $con->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $con->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $erro) {
    http_response_code(500);

    header("Content-Type: application/json; charset=utf-8");

    echo json_encode([
        "erro" => "Não foi possível conectar ao banco de dados."
    ], JSON_UNESCAPED_UNICODE);

    exit;
}
