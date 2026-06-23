import React from 'react';
import './Home.css';

// Interface tipando as propriedades que o componente recebe
interface HomeProps {
  onLogout: () => void;
}

const Home: React.FC<HomeProps> = ({ onLogout }) => {
  return (
    <div className="home-container">
      <header className="home-header">
        <h2>Meu Painel</h2>
        <button className="logout-btn" onClick={onLogout}>
          Sair
        </button>
      </header>

      <main className="home-content">
        <div className="welcome-card">
          <h1>Bem-vindo ao Sistema!</h1>
          <p>O seu login se comunicou com o servidor e você acessou a página inicial com sucesso.</p>
        </div>
      </main>
    </div>
  );
};

export default Home;