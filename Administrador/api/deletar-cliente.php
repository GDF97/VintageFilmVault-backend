<?php

    require("../config.php");
    require("../headers.php");
    
    if($_SERVER['REQUEST_METHOD'] == "DELETE"){
        $id_cliente = $_GET["id_cliente"];
        if($id_cliente){
           try {
            $sql = "CALL sp_consultar_filme_alugado_por_cliente(:id_cliente)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":id_cliente", $id_cliente);
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            if($results){
                echo json_encode(["message" => "Não foi possível deletar o cliente, pois ele está com um filme alugado"]);    
            } else{
                $sql = "delete from tb_cliente where id_cliente = :id_cliente";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id_cliente", $id_cliente);
                $stmt->execute();
            
                echo json_encode(["message" => "Cliente excluido com sucesso"]);
            }
           } catch (PDOException $th) {
            echo json_encode(["message" => $th->getMessage()]);
           } 
        }
    }

?>