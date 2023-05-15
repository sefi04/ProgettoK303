<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <title>Crea Alunno</title>
</head>
<body class="bg-dark d-flex flex-column p-3">
    <button class="btn btn-primary btn-lg align-self-start" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
        </svg>
    </button>
    <div class="title align-self-center">
        <h1 class="text-white ">
            Crea Alunno
        </h1>
    </div>

    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="align-self-center w-25">

        <div class="mt-3">
            <label class="form-label text-white" for="classe">Classe: </label>
            <select name="classe" id="classe" class="form-select">
                <option value=""></option>
                    <?php

                        session_start();

                        require "./../../conn.php";

                        $query='SELECT classe.ID, classe.settore
                        FROM docente,insegna,classe 
                        WHERE docente.id=insegna.cod_docente
                        AND insegna.cod_classe=classe.ID
                        AND docente.ID="'. $_SESSION['ID']. '";';

                        $table=$conn->query($query);

                        while($row=$table->fetch_assoc())
                        {
                            echo "<option value='".$row['ID']."'>".$row['settore']."</option>";
                        }
                    ?>
            </select>
        </div>

        <div class="mt-3">
            <label for="nome" class="form-label text-white">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome">
        </div>
        <div class="mt-3">
            <label for="cognome" class="form-label text-white">Cognome:</label>
            <input type="text" class="form-control" id="cognnome" name="cognome">
        </div>

        <div class="mt-3">
            <label for="data" class="form-label text-white">Data di nascita:</label>
            <input type="date" class="form-control" id="data" name="data">
        </div>

        <div class="mb-3 mt-3">
            <label for="email" class="form-label text-white">Email:</label>
            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
        </div>

        <div class="mt-3">
            <label for="psw" class="form-label text-white">Password temporanea:</label>
            <input type="password" class="form-control" id="psw" name="psw">
        </div>

        <div class="mt-3 mb-4">
            <label for="punti" class="form-label text-white">Punteggio:</label>
            <input type="number" class="form-control" id="punti" name="punti">
        </div>

        <button type="submit" name="Crea" class="btn btn-primary w-100">Crea</button>
    </form>
        
    <div class="offcanvas offcanvas-start" id="demo">
        <div class="offcanvas-header">
            <h1 class="offcanvas-title">Menù</h1>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <button class="btn btn-primary mb-3 mt-6" value="Home" onclick="location.href = './docente.php'">Home</button><br>
            <button class="btn btn-primary mb-3 mt-6" value="Visualizza richieste bonus" onclick="location.href = './visualizzaRichieste.php'">Visualizza richieste bonus</button><br>
            <button class="btn btn-primary mb-3 mt-6" value="Estrai alunno">Estrai alunno</button><br>
            <button class="btn btn-outline-danger mb-3 mt-6" onclick="location.href = './../index.php'">
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

<?php

    if (isset($_POST['Crea'])) 
    {
        require "./../../conn.php";
        
        session_start();
        $IDDoc=$_SESSION['ID'];

        extract($_POST);

        $psw=md5($psw);

        $sql="INSERT INTO alunno VALUES ('','$nome','$cognome','$psw','$email','$data',$classe,'$nome$cognome');";

        $ric="SELECT ID FROM alunno WHERE nome='$nome' AND cognome='$cognome' AND cod_classe=$classe";

        $flag=false;

        if ($conn->query($sql)) 
        {
            $flag=true;
        }


        $row=($conn->query($ric))->fetch_assoc();
        $ris=$row['ID'];

        $sql1="INSERT INTO intervento VALUES ('',1,$punti,date('Y-m-d'),'Creazione Alunno','$ris',$IDDoc,'');";

        if ($conn->query($sql1) AND $flag) 
        {
            echo("<div class='text-white'>Alunno aggiunto con successo</div>");
        }
    }

?>