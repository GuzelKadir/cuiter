<?php
ob_start();
require_once 'bibli_generale2.php';
require_once 'bibli-cuiteur2.php';
//appel de connection
$bd= KG_bd_connect();

//test si l'id est present dans l'url & s'il est valide
existID(isset($_GET['id']));
validID($_GET['id']);
existID(isset($_GET['id2']));
validID($_GET['id2']);

if (!isset($_GET['blablas'])) {
    $nbblablas=4;
}else{
	$nbblablas=blablaTest($_GET['blablas']);
}
//--------------------------------------------------------------------//
//requete
$usID=$_GET['id'];
$usID2=$_GET['id2'];
$sql = 'SELECT	users.usPseudo , users.usNom, blablas.blTexte, blablas.blDate , blablas.blHeure , users.usID , users.usAvecPhoto , blablas.blIDOriginal
    FROM users , blablas
    WHERE users.usID = blablas.blIDAuteur
	AND blablas.blIDAuteur = '.$usID.
	' ORDER BY blablas.blDate DESC , blablas.blHeure DESC';
$res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
$enr=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
$T=mysqli_fetch_assoc($enr);


//test pour savoir si l'utilsateur existe & savoir si il a des blablas
$Exist= 'SELECT COUNT(blID), COUNT(usID) , usID , usAvecPhoto , usPseudo
		FROM blablas , users 
		WHERE blIDAuteur = users.usID
		AND users.usID = '.$usID;
$test=mysqli_query($bd, $Exist) or KG_bd_erreur($bd, $Exist);
$G=mysqli_fetch_assoc($test);

uExist_blExist($G['COUNT(usID)'],$G['COUNT(blID)']);
//--------------------------------------------------------------------//
//debut du html
KG_aff_debut('../styles/tp3_cuiteur.css');
//header du html
KG_aff_entete('Le profil de',protect($T['usPseudo']), protect($T['usID']));
//patie infos du html
KG_aff_infos($bd , $G , $usID);
//listage des blablas
KG_aff_blablas($res , $enr , $bd, $nbblablas,'utilisateur.php' , $usID , $usID2);
//affichage du pied de page 
KG_aff_pied();
//fin du html
KG_aff_fin();
exit;
?>