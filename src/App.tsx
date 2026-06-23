import React, { useState } from 'react';
import Login from './pages/Login/Login';
import Home from './pages/Home/Home';
import Cadastro from './pages/Cadastro/Cadastro';
import './App.css';

const App: React.FC = () => {
  const [telaAtual, setTelaAtual] = useState<'login' | 'home' | 'cadastro'>('login');

  const realizarLogin = () => {
    setTelaAtual('home');
  };

  const realizarLogout = () => {
    setTelaAtual('login');
  };

  const irParaCadastro = () => setTelaAtual('cadastro');
  const voltarParaLogin = () => setTelaAtual('login')

  return (
    <div>
      {/* Exibe o Login e passa a função irParaCadastro para o botão "Cadastre-se" */}
      {telaAtual === 'login' && (
        <Login 
          onLoginSuccess={realizarLogin} 
          onNavigateCadastro={irParaCadastro} 
        />
      )}

      {/* Exibe a tela de Cadastro */}
      {telaAtual === 'cadastro' && (
        <Cadastro onNavigateLogin={voltarParaLogin} />
      )}

      {/* Exibe a Home */}
      {telaAtual === 'home' && (
        <Home onLogout={realizarLogout} />
      )}
    </div>
  );
};

export default App;