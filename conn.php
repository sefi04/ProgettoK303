<?php
    $conn=new mysqli('localhost','root','','k303');

    if ($conn->connect_error) 
    {
        echo "Connessione non riuscita ".$conn->connect_error;
    }
?>