<?php

    //*Inizzializzazione della sessione e eliminazione della precedente

    session_start();
    session_destroy();

?>


<html lang="it-IT" class="bg-dark p-3">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark p-3">
    <div class="d-flex flex-column justify-content-center align-items-center">
        <div class="title text-white"><h1>Login</h1></div>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            
            <div class="mb-3 mt-3">
                <label class="form-label text-white" for="user">Username: </label>
                <input class="form-control" name="user" type="text">
            </div>
            <div class="mb-3 mt-3">
                <label class="form-label text-white" for="pass">Password: </label>
                <input class="form-control" name="pass" type="password">
            </div>

            <button type="submit" class="btn btn-primary" name="accedi">Submit</button>
        </form>
        
            <?php

                //! messaggio in caso di credenziali errate

                if (!empty($_GET['message'])) 
                {
                    $message=$_GET['message'];
                    echo 
                    ("
                        <div class='alert alert-danger alert-dismissible w-25 fade show'>
                            $message
                            <button type='button' class='btn-close' data-bs-dismiss='alert' ></button>
                        </div>
                    ");
                }
            ?>
            
        
    </div>
</body>
</html>

<?php
    require 'conn.php'; //? connessione al Database
    
    // PostBack eseguito in caso di avvenuto invio della form

    if (isset($_POST['accedi'])) 
    {
        extract($_POST);
        $pass=md5($pass); //? Cifratura della password

        session_start();

        $_SESSION['user']=$user; //* Salvataggio dell'ID utente nella sessione

        $query="SELECT * FROM docente WHERE username='$user';"; //* Controllo presenza username nella tabella docenti

        $table=$conn->query($query);

        if ($table->num_rows>0) 
        {

            $row=$table->fetch_assoc();

            if($row['password']==$pass) //* Controllo password
            {
                $_SESSION['ID']=$row['ID'];
                redirect("./pages/areaDocente/docente.php",""); //* Reindirizzamento alla home docente
            }
            else
            {
                echo "password errata";
                redirect("./index.php","Password errata"); //! Reindirizzamento al login con messaggio di password errata
            }
        } 
        else 
        {
            $query="SELECT * FROM alunno WHERE username='$user';"; //* Controllo presenza username nella tabella alunni

            $table=$conn->query($query);

            if ($table->num_rows==0) //! Reindirizzamento al login con messaggio di untente non trovato
            {
                echo "User non presente";
                redirect("./index.php","Username non trovato");
            }
            else 
            {
                $row=$table->fetch_assoc();

                if($row['password']==$pass) //* Controllo password
                {
                    $_SESSION['ID']=$row['ID'];
                    redirect("./pages/areaAlunno/alunno.php",""); //* Reindirizzamento alla home alunno
                }
                else
                {
                    redirect("./index.php","Password errata"); //! Reindirizzamento al login con messaggio di password errata
                }
            }

        }
    }
    
    //? Funzione di reindirizzamneto con eventuale messaggio passato con il GET
    function redirect($location,$message)
    {
        header("Location: $location?message=$message");
    }
    
        
?>