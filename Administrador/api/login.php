<?php
    require("../config.php");
    require("../headers.php");

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $data = json_decode(file_get_contents("php://input", true));
        if($data){
            $login = $data->login;
            $senha = $data->senha;

            try{
                $sql = "CALL sp_consultar_adm(:login, :senha)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":login", $login);
                $stmt->bindParam(":senha", $senha);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $cd_adm = $result["cd_adm"];
                    $nm_adm = $result["nm_administrador"];
                    echo json_encode(array("status" => "ok", "adm" => array("codigo" => $cd_adm, "nome" => $nm_adm)));
                } else {
                    echo json_encode(array("status" => "error", "message" => "Usuário não encontrado"));
                }
            } catch(PDOException $e){
                echo json_encode(array("ERROR" => $e->getMessage()));
            }
        }
    }
?>