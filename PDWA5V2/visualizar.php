<?php
session_start();
ob_start();
include_once './conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (empty($id)) {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Imovel não encontrado!</p>";
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Imoveis - Visualizar</title>
    </head>
    <body>
        <a href="index.php">Listar Imoveis</a><br>
        <a href="cadastrar.php">Cadastrar Imoveis</a><br>

        <h1>Visualizar Imovel</h1>

        <?php
        $query_imoveis = "SELECT id, tipo, endereco, valor, situacao FROM imoveis WHERE id = $id LIMIT 1";
        $result_imoveis = $conn->prepare($query_imoveis);
        $result_imoveis->execute();

        if (($result_imoveis) AND ($result_imoveis->rowCount() != 0)) {
            $row_imoveis = $result_imoveis->fetch(PDO::FETCH_ASSOC);

            extract($row_imoveis);        
            echo "ID: $id <br>";
            echo "Tipo: $tipo <br>";
            echo "Endereco: $endereco <br>";
            echo "Valor: $valor <br>";
            echo "situacao: $situacao <br>";
        } else {
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Imovel não encontrado!</p>";
            header("Location: index.php");
        }
        ?>
    </body>
</html>
