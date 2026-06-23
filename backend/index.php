<?php
    session_start();

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json; charset=utf-8');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
        http_response_code(200);
        exit();
    }

    define('BASE_DIR', __DIR__ . '/api/');

    //Tabela de rotas
    $rotas = [
        //Endpoint Login
        'api/login' => [ 
            'file' => 'auth/login.php',
            'login' => false,
            'grupos' => null
    ],
        //Endpoint Cadastro
        'api/cadastro' => [
            'file' => 'auth/cadastro.php',
            'login' => false,
            'grupos' => null
        ]
    ];

    //Captura de URL
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $route = trim($requestUri, '/');

    //Processamento da rota
    if (array_key_exists($route, $rotas)){
        $configRota = $rotas[$route];
        $file = $configRota['file'];
    } else {
        http_response_code(404);
        echo json_encode(["status" => "erro", "mensagem" => "Endpoint não encontrado: " . $route]);
        exit;
    }

    //Inclusão do arquivo do endpoint
    $filePath = BASE_DIR . $file;

    if (file_exists($filePath)){
        include $filePath;
    } else {
        http_response_code(500);
        echo json_encode(["status" => "erro", "mensagem" => "Arquivo interno ausente: {$file}"]);
        exit;
    }
?>