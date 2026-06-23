<?php

class Router{

    private $rotas = [];

    //Registro de rotas tipo POST
    public function post($caminho, $acao)
    {
        $this->rotas[] = [
            'metodo' => 'POST',
            'caminho' => $caminho,
            'acao' => $acao
        ];
    }

    //Registro de rotas tipo GET
    public function get($caminho, $acao)
    {
        $this->rotas[] = [
            'metodo' => 'GET',
            'caminho' => $caminho,
            'acao' => $acao
        ];
    }

    //Método que fas as rotas funcionarem
    public function rodar()
    {
        $uriAtual = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $metodoatual = $_SERVER['REQUEST_METHOD'];

        foreach ($this->rotas as $rota){
            //Se a URL e o método (GET/POST) baterem com a rota registsrada
            if($rota['caminho'] == $uriAtual && $rota['metodo'] === $metodoatual){
                //Executa a função associada a essa rota
                call_user_func($rota['acao']);
                return;
            }

            http_response_code(404);
            echo json_encode(["erro" => "Rota não encontrada. URL: $uriAtual"]);
        }
    }
}