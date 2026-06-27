<?php

    require_once __DIR__ . '../class/Classes.php';

    
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, OPTIONS"); // Só aceita POST e a pergunta fantasma
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Max-Age: 86400");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    if($_SERVER['REQUEST_METHOD'] !== 'POST')
    {
        http_response_code(409);
        echo json_encode(["erro" => "Método não permitido!"]);
    }

    //captura os dados do Json 
    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->email) && !empty($data->senha))
    {
        $usuario = new Usuario();

        $resultado = $usuario->login($data->email, $data->senha);

        if($resultado['status'] === 'sucesso'){
            http_response_code(200);
            echo json_encode([
                "mensagem" => "Login realizado com sucesso!",
                "token" => $resultado['token']
            ]);
        } else {
            http_response_code(401); //usuario nao encontrado
            echo json_encode(["erro" => "Usuario nao encontrado!"]);
        }

    } else {
        http_response_code(400); // 400 Bad Request
        echo json_encode(["erro" => "Por favor, preencha o e-mail e a senha."]);
    }

?>