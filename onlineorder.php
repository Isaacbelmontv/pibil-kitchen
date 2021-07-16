<?php
if($_POST)
{


	$enlace = mysqli_connect("198.59.144.8", "cocinama_maestro", "M@3sTr0.*1324", "cocinama_formulario");

	$nombre       = filter_var($_POST["userName"], FILTER_SANITIZE_STRING);
	$telefono   = filter_var($_POST["userTelephone"], FILTER_SANITIZE_EMAIL);	

	if (!$enlace) {
		echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
		echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
		echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}

		// Realizar una consulta SQL
		$sql = "SELECT * FROM formulario WHERE telefono = $nombre";

		if (!$resultado = $mysqli->query($sql)) {
			// ¡Oh, no! La consulta falló. 
			echo "Lo sentimos, este sitio web está experimentando problemas.";
		
			// De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
			// cómo obtener información del error
			echo "Error: La ejecución de la consulta falló debido a: \n";
			echo "Query: " . $sql . "\n";
			echo "Errno: " . $mysqli->errno . "\n";
			echo "Error: " . $mysqli->error . "\n";
			exit;
		}
		
		// ¡Uf, lo conseguimos!. Sabemos que nuestra conexión a MySQL y nuestra consulta
		// tuvieron éxito, pero ¿tenemos un resultado?
		if ($resultado->num_rows === 0) {

			$sql = "INSERT INTO formulario (telefono, nombre)
			VALUES ( $telefono, $nombre)";

			//SE ENVIA FORM Y SE INSERTA EN BD
			$to_Email   	= "isaacbelmontv@gmail.com"; //Replace with recipient email address
			$subject        = 'MAESTRO PIBIL - Cupon RAPPI'; //Subject line for emails
		
		
			//check if its an ajax request, exit if not
			if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
			
				//exit script outputting json data
				$output = json_encode(
				array(
					'type'=>'error', 
					'text' => 'Request must come from Ajax'
				));
				
				die($output);
			} 
			
			//check $_POST vars are set, exit if any missing
			if(!isset($_POST["userName"]))
			{
				$output = json_encode(array('type'=>'error', 'text' => 'Campos vacios!'));
				die($output);
			}
		
			//Sanitize input data using PHP filter_var().
			$user_Name        = filter_var($_POST["userName"], FILTER_SANITIZE_STRING);
			
			$user_Telephone =" ";
		
			if (isset($_POST["userTelephone"])){
			$user_Telephone   = filter_var($_POST["userTelephone"], FILTER_SANITIZE_EMAIL);	
			}
			
			
			//additional php validation
			if(strlen($user_Name)<3) // If length is less than 3 it will throw an HTTP error.
			{
				$output = json_encode(array('type'=>'error', 'text' => 'El nombre es muy corto!'));
				die($output);
			}
			
			
			$message_Body = "<strong>Nombre: </strong>". $user_Name ."<br>";
			$message_Body .= "<strong>Teléfono: </strong>". $user_Telephone ."<br>";
			
		
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			
			
			$sentMail = @mail($to_Email, $subject, $message_Body, $headers);
			
			if(!$sentMail)
			{
				$output = json_encode(array('type'=>'error', 'text' => '¡No se pudo enviar el correo! Por favor verifique con el administrador.'));
				die($output);
			}else{
				$output = json_encode(array('type'=>'message', 'text' => 'Hi '.$user_Name .' Gracias por su registro.'));
				die($output);
			}


			exit;
		}else{
			$output = json_encode(array('type'=>'message', 'text' => 'Hi '.$user_Name .' El teléfono ya fue registrado'));
			die($output);
			exit;
		}

	mysqli_close($enlace);
}
?>