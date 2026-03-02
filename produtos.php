<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/produtos.css">
    <link rel="stylesheet" href="./css/modalSair.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<?php include("components/sidebar.php"); ?>
 
<div class="main">
    <div class="topbar">
        <h1>Produtos</h1>

        <button class="btn-add-product" onclick="abrirModalProduto()">
            <i class='bx bx-plus'></i>
            Novo Produto
        </button>
    </div>

    <div class="products-grid">

    <!-- CARD 1 -->
    <div class="product-card">
        <div class="product-image">
            <img src="https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcSv4mEsKj7jOlaoKR33qlkkgKtkVmb2TuHeHUg8Mi1My2v5Ii5GvsBI5EVR_FxX2xuqd6MdMpgFZPPsShhWJBCGZpkWVjUp-jajtvezet2lc21ji3Yeyy6RBg&usqp=CAc" alt="Produto">
            <div class="badge">ID #001 • Eletrônicos</div>
        </div>

        <div class="product-content">
            <h3>Mouse Gamer RGB</h3>
            <span class="price"><i>R$ 149,90</i></span>
            <p>Mouse ergonômico com iluminação RGB personalizável e alta precisão para jogos.</p>

            <span class="stock in-stock">Em estoque</span>

            <div class="card-actions">
                <button class="btn primary">Alterar</button>
                <button class="btn danger">Remover</button>
            </div>
        </div>
    </div>


    <!-- CARD 2 -->
    <div class="product-card">
        <div class="product-image">
            <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8" alt="Produto">
            <div class="badge">ID #002 • Informática</div>
        </div>

        <div class="product-content">
            <h3>Notebook Ultra Slim</h3>
            <span class="price"><i>R$ 149,90</i></span>
            <p>Notebook leve e potente com SSD NVMe e tela Full HD ideal para produtividade.</p>

            <span class="stock out-stock">Esgotado</span>

            <div class="card-actions">
                <button class="btn primary">Alterar</button>
                <button class="btn danger">Remover</button>
            </div>
        </div>
    </div>


    <!-- CARD 3 -->
    <div class="product-card">
        <div class="product-image">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30" alt="Produto">
            <div class="badge">ID #003 • Acessórios</div>
        </div>

        <div class="product-content">
            <h3>Smartwatch Series X</h3>
            <span class="price"><i>R$ 149,90</i></span>
            <p>Relógio inteligente com monitoramento cardíaco, notificações e bateria de longa duração.</p>

            <span class="stock in-stock">Em estoque</span>

            <div class="card-actions">
                <button class="btn primary">Alterar</button>
                <button class="btn danger">Remover</button>
            </div>
        </div>
    </div>

        <div class="product-card">
        <div class="product-image">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30" alt="Produto">
            <div class="badge">ID #003 • Acessórios</div>
        </div>

        <div class="product-content">
            <h3>Smartwatch Series X</h3>
            <span class="price"><i>R$ 149,90</i></span>
            <p>Relógio inteligente com monitoramento cardíaco, notificações e bateria de longa duração.</p>

            <span class="stock in-stock">Em estoque</span>

            <div class="card-actions">
                <button class="btn primary">Alterar</button>
                <button class="btn danger">Remover</button>
            </div>
        </div>
    </div>

        <div class="product-card">
        <div class="product-image">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30" alt="Produto">
            <div class="badge">ID #003 • Acessórios</div>
        </div>

        <div class="product-content">
            <h3>Smartwatch Series X</h3>
            <span class="price"><i>R$ 149,90</i></span>
            <p>Relógio inteligente com monitoramento cardíaco, notificações e bateria de longa duração.</p>

            <span class="stock in-stock">Em estoque</span>

            <div class="card-actions">
                <button class="btn primary">Alterar</button>
                <button class="btn danger">Remover</button>
            </div>
        </div>
    </div>

        <div class="product-card">
        <div class="product-image">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30" alt="Produto">
            <div class="badge">ID #003 • Acessórios</div>
        </div>

        <div class="product-content">
            <h3>Smartwatch Series X</h3>
            <span class="price"><i>R$ 149,90</i></span>
            <p>Relógio inteligente com monitoramento cardíaco, notificações e bateria de longa duração.</p>

            <span class="stock in-stock">Em estoque</span>

            <div class="card-actions">
                <button class="btn primary">Alterar</button>
                <button class="btn danger">Remover</button>
            </div>
        </div>
    </div>

</div>

</div>


<!-- MODAL DE + NOVO PRODUTO -->
 <div id="productModal" class="modal">
    <div class="modal-product">

        <div class="modal-header">
            <h2>Novo Produto</h2>
            <i class='bx bx-x' onclick="fecharModalProduto()"></i>
        </div>

        <form class="product-form">

            <div class="form-grid">

                <div class="form-group">
                    <label>Nome do Produto</label>
                    <input type="text" placeholder="Digite o nome">
                </div>

                <div class="form-group">
                    <label>Preço</label>
                    <input type="number" placeholder="0.00">
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
                    <label>Quantidade em Estoque</label>
                    <input type="number" placeholder="0">
                </div>

                <div class="form-group full">
                    <label>Descrição</label>
                    <textarea rows="3" placeholder="Descrição curta do produto"></textarea>
                </div>

                <div class="form-group full">
                    <label>Imagem do Produto</label>
                    <input type="file">
                </div>

            </div>

            <div class="modal-actions">
                <button type="button" class="btn cancel" onclick="fecharModalProduto()">Cancelar</button>
                <button type="submit" class="btn primary">Salvar Produto</button>
            </div>

        </form>

    </div>
</div>






<?php include("components/modalSair.php"); ?>
<script src="./js/main.js"></script>
</body>
</html>