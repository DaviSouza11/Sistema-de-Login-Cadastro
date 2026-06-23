import React, { useState } from 'react';
import './Cadastro.css';

interface CadastroProps {
  onNavigateLogin: () => void; // Função para voltar à tela de login
}

const Cadastro: React.FC<CadastroProps> = ({ onNavigateLogin }) => {
  const [nome, setNome] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');

  const handleCadastro = async (e: React.FormEvent) => {
    e.preventDefault();

    // Validação simples para ver se as senhas batem
    if (password !== confirmPassword) {
      alert("As senhas não coincidem!");
      return;
    }

    const payload = {
      acao: 'cadastro', // Uma dica para o seu futuro PHP saber o que fazer
      nome,
      email,
      password
    };

    try {
      const resposta = await fetch('http://localhost:8080/index.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload)
      });

      const dados = await resposta.json();
      console.log("Retorno do Servidor:", dados);

      alert("Cadastro realizado com sucesso! Faça seu login.");
      onNavigateLogin(); // Manda o usuário de volta para o Login
      
    } catch (erro) {
      console.error("Erro na requisição:", erro);
      alert("Erro ao conectar com o servidor.");
    }
  };

  return (
    <div className="cadastro-container">
      <div className="cadastro-card">
        
        <div className="cadastro-header">
          <h2>Criar Conta</h2>
          <p>Preencha os dados para se cadastrar</p>
        </div>

        <form onSubmit={handleCadastro}>
          
          <div className="input-group">
            <label>Nome Completo</label>
            <input
              type="text"
              value={nome}
              onChange={(e) => setNome(e.target.value)}
              placeholder="Digite seu nome"
              required
            />
          </div>

          <div className="input-group">
            <label>E-mail</label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder="Digite seu e-mail"
              required
            />
          </div>

          <div className="input-group">
            <label>Senha</label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="Crie uma senha"
              required
            />
          </div>

          <div className="input-group">
            <label>Confirmar Senha</label>
            <input
              type="password"
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
              placeholder="Repita a senha"
              required
            />
          </div>

          <button type="submit" className="cadastro-btn">
            CADASTRAR
          </button>
          
        </form>

        <div className="cadastro-footer">
          Já tem uma conta?{' '}
          <button className="link-btn" onClick={onNavigateLogin}>
            Faça Login
          </button>
        </div>
        
      </div>
    </div>
  );
};

export default Cadastro;