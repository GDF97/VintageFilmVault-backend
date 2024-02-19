<?php

    require("../config.php");
    require("../headers.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $data = json_decode(file_get_contents("php://input", true));
        if($data){
            $filme_obj = $data->filme_obj;
            $filme_poster = createImage();
            $categorias = $data->categorias_array;

            try{
                $sql = "CALL sp_cadastrar_filme(:nome, :ano_lancamento, :vl_filme, :tipo_midia, :dsc, :filme_poster)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":nome", $filme_obj->nome);
                $stmt->bindParam(":ano_lancamento", $filme_obj->ano_lancamento);
                $stmt->bindParam(":vl_filme", $filme_obj->vl_filme);
                $stmt->bindParam(":tipo_midia", $filme_obj->tipo_midia);
                $stmt->bindParam(":dsc", $filme_obj->dsc_filme);
                $stmt->bindParam(":filme_poster", $filme_poster);
                $stmt->execute();

                $sql = "SELECT id_filme FROM tb_filmes ORDER BY id_filme DESC LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                $id_filme = $result['id_filme'];

                foreach ($categorias as $categoria) {
                    $sql = "CALL sp_cadastrar_genero_no_filme(:id_filme, :categoria)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(":categoria", $categoria);
                    $stmt->bindParam(":id_filme", $id_filme);
                    $stmt->execute();
                }

                echo json_encode(["message" => "Filme Inserido com sucesso!"]);
            }
            catch(PDOException $e){
                echo json_encode($e->getMessage());
            }
        }
    }
    else{
        echo json_encode(array("message" => "Metodo não permitido"));
    }


    function createImage(){
        return "Imagem";
    }
?>