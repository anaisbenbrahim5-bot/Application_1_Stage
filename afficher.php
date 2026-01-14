 <html>
     <head>
         <style>
             table{ border : 1px solid black;}
             tr td{
                 border : 1px solid black;
             }

         </style>
     </head>
     <body>
    <table>
        <tr>
            <td>Date</td>
            <td>Heure</td>
            <td>numerdo de carte</td>
            <td>composant</td>
            <td>expected</td>
            <td>actual</td>
            <td>low</td>
            <td>high</td>
        </tr>
        <?php
        include("connexion.php");
        $sql="select * from log";
        $req=$connexion->query($sql);
        while($d=$req->fetch(PDO::FETCH_ASSOC)){
            echo "<tr>
                  <td>" . $d['date'] . "</td>
                  <td>" . $d['hour'] . "</td>
                  <td>" . $d['num_carte'] . "</td>
                  <td>" . $d['component'] . "</td>
                  <td>" . $d['expected'] . "</td>
                  <td>" . $d['actual'] . "</td>
                  <td>" . $d['low'] . "</td>
                  <td>" . $d['high'] . "</td>
                  </tr>";
        }
         ?> 
    </table>
    </body>
 </html>