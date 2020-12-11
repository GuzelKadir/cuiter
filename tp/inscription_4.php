<?php
ob_start();

require_once 'bibli_generale.php';

//appel de connection
$bd= KG_bd_connect();

//debut du html
$_POST=array_map('trim', $_POST);
KG_aff_debut('#','inscription 4');
echo '<h1>Réception du formulaire<br>Inscription utilisateur</h1>';
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
     $array[]= 'La date de naissance n\'est pas valide';
   }

   if(count($array)!=0){
      echo  '<STRONG>Les erreurs suivantes ont été détectées</STRONG><br>';
            '<p>';
      foreach ($array as $i => $value) {
         echo $value;
      }
      echo '<p>';
      exit;
   }

   $bd= KG_bd_connect();
   $pseudo=mysqli_escape_string($bd,$_POST['txtPseudo']);
   $sql="SELECT COUNT(usID)
        FROM users 
        WHERE usPseudo ='$pseudo'";
   $res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
   $T=mysqli_fetch_assoc($res);
   if($T['COUNT(usID)']!=0){
      echo '<STRONG>Les erreurs suivantes ont été détectées</STRONG>';
      echo '<p>Ce pseudo existe déja, il faut changé le pseudo</p>';
      exit;
   }
	
	$pwd = mysqli_escape_string($bd,password_hash($_POST['txtPasse'], PASSWORD_DEFAULT));
	$nom = mysqli_escape_string($bd,$_POST['txtNom']);
	$mail=mysqli_escape_string($bd,$_POST['txtMail']);
	$date=$annee*10000+$mois*100+$jour;
	$dateins= date('Y').date('m').date('d');
	$photo=0;
	$sql="INSERT INTO users (usNom , usMail , usPseudo , usPasse , usDateNaissance , usDateInscription , usAvecPhoto)
		VALUES ('$nom','$mail','$pseudo','$pwd','$date','$dateins','$photo')";
	$res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
	echo '<p>Un nouvel utilisateur a bien été enregistré<br>Il a le numéro ',mysqli_insert_id($bd),'</p>';
//fin du html
KG_aff_fin();
exit;
?>