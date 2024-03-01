<?php

    require("../config.php");
    require("../headers.php");

    if($_SERVER['REQUEST_METHOD'] == "PUT"){
        $data = json_decode(file_get_contents("php://input", true));
        if($data){
            $film_obj = $data->film_obj;
            try {
                $sql = "CALL sp_atualizar_filme(:id_filme, :nm_filme, :ano_lancamento, :vl_filme, :tipo_midia, :dsc_filme)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id_cliente", $film_obj->id_cliente);
                $stmt->bindParam(":nm_filme", $film_obj->nm_filme);
                $stmt->bindParam(":ano_lancamento", $film_obj->ano_lancamento);
                $stmt->bindParam(":vl_filme", $film_obj->vl_filme);
                $stmt->bindParam(":tipo_midia", $film_obj->tipo_midia);
                $stmt->bindParam(":desc_filme", $film_obj->desc_filme);
                $stmt->execute();

                echo json_encode(["status" => "sucesso", "message" => "Filme atualizado com sucesso!"]);
            } catch (PDOException $th) {
                echo json_encode(["status" => "error", "message" => $th->getMessage()]);
            }
        } else{
            echo json_encode(["status" => "error", "message" => "Não há informações para atualizar"]);
        }
    } else{
        echo json_encode(["status" => "error", "message" => "Metódo não permitido!"]);
    }

?>