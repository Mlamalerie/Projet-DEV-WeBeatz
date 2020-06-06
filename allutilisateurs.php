<?php
session_start();
$_SESSION['ici_index_bool'] = false;

include('assets/db/connexiondb.php');

if(isset($_SESSION['user_id'])){
    $req =$BDD->prepare("SELECT * FROM user");
    $req->execute(array());
} 
else{
    $req =$BDD->prepare("SELECT * FROM user");
    $req->execute();
}

$afficher_membres=$req->fetchAll();

?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php        
            require_once('assets/skeleton/headLinkCSS.html');
        ?>

        <!--    Lien pour défiler les pages    -->
         <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">


        <link rel="stylesheet" type="text/css" href="assets/css/navbar.css">
        <link rel="stylesheet" type="text/css" src="assets/css/allutilisateurs.css">
        <title>All Users</title>

        <style>
            .roundedImage {  /*image arrondie*/
                overflow:hidden; 
                -webkit-border-radius:75%;
                -moz-border-radius:75%; 
                border-radius:75%;
            }

            #user_tableau, .btn{
                font-size: 12px;
            }
        </style>
    </head>
    <body>
        <!--   ************************** NAVBAR  **************************  -->

        <?php
            require_once('assets/skeleton/navbar.php');
        ?>
        <br/><br/><br/><br/>


        <div class="row py-5">
            <div class="col-lg-10 mx-auto">
                <div class="card rounded shadow border-0">
                    <div class="card-body p-5 bg-white rounded">
                        <div class="table-responsive">
                            <table id="user_tableau" style="width:100%" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Role</th>  
                                        <th>Image</th>    
                                        <th>Pseudo</th>
                                        <th>E-Mail</th>
                                        <th>Sexe</th>
                                        <th>Date de naissance</th>
                                        <th>Pays</th>
                                        <th>Ville</th>
                                        <th>Date d'inscription</th>
                                        <th>Statut</th>
                                        <th>Nombre de follow(s)</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php 
                                    foreach($afficher_membres as $am){
                                    ?>
                                    <tr>
                                        <td>
                                            <?=$am['user_role']?>
                                        </td>
                                        <td>
                                            <img src="<?=$am['user_image']?>" style="height : 50px; width : 50px;" class="img-fluid mb-3 roundedImage shadow-sm">
                                        </td>   
                                        <td>
                                            <?=$am['user_pseudo']?>
                                        </td>
                                        <td>
                                            <?=$am['user_email']?>
                                        </td>
                                        <td>
                                            <?=$am['user_sexe']?>
                                        </td>
                                        <td>
                                            <?=$am['user_datenaissance']?>
                                        </td>
                                        <td>
                                            <?=$am['user_pays']?>
                                        </td>
                                        <td>
                                            <?=$am['user_ville']?>
                                        </td>
                                        <td>
                                            <?=$am['user_dateinscription']?>
                                        </td>
                                        <td>
                                            <?=$am['user_statut']?>
                                        </td>
                                        <td>
                                           <?php
                                            $req1 = $BDD->prepare("SELECT *
                                                                    FROM relation
                                                                    WHERE id_receveur = ? AND statut = 1");
                                            $req1->execute(array($am['user_id']));
                                            $nb_follow=0;

                                            $resuRELA = $req1->fetchAll();
                                            foreach($resuRELA as $rr){

                                                foreach($rr as $key => $value){

                                                    if($key =='statut' && $value== 1){

                                                        $nb_follow++;
                                                    }   
                                                } 
                                            }
                                        
                                        ?> 
                                        <?= $nb_follow ?>
                                        </td>
                                        <td>
                                            <button class="btn">Supprimer</button>
                                            <button class="btn">Modifier</button>
                                            <button class="btn">Désactiver</button>
                                        </td>
                                    </tr>
                                    <?php 
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--      SCRIPTS      -->
        <?php 
        require_once('assets/skeleton/endLinkScripts.php');
        ?>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> 
        <script type="text/javascript">
            $(function() {
                $(document).ready(function() {
                    $('#example').DataTable();
                });
            });
        </script>
    </body>
</html>