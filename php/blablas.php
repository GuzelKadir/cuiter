<?php
ob_start();
require_once 'bibli_generale.php';
require_once 'bibli-cuiteur.php';

//appel de connection
$bd= KG_bd_connect();

//recuperer la session deja ouverte.
session_start();

//test si session bien ouverte.
KG_verifie_authentification();

// verification de l'existance de la variable blablas
if (!isset($_GET['blablas'])) {
  	$nbblablas=4;
}else{
	$nbblablas=blablaTest($_GET['blablas']);
}

//test si l'id est present dans l'url & s'il est valide & verification du nombre d'argument dans l'url
tooManyArg($_GET, 2);





//recuperation de l'id
$usID=mysqli_escape_string($bd,decryptage($_GET['id']));

//test pour savoir si l'utilsateur existe
$Exist= "SELECT COUNT(usID)
		FROM users
		WHERE users.usID = '$usID'";
$test=mysqli_query($bd, $Exist) or KG_bd_erreur($bd, $Exist);
$G=mysqli_fetch_assoc($test);
//si l'utilisateur d'existe pas redirection vers page utilisateur pur afficher message
if($G['COUNT(usID)']==0){
	header('location: utilisateur.php?id='.cryptage(protect($usID)));
}
//REQUETE SQL QUI PERMET DE SAVOIR SI IL EXISTE DES BLABLAS A AFFICHER
$bl= "SELECT COUNT(blID) , usPseudo
		FROM users ,blablas
		WHERE users.usID = '$usID'
		AND blIDAuteur = usID";
$existbl=mysqli_query($bd, $bl) or KG_bd_erreur($bd, $bl);
$B=mysqli_fetch_assoc($existbl);
$nombrebl=protect($B['COUNT(blID)']);



///////////HTML//////////////
//debut du html
KG_aff_debut('../styles/tp3.css' , 'blablas');
//header du html
if($_SESSION['id']==$usID){
	KG_aff_entete('n','Mes blablas');
}else{
	KG_aff_entete('n','Les blablas de '.protect($B['usPseudo']));
}
//patie infos de cuiteur
KG_aff_infos($bd , $_SESSION['id']);

if($nombrebl==0){
	//affichage des blablas
	KG_aff_blablas($bd,$existbl,$nbblablas, 'blablas.php',$nombrebl ,$usID);
}else{
	//REQUETE SQL QUI PERMET DE RECUPERER TOUT LES BLABLAS DE L UTILISATEUR
	$sql = "SELECT auteur.usID AS autID, auteur.usPseudo AS autPseudo, auteur.usNom AS autNom, auteur.usAvecPhoto AS autAvecPhoto, blID,  blTexte, blDate, blHeure, origin.usID AS oriID, origin.usPseudo AS oriPseudo, origin.usNom As oriNom, origin.usAvecPhoto AS oriAvecPhoto
	              FROM (blablas
	              INNER JOIN users AS auteur ON blIDAuteur = usID)
	              LEFT OUTER JOIN users AS origin ON blIDOriginal = origin.usID
	              WHERE auteur.usID = '$usID'
	              ORDER BY blID DESC";
	$sqlblablas=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
	//affichage des blablas
	KG_aff_blablas($bd,$sqlblablas,$nbblablas , 'blablas.php', 1 ,$usID);
}
//affichage du pied de page 
KG_aff_pied();
//fin du html
KG_aff_fin();
exit;
?>