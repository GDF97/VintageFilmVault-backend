<?php

    require("../config.php");
    require("../headers.php");

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $id_filme = $_GET["id_filme"];
        if($id_filme){
            try {
                $sql = "CALL sp_consultar_filme_por_id(:id_filme)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id_filme", $id_filme);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if($results){
                    echo json_encode($results);
                } else{
                    echo json_encode(["status" => "warning"]);
                }
            } catch (PDOException $th) {
                echo json_encode(["status" => "error", "message" => $th->getMessage()]);
            }
        }
    } else{
        echo json_encode(["status" => "error", "message" => "Metódo não permitido!"]);
    }

?>