<?php
header("Access-Control-Allow-Origin: *"); //laisse  l'accées au angular
header("Content-Type: application/json"); //le contenu devient on json qui etait en texte
include("connexion.php");
$sql="SELECT * FROM log";
$req=$connexion->query($sql);
$data=[];
while($row=$req->fetch(PDO::FETCH_ASSOC)){
    $data[]=$row;
}
echo json_encode($data);
?>