<?php

    require("../config.php");
    require("../headers.php");

    if($_SERVER['REQUEST_METHOD'] == "GET"){
        $category = $_GET['categoria'];
        if($category){
            try{    
                $sql = "CALL sp_selecionar_filmes_por_genero(:genero)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":genero", $category);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if($results){
                    echo json_encode($results);
                } else{
                    echo json_encode(['status' => "warning", "message" => "Não há filmes com essa categoria"]);
                }
            }
            catch(PDOException $e){
                echo json_encode($e->getMessage());
            }
        }
    }
?>