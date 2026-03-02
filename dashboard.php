<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link rel="stylesheet" href="./css/style.css">
<link rel="stylesheet" href="./css/sidebar.css">
<link rel="stylesheet" href="./css/modalSair.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<body>

<?php include("components/sidebar.php"); ?>

<div class="main">
    <div class="topbar">
        <h1>Dashboard</h1>
    </div>

    <div class="cards">
        <div class="card">
            <i class='bx bx-user'></i>
            <h3>120</h3>
            <p>Clientes</p>
        </div>

        <div class="card">
            <i class='bx bx-box'></i>
            <h3>58</h3>
            <p>Produtos</p>
        </div>

        <div class="card">
            <i class='bx bx-cart'></i>
            <h3>32</h3>
            <p>Pedidos</p>
        </div>
    </div>
</div>

<?php include("components/modalSair.php"); ?>



<script src="./js/main.js"></script>
</body>
</html>