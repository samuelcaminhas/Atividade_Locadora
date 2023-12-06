<?php
    header("Content-Type: application/json", true);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");

    require_once "bancoDeDados.php";

    function verificarCarro($idCat){
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT id FROM carro ORDER BY id");
            $stmt->execute();
            foreach($stmt as $linha){
                if($linha["id"] == $idCat){
                    return true;
                }
            }
        } 
        catch (Exception $th) {
            echo json_encode(array("status" => "erro"));
            exit;
        }
        return false;
    }

    function proximoId(){
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT id FROM pessoa WHERE id = (SELECT MAX(id) FROM pessoa)");
            $stmt->execute();
            foreach($stmt as $linha){
                return(intval($linha["id"]) + 1);
            }
        } catch (Exception $th) {
            echo json_encode(array("status" => "erro"));
            exit;
        }
        return null;
    }

    if(isset($_GET["nome"]) and !empty($_GET["nome"]) and isset($_GET["sobrenome"]) and !empty($_GET["sobrenome"]) and isset($_GET["observacoes"]) and !empty($_GET["observacoes"]) and isset($_GET["idcarro"]) and !empty($_GET["idcarro"])){
        $carro = ($_GET["idcarro"]);
        $nome = $_GET["nome"];
        $sobrenome = $_GET["sobrenome"];
        $observacoes = $_GET["observacoes"];
        if(verificarcarro($carro)){
            try {
                $id = proximoId();
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("INSERT INTO pessoa(id,nome,sobrenome,observacoes,editavel,id_carro) VALUES (:id, :nome, :sobrenome, :observacoes, :editavel, :id_carro)");
                $stmt->execute([
                    "id" => $id,
                    "nome" => $nome,
                    "sobrenome" => $sobrenome,
                    "observacoes" => $observacoes,
                    "editavel" => 1,
                    "id_carro" => $carro
                ]);
                if ($stmt->rowCount() > 0) {
                    echo json_encode(array("status" => "ok", "id" => $id));                   
                } 
                else {
                    echo json_encode(array("status" => "erro", "msg" => "ERRO GENÉRICO"));
                }
            } 
            catch (Exception $th) {
                echo json_encode(array("status" => "erro", "msg" => "ERRO GENÉRICO"));
                exit;
            }
        }
        else{
            echo json_encode(array("status" => "erro", "msg" => "carro não cadastrada"));
        }
    }
    else{
        echo json_encode(array("status" => "erro", "msg" => "parâmetros incompletos"));
    }
?>