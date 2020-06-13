<?php
session_start();
include_once("assets/db/connexiondb.php");
$_SESSION['ici_index_bool'] = false;


$reqG = $BDD->prepare("SELECT genre_nom,id FROM genre  ORDER BY genre_nom ASC");
$reqG->execute(array());
$listeGenres = $reqG->fetchAll();

print_r("$ <br><br><br><br>");

print_r($_FILES);print_r($_POST);



$okconnectey = false;
if(isset($_SESSION['user_id']) || isset($_SESSION['user_pseudo'])  ) {

    $okconnectey = true;
} 

if(empty($_FILES)) {

}


require_once 'assets/functions/uploadFile.php';

$icon = "<i class='fas fa-exclamation-circle mr-1'></i>";


// UPLOADER
$upd = new uploadFile();
if(isset($_FILES['uploadAudio'])) {
    if($_FILES['uploadAudio']['size'] != 0) { 
        // FICHIER RECU
        var_dump($_FILES['uploadAudio']);
        $tmp_name = $_FILES['uploadAudio']['tmp_name'];
        $name = $_FILES['uploadAudio']['name'];


        $nomduboug = $_SESSION['user_pseudo'];
        $idduboug = $_SESSION['user_id'];

        $destination = $upd->uploadAudio($tmp_name,$name,$nomduboug,$idduboug);


    }
    else {
        $destination = "error0";
    }

    $okaudioposer = true;
    if (substr($destination,0,-1) == "error") { 
        if ($destination[5] == "0") { 
            $err_upload = "Taille 0";
            $okaudioposer = false;

        } else if( $destination[5] == "1") { 
            $err_upload = "ceci n'est pas un audio";
            $okaudioposer = false;

        }else if( $destination[5] == "2") { 
            $err_upload = "erreur inconnu";
            $okaudioposer = false;

        }
    }  else {
            $_SESSION['destination'] = $destination;
        }


} 
print_r($_SESSION);
$nn = pathinfo($_SESSION['destination']);
//var_dump($nn);
$ext =  strtolower($nn['extension']);

$dir = "data/".$_SESSION['user_id']."-".$_SESSION['user_pseudo']."/beats/";
$fichier = $dir.basename($_SESSION['user_id']."-beat-x".$ext);



