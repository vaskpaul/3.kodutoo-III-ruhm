<?php 
class Note {
	
    private $connection;
	
	function __construct($mysqli){
		$this->connection = $mysqli;
	}
	
	/* KLASSI FUNKTSIOONID */
    
    function saveNote($toit, $kalorid, $kuupaev) {
		
		$stmt = $this->connection->prepare("INSERT INTO toitumine2 (toit, kalorid, kuupaev) VALUES (?, ?, ?)");
		echo $this->connection->error;
		
		$stmt->bind_param("sss", $toit, $kalorid, $kuupaev );

		if ( $stmt->execute() ) {
			echo "salvestamine õnnestus";	
		} else {	
			echo "ERROR ".$stmt->error;
		}
		
	}
	
	
	function getAllNotes($q, $sort, $order) {
		//lubatud tulbad
		$allowedSort = ["id", "toit", "kalorid", "kuupaev"];
		if(!in_array($sort, $allowedSort)){
			//ei olnud lubatud tulpade sees
			$sort = "id";  //ilu pärast võib lasta sorteerida ilu järgi, võib panna ka exit();
		}
		$orderBy = "ASC";
		if($order == "DESC"){
				$orderBy = "DESC";
		}
		
		echo "sorteerin ".$sort." ".$orderBy." ";
		//otsime
		if($q !=""){
			echo "Otsin: ".$q;
			$stmt = $this->connection->prepare("
				SELECT id, toit, kalorid, kuupaev
				FROM toitumine2
				WHERE deleted IS NULL
				AND (toit LIKE ? OR kalorid LIKE ? OR kuupaev LIKE ?)
				ORDER BY $sort $orderBy
		");
		$searchWord = "%".$q."%";
		$stmt->bind_param("sss", $searchWord, $searchWord, $searchWord);
		}else{
			//ei otsi
			$stmt = $this->connection->prepare("
				SELECT id, toit, kalorid, kuupaev
				FROM toitumine2
				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
		");
		}
		$stmt->bind_result($id, $toit, $kalorid, $kuupaev);
		$stmt->execute();
		
		$result = array();
		
		while ($stmt->fetch()) {
			//echo $note."<br>";
			
			$object = new StdClass();
			$object->id = $id;
			$object->toit = $toit;
			$object->kalorid = $kalorid;
			$object->kuupaev = $kuupaev;
			
			
			array_push($result, $object);
			
		}
		
		return $result;
		
	}
	
	function getSingleNoteData($edit_id){
    		
		$stmt = $this->connection->prepare("SELECT toit, kalorid, kuupaev FROM toitumine2 WHERE id=? AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($toit, $kalorid, $kuupaev);
		$stmt->execute();
		
		//tekitan objekti
		$n = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			$n->toit = $toit;
			$n->kalorid = $kalorid;
			$n->kuupaev = $kuupaev;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();		
		return $n;
		
	}


	function updateNote($id, $toit, $kalorid, $kuupaev){
				
		$stmt = $this->connection->prepare("UPDATE toitumine2 SET toit=?, kalorid=?, kuupaev=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("sssi",$toit, $kalorid, $kuupaev, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
	}
	
	function deleteNote($id){
		
		$stmt = $this->connection->prepare("
			UPDATE toitumine2 
			SET deleted=NOW() 
			WHERE id=? AND deleted IS NULL
		");
		$stmt->bind_param("i", $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
	}
} 
?>