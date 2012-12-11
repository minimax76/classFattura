<?php
  include('class.fattura.php');

	//istanzio l'oggetto Fattura e lo passo alla variabile $fatt
	$fatt = new Fattura();
	
	// definisco i valori da attribuire alle varie proprietà di cui si compone l'oggetto Fattura
	$fatt -> numero = 3;
	$fatt -> data = '2008-02-06';
	$fatt -> destinatario = 'Giulia Verdi';
	$fatt -> anno = 2008;
	$fatt -> server = "localhost";
	$fatt -> username = "root";
	$fatt -> password = "";
	$fatt -> db = "fatturazione";
	
	// tramite l'apposito metodo aggiungo righe alla fattura
	$fatt -> nuovaRiga('Potatura siepi',200);
	$fatt -> nuovaRiga('Semina prato',350);
	
	// salvo la fattura nel DB
	$esito = $fatt -> salva();	
	
	// valuto l'esito dell'operazione e stampo il risultato a video
	if ($esito) {
		echo 'Fattura registrata correttamente!';
	}
	else {
		echo 'Si è verificato un problema durante la registrazione della fattura!';
	}
	
?>