<?php
session_start();
$_SESSION['ici_index_bool'] = false;
include_once("assets/db/connexiondb.php");


$beat_id = (int)$_GET['id'];


$req = $BDD -> prepare("SELECT * FROM beat WHERE beat_id = ?");
$req->execute(array($beat_id));
$instru = $req->fetch();

$okconnectey = false;
if(isset($_SESSION['user_id']) || isset($_SESSION['user_pseudo'])  ) {
    print_r($_SESSION);
    $okconnectey = true;
} else{
    echo "Pas de connexion";
}



?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title><?= $instru['beat_title'] ?> by <?= $instru['beat_author'] ?> • WeBeatz </title>
        <?php
    require_once('assets/skeleton/headLinkCSS.html');
        ?>
        <link rel="stylesheet" type="text/css" href="assets/css/navbar.css">
        <link rel="stylesheet" type="text/css" href="assets/css/search.css">
        <!--  Audio player de mathieu   -->
        <link rel="stylesheet" type="text/css" href="assets/skeleton/AudioPlayer/audioplayer.css">
        <link rel="stylesheet" type="text/css" href="assets/css/view-beat.css">
    </head>
    <body>

        <!--   *************************************************************  -->
        <!--   ************************** NAVBAR  **************************  -->
        <?php require_once('assets/skeleton/navbar.php');  require_once('assets/functions/js-panier.php');?>


        <!-- Demo header-->
        <section class="mt-5 pb-4 header text-center">
            <div class="bg-dark container py-5 text-white rounded vb-color">
                <div class="row">
                    <div>
                        <img src="<?= $instru['beat_cover']?>" alt="" width="150" class="img-fluid rounded shadow-sm vb-cover">
                    </div>
                    <div class="text-light vb-text">
                        <?= $instru['beat_title']?> <br/>
                        <a class="text-light vb-stext" href="profils.php?profil_id=<?= $instru['beat_author_id']?>">
                            <u><?= $instru['beat_author']?></u>
                        </a>
                    </div>
                    <div class="col-5 vb-desc">
                        <?= $instru['beat_description']?>
                    </div>
                </div>
                <section id="divInfo" class="py-3">
                    <?php  $tags = explode(',',$instru['beat_tags']); ?>



                    <div scope="row" class=" border-0 align-middle rounded">
                        <div class="p-0 rounded ">
                            <?php foreach($tags as $t) { if(strlen($t)>1){ $t = trim($t);

                            ?>
                            <a class="spanTag  badge badge-light text-dark px-2 rounded-pill ml-2" href="search.php?Type=beats&q=<?= $t ?>">#<?= $t ?> </a>
                            <?php }} ?>
                        </div>

                    </div>

                </section>
            </div>




            <!-- Animated button -->
            <span id='btnplayView-<?=$instru['beat_id']?>' onclick="playPause(0,<?=$instru['beat_id']?>)" class="animated-btn text-white" href="#"><i class="fa fa-play iconPlay"></i></span>




        </section>

        <section class="mt-2 pb-4 header text-center">
            <div id="resultcontentAlea"  class="container py-5 text-white rounded bg-primary mb-4 vb-color2" >


                <?php

    $req = $BDD->prepare("SELECT beat_id
                            FROM beat
                            WHERE beat_id <> ?
                            ");
                  $req->execute(array( $instru['beat_id'] ));
                  $resuID = $req->fetchAll();
                  shuffle($resuID);
                  shuffle($resuID);
                  var_dump(count($resuID));

                  $resuBEATS = [];
                  for ($i = 0; $i < 3; $i++){
                      $req = $BDD->prepare("SELECT *
                            FROM beat
                            WHERE beat_id = ?");
                      $req->execute(array($resuID[$i]['beat_id']));
                      $resuB = $req->fetchAll();

                      $resuBEATS = array_merge($resuBEATS,$resuB);
                  }
                  $yadesresultatsBEATS = false;
                  if (isset($resuBEATS) && !empty($resuBEATS)){
                      $yadesresultatsBEATS = true;
                  }

                  $oublielepremier = false;

                ?>

                <?php  $decal = 1; require_once('assets/skeleton/tableBeatSearch.php'); ?>

            </div>
        </section>



        <?php
        require_once('assets/skeleton/endLinkScripts.php');
        ?>

        <!--   *************************************************************  -->
        <!--   ************************** MUSIC PLAYER  **************************  -->
        <?php
        if(isset($resuBEATS) && !empty($resuBEATS)) {
            $resuPLAYLIST = $resuBEATS;
            array_unshift($resuPLAYLIST, $instru);
        } else {
            $resuPLAYLIST = array();
        }

        require_once('assets/skeleton/AudioPlayer/audioplayer.php');
        ?>


    </body>

</html>
