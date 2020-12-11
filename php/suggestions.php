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
KG_aff_debut('../styles/tp3.css', 'suggestions');
//header du html
KG_aff_entete('n','suggestions', 'suggestions.php');
//patie infos du html
KG_aff_infos($bd , $usID);
//affcihage de la recherche
$max=5;
$suggestions= "SELECT *
FROM users
WHERE usID IN
            (SELECT eaIDAbonne
            FROM estabonne
            WHERE eaIDUser IN
                            (SELECT eaIDAbonne
                            FROM estabonne
                            WHERE eaIDUser = {$_SESSION['id']}))
AND usID NOT IN(SELECT eaIDAbonne
                            FROM estabonne
                            WHERE eaIDUser = {$_SESSION['id']})
AND usID != {$_SESSION['id']}
LIMIT $max";
$recherche=mysqli_query($bd, $suggestions) or KG_bd_erreur($bd, $suggestions);
echo '<div id=divconnexion>',
MLM_GK_aff_recherche($bd,$recherche,'suggestions.php',$max);
echo '</div>';
//affichage du pied de page 
KG_aff_pied();
//fin du html
KG_aff_fin();
exit;
?>