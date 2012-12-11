<?php
  class Fattura {
		// proprietà generiche della fattura
		var $numero;
		var $data;
		var $destinatario;
		var $anno;
		// le proprietà che seguono servono all'oggetto Fattura per poter aprire una connessione col DB
		var $server;
		var $username;
		var $password;
		var $db;		
		// proprietà che utilizzeremo per memorizzare la collezione (array) di righe di cui si compone la fattura
		var $righe;
		
		// definisco il costruttore
		// ... un costruttore è una funzione (metodo) che ha lo stesso nome della classe
		// ... nel nostro caso usiamo il costruttore per definire come array vuoto la proprietà "righe"
		function Fattura() {
			$this -> righe = array();
		}
		
		// NOTA: in PHP5 il costruttore, anzichè prendere il nome della classe, deve assumere il nome __construct, per il resto è uguale
		/**************
		function __construct() {
			$this -> righe = array();
		}
		** FINE NOTA **/
		
		// funzione (Metodo) che si occupa di Aggiungere una riga al DB
		function nuovaRiga($descrizione, $imponibile) {
			$riga = new RigaFattura();
			$riga -> descrizione = $descrizione;
			$riga -> imponibile = $imponibile;
			
			$this -> righe[] = $riga;
		}
		
		// funzione che si occupa di salvare i dati della fattura nel DB
		function salva() {
			// apro la connessione col DB
			$conn = mysql_connect($this->server, $this->username, $this->password);
			mysql_select_db($this->db, $conn);

			// inserisco i dati generici della fattura nella tabella "fatture"
			$query = "INSERT INTO fatture (numero,anno,data,destinatario) VALUES
						(" .  $this-> numero. "," . $this -> anno . ",'" . $this -> data . "','" . $this -> destinatario.  "')";
			$esito = mysql_query($query, $conn);

			// a questo punto verifico se prima la query è stata eseguita con successo e proseguo solo in questo caso
			if ($esito==true) {	// SUCCESSO!
				// recupero l'ultimo id inserito
				$ultimo_id_fattura = mysql_insert_id($conn);
				
				// determino di quante righe si compone la fattura
				// ... count è una funzione PHP che, dato un array, mi dice di quante voci si compone
				$numero_righe = count($this -> righe);
				
				// effettuo un ciclo che scorra tutti gli elementi dell'array
				for ($i=0; $i<$numero_righe; $i++) {
					// ogni elemento dell'array, per come l'abbiamo costruito, è un oggetto di tipo RigaFattura
					$oggetto_riga = $this -> righe[$i];
					
					// estraggo dall'oggetto RigaFattura le informazioni e le memorizzo nella tabella "dettaglio_fatture"
					$query = "INSERT INTO dettaglio_fatture (id_fattura,descrizione,imponibile) VALUES
								($ultimo_id_fattura,'" . $oggetto_riga -> descrizione . "'," . $oggetto_riga -> imponibile . ")";
					mysql_query($query,$conn);
				}
				// comunico al mondo esterno che il salvataggio è avvenuto con successo
				return true;
			} else {			
				// comunico al mondo esterno che si sono verificati dei problemi durante il salvataggio
				return false;
			}			
		}
	}
	
	// ogni riga della fattura può a sua volta essere vista come un oggetto
	// si tratta di un oggetto molto semplice che racchiude informazioni sulla "descrizione" e sull'"imponibile"
	class RigaFattura {
		var $descrizione;
		var $imponibile;
	}
?>