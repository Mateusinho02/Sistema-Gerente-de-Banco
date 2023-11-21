<?php
// Inclua a conexão com o banco de dados (conexao.php)
include 'conexao.php';


// Verifique se há um ID de cliente na URL
if (!isset($_GET['id_cliente']) || empty($_GET['id_cliente'])) {
    header('Location: tela_gerente.php');
    exit();
}


$id_cliente = $_GET['id_cliente'];


// Consulta SQL para obter as informações do cliente
$sql_cliente = "SELECT * FROM cliente WHERE id = $id_cliente";
$resultado_cliente = $conn->query($sql_cliente);


// Verifique se o cliente existe
if ($resultado_cliente->num_rows === 0) {
    header('Location: tela_gerente.php');
    exit();
}


$cliente = $resultado_cliente->fetch_assoc();


// Consulta SQL para obter as informações do cartão
$sql_cartao = "SELECT * FROM cartao WHERE id_cliente = $id_cliente";
$resultado_cartao = $conn->query($sql_cartao);


// Verifique se o cliente possui um cartão
if ($resultado_cartao->num_rows > 0) {
    $cartao = $resultado_cartao->fetch_assoc();
} else {
    $cartao = null;
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="visualizar_cliente.css">
    <title>Visualizar Cliente</title>
</head>
<body>


    <div class="container">
        <h2>Detalhes do Cliente</h2>


        <table>
            <tr>
                <th>ID do Cliente</th>
                <td><?php echo $cliente['id']; ?></td>
            </tr>
            <tr>
                <th>Nome</th>
                <td><?php echo $cliente['nome']; ?></td>
            </tr>
            <tr>
                <th>CPF</th>
                <td><?php echo $cliente['cpf']; ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $cliente['email']; ?></td>
            </tr>
            <tr>
                <th>Data de Nascimento</th>
                <td><?php echo $cliente['data_nascimento']; ?></td>
            </tr>
            <tr>
                <th>Endereço</th>
                <td><?php echo $cliente['endereco']; ?></td>
            </tr>
            <tr>
                <th>Telefone</th>
                <td><?php echo $cliente['telefone']; ?></td>
            </tr>
            <tr>
                <th>Cartão</th>
                <td>
                    <?php
                    if ($cartao) {
                        echo 'Número: ' . $cartao['numero'] . '<br>';
                        echo 'Código: ' . $cartao['codigo'];
                    } else {
                        echo 'Nenhum cartão cadastrado';
                    }
                    ?>
                </td>
            </tr>
        </table>
        <br>
        <a href="gerente.php" class="botao">Voltar</a>
    </div>


</body>
</html>


