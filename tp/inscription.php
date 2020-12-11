<?php
ob_start();

require_once 'bibli_generale.php';
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
	$inputPseudo=KG_html_form_input('text','txtPseudo',protect($valPseudo),20);
	$Pseudo=KG_html_table_ligne('Choisir un pseudo' , $inputPseudo);
	$inputPass=KG_html_form_input('password','txtPasse','',20);
	$Passe=KG_html_table_ligne('Choisir un mot de passe' , $inputPass);
	$inputPass2=KG_html_form_input('password','txtVerif','',20);
	$Passe2=KG_html_table_ligne('Répéter le mot de passe' , $inputPass2);
	$inputNom=KG_html_form_input('text','txtNom',protect($valNom),40);
	$Nom=KG_html_table_ligne('Indiquer votre nom' ,$inputNom);
	$inputMail=KG_html_form_input('text','txtMail',protect($valMail),40);
	$Mail=KG_html_table_ligne('Donner une adresse mail' , $inputMail);

	$selectDate=KG_html_form_date('selNais',200,$valJ,$valM,$valA);
	$Date=KG_html_table_ligne('Votre date de naissance' , $selectDate);
	$submit=KG_html_form_input('submit','btnValider','Je m\'inscris');
	$submitButton=KG_html_table_ligne('',$submit);
	echo '<h1>Inscription d\'un utilisateur</h1>',
	'<form method="POST" action="inscription.php">',
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
	'</form>';
}

$bd= KG_bd_connect();
$_POST=array_map('trim', $_POST);
//debut du html
KG_aff_debut('#','inscription');
//verifie si une session est deja ouverte ou non
session_start();
if ( $_SESSION!=NULL ){
	header('location: cuiteur.php');
	exit;
}


if(isset($_POST['btnValider'])==false){
	afficher_formulaire();
}else{
	$array=KGl_new_user($bd);
	if(count($array)!=0){
		echo '<strong>Les erreurs suivantes ont été détectées</strong>';
		echo '<p>';
		foreach ($array as $i => $value) {
			echo $value;
		}
		echo '</p>';
	}else{
		$_SESSION['id']= mysqli_insert_id($bd);
		$_SESSION['pseudo']= $_POST['txtPseudo'];
		header('location: protegee.php');
		exit;
	}
	$jour=(int)$_POST['selNais_j'];
    $mois=(int)$_POST['selNais_m'];
	$annee=(int)$_POST['selNais_a'];
	afficher_formulaire($_POST['txtPseudo'],$_POST['txtNom'],$_POST['txtMail'], $jour,$mois,$annee);
}
//fin du html
KG_aff_fin();


exit;
?>