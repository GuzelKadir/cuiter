<?php
ob_start();
require_once 'php/bibli_generale.php';
require_once 'php/bibli-index-inscription.php';

if(isset($_SESSION['id'])&&isset($_SESSION['pseudo'])){
	header('location: php/deconnexion.php');
}
function KG_aff_connexion($valuePseudo='', $valueMdp=''){
	$P=KG_html_form_input('text','txtPseudo',$valuePseudo,25);
	$pseudo=KG_html_table_ligne('<p class="textform">Pseudo</p>', $P);
	$M=KG_html_form_input('password','txtPasse',$valueMdp,25);
	$mdp=KG_html_table_ligne('<p class="textform">Mot de passe</p>', $M);
	$B=KG_html_form_input('submit','btnValider','Connexion',10);
	$boutton=KG_html_table_ligne('', $B);

	echo '<div id="divconnection">',
		'<form method="POST" action="index.php">',
			'<table>';
			echo $pseudo;
			echo $mdp;
			echo $boutton;
			echo
			'</table>',
		'</form>',
		'<p id="texteBaspage">Pas encore de compte ? <a href="php/inscription.php">Inscrivez-vous</a> sans plus tarder!<br><br>Vous hésitez à vous inscrire ? Laissez-vous séduire par une <a href="html/presentation.html">présentation</a> des possibilités de Cuiteur.</p>',
	'</div>';
}
//appel de connection
$bd= KG_bd_connect();
$_POST=array_map('trim', $_POST);
//debut du html
KG_aff_debut('styles/index.css', 'index');
//header du html
KG_aff_entete();
//patie infos du html
KG_aff_infos();

if(isset($_POST['btnValider'])==false){
	KG_aff_connexion();
}else{
	$pseudo=mysqli_escape_string($bd,$_POST['txtPseudo']);
	$mdp = $_POST['txtPasse'];
	$sql="SELECT COUNT(usID), usID , usPasse
			FROM users 
			WHERE usPseudo='$pseudo'";
	$res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
	$T=mysqli_fetch_assoc($res);
	if(($T['COUNT(usID)']==0 && $pseudo!='')||$pseudo==''){
		echo '<p>Utilisateur inexistant veuillez reesayer avec un pseudo valide.</p>';
		KG_aff_connexion(protect($_POST['txtPseudo']),protect($_POST['txtPasse']));
		//affichage du pied de page 
		KG_aff_pied();
		//fin du html
		KG_aff_fin();
		exit;
	}
	if(!password_verify($mdp, $T['usPasse'])){
		echo '<p>Le mot de passe saisie n\'est pas correct.</p>';
		KG_aff_connexion(protect($_POST['txtPseudo']),protect($_POST['txtPasse']));
		//affichage du pied de page 
		KG_aff_pied();
		//fin du html
		KG_aff_fin();
		exit;
	}
	session_start();
	$_SESSION['id']=$T['usID'];
	$_SESSION['pseudo']= $_POST['txtPseudo'];
	header('Location: php/cuiteur.php');
}
//affichage du pied de page 
KG_aff_pied();
//fin du html
KG_aff_fin();
exit;
?>