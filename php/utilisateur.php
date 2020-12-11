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

//test si l'id est present dans l'url & s'il est valide
if(!isset($_POST['id'])){
	$id=decryptage($_GET['id']);
}else{ //si le bouton est actionner on l'abonne ou desabonne
	$id=$_POST['id'];
	if(isset($_POST['sabonner'])){
		$date= date('Y').date('m').date('d');
		$sabonner="INSERT INTO estabonne (eaIDUser , eaIDAbonne, eaDate)
				VALUES ({$_SESSION['id']},{$_POST['id']},'$date')";
		$sabonne=mysqli_query($bd, $sabonner) or KG_bd_erreur($bd, $sabonner);	
	}else{
		$desabonne="DELETE FROM estabonne WHERE eaIDUser = {$_SESSION['id']} AND eaIDAbonne = {$_POST['id']}";
		$desa=mysqli_query($bd, $desabonne) or KG_bd_erreur($bd, $desabonne);
	}		
}

//requete pour recuperer le pseudo
$usID=$_SESSION['id'];
$sqlpseudo = "SELECT usPseudo FROM users WHERE usID ='$id'";
$enr=mysqli_query($bd, $sqlpseudo) or KG_bd_erreur($bd, $sqlpseudo);
$PS=mysqli_fetch_assoc($enr);


//test pour savoir si l'utilsateur existe 
$Exist= "SELECT COUNT(usID)
		FROM  users 
		WHERE usID = '$id'";
$test=mysqli_query($bd, $Exist) or KG_bd_erreur($bd, $Exist);
$G=mysqli_fetch_assoc($test);
//--------------------------------------------------------------------//
//debut du html
KG_aff_debut('../styles/tp3.css', 'utilisateur');
//header du html
if($G['COUNT(usID)']=='0'){
	KG_aff_entete('n','Cette utilisateur n\'éxiste pas', 'compte.php');
}else{
	KG_aff_entete('n','Le profil de '.protect($PS['usPseudo']), 'compte.php');
}
//patie infos du html
KG_aff_infos($bd , $usID);
//affciher profil s'il existe
if($G['COUNT(usID)']!='0'){
	KG_afficher_profil($bd, $id);
}
//affichage du pied de page 
KG_aff_pied();
//fin du html
KG_aff_fin();
exit;
?>