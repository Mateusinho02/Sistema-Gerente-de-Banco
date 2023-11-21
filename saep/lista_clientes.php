<?php
// Iniciar a sessão
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['id_gerente'])) {
    header('Location: index.php');
    exit();
}

// Incluir a conexão com o banco de dados
include 'conexao.php';

// Inicializar variáveis
$nome = $cpf = $email = $data_nascimento = $endereco = $telefone = '';
$erro_cliente = $erro_cartao = '';

// Função para formatar o CPF
function formatarCPF($cpf)
{
    return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
}

// Função para formatar o telefone
function formatarTelefone($telefone)
{
    return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
}

// Processar o formulário de cadastro de cliente quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_cliente'])) {
    // Validar os campos do formulário (você pode adicionar validações mais robustas conforme necessário)
    $nome = $_POST['nome'];
    $cpf = formatarCPF($_POST['cpf']);
    $email = $_POST['email'];
    $data_nascimento = $_POST['data_nascimento'];
    $endereco = $_POST['endereco'];
    $telefone = formatarTelefone($_POST['telefone']);

    // Inserir os dados do cliente no banco de dados
    $sql_inserir_cliente = "INSERT INTO cliente (nome, cpf, email, data_nascimento, endereco, telefone, id_gerente)
                            VALUES ('$nome', '$cpf', '$email', '$data_nascimento', '$endereco', '$telefone', '".$_SESSION['id_gerente']."')";

    if ($conn->query($sql_inserir_cliente) === TRUE) {
        header('Location: lista_clientes.php'); // Redirecionar para a lista de clientes após o cadastro
        exit();
    } else {
        $erro_cliente = 'Erro ao cadastrar o cliente: ' . $conn->error;
    }
}

// Processar o formulário de cadastro de cartão quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_cartao'])) {
    // Validar os campos do formulário (você pode adicionar validações mais robustas conforme necessário)
    $id_cliente = $_POST['id_cliente'];
    $cartao_numero = $_POST['cartao'];

    // Inserir os dados do cartão no banco de dados
    $sql_inserir_cartao = "INSERT INTO cartao (id_cliente, numero)
                           VALUES ('$id_cliente', '$cartao_numero')";

    if ($conn->query($sql_inserir_cartao) === TRUE) {
        // Redirecionar ou realizar outras ações após o cadastro do cartão
        // ...
    } else {
        $erro_cartao = 'Erro ao cadastrar o cartão: ' . $conn->error;
    }
}

// Fechar a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="lista_cliente.css">
    <title>Cadastrar Cliente</title>
</head>
<body>
    <div class="container">
        <h2>Cadastrar Novo Cliente</h2>

        <!-- Exibir mensagem de erro do cliente, se houver -->
        <?php if (!empty($erro_cliente)) : ?>
            <p class="erro"><?php echo $erro_cliente; ?></p>
        <?php endif; ?>

        <!-- Formulário de Cadastro de Cliente -->
        <form method="post" action="">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email">

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento">

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco">

            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone">

            <br>
            <button type="submit" class="botao" name="cadastrar_cliente">Cadastrar Cliente</button><br>
        </form>

      

        <!-- Exibir mensagem de erro do cartão, se houver -->
        <?php if (!empty($erro_cartao)) : ?>
            <p class="erro"><?php echo $erro_cartao; ?></p>
        <?php endif; ?>


        <br><a href="gerente.php" class="small-button">Voltar</a>
    </div>
</body>
</html>
