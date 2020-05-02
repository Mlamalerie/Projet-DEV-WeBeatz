<?php
include_once("db/connexiondb.php"); // inclure le fichier pour se connecter à la base de donnée
include_once("fichierfct.php");

print_r($_POST);

if(!empty($_POST)){

    extract($_POST); // si pas vide alors extraire le tableau, grace a ça on pourra directemet mettre le nom de la varilable en dur

    $ok = true;

    if(isset($_POST['inscription'])){
        $pseudo = (String) trim($pseudo);
        $email = (String) trim($email);
        $motdepasse = (String) trim($motdepasse);
        $prenom = (String) trim($prenom);
        $nom = (String) trim($nom);


        $departement = (int) $departement;

        $naiss_jour = (int) $naiss_jour;
        $naiss_mois = (int) $naiss_mois;
        $naiss_annees = (int) $naiss_annees;

        $date_naissance = (String) null;

        // Verif-Sécurité
        if(empty($pseudo)) {
            $ok = false;
            $err_pseudo = "Veuillez renseigner ce champ !";echo "***x";

        }
        if(empty($motdepasse)) {
            $ok = false;
            $err_motdepasse = "Veuillez renseigner ce champ !";echo "**v*";

        }

        if(empty($nom)) {
            $ok = false;
            $err_nom = "Veuillez renseigner ce champ !";echo "**b*";

        }
        if(empty($prenom)) {
            $ok = false;
            $err_prenom = "Veuillez renseigner ce champ !";echo "*c**";

        }
        if(empty($email)) {
            $ok = false;
            $err_email = "Veuillez renseigner ce champ !";echo "*t**";

        }
        // verif date de naissance
        $verif_jour = array();
        for($i = 1; $i <= 31; $i++) {
            array_push($verif_jour,$i);
        }

        if(!in_array($naiss_jour, $verif_jour )) {
            $ok = false;
            $err_naiss_jour = "Veuillez renseigner ce champ !";echo "**zz*";

        }

        $verif_mois = array();
        for($i = 1; $i <= 12; $i++) {
            array_push($verif_mois,$i);
        }

        if(!in_array($naiss_mois, $verif_mois )) {
            $ok = false;
            $err_naiss_mois = "Veuillez renseigner ce champ !";echo "*eyez**";

        }


        $aaa_debut = 1950; $aaa_n = 70;

        $verif_annees = array();
        for($i = 0; $i <$aaa_n ; $i++) {
            array_push($verif_annees,($aaa_debut+$i));
        }
       
        if(!in_array($naiss_annees, $verif_annees )) {
            $ok = false;
            $err_naiss_annees = "Veuillez renseigner ce champ !";echo "*ee**";

        }

        if (!checkdate($naiss_jour,$naiss_mois,$naiss_annees)){
            $ok = false;
            $err_date = "Date fausse !";echo "**::*";

        }else {
            $date_naissance = $naiss_annees .'-'. $naiss_mois.'-'.$naiss_jour;

        }
        // veri departement 


        $verif_departement = array();
        for($i = 1; $i <100000; $i++) {
            array_push($verif_departement,$i);
        }

        if(!in_array($departement, $verif_departement )) {
            $ok = false;
            $err_departement = "Veuillez renseigner ce champ !";echo "*aaa**";

        }
        if($ok) {

            $date_inscription = date("Y-m-d");

            // preparer requete
            $req = $BDD->prepare("INSERT INTO user (user_pseudo,user_mail,user_password,user_prenom,user_nom,user_datenaissance,user_departement,user_dateinscription,user_dateconnexion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"); 

            $req->execute(array($pseudo,$email,$motdepasse,$prenom,$nom,$date_naissance,$departement,$date_inscription,$date_inscription));
        }


    }
}

?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <title>Inscription</title>
    </head>
    <body>
        <!--   *************************************************************  -->
        <!--   ************************** NAVBAR  **************************  -->

        <?php
        require_once('skeleton/menu.php');

        ?>

        <h1>Inscription</h1>
        <!--   *************************************************************  -->
        <!--   ************************** Form  **************************  -->
        <form method="post">
            <div class="form-group">
                <label for="nom">Comment vous appelez vous ?</label>
                <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom">
                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prenom">
            </div>

            <div class="form-group">
                <?php

                if(isset($err_pseudo)){
                    echo $err_pseudo;
                } 
                ?>
                <label for="pseudo">Votre Pseudo</label>
                <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo">
            </div>

            <div class="form-group">
<?php

                if(isset($err_pseudo)){
                    echo $err_pseudo;
                } 
                ?>
                <label for="email">Votre Adresse Email</label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Adresse Email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
               <?php

                if(isset($err_pseudo)){
                    echo $err_pseudo;
                } 
                ?>
                <label for="motdepasse">Mot de passe</label>
                <input type="password" class="form-control" id="motdepasse" name ="motdepasse" placeholder="Mot de passe">
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>

            <div class="form-group">
               <?php

                if(isset($err_pseudo)){
                    echo $err_pseudo;
                } 
                ?>
                <select name="naiss_jour">
                    <?php

                    listannee(1,31);
                    ?>
                </select>
                <select name="naiss_mois">
                    <option value="1">Janvier </option>
                    <option value="2">Février </option>
                    <option value="3">Mars </option>
                    <option value="4">Avril </option>
                    <option value="5">Mai</option>
                    <option value="6">Juin </option>
                    <option value="7">Juillet </option>
                    <option value="8">Aout </option>
                    <option value="9">Septembre </option>
                    <option value="10">Octobre </option>
                    <option value="11">Novembre </option>
                    <option value="12">Décembre </option>
                </select>
                <select name="naiss_annees">
                    <?php

                    listannee(1950,70);
                    ?>
                </select> 
            </div>
            <div class="form-group">
                <select name="departement">
                    <option value="1">Ain </option>
                    <option value="2">Aisne</option>

                </select>

            </div>

            <button type="submit" class="btn btn-primary" name="inscription">Submit</button>
        </form>



        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>