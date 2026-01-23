<?php
header("Access-Control-Allow-Origin: *"); //laisse  l'accées au angular
header("Content-Type: application/json"); //le contenu devient on json qui etait en texte
include("connexion.php");
$num = $_GET['num_carte'] ?? '';

$sql = "
    SELECT date, hour, num_carte, component, expected, actual, low, high
    FROM log
    WHERE num_carte LIKE ?
    ORDER BY date DESC, hour DESC
    LIMIT 500
";

$stmt = $connexion->prepare($sql);
$stmt->execute(['%' . $num . '%']);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

?>