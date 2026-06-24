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
    
}