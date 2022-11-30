<?php
session_start();
ob_start();
include_once './conexao.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cadastrar imovel</title>
    </head>
    <body>
        <a href="index.php">Listar Imoveis</a><br>
        <a href="cadastrar.php">Cadastrar Imoveis</a><br>
        <h1>Cadastrar Imovel</h1>
        <?php
        //Receber os dados do formulário
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
   
        if (!empty($dados['CadImoveis'])) {

            $empty_input = false;

            $dados = array_map('trim', $dados);
            if (in_array("", $dados)) {
                $empty_input = true;
                echo "<p style='color: #f00;'>Erro: Necessário preencher todos campos!</p>";
            } 

            if (!$empty_input) {
                $query_imoveis = "INSERT INTO imoveis (tipo, endereco, valor, situacao) VALUES (:tipo, :endereco, :valor, :situacao) ";
                $cad_imoveis = $conn->prepare($query_imoveis);
                $cad_imoveis->bindParam(':tipo', $dados['tipo'], PDO::PARAM_STR);
                $cad_imoveis->bindParam(':endereco', $dados['endereco'], PDO::PARAM_STR);
                $cad_imoveis->bindParam(':valor', $dados['valor'], PDO:: PARAM_INT);
                $cad_imoveis->bindParam(':situacao', $dados['situacao'], PDO::PARAM_STR);
                $cad_imoveis->execute();
                if ($cad_imoveis->rowCount()) {
                    echo "<p style='color: green;'>Imovel cadastrado com sucesso!</p>";
                    unset($dados);
                } else {
                    echo "<p style='color: #f00;'>Erro: Imovel não cadastrado!</p>";
                }
            }
        }
        ?>
        <form name="cad-usuario" method="POST" action="">
            <label>tipo: </label>
            <input type="text" name="tipo" id="tipo" placeholder="tipo de imovel" value="<?php
            if (isset($dados['tipo'])) {
                echo $dados['tipo'];
            }
            ?>"><br><br>

            <label>Endereço: </label>
            <input type="endereco" name="endereco" id="endereco" placeholder="Endereço" value="<?php
            if (isset($dados['endereco'])) {
                echo $dados['endereco'];
            }
            ?>"><br><br>

             <label>valor: </label>
            <input type="int" name="valor" id="valor" placeholder="valor do imovel" value="<?php
            if (isset($dados['valor'])) {
                echo $dados['valor'];
            }
            ?>"><br><br>

             <label>situacao: </label>
            <input type="text" name="situacao" id="situacao" placeholder="situacao do imovel" value="<?php
            if (isset($dados['situacao'])) {
                echo $dados['situacao'];
            }
            ?>"><br><br>

            <input type="submit" value="Cadastrar" name="CadImoveis">
        </form>
    </body>
</html>
