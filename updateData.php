<?php
session_start();
include "connection.php";

$best = 0;
if(isset($_SESSION['user']))
{
	$sql = "SELECT * FROM ".$_POST['tipo']." t, users u WHERE t.id_user=".$_SESSION['id']." AND u.id=t.id_user";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_array())
		{
			if($_POST['score'] > $row['score'])
			{
				$sql2 = "UPDATE ".$_POST['tipo']." SET score=" . $_POST['score'] . ", intentos=" . ($row['intentos']+1) . " WHERE id_user=".$_SESSION['id'];
				if(!$conn->query($sql2)){
					echo "ERROR";
				}
				$best = $_POST['score'];
			}
			else
			{
				$sql2 = "UPDATE ".$_POST['tipo']." SET intentos=" . ($row['intentos']+1) . " WHERE id_user=".$_SESSION['id'];
				if(!$conn->query($sql2)){
					echo "ERROR";
				}
				$best = $row['score'];
			}
			
		}
	}
	else
	{
		$sql = "INSERT INTO ".$_POST['tipo']." (id_user, score, intentos) VALUES (".$_SESSION['id'].", ".$_POST['score'].", 1)";
		if(!$conn->query($sql))
		{
			echo "ERROR";
		}
		$best = $_POST['score'];
	}
	$conn->close();
}

echo $best;

?>