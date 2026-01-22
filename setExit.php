<?php
header("Access-Control-Allow-Origin: http://localhost:4200"); //laisse  l'accées au angular
header("Access-Control-Allow-Methods: POST, OPTIONS"); //php accepte les requete post et option
header("Access-Control-Allow-Headers: Content-Type"); 
header("Content-Type: application/json"); //le contenu devient on json qui etait en texte
// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
include("connexion.php");
$data=json_decode(file_get_contents("php://input"),true);
if(!$data){
    echo json_encode(["success"=>false]);
    exit;
}
$sql="INSERT INTO  `exit`(date,hour,num_carte,component,expected,actual,low,high,problem) 
values(?,?,?,?,?,?,?,?,?)";
$req=$connexion->prepare($sql);
$req->execute([
    $data['date'],
    $data['hour'],
    $data['num_carte'],
    $data['component'],
    $data['expected'],
    $data['actual'],
    $data['low'],
    $data['high'],
    $data['problem']
]);
echo json_encode(["success"=>true]);
?>