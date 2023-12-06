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

    if(isset($_GET["id"]) and !empty($_GET["id"])){
        $lista = [];
        $carro = ($_GET["id"]);
        if(verificarcarro($carro)){
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM pessoa WHERE id_carro = $carro ORDER BY id");
                $stmt->execute();
                $lista = $stmt->fetchAll();
            } catch (Exception $th) {
                echo json_encode(array("status" => "erro"));
                exit;
            }
            echo json_encode($lista);
        }
        else{
            echo json_encode(array("status" => "erro","msg" => "carro não cadastrada"));
        }
    }
    else{
        echo json_encode(array("status" => "erro", "msg" => "id não enviado"));
    }
?>