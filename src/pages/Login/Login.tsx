import React, { useState } from 'react';
import './Login.css';

// 1. Definimos o que o componente espera receber de fora
interface LoginProps {
  onLoginSuccess: () => void;
  onNavigateCadastro: () => void;
}

const Login: React.FC<LoginProps> = ({ onLoginSuccess, onNavigateCadastro }) => {
  // 2. Estados com tipagem implícita (TypeScript sabe que é string)
  const [email, setEmail] = useState('');
  const [senha, setSenha] = useState('');

  // 3. Tipamos o evento como React.FormEvent para o TypeScript não reclamar
  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();

    // AJUSTE: Traduzimos 'password' para 'senha' para o PHP entender perfeitamente
    const payload = {
      email: email,
      senha: senha
    };

    try {
      // Faz o disparo para a API PHP local
      const resposta = await fetch('http://localhost:8080/api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload)
      });

      const dados = await resposta.json();
      console.log("Retorno da API:", dados);

      // AJUSTE: Lógica real validando a resposta do servidor
      if (resposta.ok) {
        
        // Guarda a "pulseira VIP" (JWT) no bolso do navegador
        if (dados.token) {
          localStorage.setItem('token', dados.token);
        }
        onLoginSuccess(); // Troca de tela apenas se deu tudo certo!
        
      } else {
        // Se a senha estiver errada, o PHP devolve status 401 e cai aqui
        alert("Erro: " + (dados.erro || "E-mail ou senha incorretos."));
      }
      
    } catch (erro) {
      console.error("Erro na requisição:", erro);
      alert("Não foi possível conectar ao servidor PHP.");
    }
  };

  return (
    <div className="login-container">
      <div className="login-card">
        <div className="login-header">
          <h2>Acessar Plataforma</h2>
          <p>Bem-vindo de volta!</p>
        </div>

        <form onSubmit={handleLogin}>
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
              value={senha}
              onChange={(e) => setSenha(e.target.value)}
              placeholder="Digite sua senha"
              required
            />
          </div>

          <div className="forgot-password">
            <a href="#esqueceu">Esqueceu a senha?</a>
          </div>

          <button type="submit" className="login-btn">
            ENTRAR
          </button>
        </form>

        <div className="login-footer">
          Ainda não tem uma conta?{' '}
          <button type="button" className="link-btn" onClick={onNavigateCadastro} style={{background: 'none', border: 'none', color: '#1a73e8', fontWeight: 'bold', cursor: 'pointer'}}>
            Cadastre-se
          </button>
        </div>
      </div>
    </div>
  );
};

export default Login;