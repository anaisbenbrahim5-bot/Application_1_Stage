<?php
include("connexion.php");
?>
<!DOCTYPE html>
<html>
    <head></head>
    <body>
    <form action="" method="post">
        <textarea name="log" id=""></textarea>
        <input type="submit">
    </form>
    </body>
</html>
<?php
if(isset($_POST['log'])){
    $log=$_POST['log'];
    $lignes=explode("\n",$log);
    for($i=0;$i<count($lignes) - 1;$i++){
        if(str_contains($lignes[$i],"<") || str_contains($lignes[$i],">")){
            $component=substr($lignes[$i+1],strpos($lignes[$i+1],"(")+1,strpos($lignes[$i+1],")")-strpos($lignes[$i+1],"(")-1);
           //echo "<p> $component </p>";
           $low=substr($lignes[$i],strpos($lignes[$i],"(")+1,strpos($lignes[$i],",")-strpos($lignes[$i],"(")-1);
           //echo "<p> $low </p>";
           $high=substr($lignes[$i],strpos($lignes[$i],",")+1,strpos($lignes[$i],")")-strpos($lignes[$i],",")-1);
           //echo "<p> $high </p>";
           $ligne=trim($lignes[$i+3]);
           $words = preg_split('/\s+/', $ligne);
           if (count($words) == 2) {
               $expect = $words[0];
               $actual = $words[1];
            } 
            else if (count($words) == 3) {
                if (str_contains($words[1], 'OHMS')) {
                    $expect = $words[0] . ' ' . $words[1];
                    $actual = $words[2];
                } else {
                    $expect = $words[0];
                    $actual = $words[1] . ' ' . $words[2];
                }
            } 
            else if (count($words) == 4) {
                $expect = $words[0] . ' ' . $words[1];
                $actual = $words[2] . ' ' . $words[3];
            } 
            else{
                $expect="-";
                $actual=$words[count($words)-2] . ' ' . $words[count($words)-1];
            }
           echo "<p> $actual </p>";
            $stm = $connexion->prepare("INSERT INTO log (componant,expected,actual,low,high)  VALUES ( ?, ?, ?, ?, ?)");
            $stm->execute(array($component,$expect,$actual,$low,$high));
        }
        if(str_contains($lignes[$i],"Num")){
            $num=substr($lignes[$i],strpos($lignes[$i]," "),5);
            //echo "<p> $num </p>";
        }
        $ligne2=$lignes[$i];
        if($ligne2[0]=="]"){
            //echo "<p> $ligne2 </p>";
            $date=substr($ligne2,strpos($ligne2,"]")+1,strpos($ligne2," ")-strpos($ligne2,"]")-1);
            //echo "<p> $date </p>";
            $heure=substr($ligne2,strpos($ligne2," "));
            //echo "<p> $heure </p>";
            $sql2="Update log  set date='$date',heur='$heure' where heur=0";
            $s = $connexion->exec($sql2);
        }
        $sql3="Update log set num_carte='$num' where num_carte=0";
        $n = $connexion->exec($sql3);

    }
}
?>