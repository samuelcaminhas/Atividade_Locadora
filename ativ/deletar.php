<?php
    header("Content-Type: application/json", true);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");

    require_once "bancoDeDados.php";

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


    if(isset($_GET["id"]) and !empty($_GET["id"]) || ($_GET["id"] === "0") ){
        $pessoa = ($_GET["id"]);
        if(verificarpessoa($pessoa)){
            if(verificarEditavel($pessoa)){
                try {
                    $conexao = Conexao::getConexao();
                    $stmt = $conexao->prepare("DELETE FROM pessoa WHERE id = ?");
                    $stmt->execute([$pessoa]);
                    if ($stmt->rowCount() > 0) {
                        echo json_encode(array("status" => "ok"));
                    } 
                    else {
                        echo json_encode(array("status" => "erro"));
                    }
                } catch (Exception $th) {
                    echo json_encode(array("status" => "erro"));
                    exit;
                }
            }
            else{
                echo json_encode(array("status" => "erro","msg" => "pessoa não editável"));
            }
        }
        else{
            echo json_encode(array("status" => "erro","msg" => "id não encontrado"));
        }
    }
    else{
        echo json_encode(array("status" => "erro", "msg" => "id não enviado"));
    }
?>