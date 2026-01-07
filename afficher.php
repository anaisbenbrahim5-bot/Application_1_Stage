 <html>
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
            
        }
         ?> 
    </table>
 </html>