<?php
    require 'conn.php';

    function calcolaProb($classe,$alunno=NULL)
    {
        require 'conn.php';

        $scatola=array(); 

        $idDoc=$_SESSION['ID'];

        $alunni=$conn->query("SELECT id FROM alunno WHERE cod_classe=$classe"); //* Selezione di tutti gli alunni appartenenti ad una classe

        if ($alunni->num_rows>0) 
        {
            foreach ($alunni as $row) 
            {
                $id=$row['id'];

                $table=$conn->query("SELECT SUM(punteggio) AS tot FROM intervento,alunno WHERE intervento.cod_alunno=alunno.ID AND alunno.ID=$id");
                $row=$table->fetch_assoc();
                $pnt=$row['tot'];
                
                $pnt=$row['tot'];
                
                for ($i=0; $i < $pnt; $i++) //* inserimento nell'array dei nomi
                { 
                    array_push($scatola,$id);
                }

            }
            
            if($alunno==NULL) //? Se la funzione viene invocata per estrarre un'alunno non verra' richiesto il parametro alunno e quindi in caso sia NULL si procede all'estrazione
            {
                $idEstratto=$scatola[rand(0,count($scatola)-1)];

                $table=$conn->query("SELECT * FROM alunno WHERE ID=$idEstratto");
                $row=$table->fetch_assoc();  

                extract($row);

                echo "
                <div class='container mt-4 d-flex flex-column align-items-center bg-primary rounded w-75 p-4 text-white'>
                <h3>Nome: $nome</h3>
                <h3>Cognome: $cognome </h3>
                <h3>Data di Nascita: $data_nascita</h3>
                ";
                $conn->query("INSERT INTO estrazione VALUES (NULL,CURRENT_DATE,$idDoc,$idEstratto)");
            }
            else //? in caso contrario si restituiscono il punteggio e la probabilita' dell'alunno
            {
                $idEstratto=$alunno;
            }
            
            $table=$conn->query("SELECT SUM(punteggio) AS tot FROM intervento,alunno WHERE intervento.cod_alunno=alunno.ID AND alunno.ID=$idEstratto");
            $row=$table->fetch_assoc();
            if ($row['tot']!=NULL and $row['tot']>0) 
            {
                $pnt=$row['tot'];
            } 
            else 
            {
                $pnt=1; //!Il punteggio nonn puo' essere inferiore ad 1 perche' ci deve essere sempre una minima probabilita' di essere estratti
            }

            $prob=round($pnt/count($scatola)*100,2); //? calcolo probabilita'


            if($alunno==NULL)
            {
                echo"
                <h3>Punteggio:  $pnt</h3>
                <h3>Probabilità: $prob %</h3>
                </div>;";
            }

            $out['prob']=$prob;
            $out['pnt']=$pnt;

            return $out;

        }
        else
        {
            return 0;
        }
    }
?>