<div id="deleteModal" class="modal">

    <div class="modal-delete">

        <div class="delete-icon">
            <i class='bx bx-trash'></i>
        </div>

        <h3>Excluir Produto</h3>

        <p>Tem certeza que deseja remover este produto?<br>
        Essa ação não poderá ser desfeita.</p>

        <div class="delete-actions">

            <button class="btn cancel" onclick="fecharModalExcluir()">
                Cancelar
            </button>

            <!-- Esse form envia o id para deletar.php -->
            <form id="formDeletar" method="POST" action="model/deletar_produto.php">
                <input type="hidden" name="id_produto" id="inputIdDeletar">
                <button type="submit" class="btn danger">
                    Excluir
                </button>
            </form>

        </div>

    </div>

</div>