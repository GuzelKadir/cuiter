<?php
ob_start();
require_once 'bibli_generale2.php';
require_once 'bibli-cuiteur2.php';
//appel de connection
$bd= KG_bd_connect();


//test si l'id est present dans l'url & s'il est valide
existID(isset($_GET['id']));
validID($_GET['id']);
if (count($_GET)!=1 && count($_GET)!=3) {
	tooManyArg($_GET,1);
}
//--------------------------------------------------------------------//
if (!isset($_GET['blablas'])) {
    $nbblablas=4;
}else{
	$nbblablas=blablaTest($_GET['blablas']);
}
//requete
$usID=$_GET['id'];
$usID2=0;
$sql= '(SELECT users.usPseudo , users.usNom, blablas.blTexte, blablas.blDate , blablas.blHeure , users.usID , users.usAvecPhoto , blablas.blIDOriginal , blID
FROM blablas , users
WHERE blablas.blIDOriginal = 0
AND blIDAuteur = '.$usID.
' AND blIDAuteur = users.usID)
UNION
(SELECT users.usPseudo , users.usNom, blablas.blTexte, blablas.blDate , blablas.blHeure , users.usID , users.usAvecPhoto , blablas.blIDOriginal ,blID
FROM estabonne , blablas ,users
WHERE  estabonne.eaIDUser = '.$usID.
' AND estabonne.eaIDAbonne = users.usID
AND blablas.blIDAuteur = estabonne.eaIDAbonne)
UNION
(SELECT users.usPseudo , users.usNom, blablas.blTexte, blablas.blDate , blablas.blHeure , users.usID , users.usAvecPhoto , blablas.blIDOriginal ,blID
FROM mentions , blablas , users
WHERE mentions.meIDBlabla =  blablas.blID
AND blablas.blIDAuteur=users.usID
AND mentions.meIDUser = '.$usID.')
ORDER BY blDate DESC , blHeure DESC';
$res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
$enr=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
$T=mysqli_fetch_assoc($enr);
//test pour ssavoir si l'utilsateur existe & savoir si il a des blablas
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
KG_aff_entete('',protect($G['usPseudo']), $usID);
//patie infos du html
KG_aff_infos($bd , $G , $usID);
//listage des blablas
KG_aff_blablas($res , $enr , $bd, $nbblablas,'cuiteur.php', $usID , $usID2);
//affichage du pied de page 
KG_aff_pied();
//fin du html
KG_aff_fin();
exit;
?>