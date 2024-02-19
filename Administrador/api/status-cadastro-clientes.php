<?php

    require("../config.php");
    require("../headers.php");

    if($_SERVER['REQUEST_METHOD'] == "PUT"){
        $data = json_decode(file_get_contents("php://input", true));
        if($data){
            $id_cli = $data->id_cliente;
            $status = $data->status;

            try{
                $sql = "CALL sp_mudar_estado_do_cliente(:id_cli, :status)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id_cli", $id_cli);
                $stmt->bindParam(":status", $status);
                $stmt->execute();

                echo json_encode(["message"=>"Usuário ".$status]);
            }
            catch(PDOException $e){

            }
        }
    }

?>