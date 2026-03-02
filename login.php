<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Gestão</title>
<!-- Fonte Inter -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<!-- Boxicons -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="./css/style.css">
</head>

<body class="login-body">

<div class="background-glow"></div>

<div class="login-container">
    <div class="login-card">

        <h1>Bem-vindo</h1>
        <p class="subtitle">Acesse sua conta para continuar</p>

        <div class="input-field">
            <i class='bx bx-user'></i>
            <input type="text" placeholder="Usuário">
        </div>

        <div class="input-field">
            <i class='bx bx-lock-alt'></i>
            <input type="password" placeholder="Senha">
        </div>

        <div class="forgot">
            <a href="#">Esqueceu a senha?</a>
        </div>

        <button class="btn-login" onclick="window.location.href='dashboard.php'">
            Entrar
        </button>

        <div class="divider">
            <span>ou continue com</span>
        </div>

        <div class="social-login">
            <button class="social-btn">
                <i class='bx bxl-google'></i>
            </button>
            <button class="social-btn">
                <i class='bx bxl-github'></i>
            </button>
        </div>

    </div>
</div>

</body>
</html>