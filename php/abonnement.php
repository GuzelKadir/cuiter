<?php
ob_start();
require_once 'bibli_generale.php';
require_once 'bibli-cuiteur.php';
//appel de connection
$bd= KG_bd_connect();
//test si id est present dans l'url
tooManyArg($_GET, 1);
//recuperer la session deja ouverte.
session_start();
//test si session bien ouverte.
KG_verifie_authentification();
$usID=mysqli_escape_string($bd,decryptage($_GET['id']));
//faire une fonction pour ca
//test pour savoir si l'utilsateur existe
$Exist= "SELECT COUNT(usID)
        FROM users
        WHERE users.usID = '$usID'";
$test=mysqli_query($bd, $Exist) or KG_bd_erreur($bd, $Exist);
$G=mysqli_fetch_assoc($test);



//si l'utilisateur d'existe pas redirection vers page utilisateur pour afficher message
if($G['COUNT(usID)']==0){
    header('location: utilisateur.php?id='.cryptage(protect($usID)));
}
//traitement du formulaire
$_POST=array_map('trim', $_POST);
if (isset($_POST['btnValider'])) {
    foreach ($_POST as $key => $value) {
        if (is_numeric($key)) {
            if ($value==1) {
                $desabonner="DELETE FROM estabonne WHERE eaIDUser={$_SESSION['id']} AND eaIDAbonne=$key";
                $desa=mysqli_query($bd, $desabonner) or KG_bd_erreur($bd, $desabonner);
            }else{
                $date= date('Ymd');
                $sabonner="INSERT INTO estabonne (eaIDUser , eaIDAbonne, eaDate)
                VALUES ({$_SESSION['id']},$key,'$date')";
                $sabonne=mysqli_query($bd, $sabonner) or KG_bd_erreur($bd, $sabonner);
            }
        }
    }
    header('location: cuiteur.php');
}


///////////HTML//////////////
//debut du html
KG_aff_debut('../styles/tp3.css', 'abonnement');
//header du html
/*REQUETE POUR AVOIR LES INFOS DE LA TABLE USERS*/
$sql= "SELECT * , COUNT(blID)
        FROM users , blablas
        WHERE users.usID = blablas.blIDAuteur
        AND usID='$usID'";
$info=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
$I=mysqli_fetch_assoc($info);
$pseudo=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
$P=mysqli_fetch_assoc($pseudo);
if ($_SESSION['id']==decryptage($_GET['id'])) {
    KG_aff_entete('n','Mes abonnement', 'abonnement.php');
}else{
    $pse=protect($P['usPseudo']);
    KG_aff_entete('n','Les abonnement de '.$pse, 'abonnement.php');
}

//patie infos du html
KG_aff_infos($bd , $_SESSION['id']);

/*REQUETE POUR AVOIR LE NOMBRE D'ABONNEMENT*/
$nbabonnement= "SELECT COUNT(eaIDAbonne)
        FROM estabonne
        WHERE eaIDUser='$usID'";
$abo=mysqli_query($bd, $nbabonnement) or KG_bd_erreur($bd, $nbabonnement);
$A=mysqli_fetch_assoc($abo);

$test=mysqli_query($bd, $nbabonnement) or KG_bd_erreur($bd, $nbabonnement);
$T=mysqli_fetch_assoc($test);

if($T['COUNT(eaIDAbonne)']==0){
    //a revoir pour le div
    echo '<div id="blablavide">',
            '<p>Aucun abonnement à afficher</p>',
        '</div>';
}else{
    //affichage de la recherche
    /*REQUETE POUR AVOIR LE NOMBRE D'ABONNEE*/
    $nbabonne= "SELECT *, COUNT(estabonne.eaIDUser)
    			FROM estabonne
    			WHERE estabonne.eaIDAbonne ='$usID'";
    $nbabo=mysqli_query($bd, $nbabonne) or KG_bd_erreur($bd, $nbabonne);
    $Abo=mysqli_fetch_assoc($nbabo);
    /*REQUETE POUR AVOIR LE NOMBRE DE MENTIONS*/
    $mentions= "SELECT COUNT(meIDBlabla)
    			FROM mentions
    			WHERE meIDUser ='$usID'";
    $nbmentions=mysqli_query($bd, $mentions) or KG_bd_erreur($bd, $mentions);
    $M=mysqli_fetch_assoc($nbmentions);
    /*INITIALISATION DE VARIABLE*/
    $nbbla=protect($I['COUNT(blID)']);
    $nbment=protect($M['COUNT(meIDBlabla)']);
    $nbabos=protect($Abo['COUNT(estabonne.eaIDUser)']);
    $abonnement=protect($A['COUNT(eaIDAbonne)']);
    $pp=profilePicture($usID, $I['usAvecPhoto']);
    $pseudo=protect($I['usPseudo']);
    $nom=protect($I['usNom']);
    echo
    '<div id=soustitre>',
        '<img src="',$pp,'" alt="',$pseudo,'">
        <p><a href="utilisateur.php?id=',cryptage(protect($usID)),'" >',$pseudo,'</a> ',$nom,'</p>',
        '<ul id=infoUtilisateur>',
        '<li><a href="blablas.php?id=',cryptage(protect($usID)),'">',$nbbla,' blablas</a> - </li>',
        '<li><a href="mentions.php?id=',cryptage(protect($usID)),'">',$nbment,' mentions</a> - </li>',
        '<li><a href="abonnes.php?id=',cryptage(protect($usID)),'">',$nbabos,' abonnés</a> - </li>',
        '<li><a href=#>',$abonnement,' abonnement</a></li>',
        '</ul>',
    '</div>';   

    $sql="SELECT * FROM users , estabonne WHERE eaIDUser='$usID' AND usID = eaIDAbonne";
    $recherche=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
    echo '<div id=abonnement>';
    MLM_GK_aff_recherche($bd,$recherche);
    echo '</div>';
}
//affichage du pied de page 
KG_aff_pied();
//fin du html
KG_aff_fin();
exit;
?>