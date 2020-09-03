<?php
class CurlRequest{
	
	public function sendPost($data, $url){
		//iniciar peticion
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
		curl_setopt($ch, CURLOPT_URL, $url);
		
		$response = curl_exec($ch);
		// Se cierra el recurso CURL y se liberan los recursos del sistema
		curl_close($ch);
		if(!$response) {
		    return 'ERROR';
		}else{
			print_r($response);
			$response = json_decode($response, true);
			if(isset($response['mensaje']))
				return $response['mensaje'];
			else
				return 'ERROR';
		}
	}

	public function sendPut(){
		//datos a enviar
		$data = array("a" => "a");
		//url contra la que atacamos
		$ch = curl_init("http://localhost/curlRequest/api.php");
		//a true, obtendremos una respuesta de la url, en otro caso, 
		//true si es correcto, false si no lo es
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//establecemos el verbo http que queremos utilizar para la petición
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		//enviamos el array data
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
		//obtenemos la respuesta
		$response = curl_exec($ch);
		// Se cierra el recurso CURL y se liberan los recursos del sistema
		curl_close($ch);
		if(!$response) {
		    return false;
		}else{
			return $response;
		}
	}

	public function sendGet(){
		//datos a enviar
		$data = array("a" => "a");
		//url contra la que atacamos
		$ch = curl_init("http://localhost/curlRequest/api.php");
		//a true, obtendremos una respuesta de la url, en otro caso, 
		//true si es correcto, false si no lo es
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//establecemos el verbo http que queremos utilizar para la petición
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		//enviamos el array data
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
		//obtenemos la respuesta
		$response = curl_exec($ch);
		// Se cierra el recurso CURL y se liberan los recursos del sistema
		curl_close($ch);
		if(!$response) {
		    return false;
		}else{
			var_dump($response);
		}
	}

	public function sendDelete(){
		//datos a enviar
		$data = array("a" => "a");
		//url contra la que atacamos
		$ch = curl_init("http://localhost/curlRequest/api.php");
		//a true, obtendremos una respuesta de la url, en otro caso, 
		//true si es correcto, false si no lo es
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//establecemos el verbo http que queremos utilizar para la petición
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		//enviamos el array data
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
		//obtenemos la respuesta
		$response = curl_exec($ch);
		// Se cierra el recurso CURL y se liberan los recursos del sistema
		curl_close($ch);
		if(!$response) {
		    return false;
		}else{
			var_dump($response);
		}
	}
}