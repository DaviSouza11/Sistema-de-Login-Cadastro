<?php

require_once __DIR__ . '/../config/Conexao.php';

class Usuario
{
    private $db;

    public function __construct()
    {
        $this->db = Conexao::conexao();
    }

    //Get
    public function listar()
    {
        $stmt = $this->db->query("SELECT id, nome, email FROM usuarios");
        return $stmt->FetchAll();
    }

    //
    public function cadastrar($nome, $email, $senha)
    {

        //PDO e regra de negócio
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if($stmt->rowCount() > 0){
            http_response_code(409);
            return ["erro" => "Email já cadastrado"];
        }

        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO usuarios(nome, email, senha) VALUES (:nome, :email, :senha)");

        if ($stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $senhaCriptografada])){
            http_response_code(201);
            return ["mensagem" => "Usuario cadastrado com sucesso!"];
        } 
        
        http_response_code(500);
        return ["mensagem" => "Falha ao criar usuário!"];
    } 

    public function editar($id, $nome, $email)
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id");
        $stmt->execute([':nome' => $nome, ':email' => $email, ':id' => $id]);
        return ["mensagem" => "Usuário atualizado com sucesso!"];
    }

    public function deletar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return ["mensagem" => "Usuário removido com sucesso!"];
    }

    private function gerarMeuJWT($id, $email)
    {
        //Cabeçalho dizendo que é um JWT e qual matemática usar
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        $payload = json_encode([
            'id' => $id,
            'email' => $email,
            'exp' => time() + (60 * 60 * 2) //exp em 2 horas
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $chave_secreta = 'M1nh4_Ch4v3_S3cr3t4_Svp3r_S3gur4_!@#';

        $assinatura = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $chave_secreta, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($assinatura));

        // O JWT final é simplesmente as 3 partes coladas por um ponto final
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    }

    public function login($email, $senha)
    {
        $stmt = $this->db->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);

        //Pega a linha do banco de guarda na variável usuario (Fetch "guardar")
        $usuario = $stmt->fetch();

        //aqui verifica se o usuário existe and a senha coincide
        if ($usuario && password_verify($senha, $usuario['senha'])){
            
            //
            $token_jwt = $this->gerarMeuJWT($usuario['id'], $usuario['email']);

            http_response_code(200);
            return [
                "mensagem" => "Login realizado com sucesso!",
                "token" => $token_jwt,
                "usuario" => [
                    "id" => $usuario['id'],
                    "nome" => $usuario['nome'],
                    "email" => $usuario['email']
                ]
                ];
        } 

        http_response_code(401);
        return ["erro" => "Email ou senha incorretos."];
            

    }

    public function validarJWT($token_jwt)
    {

        //separa as 3 partes do token (header, payload e assinatura)
        $partes = explode('.', $token_jwt);
        if (count($partes) != 3){
            return false; //Token com estrutura inválida
        }

        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $partes;

        //Recria a assinatura usando a chave secreta de login
        $chave_secreta = 'M1nh4_Ch4v3_S3cr3t4_Svp3r_S3gur4_!@#';
        $assinaturaValida = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature, true);
        $base64UrlSignatureValida = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($assinaturaValida));

        //Verifica se a assinatura coincide com a conta
        if ($base64UrlSignature !== $base64UrlSignatureValida){
            return false; //token falsificado ou corrompido
        }

        //decodifica os dados do payload para verificar a expiração
        $payload = json_encode(base64_decode($base64UrlPayload), true);

        if ($payload['exp' < time()]){
            return false; //token expirado
        }

        return $payload;
    }
    
}