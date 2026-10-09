
<?php

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

require_once "config.php";

try {
    $metodo = $_SERVER["REQUEST_METHOD"];
    $rota = $_GET["rota"] ?? "clientes";

    // LISTAR CLIENTES (READ)
    if ($rota === "clientes" && $metodo === "GET") {

        $sql = "SELECT * FROM Clientes";
        $stmt = $con->query($sql);

        echo json_encode(
            $stmt->fetchAll(),
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }

    // CADASTRAR CLIENTE (CREATE)
    if ($rota === "clientes" && $metodo === "POST") {

        $nome = trim($_POST["nome"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $telefone = trim($_POST["telefone"] ?? "");
        $endereco = trim($_POST["endereco"] ?? "");

        if (
            $nome === "" ||
            $email === "" ||
            $telefone === "" ||
            $endereco === ""
        ) {
            http_response_code(400);

            echo json_encode([
                "erro" => "Preencha todos os campos."
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $sql = "INSERT INTO Clientes
                (nome, email, telefone, endereco)
                VALUES (:nome, :email, :telefone, :endereco)";

        $stmt = $con->prepare($sql);

        $stmt->execute([
            ":nome" => $nome,
            ":email" => $email,
            ":telefone" => $telefone,
            ":endereco" => $endereco
        ]);

        http_response_code(201);

        echo json_encode([
            "mensagem" => "Cliente cadastrado com sucesso!"
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // ROTA NÃO ENCONTRADA
    http_response_code(404);

    echo json_encode([
        "erro" => "Rota não encontrada."
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $erro) {

    error_log($erro->getMessage());

    http_response_code(500);

    echo json_encode([
        "erro" => "Erro ao consultar ou cadastrar cliente. Verifique o banco e as tabelas."
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $erro) {

    error_log($erro->getMessage());

    http_response_code(500);

    echo json_encode([
        "erro" => "Ocorreu um erro interno no servidor."
    ], JSON_UNESCAPED_UNICODE);
}

