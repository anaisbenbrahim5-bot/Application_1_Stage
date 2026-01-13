<?php
$host='localhost'; 
$dbname='GestionLog';  
$username='root';  
$password=''; 
try
{
	$connexion = new PDO('mysql:host='.$host.';dbname='.$dbname,$username, $password);
}
catch(PDOException $e) 
{ 
    echo 'Erreur : '.$e->getMessage().'<br />'; 
    echo 'N° : '.$e->getCode(); 
}       
?>