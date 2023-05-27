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
    <title>Assegna Bonus</title>
</head>
<body class="bg-dark d-flex flex-column align-items-center p-3">

    <button class="btn btn-primary btn-lg align-self-start" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
        </svg>
    </button>

    <div class="title text-white"><h1>Assegna Bonus</h1></div>


    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

        <div class="form-floating">
            <select class="mt-3 form-select" name="alunno" id="alunno">
                <option value=""></option>
                <?php

                    require "./../../conn.php";

                    session_start();
                    $id=$_SESSION['ID'];

                    //* Popolazione select con i compagni di classe dell'alunno

                    $ris=$conn->query("SELECT cod_classe FROM alunno WHERE ID=$id");
                    $ris=$ris->fetch_assoc();
                    $classe=$ris['cod_classe'];

                    $table=$conn->query("SELECT ID,nome,cognome FROM alunno WHERE cod_classe=$classe AND ID != $id");

                    while($row=$table->fetch_assoc())
                    {
                       extract($row);
                       
                       echo "<option value='$ID'>$nome $cognome</option>";

                    }
                ?>
            </select>
            <label for="alunno">Selezionare alunno</label>
        </div>

        <div class="mt-3">
            <label class="form-label text-white" for="valore">Inserire valore bonus:</label>
            <input type="number" name="valore" id="valore" class="form-control">
        </div>

        <div class="mt-3">
            <label class="form-label text-white" for="dsc">Inserire motivazione bonus:</label>
            <textarea class="form-control" name="dsc" id="dsc" cols="30" rows="2"></textarea>
        </div>

        <button type="submit" name="assegna" class="btn btn-primary mt-3">Richiedi Bonus</button>

    </form>


    <div class="offcanvas offcanvas-start" id="demo">
        <div class="offcanvas-header">
            <h1 class="offcanvas-title">Menù</h1>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <button class="btn btn-primary mb-3 mt-6" value="home" onclick="location.href = './alunno.php'">Home</button><br>
            <button class="btn btn-primary mb-3 mt-6" value="cambioPassword" onclick="location.href = './cambioPass.php'">Cambia password</button><br>
            <button class="btn btn-outline-danger mb-3 mt-6" onclick="location.href = './../../index.php'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z"/>
                    <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
                </svg>
                Log Out
            </button>
        </div>
    </div>

    

    <?php
        if(isset($_POST['assegna'])) //* Inserimento richiesta
        {
            extract($_POST);

            $valore=-$valore;

            if ($conn->query("INSERT INTO richiesta VALUES (NULL,'$dsc',$id,$valore,$alunno,NULL,NULL)")) 
            {
                echo "<h3 class='text-white'>Richiesta inviata con successo</h3>";
            } 
            else 
            {
                echo "<h3 class='text-white'>Errore ".$conn->connect_error."</h3>";
            }
            
        }
    ?>

</body>
</html>