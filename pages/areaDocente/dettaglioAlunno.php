<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettaglio Alunno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-dark d-flex flex-column align-items-center p-3">

    <?php 

        session_start();

        $idDoc=$_SESSION['ID'];

        require "./../../conn.php";

        $idAlunno=$_POST['alunno'];

        $table=$conn->query("SELECT * FROM alunno WHERE ID=$idAlunno");

        $alunno=$table->fetch_assoc();
        if(isset($_POST['AssegnaBonus']))
        {
            $pnt=$_POST['valore']*$_POST['tipo'];

            if ($_POST['tipo']==-1) 
            {
                $tipo=0;
            }
            else
            {
                $tipo=1;
            }

            $sql="SELECT SUM(punteggio) AS pntAlunno FROM intervento WHERE cod_alunno=$idAlunno";
            $table=$conn->query($sql);
            $row=$table->fetch_assoc();
            if ($row['pntAlunno']+$pnt>0) 
            {
                if ($conn->query("INSERT INTO intervento VALUES ('',$tipo,$pnt,CURRENT_DATE(),'Assegnato dal docente',$idAlunno,$idDoc,NULL)")===TRUE) 
                {
                    echo "<h3 class='text-white'>Bonus Assegnato correttamente</h3>";
                }
                else 
                {
                    echo "<p class='text-white'>Errore nell'inserimento: ".$conn->error."</p>";
                }
            } 
            else 
            {
                echo "<p class='text-white'>Il punteggio e' troppo basso</p>";
            }
            
        }
    ?>

    <button class="btn btn-primary btn-lg align-self-start" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
        </svg>
    </button>
    <div class="title text-white"><h1>Dettaglio Alunno</h1></div>

    <div class="container mt-4 d-flex flex-column align-items-center bg-primary rounded w-75 p-4 text-white">
        <h3>Nome: <?php echo $alunno['nome'] ?></h3>
        <h3>Cognome: <?php echo $alunno['cognome'] ?></h3>
        <h3>Data di Nascita: <?php echo $data=date('d/m/Y', strtotime($alunno['data_nascita'])); ?></h3>
        <h3>e-Mail: <?php echo $alunno['email'] ?></h3>

        <?php 

            $table=$conn->query("SELECT SUM(punteggio) AS tot FROM intervento,alunno WHERE intervento.cod_alunno=alunno.ID AND alunno.cod_classe=(SELECT cod_classe FROM alunno WHERE ID=$idAlunno)");
            $row=$table->fetch_assoc();
            $totpunt=$row['tot'];

            $table=$conn->query("SELECT SUM(punteggio) AS tot FROM intervento WHERE cod_alunno=$idAlunno");
            $row=$table->fetch_assoc();
            if ($row['tot']!=NULL and $row['tot']>0) 
            {
                $pnt=$row['tot'];
            } 
            else 
            {
                $pnt=1;
            }
            
            
            $table=$conn->query("SELECT COUNT(alunno.ID) AS tot FROM alunno,classe WHERE cod_classe=(SELECT cod_classe FROM alunno WHERE ID=$idAlunno)");
            $row=$table->fetch_assoc();
            $totAlunn=$row['tot'];

            if ($totpunt!=0) 
            {
                $prob=round(100.0-($pnt/($totpunt)*100), 2);
            }
            else
            {
                
                $prob=1/$row['tot']*100;
            }
            

        ?>

        <h3>Punteggio: <?php echo $pnt ?></h3>
        <h3>Probabilità: <?php echo $prob ?>%</h3>

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

                $table=$conn->query("SELECT intervento.data, intervento.descrizione, intervento.punteggio,intervento.tipologia,intervento.cod_richiesta FROM intervento WHERE intervento.cod_alunno=$idAlunno ORDER BY intervento.data DESC;");

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

                $table=$conn->query("SELECT ID, data FROM estrazione WHERE cod_alunno=$idAlunno;
                ");

                while($row=$table->fetch_assoc())
                {
                    $data= $row['data'];

                    $data=date('d/m/Y', strtotime($data));

                    $id=$row['id'];

                    
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
            <button class="btn btn-primary mb-3 mt-6" value="Home" onclick="location.href = './docente.php'">Home</button><br>
            <button class="btn btn-primary mb-3 mt-6" value="Crea alunno" onclick="location.href = './creaAlunno.php'">Crea alunno</button><br>
            <button class="btn btn-primary mb-3 mt-6" value="Visualizza richieste bonus" onclick="location.href = './visualizzaRichieste.php'">Visualizza richieste bonus</button><br>
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

