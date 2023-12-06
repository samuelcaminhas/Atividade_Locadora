<?php
    header("Content-Type: application/json", true);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");

    require_once "bancoDeDados.php";

    function verificarcarro($idCat){
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT id FROM carro ORDER BY id");
            $stmt->execute();
            foreach($stmt as $linha){
                if($linha["id"] == $idCat){
                    return true;
                }
            }
        } catch (Exception $th) {
            echo json_encode(array("status" => "erro"));
            exit;
        }
        return false;
    }

    function verificarpessoa($idNot){
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT id FROM pessoa ORDER BY id");
            $stmt->execute();
            foreach($stmt as $linha){
                if($linha["id"] == $idNot){
                    return true;
                }
            }
        } catch (Exception $th) {
            echo json_encode(array("status" => "erro"));
            exit;
        }
        return false;
    }

    function verificarEditavel($idNot){
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT id, editavel FROM pessoa ORDER BY id");
            $stmt->execute();
            foreach($stmt as $linha){
                if($linha["id"] == $idNot){
                    if($linha["editavel"] == "1"){
                        return true;
                    }
                    else{
                        return false;
                    }
                }
            }
        } catch (Exception $th) {
            echo json_encode(array("status" => "erro"));
            exit;
        }
        return false;
    }

    if(isset($_GET["id"]) and !empty($_GET["id"]) and isset($_GET["nome"]) and !empty($_GET["nome"]) and isset($_GET["sobrenome"]) and !empty($_GET["sobrenome"]) and isset($_GET["observacoes"]) and !empty($_GET["observacoes"]) and isset($_GET["idcarro"]) and !empty($_GET["idcarro"])){
        $carro = ($_GET["idcarro"]);
        $pessoa = ($_GET["id"]);
        $nome = $_GET["nome"];
        $sobrenome = $_GET["sobrenome"];
        $observacoes = $_GET["observacoes"];
        if(verificarpessoa($pessoa)){
            if(verificarEditavel($pessoa)){
                if(verificarcarro($carro)){
                    try {
                        $conexao = Conexao::getConexao();
                        $stmt = $conexao->prepare("UPDATE pessoa SET nome=?, sobrenome=?, observacoes=?, id_carro=? WHERE id=?");
                        $stmt->execute([$nome, $sobrenome, $observacoes, $carro ,$pessoa]);
            
                        if ($stmt->rowCount() > 0) {
                            echo json_encode(array("status" => "ok"));
                        } else {
                            echo json_encode(array("status" => "erro"));
                        }
                    } catch (Exception $th) {
                        echo json_encode(array("status" =>  "erro"));
                        exit;
                    }
                }
                else{
                    echo json_encode(array("status" => "erro", "msg" => "carro não cadastrada"));
                }
            }
            else{
                echo json_encode(array("status" => "erro", "msg" => "pessoa não editável"));
            }
        }
        else{
            echo json_encode(array("status" => "erro", "msg" => "pessoa não cadastrada"));
        }
    }
    else{
        echo json_encode(array("status" => "erro", "msg" => "parâmetros incompletos"));
    }
?>