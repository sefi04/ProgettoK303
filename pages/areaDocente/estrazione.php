<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <title>Estrazione</title>
</head>
<body class="bg-dark d-flex flex-column align-items-center p-3">

    <button class="btn btn-primary btn-lg align-self-start" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
        </svg>
    </button>

    <div class="title align-self-center">
        <h1 class="text-white ">Estrazione</h1>
    </div>

    <form class="w-75 d-flex flex-column align-items-center" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="mt-3 form-floating w-25 ">
            
            <select name="classe" id="classe" class="form-select">
                <option value=""></option>
                <?php

                    require "./../../conn.php";

                    $table=$conn->query("SELECT ID, settore FROM classe");
                    while ($row=$table->fetch_assoc()) 
                    {
                        $id=$row['ID'];
                        $nome=$row['settore'];

                        echo "<option value='$id'>$nome</option>";
                    }

                ?>
            </select>
            <label for="classe" class="form-label">Classe:</label>
        </div>

        <script type="text/javascript">document.getElementById('classe').value=<?php echo $_POST['classe']?></script>

        <button class="btn btn-primary mt-3" type="submit" name="estrai">Estrai</button>

    </form>

    <?php 

        if (isset($_POST['estrai']) && $_POST['classe']!='') 
        {
            $classe=$_POST['classe'];
            $scatola=array();

            $alunni=$conn->query("SELECT id FROM alunno WHERE cod_classe=$classe");

            if ($alunni->num_rows>0) 
            {
                foreach ($alunni as $row) 
                {
                    $id=$row['id'];

                    $table=$conn->query('SELECT SUM(punteggio) AS tot FROM intervento');
                    $row=$table->fetch_assoc();
                    $totpunt=$row['tot'];
        
                    $table=$conn->query("SELECT SUM(punteggio) AS tot FROM intervento WHERE cod_alunno=$id");
                    $row=$table->fetch_assoc();
                    $pnt=$row['tot'];
        
                    $prob=round(100.0-($pnt/$totpunt*100), 2);


                    for ($i=0; $i < $prob; $i++) 
                    { 
                        array_push($scatola,$id);
                    }

                }

                $idEstratto=$scatola[rand(0,count($scatola))];

                $table=$conn->query("SELECT * FROM alunno WHERE ID=$idEstratto");
                $row=$table->fetch_assoc();
                $nome=$row['nome'];
                $cognome=$row['cognome'];
                $data=$row['data_nascita'];
                


                echo "
                    <div class='container mt-4 d-flex flex-column align-items-center bg-primary rounded w-75 p-4 text-white'>
                    <h3>Nome: $nome</h3>
                    <h3>Cognome: $cognome </h3>
                    <h3>Data di Nascita: $data</h3>
                    ";
                 
                $table=$conn->query('SELECT SUM(punteggio) AS tot FROM intervento');
                $row=$table->fetch_assoc();
                $totpunt=$row['tot'];
    
                $table=$conn->query("SELECT SUM(punteggio) AS tot FROM intervento WHERE cod_alunno=$idEstratto");
                $row=$table->fetch_assoc();
                $pnt=$row['tot'];
    
                $prob=round(100.0-($pnt/$totpunt*100), 2);
            
                   

                echo"
                    <h3>Punteggio:  $pnt</h3>
                    <h3>Probabilità: $prob%</h3>
                    </div>;";

            } 
            else 
            {
                echo "<h3 class='text-white'>Nessun alunno da estrarre</h3>";
            }
            
        }
        else
        {
            "<h3>Selezionare una classe</h3>";
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
            <button class="btn btn-primary mb-3 mt-6" value="Visualizza richieste bonus" onclick="location.href = './visualizzaRichieste.php'">Visualizza richieste bonus</button><br>
            <button class="btn btn-primary mb-3 mt-6" value="Estrai alunno">Estrai alunno</button><br>
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