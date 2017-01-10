<?php
	//edit.php
	require("../functions.php");
	
    require("../class/Helper.class.php");
	$Helper = new Helper();

	require("../class/Note.class.php");
	$Note = new Note($mysqli);
	
	/// kas aadressireal on delete
	if(isset($_GET["delete"])){
		// saadan kaasa aadressirealt id
		$Note->deleteNote($_GET["id"]);
		header("Location: data.php");
		exit();
		
	}
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$Note->updateNote($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["toit"]), $Helper->cleanInput($_POST["kalorid"]), $Helper->cleanInput($_POST["kuupaev"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//saadan kaasa id
	$c = $Note->getSingleNoteData($_GET["id"]);
	//var_dump($c);

	
?>
<?php require("../header.php"); ?>
<br><br>
<a href="data.php"> tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="toit" >Toit</label><br>
	<textarea  id="toit" name="toit"><?php echo $c->toit;?></textarea><br>
	<label for="kalorid" >Kalorid</label><br>
	<textarea  id="kalorid" name="kalorid"><?php echo $c->kalorid;?></textarea><br>
	<label for="kuupaev" >Kuupäev</label><br>
	<textarea  id="kuupaev" name="kuupaev"><?php echo $c->kuupaev;?></textarea><br>
  	
	<input type="submit" name="update" value="Salvesta">
  </form>
  
<br>
<br>
<a href="?id=<?=$_GET["id"];?>&delete=true">kustuta</a>

<?php require("../footer.php"); ?>
  
  
  
  
  
  