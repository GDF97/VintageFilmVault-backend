<?php

    require("../config.php");
    require("../headers.php");

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $data = json_decode(file_get_contents("php://input", true));
        if($data){
            $nome = $data->formObj->nome;
            $email = $data->formObj->email;
            $senha = $data->formObj->senha;
            $endereco = $data->formObj->endereco;
            try {
                $sql = "select * from tb_cliente where nm_email = :email";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email", $email);
                $stmt->execute();
                $results = $stmt->fetch();

                if($results){
                    echo json_encode(["status" => "error", "message" => "Já existe uma conta cadastrada com esse endereço de email"]);
                } else {
                    try {
                        $sql = "CALL sp_cadastrar_cliente(:nome, :email, :senha, :endereco)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(":nome", $nome);
                        $stmt->bindValue(":email", $email);
                        $stmt->bindValue(":senha", $senha);
                        $stmt->bindValue(":endereco", $endereco);
                        $stmt->execute();

                        echo json_encode(["status" => "success", "message" => "Cadastro enviado com sucesso! Aguarde a aprovação do mesmo!"]);
                        
                    } catch (PDOException $th) {
                        echo json_encode(["status" => "error", "message" => $th->getMessage()]);
                    }
                }
            } catch (PDOException $th) {
                echo json_encode(["status" => "error", "message" => $th->getMessage()]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "nenhuma informação foi enviada"]);
        }
    } else{
        echo json_encode(["status" => "error", "message" => "Metódo não permitido"]);
    }

?>
