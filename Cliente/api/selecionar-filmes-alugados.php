<?php

    require("../config.php");
    require("../headers.php");

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $id_cliente = $_GET["id_cliente"];
        if($id_cliente){
            try {
                $sql = "CALL sp_consultar_filme_alugado_por_cliente(:id_cliente)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id_cliente", $id_cliente);
                $stmt->execute();
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