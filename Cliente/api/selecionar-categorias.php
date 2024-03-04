<?php

    require("../config.php");
    require("../headers.php");

    if($_SERVER['REQUEST_METHOD'] == "GET"){
        try{    
            $sql = "CALL sp_resgatar_generos()";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($results);
        }
        catch(PDOException $e){
            echo json_encode($e->getMessage());
        }
    }
?>