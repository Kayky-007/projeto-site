<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar</title>
    <link rel="stylesheet" href="./css/produto_editar.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <?php include("components/sidebar.php"); ?>

<div class="main">

    <div class="topbar">
        <h1>Editar Produto</h1>
    </div>

    <div class="form-container">

        <form class="product-form">

        <div class="edit-meta">
  <span class="badge badge-id">ID #001</span>
  <span class="badge badge-cat">Eletrônicos</span>
</div>

            <div class="form-grid">

                <div class="form-group">
                    <label>Nome do Produto</label>
                    <input type="text" value="Mouse Gamer RGB">
                </div>

                <div class="form-group">
                    <label>Preço</label>
                    <input type="number" value="149.90">
                </div>

                <div class="form-group">
                    <label>Categoria</label>
                    <select>
                        <option>Eletrônicos</option>
                        <option>Informática</option>
                        <option>Acessórios</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Estoque</label>
                    <input type="number" value="12">
                </div>

                <div class="form-group full">
                    <label>Descrição</label>
                    <textarea rows="3">Mouse gamer com RGB personalizável</textarea>
                </div>

            </div>

            <div class="form-actions">

                <a href="produtos.php" class="btn cancel">
                    Cancelar
                </a>

                <button type="button" class="btn primary" onclick="salvarAlteracoes()">
                    Salvar Alterações
                </button>

            </div>

        </form>

    </div>

</div>

<script src="js/main.js"></script>
</body>
</html>