<?php

    session_start();

    if (!isset($_SESSION['ID'])) //! in caso di logout e quindi sessione non presente l'utente viene reindirizzato al login
    {
        header('Location: ./../../index.php');
    }


?>

<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Richieste</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-dark d-flex flex-column align-items-center p-3">

    <?php

        require "./../../conn.php";

        
        if (isset($_POST['approva'])) //* Approvazione quando viene premuto il pulsante
        {
            foreach ($_POST as $key => $value) //* Approvazione di tutte le check-box attivate
            {
                if($value=='on') 
                {
                    $idDoc=$_SESSION['ID'];

                    $table=$conn->query("SELECT descrizione,cod_alunno,valore,cod_destinatario FROM richiesta WHERE ID=$key");
                    $row=$table->fetch_assoc();
                    extract($row);

                    $sql="SELECT SUM(punteggio) AS pntAlunno FROM intervento WHERE cod_alunno=$cod_destinatario";
                    $table=$conn->query($sql);
                    $row=$table->fetch_assoc();

                    $flag=0;

                    if ($row['pntAlunno']+$valore>0) //! La richiesta viene approvata solo se il punteggio risulta maggiore di 0 altrimenti rimane in stallo
                    {
                        $sql="INSERT INTO intervento VALUES (NULL,1,$valore,CURRENT_DATE(),'$descrizione',$cod_destinatario,$idDoc, $key );";
                        $conn->query($sql);
                        $conn->query("UPDATE richiesta SET cod_docente = $idDoc , rifiutata = 0 WHERE ID=$key");
                        $flag=1;
                    } 
                }
            }
        }
        elseif (isset($_POST['rifiuta'])) 
        {
            foreach ($_POST as $key => $value) 
            {
                if($value=='on')
                {
                    $flag=1;

                    $idDoc=$_SESSION['ID'];
                    $conn->query("UPDATE richiesta SET cod_docente = $idDoc, rifiutata = 1 WHERE ID=$key"); //* Rifiuto della richiesta
                }
            }
        }

    ?>


    <button class="btn btn-primary btn-lg align-self-start" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
        </svg>
    </button>
    <div class="title text-white"><h1>Visualizza richieste bonus</h1></div>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <table class="table table-dark table-bordered table-striped">
            <thead>
                <td class="text-white"></td>
                <td class="text-white">ID Richiesta</td>
                <td class="text-white">ID Alunno</td>
                <td class="text-white">Nome alunno</td>
                <td class="text-white">Cognome alunno</td>
                <td class="text-white">Motivo bonus</td>
                <td class="text-white">Bonus</td>
                <td class="text-white">Nome richiedente</td>
                <td class="text-white">Cognome richiedente</td>
            </thead>
            <tbody>
                <?php
                    
                    //* Popolamento della tabella con tutte le richieste e le check-box con valore = richiesta.ID

                    $table=$conn->query("SELECT richiesta.ID AS IDRichiesta, destinatario.ID, destinatario.nome, destinatario.cognome, descrizione, valore, alunno.nome AS nomeRich, alunno.cognome AS cognomeRich FROM richiesta, alunno , alunno AS destinatario  WHERE richiesta.cod_destinatario=destinatario.ID AND richiesta.cod_alunno=alunno.ID AND richiesta.cod_docente IS NULL;");

                    if ($table->num_rows>0)
                    {
                        while($row=$table->fetch_assoc())
                        {
                            $idRich=$row['IDRichiesta'];
                            echo("<tr>");
                            echo("<td><input class='form-check-input' type='checkbox' id='$idRich' name='$idRich'".$row['ID']."'></td>");
                            foreach ($row as $val) 
                            {
                                echo("<td class='text-white'>$val</td>");
                            }
                            echo("</tr>");
                        }
                    }

                ?>
            </tbody>
        </table>

        <button type="submit" name='approva' class="btn btn-primary">Approva</button>
        <button type="submit" name='rifiuta' class="btn btn-primary">Rifiuta</button>

    </form>

    <?php

        if (isset($_POST['approva'])) 
        {
            if ($flag==0) //! in caso di logout e quindi sessione non presente l'utente viene reindirizzato al login
            {

                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Il punteggio è troppo basso per accettare questo bonus!!!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>"; //! Output in caso di richiesta non approvata con successo
            }
        }  

    ?>

    <div class="offcanvas offcanvas-start" id="demo">
        <div class="offcanvas-header">
            <h1 class="offcanvas-title">Menù</h1>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <button class="btn btn-primary mb-3 mt-6" value="Home" onclick="location.href = './docente.php'">Home</button><br>
            <button class="btn btn-primary mb-3 mt-6" value="Crea alunno" onclick="location.href = './creaAlunno.php'">Crea alunno</button><br>
            <button class="btn btn-primary mb-3 mt-6" value="Estrai alunno" onclick="location.href = './estrazione.php'">Estrai alunno</button><br>
            <button class="btn btn-outline-danger mb-3 mt-6" onclick="location.href = './../../index.php'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z"/>
                    <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
                </svg>
                Log Out
            </button>
        </div>
    </div>

</body>
</html>