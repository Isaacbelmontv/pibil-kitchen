<?php
		function limpiar($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
	// Debes editar las próximas dos líneas de código de acuerdo con tus preferencias
	$email_to = "maestro.pibil@gmail.com";
	$email_subject = "MAESTRO PIBIL - Cupon RAPPI";
	
	// Aquí se deberían validar los datos ingresados por el usuario
	if(!isset($_POST["userName"]) ||
	!isset($_POST["userTelephone"])
	) {
	
	echo "<b>Ocurrió un error y el formulario no ha sido enviado. </b><br />";
	echo "Por favor, vuelva atrás y verifique la información ingresada<br />";
	die();
	}
	
	$email_message = "Detalles del formulario de contacto:\n\n";
	$email_message .= "Nombre: " . limpiar($_POST["userName"]) . "\n";
	$email_message .= "Telefono: " . limpiar($_POST["userTelephone"]) . "\n";

	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	// More headers
	$headers .= 'From: <maestro@cocinamaestro.com>' . "\r\n";
	$sentMail = mail($email_to,$email_subject,$email_message,$headers);



	if(!$sentMail)
	{
		$output = json_encode(array('type'=>'error', 'text' => '¡No se pudo enviar el correo! Por favor verifique con el administrador.'));
		die($output);
	}else{
		$output = json_encode(array('type'=>'message', 'text' => 'Hola '.limpiar($_POST["userName"]) .' Gracias por su registro.'));
		die($output);
	}

?>