<?php
session_start();

include('assets/db/connexiondb.php'); 

/*print_r($_GET);*/

if (!isset($_GET['profil_id'])){
    header('Location: utilisateurs.php'); 
    exit;
}

$id = (int)$_GET['profil_id'];/*récupère id du profil qu'on a cliqué*/

$req = $BDD->prepare("SELECT * 
    FROM user 
    WHERE user_id = ?");

$req->execute(array($id));

$afficher_profil = $req->fetch();

/*print_r($afficher_profil);*/

?>


<!DOCTYPE html>

<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Mon profil</title>
        <style>
            .container{
                background: #7728b2;
                color: white;
            }
            .infos{
                background: red;
            }
            .msg-btn{
                margin:10px 0px 40px 0px; 
                background:rgba(121, 6, 247,1);
                border: 1px solid rgba(121, 6, 247,0.5); 
                padding:10px 25px; 
                color: #ffffff; 
                border-radius: 3px; 
                cursor:pointer; 
            }
            .follow-btn{
                margin:10px 0px 40px 0px;
                border: 1px solid rgba(121, 6, 247,0.5); 
                padding:10px 25px; 
                border-radius: 3px; cursor:pointer; 
                margin-left:10px; 
                background: white;
                color:rgba(121, 6, 247,1);
            }
            .infos-privee-btn{
                margin:10px 0px 40px 0px;
                margin-left:10px;
                background: #000000;
                color:rgba(121, 6, 247,1);
                padding:10px 25px; 
                border-radius: 3px; cursor:pointer; 
                
            }
            .infos-privee-btn a{
                text-decoration: none;
               color:rgba(121, 6, 247,1);
            }
            .editer-btn{
                margin:10px 0px 40px 0px;
                margin-left:10px;
                background: grey;
                color:rgba(121, 6, 247,1);
            
                border-radius: 3px; cursor:pointer; 
                
            }
            .editer-btn a{
                text-decoration: none;
               color:rgba(121, 6, 247,1);
            }
        </style>

    </head>
    <body>
        <div class="container">
            <div class="row">

               
                <div class="col-md-4" style="width: 150px;height: 150px; padding: 10px;display:inline-block;width:15%";>
                    <img src="img/<?=$afficher_profil['user_image']?>" style="width: 150px;height: 150px;">
                </div>
                
                <div class="col-md-4 infos" style="display:inline-block;width:40%;margin-left:5%">
                    <h2><?= $afficher_profil['user_pseudo']?></h2>         
                    <ul>                   
                        <li>Sexe : <?= $afficher_profil['user_sexe'] ?></li> 
                         <li>Né le : <?= $afficher_profil['user_datenaissance'] ?></li>                             
                        <li><?= $afficher_profil['user_ville'] ?></li>             <li><?= $afficher_profil['user_email'] ?></li>   
                        <textarea><?= $afficher_profil['user_description'] ?></textarea> 
                         <li>Ce compte a été crée le : <?= $afficher_profil['user_dateinscription'] ?></li>                                         
                    </ul>
                </div>
                
                <div class="col-md-4" style="display:inline-block;width:30%;margin-left:5%">
                   <button class="msg-btn">DM</button>
                    <button class="follow-btn">Follow</button>
                       
                        <button class="infos-privee-btn"><a href="privee.php?profil_id=<?= $afficher_profil['user_id']?>" >Infos privée</a></button>
                        
                        <button class="editer-btn"><a href="editer-profil.php?profil_id=<?= $afficher_profil['user_id']?>" >Editer</a></button>
                    <div>
                        <?= $afficher_profil['user_nbfollowers'] ?> Follower(s)
                    </div>
                </div>
                
                
            </div>
        </div>                                                                   

    </body>
</html>