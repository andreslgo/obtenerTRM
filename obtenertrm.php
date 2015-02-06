<?php


//Se genera archivo de log
$log = fopen('log_trm.log', 'a+');
$date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");

try {
	//Se incluye de forma obligatoria la libreria nusoap
	require_once('lib/nusoap.php');

	//Se realiza la conexion con el webservice de SuperFinanciera a través de SOAP
	$soap = new soapclient("https://www.superfinanciera.gov.co/SuperfinancieraWebServiceTRM/TCRMServicesWebService/TCRMServicesWebService?WSDL", array(
		'soap_version'   => SOAP_1_1,
		'trace' => 1,
		"location" => "http://www.superfinanciera.gov.co/SuperfinancieraWebServiceTRM/TCRMServicesWebService/TCRMServicesWebService",
	));
	//Se llama el metodo queryTCRM identificada en el WDSL
	$response = $soap->queryTCRM(array('tcrmQueryAssociatedDate' => $date));
	$response = $response->return;
	//Se verifica si la respuesta del WebService es correcta
	if($response->success){
		fwrite($log, 'La TRM obtenida para '. $date . ' es: '. $response->value);
	}
	else{
		fwrite($log, 'No se logro obtener la TRM para '. $date);
	}
	fwrite($log, chr(13));	
	fwrite($log, 'Proceso terminado a ' . date("Y-m-d H:i:s"));
	fwrite($log, chr(13));	
}catch(Exception $e){
	fwrite($log, 'Error al obtener la TRM: ' . $e->getMessage());
}
fclose($log);
?>