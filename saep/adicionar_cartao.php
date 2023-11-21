<?php
session_start();


// Verificar se o gerente está autenticado
if (!isset($_SESSION['id_gerente'])) {
    header('Location: index.php');
    exit();
}


// Incluir a conexão com o banco de dados
include 'conexao.php';


// Verificar se o ID do cliente foi passado pela URL
if (!isset($_GET['id_cliente'])) {
    echo "ID do cliente não especificado.";
    exit();
}


$id_cliente = $_GET['id_cliente'];


// Consultar se o cliente já possui um cartão
$sql_verificar_cartao = "SELECT id FROM cartao WHERE id_cliente = $id_cliente";
$resultado_verificar_cartao = $conn->query($sql_verificar_cartao);


// Se o cliente já tiver um cartão, redirecionar de volta à página do cliente
if ($resultado_verificar_cartao->num_rows > 0) {
    header("Location: visualizar_cliente.php?id_cliente=$id_cliente");
    exit();
}


// Processar o formulário de adição de cartão
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os dados do formulário
    $numero_cartao = isset($_POST['numero_cartao']) ? $_POST['numero_cartao'] : '';
    $codigo_cartao = isset($_POST['codigo_cartao']) ? $_POST['codigo_cartao'] : '';


    // Verificar se os campos obrigatórios não estão vazios
    if (empty($numero_cartao) || empty($codigo_cartao)) {
        echo "Erro: Preencha todos os campos obrigatórios.";
        exit();
    }


    // Inserir o cartão no banco de dados
    $sql_inserir_cartao = "INSERT INTO cartao (id_cliente, numero, codigo)
                            VALUES ($id_cliente, '$numero_cartao', '$codigo_cartao')";


    if ($conn->query($sql_inserir_cartao) === TRUE) {
        echo "Cartão adicionado com sucesso!";
        // Você pode redirecionar para a página do cliente ou realizar outra ação necessária
    } else {
        echo "Erro ao adicionar o cartão: " . $conn->error;
    }
}


?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adicionar.css">
    <title>Adicionar Cartão</title>
</head>
<body>
    <div class="container">
        <h2>Adicionar Cartão</h2>
       
        <!-- Formulário de adição de cartão -->
        <form action="" method="post">
            <label for="numero_cartao">Número do Cartão:</label>
            <input type="text" id="numero_cartao" name="numero_cartao" required>
           
            <label for="codigo_cartao">Código do Cartão:</label>
            <input type="text" id="codigo_cartao" name="codigo_cartao" required>
           
            <button type="submit">Adicionar Cartão</button>
        </form>


        <a href="visualizar_cliente.php?id_cliente=<?php echo $id_cliente; ?>" class="voltar">Voltar para Visualizar Cliente</a>
    </div>
</body>
</html>


<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>