if (!empty($_POST)) {
    echo 'emppy';
    extract($_POST); // si pas vide alors extraire le tableau, grace a ça on pourra directemet mettre le nom de la varilable en dur

    $ok = true;

    if(isset($_POST['Uploader-mon-instru']) ){
        echo " *_";
        $b_title = (String) $b_title;
        $b_description = (String) $b_description;
        $b_tags = (String) $b_tags;
        $b_genre = (int) $b_genre;
        $b_year = (int) $b_year;
        $b_price = (float) $b_price;





        if(empty($b_title)) {
            $ok = false; 
            $err_b_title = "Veuillez renseigner ce champ !"; 

        } 

        if(empty($b_description)) {
            $ok = false;
            $err_b_description = "Veuillez renseigner ce champ !"; 
        } 
        //*** Verification du Tag
        if(empty($b_tags)) {
            $ok = false;
            $err_b_tags = "Veuillez renseigner ce champ !"; 
        } 
        //*** Verification du Genre
        $req = $BDD->prepare("SELECT genre_nom 
                            FROM genre
                            WHERE id = ?");
        $req->execute(array($b_genre));
        $verif_g = $req->fetch();

        if(empty($b_genre)) {
            $ok = false;
            $err_b_genre = "Veuillez renseigner ce champ !"; 

        } else if($b_genre == -1){
            echo "$$";
            $ok = false;
            $err_b_genre = "oh !";
        }
        else if(!isset($verif_g['genre_nom'])){ // si 
            $ok = false;
            $err_b_genre = "Veuillez renseigner ce champ !";
        }
        //*** Verification du Année
        if(empty($b_year)) {
            $ok = false;
            $err_b_year = "Veuillez renseigner ce champ !";  

        }
        //*** Verification du Prix
        if(isset($_POST['freebay'])) {
            $b_price = 0.00;
        } else if(empty($b_price)) {
            $ok = false;
            $err_b_price = "Veuillez renseigner ce champ !"; 
        }


        if($ok) {
            echo "€€OOOOK";

            $date_upload = date("Y-m-d H:i:s"); 

            // preparer requete insertion
            $req = $BDD->prepare("INSERT INTO beat (beat_title,beat_author,beat_author_id,beat_format,beat_genre,beat_description,beat_year,beat_price,beat_dateupload,beat_tags,beat_source) VALUES (?,?,?,?,?,?,?,?,?,?,?)"); 

            $req->execute(array($b_title,$_SESSION['user_pseudo'],$_SESSION['user_id'],$ext,$b_genre,$b_description,$b_year,$b_price,$date_upload,$b_tags,$fichier));

            // recup beat_id
            $req = $BDD->prepare("SELECT beat_id FROM beat WHERE (beat_title = ? AND beat_author = ? AND beat_author_id = ? AND beat_format = ? AND beat_genre = ? AND beat_description = ? AND beat_year = ? AND beat_price = ? AND beat_dateupload = ? AND beat_tags = ? AND beat_source = ?) "); 

            $req->execute(array($b_title,$_SESSION['user_pseudo'],$_SESSION['user_id'],$ext,$b_genre,$b_description,$b_year,$b_price,$date_upload,$b_tags,$fichier));
            $bb = $req->fetch();


            if(isset($bb)) {

                echo $fichier;
                $newfichier = $dir.basename($_SESSION['user_id']."-beat-".$bb['beat_id'].".".$ext);
                if(rename($fichier,$dir.basename($_SESSION['user_id']."-beat-".$bb['beat_id'].".".$ext))) {
                    echo 'rennneeaame';

                    // header('Location: view-beat.php?beat_id='.$bb['beat_id']);
                    // exit;

                    echo "<script> alert('".$fichier."') </script>";
                    echo "<script> alert('".$_SESSION['user_id']."-beat-".$bb['beat_id'].".".$ext."') </script>";
                }




            }else {
                echo'not bb';
            }



        } else {
            echo "not ok";
        }

    }

}



if(isset($err_upload)) {
    echo $err_upload;
}

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <?php
        require_once('assets/skeleton/headLinkCSS.html');
        ?>
        <script src="https://kit.fontawesome.com/8157870d7a.js" crossorigin="anonymous"></script>
        <!--        <link rel="stylesheet" type="text/css" href="assets/css/styles-index.css"> -->
        <link rel="stylesheet" type="text/css" href="assets/css/navbar.css">

        <link rel="stylesheet" type="text/css" href="assets/css/edit-upload.css">
        <link rel="stylesheet" type="text/css" href="assets/css/button-style2ouf.css">

        <title>New upload</title>
    </head>
    <body onload="gogoUpload2()">

        <!--   *************************************************************  -->
        <!--   ************************** NAVBAR  **************************  -->

        <?php
        //  require_once('assets/skeleton/navbar.php');
        ?>;
        <?php
        if (isset($err_upload)) { ?>
        <a href=javascript:history.go(-1)>Retournez en arrière</a>

        <?php   }


        else {?>

        <?php if(isset($destination)) {echo $destination;}


        ?>
        <section class="mt-5 pb-4 header text-center">

            <div class="bg-dark container py-5 text-white rounded">


                <form id='formNewUpload' action="" method="post">
                    <input type="hidden" name="destinationbay" id="destinationbay" value="<?php if(isset($destination)){ echo $destination;} ?>">

                    <!--TITRE-->
                    <div class="form-group w-75 mx-auto">
                        <input onkeyup="gogoUpload2()" type="text" class="mb-2  mx-auto text-center form-control rounded-pill shadow-sm px-4" id="b_title" name="b_title" placeholder="Mettez un title pour votre profil"  value="<?php if (isset($b_title)) {echo $b_title;} ?>" autofocus>

                        <?php if(isset($err_b_title)){echo "<span class='spanAlertchamp'> ";echo $icon . $err_b_title ;echo "</span> ";} ?>
                    </div>

                    <!--GENRE & ANN2E --> 
                    <div class="form-group w-75 mx-auto d-flex justify-content-center">

                        <select onchange="gogoUpload2()" name="b_genre" id="b_genre" class="mb-2  text-center form-control rounded-pill  shadow-sm px-4">
                            <option class=" " value="-1">Selectionner Genre</option>
                            <?php 

              foreach($listeGenres as $gr){
                            ?>
                            <option class=" " value="<?=$gr['id']?>"><?= $gr['genre_nom']?></option>
                            <?php
              }
                            ?>

                        </select>
                        <input onchange="gogoUpload2()" onkeyup="gogoUpload2()" type="number" min="1900" max="<?= date("Y")+5?>" class="mb-2  text-center form-control rounded-pill  shadow-sm px-4" id="b_year" name="b_year" placeholder="Mettez un year pour votre profil"  value="<?php if(isset($b_year)){echo $b_year;} else { echo date("Y");} ?>" autofocus>


                    </div>
                    <!--DESCRITION--> 
                    <div class="form-group w-75 mx-auto d-flex justify-content-center">
                        <textarea onkeyup="gogoUpload2()" id="b_description" name="b_description" class="mb-2 form-control shadow-sm " placeholder="description ici la" value="this.value.trim()"><?php if (isset($b_description)) {echo $b_description;} ?></textarea>
                    </div>
                    <!--TAGS--> 
                    <div class="form-group w-75 mx-auto d-flex justify-content-center">
                        <input onkeyup="gogoUpload2()" type="text" class="mb-2 text-center form-control rounded-pill  shadow-sm  px-4" id="b_tags" name="b_tags" placeholder="Mettez un tags pour votre profil"  value="<?php if (isset($b_tags)) {echo $b_tags;} ?>" >

                    </div>

                    <!--PRICE-->
                    <div class="form-group w-75 mx-auto d-flex justify-content-center">
                        <!--free-->
                        <div class="custom-control custom-switch m-0">
                            <input onchange="gogoUpload2()" name="freebay" class="custom-control-input" id="freebay" type="checkbox" <?php if(isset($_POST['freebay']) || (isset($b_price) && $b_price == 0.00)){ ?> checked <?php } ?> >
                            <label class="custom-control-label " for="freebay"> FREE BEAT</label>

                        </div>
                        <!--money-->
                        <input  onchange="gogoUpload2()" onkeyup="gogoUpload2()" type="number" step="0.01" min="1" max="10000" class="mb-2 text-center form-control rounded-pill  shadow-sm px-4" id="b_price" name="b_price" placeholder="Mettez un price pour votre profil"  value="<?php if(isset($b_price) && !isset($_POST['freebay'])){echo $b_price;}?>" autofocus>
                    </div>



                    <div id="iciBtnSubmit" class="w-75 mx-auto "></div>


                    <p class="text-muted mt-2">
                        <span id="spanErreurTitle" class="text-danger d-none"> </span>
                        <span id="spanErreurYear" class="text-danger d-none"> </span>
                        <span id="spanErreurGenre" class="text-danger d-none"> </span>
                        <span id="spanErreurDescription" class="text-danger d-none"> </span>
                        <span id="spanErreurTags" class="text-danger d-none"> </span>
                        <span id="spanErreurPrice" class="text-danger d-none"> </span>
                    </p>




                </form>


            </div>



        </section>




        <?php



             }
        ?>







        <?php
        require_once('assets/skeleton/endLinkScripts.php');
        ?>
        <script>

            function isAlphanumeric(string)
            {
                for ( var i = 0; i < string.length; i++ )
                {
                    ch = string.charAt(i);

                    if (!(ch >= '0' && ch <= '9') && 	// Numeric (0-9)
                        !(ch >= 'A' && ch <= 'Z') && 		// Upper alpha (A-Z)
                        !(ch >= 'a' && ch <= 'z') && !(ch == " " || ch == "é" || ch == 'è') ) 			// Lower alpha (a-z)
                        return false;
                }
                return true;
            }

            function isNumeric(string)
            {
                for ( var i = 0; i < string.length; i++ )
                {
                    ch = string.charAt(i);

                    if (!(ch >= '0' && ch <= '9')	// Numeric (0-9)
                       ) 			
                        return false;
                }
                return true;
            }

            function gogoUpload2(){
                let icon = "<i class='fas fa-exclamation-circle mr-1'></i>";
                let ok = true;
                //******************************************** ETAPE 1
                let erreurTitle = document.getElementById('spanErreurTitle');
                let title = document.getElementById('b_title');

                //-title
                if(title.value.trim().split(' ').length-1 > 2){ // plus de 1 espace
                    erreurTitle.classList.remove("d-none"); //afficher erreur
                    erreurTitle.innerHTML = icon + "Votre titre doit comporter au plus 2 espace";

                    ok = false;

                }else if (!isAlphanumeric(title.value.trim())){
                    erreurTitle.classList.remove("d-none");  //afficher erreur
                    erreurTitle.innerHTML = icon + "Votre titre doit etes soit lettre soit nombre";
                    ok = false;

                } 
                else if (title.value.trim().length > 20){
                    erreurTitle.classList.remove("d-none");  //afficher erreur
                    erreurTitle.innerHTML = icon + "Titre trop grand";
                    ok = false;
                } 
                else {
                    erreurTitle.classList.add("d-none");
                }

                let genre = document.getElementById('b_genre');
                let erreurGenre = document.getElementById('spanErreurGenre');
                if(genre.value == -1){
                    ok = false;
                }
                //--b_year
                let maxyea = <?= date("Y")+5?> ;
                let erreurYear = document.getElementById('spanErreurYear');
                let year = ( document.getElementById('b_year').value);

                if(isNumeric(year)){
                    year2 = parseInt(year);
                    if (year2 < 1900 || year2 > maxyea) {
                        erreurYear.classList.remove("d-none");
                        erreurYear.innerHTML = icon + "saisir entre 1900 et <?= date("Y")+5?> svp";
                        ok = false;

                    } else {
                        erreurYear.classList.add("d-none");

                    }
                } else {
                    erreurYear.classList.remove("d-none");
                    erreurYear.innerHTML = icon + "Saisir un nombre positif, entre 1900 et <?= date("Y")+5?>";
                    ok = false;

                }

                //- description
                let erreurDescription = document.getElementById('spanErreurDescription');
                let description = document.getElementById('b_description');

                if (description.value.trim().length > 140){
                    erreurDescription.classList.remove("d-none");
                    erreurDescription.innerHTML = icon + "Description trop grande";
                    ok = false;

                }
                else {
                    erreurDescription.classList.add("d-none");

                }


                //--b_tags
                let tags = document.getElementById('b_tags');
                let erreurTags = document.getElementById('spanErreurTags');
                let tagsval = tags.value.trim();
                let tttag =  tagsval.split(',');
                console.log(tttag);
                if (tttag.length-1 > 3)  {

                    erreurTags.classList.remove("d-none");
                    erreurTags.innerHTML = icon + "Vous ne pouvez mettre que 4 tags max";
                    ok = false;


                }else if(tttag.length > 1) {
                    okvirgulebzr = false;
                    for (let i = 0; i < tttag.length; i++ ) {
                        if(tttag[i] == '') {
                            okvirgulebzr = true;
                        }
                    }
                    if(okvirgulebzr){
                        erreurTags.classList.remove("d-none");
                        erreurTags.innerHTML = icon + "Erreur virgule";
                        ok = false;
                    } else {
                        erreurTags.classList.add("d-none");
                    }

                }
                else {
                    erreurTags.classList.add("d-none");

                }



                //--freebay
                let freebay = document.getElementById('freebay');
                let erreurPrice = document.getElementById('spanErreurPrice');
                let price = document.getElementById('b_price').value;

                if(freebay.checked) {
                    document.getElementById('b_price').classList.add('d-none');
                    document.getElementById('b_price').value = null;
                    erreurPrice.classList.add("d-none");
                } else {
                    document.getElementById('b_price').classList.remove('d-none');
                    //--price
                    let price2 = parseFloat(price)
                    console.log(price);

                    if (price2 < 1 || price2 > 10000){
                        erreurPrice.classList.remove("d-none");
                        erreurPrice.innerHTML = icon + "Saisir un prix entre 1 et 10000";
                        ok = false;

                    } else {
                        erreurPrice.classList.add("d-none");
                    }

                }



                let btn = document.getElementById('uconfirm');

                let oktoutrempli = (title.value.trim().length > 0) && (description.value.trim().length > 0) && (tags.value.trim().length > 0) && (genre.value.trim().length > 0) && (document.getElementById('b_year').value.length != 0) && (freebay.checked || (!freebay.checked && price.length > 0 ));
                ok = ok && oktoutrempli;

                // ok = true;
                let divS = document.getElementById('iciBtnSubmit');
                let okyarien = false;
                if(divS.children.length == 0){
                    okyarien = true;
                }


                if (ok) {

                    if(okyarien) {
                        let btn = document.createElement('button');
                        btn.setAttribute('type','submit');
                        btn.setAttribute('id','Uploader-mon-instru');
                        btn.setAttribute('name','Uploader-mon-instru');
                        btn.setAttribute('class','btn boutonstyle2ouf w-100 btn-block p-2 rounded-pill shadow-sm');
                        btn.innerHTML = "UPLOADER MON INSTRU "
                        divS.appendChild(btn);
                    }

                }else {
                    if(!okyarien) {
                        let btn = document.getElementById('Uploader-mon-instru');
                        divS.removeChild(btn);
                    }



                }



            }

        </script>



    </body>
</html>
