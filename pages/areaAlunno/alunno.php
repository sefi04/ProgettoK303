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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Alunno</title>
</head>
<body class="bg-dark d-flex flex-column align-items-center p-3">

    <?php 
        require "./../../probabilita.php"; 

        $id=$_SESSION['ID'];

        $table=$conn->query("SELECT * FROM alunno WHERE alunno.id=$id");
        $aln=$table->fetch_assoc();

    ?>

    <button class="btn btn-primary btn-lg align-self-start" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
        </svg>
    </button>

    <div class="title text-white"><h1>Salve <?php echo $aln['nome']." ".$aln['cognome'] ?></h1></div>

    <div class="container mt-4 d-flex flex-column align-items-center bg-primary rounded w-50 p-4 text-white">
        <h3>Email: <?php echo $aln['email']?></h3>

        <h3>Punteggio: 
            <?php 
                $in=calcolaProb($aln['cod_classe'],$id);
                echo $in['pnt'];
            ?>
        </h3>

        <h3>Probabilità: 
            <?php
                
                echo $in['prob'];
            ?>
        </h3>
    </div>

    <div class="title text-white mt-5"><h2>Interventi</h2></div>

    <table class="table table-dark table-bordered table-striped w-75">
        <thead>
            <td class="text-white">Data</td>
            <td class="text-white">Descrizione</td>
            <td class="text-white">Punteggio</td>
            <td class="text-white">Tipologia</td>
            <td class="text-white">Richiedente</td>
        </thead>
        <tbody>
            <?php

                $table=$conn->query("SELECT intervento.data, intervento.descrizione, intervento.punteggio,intervento.tipologia,intervento.cod_richiesta FROM intervento WHERE intervento.cod_alunno=$id ORDER BY intervento.data DESC;");

                while($row=$table->fetch_assoc())
                {
                    $data= $row['data'];

                    $data=date('d/m/Y', strtotime($data));

                    $desc=$row['descrizione'];
                    $punti=$row['punteggio'];
                    $tipo=$row['tipologia'];

                    if ($tipo!=1) 
                    {
                        $tipo="Bonus";
                    } 
                    else 
                    {
                        $tipo="Malus";
                    }
                    
                    if (!is_null($row['cod_richiesta'])) 
                    {
                        $rich=$row['cod_richiesta'];
                    }

                    
                    echo("<tr>");
                    echo("<td class='text-white'>$data</td>");
                    echo("<td class='text-white'>$desc</td>");
                    echo("<td class='text-white'>$punti</td>");
                    echo("<td class='text-white'>$tipo</td>");
                    if (!is_null($row['cod_richiesta'])) 
                    {
                      
                        echo("<td class='text-white'>$rich</td>");
                    }
                    else
                    {
                        echo("<td class='text-white'>Assegnato dal docente</td>");
                    }
                    echo("</tr>");
                }

            ?>
        </tbody>
    </table>

    <div class="title text-white mt-5"><h2>Estrazioni</h2></div>

    <table class="table table-dark table-bordered table-striped w-75">
        <thead>
            <td class="text-white">ID</td>
            <td class="text-white">Data</td>
        </thead>
        <tbody>
            <?php

                $table=$conn->query("SELECT ID, data FROM estrazione WHERE cod_alunno=$id   ;
                ");

                while($row=$table->fetch_assoc())
                {
                    $data= $row['data'];

                    $data=date('d/m/Y', strtotime($data));

                    $id=$row['ID'];

                    
                    echo("<tr>");
                    echo("<td class='text-white'>$id</td>");
                    echo("<td class='text-white'>$data</td>");
                    echo("</tr>");
                }

            ?>
        </tbody>
    </table>

    <div class="offcanvas offcanvas-start" id="demo">
        <div class="offcanvas-header">
            <h1 class="offcanvas-title">Menù</h1>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <button class="btn btn-primary mb-3 mt-6" value="cambioPassword" onclick="location.href = './cambioPass.php'">Cambia password</button><br>
            <button class="btn btn-primary mb-3 mt-6" value="assegnaBonus" onclick="location.href = './assegnaBonus.php'">Assegna Bonus</button><br>
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