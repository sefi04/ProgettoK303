<?php 
// Include the database config file 
require './../../conn.php'; 
 
if(!empty($_GET['q']))
{ 
    // Fetch classes data based on the specific class
    $query = "SELECT * FROM alunno WHERE cod_classe = ".$_GET['q']." ORDER BY nome ASC"; 
    $result = $conn->query($query); 
     
    // Generate HTML of state options list 
    if($result->num_rows > 0){ 
        echo '<option value=""></option>'; 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['ID'].'">'.$row['nome']." ".$row['cognome'].'</option>'; 
        } 
    }else{ 
        echo '<option value="">Classe non disponibile</option>'; 
    } 
}
else
{
    echo '<option value="">Classe non disponibile</option>';
}
?>