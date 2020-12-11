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


$usID=$_SESSION['id'];
$_POST=array_map('trim', $_POST);
if (isset($_POST['btnValider'])) {
	foreach ($_POST as $key => $value) {
		if (is_numeric($key)) {
			if ($value==1) {
				$desabonner="DELETE FROM estabonne WHERE eaIDUser={$_SESSION['id']} AND eaIDAbonne=$key";
				$desa=mysqli_query($bd, $desabonner) or KG_bd_erreur($bd, $desabonner);
			}else{
				$date= date('Y').date('m').date('d');
				$sabonner="INSERT INTO estabonne (eaIDUser , eaIDAbonne, eaDate)
				VALUES ({$_SESSION['id']},$key,'$date')";
				$sabonne=mysqli_query($bd, $sabonner) or KG_bd_erreur($bd, $sabonner);
			}
		}
	}
	header('location: cuiteur.php');
}
//debut du html
KG_aff_debut('../styles/tp3.css', 'recherche');
//header du html
KG_aff_entete('n','Rechercher des utilisateurs', 'recherche.php');
//patie infos du html
KG_aff_infos($bd , $usID);
//affcihage de la recherche
$saisie='';
if (isset($_POST['recherche'])) {
	$saisie=$_POST['saisie'];
}
echo '<div id=divconnexion>',
		'<form action=# method=post id=soustitre>',
			'<input id=recherche type="text" name="saisie" value="',$saisie,'" size=35 />',
			'<input type="submit" name="recherche" value="Rechercher"/>',
		'</form>';
if(isset($_POST['recherche']) && $_POST['saisie']!=''){
	$count="SELECT count(usID)
			FROM users
			WHERE usPseudo LIKE '%$saisie%'
			OR usNom LIKE '%$saisie%'";
	$cou=mysqli_query($bd, $count) or KG_bd_erreur($bd, $count);	
	$C=mysqli_fetch_assoc($cou);
	if ($C['count(usID)']==0) {
		//a revoir pour le div
		echo '<div id="blablavide">',
                '<p>Aucun utilisateur trouver</p>',
            '</div>';
	}else{
		$saisie=mysqli_escape_string($bd,$_POST['saisie']);
		$sql="SELECT DISTINCT * 
			FROM users
			WHERE usPseudo LIKE '%$saisie%'
			OR usNom LIKE '%$saisie%'";
		$recherche=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
		echo
			'<h2 id=para>Résultats de la recherche</h2>';
		MLM_GK_aff_recherche($bd,$recherche);
	}
}
echo
	'</div>';
//affichage du pied de page 
KG_aff_pied();
//fin du html
KG_aff_fin();
exit;
?>