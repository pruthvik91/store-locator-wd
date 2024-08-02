<?php
if(!isset($_SESSION['userid']) && empty($_SESSION['userid'])){
	$redirect="index.php";
	header("location: $redirect"); 
}
?>