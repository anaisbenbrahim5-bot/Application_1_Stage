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
        if((str_contains($lignes[$i],"<") || str_contains($lignes[$i],">"))&&($lignes[$i][0]!="*"&&$lignes[$i][0]!="~")){
            $component=substr($lignes[$i+1],strpos($lignes[$i+1],"(")+1,strpos($lignes[$i+1],")")-strpos($lignes[$i+1],"(")-1);
           //echo "<p> $component </p>";
           $low=substr($lignes[$i],strpos($lignes[$i],"(")+1,strpos($lignes[$i],",")-strpos($lignes[$i],"(")-1);
           if($low==""){
               $low="-";
           }
           //echo "<p> $low </p>";
           $high=substr($lignes[$i],strpos($lignes[$i],",")+1,strpos($lignes[$i],")")-strpos($lignes[$i],",")-1);
           if($high==""){
               $high="-";
           }
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
            else if(count($words)==5){
                $expect = $words[0] . ' ' . $words[1] . ' ' . $words[2];
                $actual = $words[3] . ' ' . $words[4];
            }
            else{ 
                $expect="-";
                $actual=$words[count($words)-2] . ' ' . $words[count($words)-1];
            }
           //echo "<p> $actual </p>";
            $stm = $connexion->prepare("INSERT INTO log (componant,expected,actual,low,high)  VALUES ( ?, ?, ?, ?, ?)");
            $stm->execute(array($component,$expect,$actual,$low,$high));
        }
        if(str_contains($lignes[$i],"SER")){
            $num=substr($lignes[$i],strpos($lignes[$i]," "));
            $sql2="Update log set num_carte='$num' where num_carte=0";
            $n = $connexion->exec($sql2);
            //echo "<p> $num </p>";
        }
        $ligne3=$lignes[$i];
        if($ligne3[0]=="]"){
            //echo "<p> $ligne3 </p>";
            $date=substr($ligne3,strpos($ligne3,"]")+1,strpos($ligne3," ")-strpos($ligne3,"]")-1);
            //echo "<p> $date </p>";
            $heure=substr($ligne3,strpos($ligne3," "));
            //echo "<p> $heure </p>";
            $sql3="Update log  set heur='$heure' where heur=0";
            $sql4="Update log set date='$date' where date=0";
            $s = $connexion->exec($sql3);
            $d= $connexion->exec($sql4);
        }

    }
}
?>