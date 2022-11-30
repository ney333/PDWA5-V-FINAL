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

$query_imoveis = "SELECT id, tipo, endereco, valor, situacao FROM imoveis WHERE id = $id LIMIT 1";
$result_imoveis = $conn->prepare($query_imoveis);
$result_imoveis->execute();

if (($result_imoveis) AND ($result_imoveis->rowCount() != 0)) {
    $row_usuario = $result_imoveis->fetch(PDO::FETCH_ASSOC);
    //var_dump($row_usuario);
} else {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Imovel não encontrado!</p>";
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Imoveis - Editar</title>
    </head>
    <body>
        <a href="index.php">Listar Imoveis</a><br>
        <a href="cadastrar.php">Cadastrar Imoveis</a><br>

        <h1>Editar Imoveis</h1>

        <?php
        //Receber os dados do formulário
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        //Verificar se o usuário clicou no botão
        if (!empty($dados['EditImoveis'])) {
            $empty_input = false;
            $dados = array_map('trim', $dados);
            if (in_array("", $dados)) {
                $empty_input = true;
                echo "<p style='color: #f00;'>Erro: Necessário preencher todos campos!</p>";
            } 

            if (!$empty_input) {
                $query_up_imoveis= "UPDATE imoveis SET tipo=:tipo, endereco=:endereco, valor=:valor, situacao=:situacao WHERE id=:id";
                $edit_imoveis = $conn->prepare($query_up_imoveis);
                $edit_imoveis->bindParam(':tipo', $dados['tipo'], PDO::PARAM_STR);
                $edit_imoveis->bindParam(':endereco', $dados['endereco'], PDO::PARAM_STR);
                $edit_imoveis->bindParam(':valor', $dados['valor'], PDO:: PARAM_INT);
                $edit_imoveis->bindParam(':situacao', $dados['situacao'], PDO::PARAM_STR);
                $edit_imoveis->bindParam(':id', $id, PDO::PARAM_INT);
                if($edit_imoveis->execute()){
                    $_SESSION['msg'] = "<p style='color: green;'>Imovel editado com sucesso!</p>";
                    header("Location: index.php");
                }else{
                    echo "<p style='color: #f00;'>Erro: Imovel não editado!</p>";
                }
            }
        }
        ?>

        <form id="edit-usuario" method="POST" action="">

            <label>Tipo: </label>
            <input type="text" name="tipo" id="tipo" placeholder="Tipo de imovel" value="<?php
            if (isset($dados['tipo'])) {
                echo $dados['tipo'];
            } elseif (isset($row_usuario['tipo'])) {
                echo $row_usuario['tipo'];
            }
            ?>" ><br><br>

            <label>Endereço: </label>
            <input type="endereco" name="endereco" id="endereco" placeholder="Endereço" value="<?php
                   if (isset($dados['endereco'])) {
                       echo $dados['endereco'];
                   } elseif (isset($row_usuario['endereco'])) {
                       echo $row_usuario['endereco'];
                   }
                   ?>" ><br><br>

            <label>Valor: </label>
            <input type="valor" name="valor" id="valor" placeholder="Valor" value="<?php
                   if (isset($dados['valor'])) {
                       echo $dados['valor'];
                   } elseif (isset($row_usuario['valor'])) {
                       echo $row_usuario['valor'];
                   }
                   ?>" ><br><br>

            <label>Situaçao: </label>
            <input type="situacao" name="situacao" id="situacao" placeholder="Situaçao" value="<?php
                   if (isset($dados['situacao'])) {
                       echo $dados['situacao'];
                   } elseif (isset($row_usuario['situacao'])) {
                       echo $row_usuario['situacao'];
                   }
                   ?>" ><br><br>

            <input type="submit" value="Salvar" name="EditImoveis">
        </form>
    </body>
</html>
