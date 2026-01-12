<?php
include("connexion.php");
?>
<!DOCTYPE html>
<html>
    <head></head>
    <body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file"  name="logs[]" webkitdirectory directory multiple >
        <input type="submit">
    </form>
    </body>
</html>
<?php
if(isset($_FILES['logs'])){
    /*if(is_array($_FILES['logs']['name'])){
        echo "<p> yes </p>";
    }
    else{
        echo "<p> no </p>";
    }*/
    $nblogs=count($_FILES['logs']['name']);
    //echo "<p> $nblogs </p>";
    for($j=0;$j<$nblogs;$j++){
        $log=file_get_contents($_FILES['logs']['tmp_name'][$j]);
        $lignes=explode("\n",$log);
        for($i=0;$i<count($lignes) - 1;$i++){
            if(str_contains($lignes[$i],"SN")){
                $num=substr($lignes[$i],strpos($lignes[$i],"N")+2);
                //echo "<p> $num </p>";
            }
            if((str_contains($lignes[$i],"<") || str_contains($lignes[$i],">"))&&($lignes[$i][0]!="*"&&$lignes[$i][0]!="~")){
                if(str_contains($lignes[$i],"<")){
                  $component=substr($lignes[$i],0,strpos($lignes[$i],"<"));
                }
                else{
                    $component=substr($lignes[$i],0,strpos($lignes[$i],">"));
                }
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
            if(str_contains($lignes[$i+2],"MEASURED")){
                $expect="-";
                if(str_contains($lignes[$i],"<")){
                    $actual=substr($lignes[$i],strpos($lignes[$i],"<")+1,strpos($lignes[$i],"(")-strpos($lignes[$i],"<")-1);
                }
                else{
                    $actual=substr($lignes[$i],strpos($lignes[$i],">")+1,strpos($lignes[$i],"(")-strpos($lignes[$i],">")-1);
                }
            }
            else{
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
            }
            //echo "<p> $actual </p>";
                $stm = $connexion->prepare("INSERT INTO log (num_carte,componant,expected,actual,low,high)  VALUES ( ?, ?, ?, ?, ?, ?)");
                $stm->execute(array($num,$component,$expect,$actual,$low,$high));
        }
                $ligne3=$lignes[$i];
                if($ligne3[0]=="]"){
                    //echo "<p> $ligne3 </p>";
                    $date=substr($ligne3,strpos($ligne3,"]")+1,strpos($ligne3," ")-strpos($ligne3,"]")-1);
                    //echo "<p> $date </p>";
                    $heure=trim(substr($ligne3,strpos($ligne3," ")));
                    //echo "<p> $heure </p>";
                    $sql2="Update log set heur='$heure' where  heur='0'";
                    $sql3="Update log set date='$date' where  date='' ";
                    $s = $connexion->exec($sql2);
                    $d = $connexion->exec($sql3);
                }
    }
}
}
?>