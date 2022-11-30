<?php

session_start();
ob_start();
include_once './conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
var_dump($id);

if (empty($id)) {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Imovel não encontrado!</p>";
    header("Location: index.php");
    exit();
}

$query_imoveis = "SELECT id FROM imoveis WHERE id = $id LIMIT 1";
$result_imoveis = $conn->prepare($query_imoveis);
$result_imoveis->execute();

if (($result_imoveis) AND ($result_imoveis->rowCount() != 0)) {
    $query_del_imoveis = "DELETE FROM imoveis WHERE id = $id";
    $apagar_imoveis = $conn->prepare($query_del_imoveis);

    if ($apagar_imoveis->execute()) {
        $_SESSION['msg'] = "<p style='color: green;'>Imovel apagado com sucesso!</p>";
        header("Location: index.php");
    } else {
        $_SESSION['msg'] = "<p style='color: #f00;'>Imovel não apagado com sucesso!</p>";
        header("Location: index.php");
    }
} else {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Imovel não encontrado!</p>";
    header("Location: index.php");
}
