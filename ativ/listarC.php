<?php
    header("Content-Type: application/json", true);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");

    require_once "bancoDeDados.php";

    $lista = [];
    try {
        $conexao = Conexao::getConexao();

        $stmt = $conexao->prepare("SELECT id, nome FROM carro ORDER BY id");
        $stmt->execute();

        $lista = $stmt->fetchAll();
    } catch (Exception $th) {
        echo json_encode(array("status" => "erro"));
        exit;
    }

    echo json_encode($lista);
?>