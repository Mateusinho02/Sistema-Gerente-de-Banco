<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['id_gerente'])) {
    header('Location: index.php');
    exit();
}

// Incluir a conexão com o banco de dados
include 'conexao.php';

// Verificar se o parâmetro id_cliente está presente na URL
if (isset($_GET['id_cliente'])) {
    $id_cliente = $_GET['id_cliente'];

    // Verificar se o cliente existe antes de excluir
    $sql_verificar_cliente = "SELECT id, nome FROM cliente WHERE id = $id_cliente";
    $resultado_verificar_cliente = $conn->query($sql_verificar_cliente);

    if ($resultado_verificar_cliente->num_rows > 0) {
        // Cliente encontrado, proceder com a exclusão dos cartões associados
        $sql_excluir_cartoes = "DELETE FROM cartao WHERE id_cliente = $id_cliente";
        $conn->query($sql_excluir_cartoes);

        // Agora podemos excluir o cliente
        $sql_excluir_cliente = "DELETE FROM cliente WHERE id = $id_cliente";
        $conn->query($sql_excluir_cliente);

        // Redirecionar para a lista de clientes após a exclusão
        header('Location: gerente.php');
        exit();
    } else {
        // Cliente não encontrado, redirecionar para a lista de clientes
        header('Location: gerente.php');
        exit();
    }
} else {
    // Redirecionar para a lista de clientes se o parâmetro id_cliente não estiver presente
    header('Location: gerente.php');
    exit();
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
