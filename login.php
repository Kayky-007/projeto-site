<?php

session_start();

require_once "model/Usuario.php";

// Se já estiver logado, vai direto pro dashboard
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = trim($_POST['login'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (empty($login) || empty($senha)) {
        $erro = 'Preencha o login e a senha.';
    } else {

        $usuario = new Usuario();
        $dados   = $usuario->login($login, $senha);

        if ($dados) {
            // Salva os dados do usuario na sessão
            $_SESSION['usuario'] = [
                'id'         => $dados['id_usuario'],
                'nome'       => $dados['nome_usuario'],
                'login'      => $dados['login_usuario'],
                'permissao'  => $dados['tipo_permissao'],
            ];

            header("Location: dashboard.php");
            exit;

        } else {
            $erro = 'Login ou senha incorretos.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Gestão</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet"/>
</head>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Nunito', sans-serif;
        background: #0f172a;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .login-card {
        background: #1e293b;
        border-radius: 20px;
        padding: 40px 36px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 8px 40px rgba(0,0,0,.4);
        border: 1.5px solid #334155;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 32px;
    }

    .logo-icone {
        width: 44px;
        height: 44px;
        background: #6c7ef8;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo-icone svg { width: 22px; height: 22px; color: white; }

    .logo-texto strong {
        display: block;
        font-size: 18px;
        font-weight: 800;
        color: #f1f5f9;
    }

    .logo-texto span {
        font-size: 12px;
        font-weight: 600;
        color: #6c7ef8;
    }

    h1 {
        font-size: 22px;
        font-weight: 800;
        color: #f1f5f9;
        margin-bottom: 6px;
    }

    .subtitulo {
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 28px;
    }

    .campo {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 16px;
    }

    .campo label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .campo-input { position: relative; }

    .campo-input svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 17px;
        height: 17px;
        color: #475569;
        pointer-events: none;
    }

    .campo input {
        width: 100%;
        padding: 11px 12px 11px 38px;
        border-radius: 10px;
        border: 1.5px solid #334155;
        background: #0f172a;
        font-family: 'Nunito', sans-serif;
        font-size: 14px;
        font-weight: 600;
        color: #f1f5f9;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .campo input:focus {
        border-color: #6c7ef8;
        box-shadow: 0 0 0 3px rgba(108,126,248,.20);
    }

    .campo input::placeholder { color: #475569; }

    .erro {
        background: rgba(239,68,68,.12);
        border: 1.5px solid rgba(239,68,68,.3);
        color: #f87171;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .erro svg { width: 16px; height: 16px; flex-shrink: 0; }

    .btn-entrar {
        width: 100%;
        padding: 13px;
        background: #6c7ef8;
        color: white;
        border: none;
        border-radius: 11px;
        font-family: 'Nunito', sans-serif;
        font-size: 15px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(108,126,248,.35);
        transition: background .15s, transform .15s, box-shadow .15s;
        margin-top: 8px;
    }

    .btn-entrar:hover {
        background: #5668f0;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(108,126,248,.40);
    }

    .btn-entrar:active { transform: scale(0.98); }

    .rodape {
        text-align: center;
        margin-top: 24px;
        font-size: 12px;
        font-weight: 600;
        color: #334155;
    }
</style>
<body>

<div class="login-card">

    <div class="logo">
        <div class="logo-icone">
            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </div>
        <div class="logo-texto">
            <strong>Gestão da empresa</strong>
            <span>Sistema de gerenciamento</span>
        </div>
    </div>

    <h1>Bem-vindo(a)!</h1>
    <p class="subtitulo">Faça login para acessar o sistema.</p>

    <?php if ($erro): ?>
        <div class="erro">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php">

        <div class="campo">
            <label>Login</label>
            <div class="campo-input">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <input
                    type="text"
                    name="login"
                    placeholder="Seu login"
                    value="<?= htmlspecialchars($_POST['login'] ?? '') ?>"
                    required
                    autofocus
                >
            </div>
        </div>

        <div class="campo">
            <label>Senha</label>
            <div class="campo-input">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
                <input
                    type="password"
                    name="senha"
                    placeholder="Sua senha"
                    required
                >
            </div>
        </div>

        <button type="submit" class="btn-entrar">Entrar no sistema</button>

    </form>

    <p class="rodape">© <?= date('Y') ?> Todos os direitos reservados</p>

</div>

</body>
</html>