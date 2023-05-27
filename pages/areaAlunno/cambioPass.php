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
    <title>Cambio Password</title>
</head>
<body class="bg-dark d-flex flex-column align-items-center p-3">

    <?php
        $id=$_SESSION['ID'];
    ?>

    <button class="btn btn-primary btn-lg align-self-start" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
        </svg>
    </button>

    <div class="title text-white "><h1>Cambio Password</h1></div>


    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

        <div class="mt-3">
            <label for="pass" class="form-label text-white">Inserisci nuova password:</label>
            <input type="password" name="pass" id="pass" class="form-control">
        </div>

        <div class="mt-3">
            <label for="rep" class="form-label text-white">Ripeti la password:</label>
            <input type="password" name="rep" id="rep" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary mt-3" name="submit">Cambia Password</button>

    </form>

    <?php

        //! Messaggi

        if (isset($_GET['message'])) 
        {
            $msg=$_GET['message'];

            if($msg=="cambioAvvenuto")
            {
                echo "<div class='alert alert-success alert-dismissible'>
                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                            <strong>Success!</strong> Password modificata con successo
                        </div>";
            }
            elseif ($msg=='passwordDiverse') 
            {
                echo "<div class='alert alert-warning alert-dismissible'>
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                        <strong>Warning!</strong> This alert box could indicate a warning that might need attention.
                    </div>";
            }
            else
            {
                echo "<div class='alert alert-danger alert-dismissible'>
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                        <strong>Danger!</strong> Errore SQL
                    </div>";
            }
            
        }

        if (isset($_POST['submit'])) 
        {

            $pass=md5($_POST['pass']); //! Cifratura password
            $rep=md5($_POST['rep']);

            if ($pass=$rep) //? Controllo password e conferma password
            {
                require "./../../conn.php";

                if ($conn->query("UPDATE alunno SET alunno.password='$pass' WHERE alunno.id=$id")) //* Aggiornamento password
                {
                    header("Location: ".$_SERVER['PHP_SELF']."?message=cambioAvvenuto"); 
                }
                else
                {
                    header("Location: ".$_SERVER['PHP_SELF']."?message=erroreSQL");
                }

            }
            else
            {
                header("Location: ".$_SERVER['PHP_SELF']."?message=passwordDiverse"); //! Reindirizzamento con messaggio di errore
            }
        } 

    ?>

    <div class="offcanvas offcanvas-start" id="demo">
        <div class="offcanvas-header">
            <h1 class="offcanvas-title">Menù</h1>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <button class="btn btn-primary mb-3 mt-6" value="home" onclick="location.href = './alunno.php'">Home</button><br>
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