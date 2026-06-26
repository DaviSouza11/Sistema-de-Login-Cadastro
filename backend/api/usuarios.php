<?php
    // Traz a classe que criamos acima
    require_once __DIR__ . '/../class/Usuario.php';

    // 1. Configurações de CORS e Headers (Exatamente como você mandou)
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Max-Age: 86400");

    // 2. Preflight do CORS
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    // 3. Captura o verbo HTTP
    $metodo = $_SERVER['REQUEST_METHOD'];

    $usuario = new Usuario();

    switch($metodo){
        case 'GET':
            //Caso exista um ID, ele busca apenas os dados do usuário atrelado ao ID
            if(isset($_GET['id'])){
                echo json_encode(["mensagem" => "Mostrar usuário " . $_GET['id']]);
            //Faz uma listagem completa
            } else {
                echo json_encode($usuario->listar());
            }
            break;

        case 'POST':
            //Recebe os dados do REACT
            $data = json_decode(file_get_contents("php://input"));

            //Passa os atributos do objeto (ex: $data->nome) para o metódo
            echo json_encode($usuario->cadastrar(
                $data->nome ?? '',
                $data->email ?? '',
                $data->password ?? '',
            ));
            break;
        
        case 'PUT':
            $data = json_decode(file_get_contents("php://input"));
            echo json_encode($usuario->editar(
                $data->id ?? '',
                $data->nome ?? '',
                $data->email ?? '',
            ));
            break;
        
        case 'DELETE':
            $data = json_decode(file_get_contents("php://input"));
            echo json_encode($usuario->deletar($data->id));
            break;

        default:
        http_response_code(500);
        echo json_encode(["erro" => "Método não permitido"]);
        break;
    }   