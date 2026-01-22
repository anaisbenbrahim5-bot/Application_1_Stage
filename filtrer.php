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
    $chunks=array_chunk($_FILES['logs']['tmp_name'],500);
    foreach ($chunks as $chunk) {
        $nblogs=count($chunk);
        //echo "<p> $nblogs </p>";
        for($j=0;$j<$nblogs;$j++){
            //echo "<p> $num </p>";
            $log=file_get_contents($chunk[$j]);
            $num=null;
            $lignes=explode("\n",$log);
            for($i=0;$i<count($lignes) - 1;$i++){
                if (preg_match('/SN\s+(\d+)/', $lignes[$i], $matches)) {
                    $num = $matches[1];
                }
                $ligne3=$lignes[$i];
                    if(str_contains($ligne3,"[")){
                        //echo "<p> $ligne3 </p>";
                        $datech=substr($ligne3,strpos($ligne3,"[")+1,strpos($ligne3," ")-strpos($ligne3,"[")-1);
                        //echo "<p> $datech </p>";
                       $date = DateTime::createFromFormat('d-M-y', $datech);
                        //echo "<p> $date </p>";
                        $heure=trim(substr($ligne3,strpos($ligne3," ")));
                        //echo "<p> $heure </p>";
                    }
                if((str_contains($lignes[$i],"<") || str_contains($lignes[$i],">"))&&($lignes[$i][0]!="*"&& $lignes[$i][0]!="~" && !str_contains($lignes[$i],"POWER"))){
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
                if(!str_contains($lignes[$i+2],"EXPECTED")){
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
                        $actual = '';
                        if (count($words) >= 2) {
                            $actual = $words[count($words)-2] . ' ' . $words[count($words)-1];
                        } elseif (count($words) === 1) {
                            $actual = $words[0]; 
                        }
                }
                }
                    //echo "<p> $actual </p>";
                    $dates= $date->format('Y-m-d');
                    $stm = $connexion->prepare("INSERT IGNORE  INTO log (date, hour, num_carte, component, expected, actual, low, high)VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stm->execute([$dates,$heure,$num,$component,$expect,$actual,$low,$high]);
            }
        }
    }
}
}
?>