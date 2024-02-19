<?php

    require("../config.php");
    require("../headers.php");

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        try{
            $sql = "CALL sp_consultar_filmes()";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($results){
                echo json_encode($results);
            }
            else{
                echo json_encode(["message" => "Não há filmes cadastrados"]);
            }
        }
        catch(PDOException $e){
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

?>