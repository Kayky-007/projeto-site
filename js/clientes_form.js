
document.getElementById('campo-telefone').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '').slice(0, 11);

    if (v.length <= 10) {
        v = v.replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
    } else {
        v = v.replace(/^(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
    }

    this.value = v;
});

// ── Máscara de CPF/CNPJ automática conforme o tipo selecionado
document.getElementById('campo-documento').addEventListener('input', function () {
    const tipo = document.getElementById('campo-tipo').value;
    let v = this.value.replace(/\D/g, '');

    if (tipo === 'pf') {
        v = v.slice(0, 11);
        v = v.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
    } else {
        v = v.slice(0, 14);
        v = v.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{0,2})/, '$1.$2.$3/$4-$5');
    }

    this.value = v;
});

// Atualiza o placeholder do documento ao trocar tipo
document.getElementById('campo-tipo').addEventListener('change', function () {
    const doc = document.getElementById('campo-documento');
    doc.value = '';
    doc.placeholder = this.value === 'pj' ? '00.000.000/0001-00' : '000.000.000-00';
});

// ── Máscara de CEP: 00000-000
document.getElementById('campo-cep').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '').slice(0, 8);
    v = v.replace(/^(\d{5})(\d{0,3})/, '$1-$2');
    this.value = v;
});

// ── ViaCEP: busca endereço ao sair do campo CEP
document.getElementById('campo-cep').addEventListener('blur', function () {
    const cep = this.value.replace(/\D/g, '');

    if (cep.length !== 8) return;

    // Mostra feedback de carregando
    document.getElementById('campo-endereco').placeholder  = 'Buscando endereço...';
    document.getElementById('campo-bairro').placeholder    = 'Buscando...';
    document.getElementById('campo-cidade').placeholder    = 'Buscando...';
    document.getElementById('campo-estado').placeholder    = 'Buscando...';

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(r => r.json())
        .then(dados => {
            if (dados.erro) {
                alert('CEP não encontrado. Verifique e tente novamente.');
                limparEndereco();
                return;
            }

            // Preenche os campos automaticamente
            document.getElementById('campo-endereco').value = dados.logradouro  || '';
            document.getElementById('campo-bairro').value   = dados.bairro      || '';
            document.getElementById('campo-cidade').value   = dados.localidade  || '';
            document.getElementById('campo-estado').value   = dados.uf          || '';

            // Coloca o foco no complemento para o usuário preencher
            document.getElementById('campo-complemento').focus();
        })
        .catch(() => {
            alert('Erro ao buscar CEP. Verifique sua conexão.');
            limparEndereco();
        });
});

function limparEndereco() {
    ['campo-endereco','campo-bairro','campo-cidade','campo-estado'].forEach(id => {
        const el = document.getElementById(id);
        el.value = '';
        el.placeholder = '';
    });
}

// ── Validação antes de enviar o formulário
document.getElementById('formCadastro').addEventListener('submit', function (e) {
    e.preventDefault();

    const tipo      = document.getElementById('campo-tipo').value;
    const telefone  = document.getElementById('campo-telefone').value.replace(/\D/g, '');
    const documento = document.getElementById('campo-documento').value.replace(/\D/g, '');

    // Telefone: mínimo 10 dígitos (com DDD), máximo 11
    if (telefone.length > 0 && (telefone.length < 10 || telefone.length > 11)) {
        alert('Telefone inválido. Informe DDD + número (10 ou 11 dígitos).');
        document.getElementById('campo-telefone').focus();
        return;
    }

    // CPF: 11 dígitos
    if (tipo === 'pf' && documento.length > 0 && documento.length !== 11) {
        alert('CPF inválido. Informe os 11 dígitos.');
        document.getElementById('campo-documento').focus();
        return;
    }

    // CNPJ: 14 dígitos
    if (tipo === 'pj' && documento.length > 0 && documento.length !== 14) {
        alert('CNPJ inválido. Informe os 14 dígitos.');
        document.getElementById('campo-documento').focus();
        return;
    }

    // Tudo ok — monta o endereço completo e envia
    const endereco   = document.getElementById('campo-endereco').value;
    const bairro     = document.getElementById('campo-bairro').value;
    const cidade     = document.getElementById('campo-cidade').value;
    const estado     = document.getElementById('campo-estado').value;
    const complemento = document.getElementById('campo-complemento').value;

    // Junta tudo em um único campo para o banco
    let enderecoCompleto = endereco;
    if (complemento) enderecoCompleto += ', ' + complemento;
    if (bairro)      enderecoCompleto += ' - ' + bairro;
    if (cidade)      enderecoCompleto += ', ' + cidade;
    if (estado)      enderecoCompleto += '/' + estado;

    document.getElementById('input-endereco-completo').value = enderecoCompleto;

    this.submit();
});