<?php

    require("../config.php");
    require("../headers.php");

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $data = json_decode(file_get_contents("php://input", true));
        if($data){
            $id_filme_alugado = $data->id_filme_alugado;
            $id_cliente = $data->id_cliente;
            try{    
                $sql = "CALL sp_devolver_filme(:id_filme_alugado)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id_filme_alugado", $id_filme_alugado);
                $stmt->execute();

                $sql = "CALL sp_verificar_se_usuario_alugou_filme(:id_cliente)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id_cliente", $id_cliente);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if(!$results){
                    $sql = "CALL sp_zerar_filmes_alugados(:id_cliente)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(":id_cliente", $id_cliente);
                    $stmt->execute();
                }

                echo json_encode(["message" => "Filme devolvido com sucesso!"]);

            } catch(PDOException $e){
                echo json_encode(["message" => $e->getMessage()]);
            }
        }
    }
?>