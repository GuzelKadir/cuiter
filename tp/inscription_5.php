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
 * Fonction qui affiche le formualaire.
 */
function afficher_formulaire(){
	echo '<h1>Inscription d\'un utilisateur</h1>',
	'<form method="POST" action="inscription_5.php">',
		'<table>',
			'<tr>',
				'<td>Choisir un pseudo</td>',
				'<td><input type="text" name="txtPseudo" size="20"/></td>',
			'</tr>',
			'<tr>',
				'<td>Choisir un mot de passe</td>',
				'<td><input type="password" name="txtPasse" size="20" /></td>',
			'</tr>',
			'<tr>',
				'<td>Répéter le mot de passe</td>',
				'<td><input type="password" name="txtVerif" size="20"/></td>',
			'</tr>',
			'<tr>',
				'<td>Indiquer votre nom</td>',
				'<td><input type="text" name="txtNom" size="40"/></td>',
			'</tr>',
			'<tr>',
				'<td>Donner une adresse mail</td>',
				'<td><input type="text" name="txtMail" size="40"/></td>',
			'</tr>',
			'<tr>',
				'<td>Votre date de naissance</td>',
				'<td>',
					'<select name="selNais_j">',
					'<option value="1">1</option>',
					'<option value="2">2</option>',
					'<option value="31">31</option>',
					'</select>',
					'<select name="selNais_m">',
					'<option value="1">janvier</option>',
					'<option value="2">fevrier</option>',
					'<option value="6">juin</option>',
					'</select>',
					'<select name="selNais_a">',
					'<option value="2010">2010</option>',
					'<option value="2011">2011</option>',
					'</select>',
				'</td>',
			'</tr>',
			'<tr>',
				'<td></td>',
				'<td><input type="submit" name="btnValider" value="Je m\'inscris"  /></td>',
			'</tr>',
		'</table>',
	'</form>';
}

$bd= KG_bd_connect();
$_POST=array_map('trim', $_POST);
//debut du html
KG_aff_debut('#','inscription 5');
	
if(isset($_POST['btnValider'])==false){
	afficher_formulaire();
}else{
	$array=KGl_new_user($bd);
	if(count($array)==0){
		echo '<p>Un nouvel utilisateur a bien été enregistré<br>Il a le numéro ',mysqli_insert_id($bd),'</p>';
		exit;
	}
	echo '<strong>Les erreurs suivantes ont été détectées</strong>';
	echo '<p>';
	foreach ($array as $i => $value) {
		echo $value;
	}
	echo '</p>';
	afficher_formulaire();
}
//fin du html
KG_aff_fin();
exit;
?>