<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Richieste</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-white d-flex flex-column align-items-center p-3">

    <?php

        require "./../../conn.php";
        session_start();
        if (isset($_POST['approva'])) 
        {
            foreach ($_POST as $key => $value) 
            {
                if($value=='on')
                {
                    $idDoc=$_SESSION['ID'];
                    $conn->query("UPDATE richiesta SET cod_docente = $idDoc , rifiutata = 0 WHERE ID=$key");
                    $table=$conn->query("SELECT descrizione,cod_alunno,valore,cod_destinatario,cod_docente FROM richiesta WHERE ID=$key");
                    $row=$table->fetch_assoc();
                    extract($row);
                    $valore=-$valore;
                    $conn->query("INSERT INTO intervento VALUES('',1,$valore,CURRENT_DATE(),'$descrizione',$cod_destinatario,$cod_docente,$key)");
                }
            }
        }
        elseif (isset($_POST['rifiuta'])) 
        {
            foreach ($_POST as $key => $value) 
            {
                if($value=='on')
                {
                    $idDoc=$_SESSION['ID'];
                    $conn->query("UPDATE richiesta SET cod_docente = $idDoc, rifiutata = 1 WHERE ID=$key");
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
                <td class="text-white">Motivo bonus</td>
                <td class="text-white">Bonus</td>
                <td class="text-white">Alunno Richiedente</td>
            </thead>
            <tbody>
                <?php
                    

                    $table=$conn->query("SELECT richiesta.ID AS IDRichiesta, destinatario.ID, destinatario.nome, descrizione, valore, alunno.nome AS nomeRich FROM richiesta, alunno , alunno AS destinatario  WHERE richiesta.cod_destinatario=destinatario.ID AND richiesta.cod_alunno=alunno.ID AND richiesta.cod_docente IS NULL;");

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