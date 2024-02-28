<?php

    require("../config.php");
    require("../headers.php");

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $data = json_decode(file_get_contents("php://input", true));
        if($data){
            $id_cli = $data->id_cliente;
            $id_filme = $data->id_filme;
            $tipo_midia = $data->tipo_midia;

            try{    
                $sql = "CALL sp_alugar_filme(:id_cli, :id_filme, :tipo_midia)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id_cli", $id_cli);
                $stmt->bindParam(":id_filme", $id_filme);
                $stmt->bindParam(":tipo_midia", $tipo_midia);
                $executado_com_sucesso = $stmt->execute();
                if($executado_com_sucesso){
                    echo json_encode(["message" => "Filme Alugado com Sucesso"]);
                } else{
                    echo json_encode(["message" => "Algo deu de errado ao executar a query"]);
                }
            } catch(PDOException $e){
                echo json_encode(["message" => $e->getMessage()]);
            }
        }
    } else{
        echo json_encode(["message" => "Metodo não autorizado"]);
    }   
?>