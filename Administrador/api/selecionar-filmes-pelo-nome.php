<?php

    require("../config.php");
    require("../headers.php");

    if($_SERVER['REQUEST_METHOD'] == "GET"){
        $nome = $_GET['nome'];
        if(!$nome) {
            echo json_encode(["message" => "erro"]);
        }
        else{
            try{    
                $sql = "CALL sp_consultar_filme_pelo_nome(:nome)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":nome", $nome);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                echo json_encode($results);
            }
            catch(PDOException $e){
                echo json_encode($e->getMessage());
            }
        }
    }
?>