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

// Consulta SQL para obter o nome do gerente
$id_gerente = $_SESSION['id_gerente'];
$sql_gerente = "SELECT nome FROM gerente WHERE id = $id_gerente";
$resultado_gerente = $conn->query($sql_gerente);

if ($resultado_gerente->num_rows > 0) {
    $dados_gerente = $resultado_gerente->fetch_assoc();
    $gerente_nome = $dados_gerente['nome'];
} else {
    // Caso o gerente não seja encontrado, use um valor padrão
    $gerente_nome = "Nome do Gerente";
}

// Consulta SQL para obter a lista de clientes do gerente logado
$sql_clientes = "SELECT id, nome FROM cliente WHERE id_gerente = $id_gerente";

// Se o parâmetro de pesquisa estiver presente, adiciona à consulta
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $_GET['search'];
    $sql_clientes .= " AND nome LIKE '%$search_term%'";
}

$resultado_clientes = $conn->query($sql_clientes);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofGJhY+8FFmI5qFfKkRvHj9b8abtTE1Pi6" crossorigin="anonymous">
    <link rel="stylesheet" href="gerente.css">
    <title>Tela do Gerente!</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Bem-vindo,<?php echo $gerente_nome; ?></h2>
        </div>

        <h3>Lista de Clientes</h3>

        <form action="" method="get">
            <label for="search"></label>
            <div class="search-container">
                <input type="text" id="search" name="search" placeholder="Digite o nome do cliente" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button type="submit" class="small-button">
                    Pesquisar
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <table>
            <tr>
                <th>Número do Cliente</th>
                <th>Nome do Cliente</th>
                <th>Ações</th>
            </tr>
            <?php
            while ($row = $resultado_clientes->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['nome'] . '</td>';
                echo '<td>';
                echo '<a href="javascript:void(0);" onclick="excluirCliente(' . $row['id'] . ')" class="excluir">Excluir</a> | ';
                echo '<a href="visualizar_cliente.php?id_cliente=' . $row['id'] . '" class="visualizar">Visualizar</a>';
                
                // Verificar se o cliente não tem um cartão
                $id_cliente = $row['id'];
                $sql_verificar_cartao = "SELECT id FROM cartao WHERE id_cliente = $id_cliente";
                $resultado_verificar_cartao = $conn->query($sql_verificar_cartao);

                if ($resultado_verificar_cartao->num_rows === 0) {
                    // Cliente não tem cartão, exibe a ação "Adicionar Cartão"
                    echo ' | <a href="adicionar_cartao.php?id_cliente=' . $row['id'] . '" class="adicionar-cartao">Adicionar Cartão</a>';
                }

                echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>
        <br>
        <a href="lista_clientes.php" class="botao">Cadastrar Novo Cliente</a><br>
        <a href="logout.php" class="sair">Sair do Sistema</a>
    </div>

    <script>
        function excluirCliente(idCliente) {
            var confirmacao = confirm("Tem certeza que deseja excluir este cliente?");
            if (confirmacao) {
                // Redirecionar para a página de exclusão com o ID do cliente
                alert("Cliente excluído com sucesso!");
                window.location.href = 'excluir_cliente.php?id_cliente=' + idCliente;
            }
        }
    </script>

    <?php
    // Fechar a conexão com o banco de dados
    $conn->close();
    ?>
</body>
</html>
