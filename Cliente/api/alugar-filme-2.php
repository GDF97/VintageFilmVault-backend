<?php

	
    require("../config.php");
    require("../headers.php");

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$data = json_decode(file_get_contents("php://input", true));
		if($data){
			$filmes = $data->filmes; // Array de filmes
			$id_cli = $data->id_cliente; // Id do cliente
			
			foreach($filmes as $filme){
				try{
					$sql = "CALL sp_alugar_filme(:id_cli, :id_filme, :tipo_midia)";
					$stmt = $pdo->prepare($sql);
					$stmt->bindValue(":id_cli", $id_cli);
					$stmt->bindValue(":id_filme", $filme->id_filme);
					$stmt->bindValue(":tipo_midia", $filme->tipo_midia);
					$stmt->execute();
					echo json_encode(["message"=>"Filmes Alugados com sucesso!", "status"=>"ok"]);
				} catch(PDOException $e){
					echo json_encode(["message"=>$e->getMessage()]);
				}
			}
			
		}
	}



?>