<?php
ob_start();
require_once 'bibli_generale.php';
require_once 'bibli-index-inscription.php';
/**
 * Fonction qui teste la validiter des champs du formulaire
 *
 * @param bd     $bd    connection a la bd.
 * @return array $array tableau contenant les erreurs.
 */
function KGl_new_user($bd){
	   $value=$_POST['txtPseudo'];
	   $str=trim($value);
	   $taille=mb_strlen($value, 'UTF-8');
	   $alpha=mb_ereg_match('^[A-Za-z0-9\w]*$', $str);


	   $array= array();

	   if($taille<4 || $taille>30 || $alpha==NULL){
	   		$array[]='Le pseudo doit avoir de 4 & 30 caractères alphanumériques<br>';
	   }
	   $value=$_POST['txtPasse'];
	   $str=trim($value);
	   $taille=mb_strlen($value,'UTF-8');
	   if($taille<6){
	   		$array[]= 'Le mot de passe est obligatoire et doit avoir au moins 6 caractères<br>';
	   }
	   if($str != $_POST['txtVerif']){
	   		$array[]= 'Le mot de passe est différent dans les 2 zones<br>';
	   }
	   $value=$_POST['txtNom'];
	   $str=trim($value);
	   $noTags=strip_tags($str);
	   if($str='' || $str != $noTags){
	   		$array[]= 'Le nom est obligatoire/ Le nom ne doit pas contenir d tags HTML<br>';
	   }
	   $value=$_POST['txtMail'];
	   $str=trim($value);
	   $taille=mb_strlen($value,'UTF-8');
	   $noTags=strip_tags($str);
	   $arob=filter_var($str, FILTER_VALIDATE_EMAIL);
	   if(!$arob || $str == ''){
	   		$array[]= 'L\'adresse mail est obligatoire/L\'adresses mail n\'est pas valide<br>';
	   }

	   $jour=(int)$_POST['selNais_j'];
	   $mois=(int)$_POST['selNais_m'];
	   $annee=(int)$_POST['selNais_a'];
	   if(!checkdate($mois,$jour,$annee)){
		  $array[]= 'La date de naissance n\'est pas valide<br>';
		}
		
		$pseudo=mysqli_escape_string($bd,$_POST['txtPseudo']);
		$sql="SELECT COUNT(usID)
			  FROM users 
			  WHERE usPseudo ='$pseudo'";
		$res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
		$T=mysqli_fetch_assoc($res);
		if($T['COUNT(usID)']!=0){
			$array[]='Ce pseudo existe déja, il faut changé le pseudo<br>';
		}
		if(count($array)==0){
			$pwd = mysqli_escape_string($bd,password_hash($_POST['txtPasse'], PASSWORD_DEFAULT));
			$nom = mysqli_escape_string($bd,$_POST['txtNom']);
			$mail=mysqli_escape_string($bd,$_POST['txtMail']);
			$date=$annee*10000+$mois*100+$jour;
			$dateins= date('Y').date('m').date('d');
			$photo=0;
			$sql="INSERT INTO users (usNom , usMail , usPseudo , usPasse , usDateNaissance , usDateInscription , usAvecPhoto)
				VALUES ('$nom','$mail','$pseudo','$pwd','$date','$dateins','$photo')";
			$res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
		}
	return $array;
}
/**
 * Fonction qui afficher le formulaire avec option champ preselectionné.
 *
 * @param 	string   $valPseudo  pseudo preselectionné.
 * @param 	string   $valNom  	 nom preselectionné.
 * @param 	string   $valMail    mail preselectionné.
 * @param 	int   	 $valJ       jours preselectionné.
 * @param 	int   	 $valM       mois preselectionné.
 * @param 	int   	 $valA       annee preselectionné.
 */
function afficher_formulaire($valPseudo='', $valNom='', $valMail='', $valJ=0,$valM=0,$valA=0){
	$inputPseudo=KG_html_form_input('text','txtPseudo',protect($valPseudo),15);
	$Pseudo=KG_html_table_ligne('<p class="textform">Choisir un pseudo<p>' , $inputPseudo);
	$inputPass=KG_html_form_input('password','txtPasse','',15);
	$Passe=KG_html_table_ligne('<p class="textform">Choisir un mot de passe<p>' , $inputPass);
	$inputPass2=KG_html_form_input('password','txtVerif','',15);
	$Passe2=KG_html_table_ligne('<p class="textform">Répéter le mot de passe<p>' , $inputPass2);
	$inputNom=KG_html_form_input('text','txtNom',protect($valNom),25);
	$Nom=KG_html_table_ligne('<p class="textform">Indiquer votre nom<p>' ,$inputNom);
	$inputMail=KG_html_form_input('text','txtMail',protect($valMail),25);
	$Mail=KG_html_table_ligne('<p class="textform">Donner une adresse mail<p>' , $inputMail);

	$selectDate=KG_html_form_date('selNais',200,$valJ,$valM,$valA);
	$Date=KG_html_table_ligne('<p class="textform">Votre date de naissance<p>' , $selectDate);
	$submit=KG_html_form_input('submit','btnValider','Je m\'inscris', 10);
	$submitButton=KG_html_table_ligne('',$submit);
	echo 
	'<div id="divconnection">',
		'<p id="texteinscription">Pour vous inscrire, il suffit de :</p>',
		'<form method="POST" action="inscriptionbykadir.php">',
			'<table>';
				echo $Pseudo;
				echo $Passe;
				echo $Passe2;
				echo $Nom;
				echo $Mail;
				echo $Date;
				echo $submitButton;
				echo
			'</table>',
		'</form>',
	'</div>';
}

$bd= KG_bd_connect();
KG_aff_debut('../styles/index.css','inscription');
//header du html
KG_aff_entete_inscription();
KG_aff_infos();
//test si une session  est deja active. 
session_start();
if(isset($_SESSION['id'])){
	header('location: cuiteur.php');
}

if(isset($_POST['btnValider'])==false){
	afficher_formulaire();
}else{
	$array=KGl_new_user($bd);
	if(count($array)==0){
		session_start();
		$_SESSION['id']= mysqli_insert_id($bd);
		$_SESSION['pseudo']= $_POST['txtPseudo'];
		header('location: protegee.php');
	}
	echo '<h2>Les erreurs suivantes ont été détectées.</h2>';
	echo '<p>';
	foreach ($array as $i => $value) {
		echo $value;
	}
	echo '</p>';
	$jour=(int)$_POST['selNais_j'];
    $mois=(int)$_POST['selNais_m'];
	$annee=(int)$_POST['selNais_a'];
	afficher_formulaire($_POST['txtPseudo'],$_POST['txtNom'],$_POST['txtMail'], $jour,$mois,$annee);
}

//affichage du pied de page 
KG_aff_pied();
//fin du html
KG_aff_fin();
exit;
?>