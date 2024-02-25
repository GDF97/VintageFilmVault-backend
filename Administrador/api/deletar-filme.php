<?php

    require("../config.php");
    require("../headers.php");

    if($_SERVER['REQUEST_METHOD'] == "DELETE"){
        $id_filme = $_GET['id_filme'];

        if($id_filme){
            try{
                $sql = "CALL sp_consultar_filme_por_id(:id_filme)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id_filme", $id_filme);
                $stmt->execute();
                $results = $stmt->fetch(PDO::FETCH_ASSOC);

                if($results){
                    $sql = "select * from tb_filme_alugado where fk_filme = :id_filme";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(":id_filme", $id_filme);
                    $stmt->execute();

                    $results = $stmt->fetch(PDO::FETCH_ASSOC);

                    if($results){
                        echo json_encode(["message" => "Não foi possível deletar o filme, pois há uma pessoa com ele alugado!"]);
                    } else{

                        $sql = "delete from tb_infofilme where fk_filme = :id_filme";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(":id_filme", $id_filme);
                        $stmt->execute();

                        $sql = "CALL sp_delete_filme(:id_filme)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(":id_filme", $id_filme);
                        $stmt->execute();

                        echo json_encode(["message" => "Filme deletado com sucesso!"]);
                    }
                } else{
                    echo json_encode(["message" => "Filme não encontrado"]);
                }

            } catch(PDOException $e){
                echo json_encode(['message' => $e->getMessage()]);
            }
        }
    } else{
        echo json_encode(["message" => "Metodo não autorizado"]);
    }

?>