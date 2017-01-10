<?php 
	// et saada ligi sessioonile
	require("../functions.php");
	
    require("../class/Helper.class.php");
	$Helper = new Helper();
	
	require("../class/Note.class.php");
	$Note = new Note($mysqli);
	
	//ei ole sisseloginud, suunan login lehele
	if(!isset ($_SESSION["userId"])) {
		header("Location: login.php");
		exit();
	}
	
	//kas kasutaja tahab välja logida
	// kas aadressireal on logout olemas
	if (isset($_GET["logout"])) {
		
		session_destroy();
		
		header("Location: login.php");
		exit();
	}
	
	if (	isset($_POST["toit"]) && 
			isset($_POST["kalorid"]) && 
			isset($_POST["kuupaev"]) && 
			!empty($_POST["toit"]) && 
			!empty($_POST["kalorid"]) && 
			!empty($_POST["kuupaev"]) 
	) {
		
		$toit = $Helper->cleanInput($_POST["toit"]);
		$kalorid = $Helper->cleanInput($_POST["kalorid"]);
		$kuupaev = $Helper->cleanInput($_POST["kuupaev"]);
		$Note->saveNote($toit, $kalorid, $kuupaev);
		
	}
	
	$q = "";
	//otsisõna aadressirealt
	if(isset($_GET["q"])){
		$q = $Helper->cleanInput($_GET["q"]);
	}
	
	$sort ="id";
	$order = "ASC";
	if(isset($_GET["sort"]) && isset($_GET["order"])){
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}
	$notes = $Note->getAllNotes($q, $sort, $order);
	
	//echo "<pre>";
	//var_dump($notes);
	//echo "</pre>";

?>
<?php require("../header.php"); ?>

<h1>Data</h1>
<p>
	Tere tulemast <a href="user.php"><?=$_SESSION["userEmail"];?></a>!
	<a href="?logout=1">Logi välja</a>
</p>
<h2><i>Märkmed</i></h2>
<form method="POST">
			
	<label>Toit</label><br>
	<input name="toit" type="text">
	
	<br><br>
	
	<label>Kalorid</label><br>
	<input name="kalorid" type="text">
	
	<br><br>
	
	<label>Kuupäev</label><br>
	<input name="kuupaev" type="text">
				
	<br><br>
	
	<input type="submit">

</form>

<h2>arhiiv</h2>
<form>
	<input type="search" name ="q" value="<?=$q;?>">
	<input type="submit" value="otsi">
</form>


<h2 style="clear:both;">Tabel</h2>
<?php 

	$html = "<table class='table'>";
		
		$html .= "<tr>";
		
			$orderId = "ASC";
			if(isset($_GET["order"])&&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "id" ){
					
				$orderId = "DESC";
				}
				
		$html .= "<th>
				<a href='?q=".$q."&sort=id&order=".$orderId."'>
					id
					</a>
				</th>";
				
				$orderId = "ASC";
			if(isset($_GET["order"])&&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "toit" ){
					
				$orderId = "DESC";
				}
				
		$html .= "<th>
				<a href='?q=".$q."&sort=toit&order=".$orderId."'>
					toit
					</a>
				</th>";
			
				$orderId = "ASC";
			if(isset($_GET["order"])&&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "kalorid" ){
					
				$orderId = "DESC";
				}
				
		$html .= "<th>
				<a href='?q=".$q."&sort=kalorid&order=".$orderId."'>
					kalorid
					</a>
				</th>";
				
			$orderId = "ASC";
			if(isset($_GET["order"])&&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "kuupaev" ){
					
				$orderId = "DESC";
				}
				
		$html .= "<th>
				<a href='?q=".$q."&sort=kuupaev&order=".$orderId."'>
					kuupaev
					</a>
				</th>";
			
			

		$html .= "</tr>";

	foreach ($notes as $note) {
		$html .= "<tr>";
			$html .= "<td>".$note->id."</td>";
			$html .= "<td>".$note->toit."</td>";
			$html .= "<td>".$note->kalorid."</td>";
			$html .= "<td>".$note->kuupaev."</td>";
			$html .= "<td><a href='edit.php?id=".$note->id."'>edit.php</a></td>";
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	echo $html;

?>

<?php require("../footer.php"); ?>



