<?php

/**
 * Conexão com o banco de dados (Ambiente Local)
 */
// Definir o fuso horário do PHP para São Paulo
date_default_timezone_set('America/Sao_Paulo');

class Conexao
{
    # Variável que guarda a conexão PDO.
    protected static $db;
    
    # Reduzido levemente para ambiente local para não travar muito tempo em caso de erro
    private const MAX_CONNECT_ATTEMPTS = 3; 
    private const CONNECT_RETRY_SECONDS = 1;

    private static function connect()
    {
        # Informações sobre o banco de dados local:
        $db_host    = 'localhost'; // Endereço do seu banco local (pode ser 127.0.0.1)
        $db_nome    = 'sistema_react_php'; // Nome do banco que criamos no SQL
        $db_usuario = 'root'; // Usuário padrão de servidores locais (XAMPP/WAMP/MySQL padrão)
        $db_senha   = ''; // Senha padrão do root local geralmente é vazia (se tiver senha, coloque aqui)
        $db_driver  = 'mysql';
        $db_porta   = '3306'; // Porta padrão do MySQL

        $attempt = 0;
        do {
            try {
                # Atribui o objeto PDO à variável $db.
                self::$db = new PDO(
                    "$db_driver:host=$db_host;port=$db_porta;dbname=$db_nome;charset=utf8mb4",
                    $db_usuario,
                    $db_senha,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
                return;
            } catch (PDOException $e) {
                $attempt++;
                if ($attempt >= self::MAX_CONNECT_ATTEMPTS) {
                    throw new PDOException('Falha na conexão: ' . $e->getMessage(), (int)$e->getCode(), $e);
                }
                sleep(self::CONNECT_RETRY_SECONDS);
            }
        } while ($attempt < self::MAX_CONNECT_ATTEMPTS);
    }

    # Método estático - acessível sem instanciação.
    # Exemplo de uso: $pdo = Conexao::conexao();
    public static function conexao()
    {
        # Garante uma única instância. Se não existe uma conexão, criamos uma nova.
        if (!self::$db) {
            self::connect();
        }
        # Retorna a conexão.
        return self::$db;
    }
}