<?php 
    session_start();
    require "./../../conn.php";

    $id=$_SESSION['ID'];
    
    $table=$conn->query("SELECT * FROM docente WHERE ID=$id");

    $row=$table->fetch_assoc();

    $cognome=$row['cognome'];

?>

<html lang="it-IT" class="bg-dark p-3">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docente</title>
    <script>

        document.getElementById("class").value="";

        function showAlunno(str) {
        var xhttp;
        if (str == "") {
            document.getElementById("alunno").innerHTML = "<option value=''>Classe non selezionata</option>";
            return;
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
            document.getElementById("alunno").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "./ajaxClasseAlunno.php?q="+str, true);
        xhttp.send();
        } 
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css">

</head>
<body class="bg-dark p-3 d-flex flex-column">
    <button class="btn btn-primary btn-lg align-self-start" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
    </svg>
    </button>

    <div class="title text-white align-self-center"><h1>Salve prof <?php echo $cognome; ?></h1></div>
    
    <form action="./dettaglioAlunno.php" method="post" class="align-self-center">
        <div class="mb-3 mt-3">
            <label class="form-label text-white" for="class">Classe: </label>
            <select name="class" id="class" class="form-select" onchange="showAlunno(this.value);" required>
                <option value=""></option>
                <?php

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
        <div class="mb-5 mt-3">
            <label class="form-label text-white" for="alunno">Alunni: </label>
            <select class="form-select" name="alunno" id="alunno" required>
                <option value="">Classe non selezionata</option>
            </select>
        </div>

        <div class="mb-3 mt-3">
            <button type="submit" class="btn btn-primary" value="Dettaglioalunno">Dettaglio alunno</button>
            <button type="submit" class="btn btn-primary" name="AssegnaBonus" value="AssegnaBonus">Assegna Bonus</button>
        </div>

        <div class="input-group mb-3 mt-3">
            <input type="number" class="form-control" placeholder="Valore" name="valore" id="valore">
            <select class="form-select" name="tipo" id="tipo">
                <option value="">Inserire il tipo di intervento</option>
                <option value="1">Malus</option>
                <option value="-1">Bonus</option>
            </select>
        </div>

    </form>

    <div class="offcanvas offcanvas-start" id="demo">
        <div class="offcanvas-header">
            <h1 class="offcanvas-title">Menù</h1>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
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

