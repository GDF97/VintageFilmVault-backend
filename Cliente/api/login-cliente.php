<?php


    require("../config.php");
    require("../headers.php");

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $data = json_decode(file_get_contents("php://input", true));
        if($data){
            $email = $data->email;
            $senha = $data->senha;
            try {
                $sql = "select * from tb_cliente where nm_email = :email and cd_senha = :senha ";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email", $email);
                $stmt->bindValue(":senha", $senha);
                $stmt->execute();
                $results = $stmt->fetch(PDO::FETCH_ASSOC);

                if($results){
                    switch ($results['status_cadastro']) {
                        case "Aprovado":
                            echo json_encode(
                                [
                                "status" => "success", 
                                "message" => "O seu cadastro foi aprovado!",
                                "cliente" => [
                                    "codigo" => $results['id_cliente'],
                                    "email" => $results['nm_email'], 
                                    "nome" => $results['nm_cliente']
                                    ]
                                ]
                            );
                            break;
                        case "Pendente":
                            echo json_encode(["status" => "warning", "message" => "O seu cadastro ainda não foi aprovado"]);
                            break;
                        case "Recusado":
                            echo json_encode(["status" => "error", "message" => "O seu cadastro foi recusado!"]);
                            break;
                        default:
                            echo json_encode(["status" => "error", "message" => "Esse cliente não existe"]);
                            break;
                    }
                }
                

            } catch (PDOException $th) {
                echo json_encode(["status" => "error", "message" => $th->getMessage()]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "nenhuma informação foi enviada"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Metódo não permitido"]);
    }


?>
