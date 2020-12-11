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
$_POST=array_map('trim', $_POST);

//test pour voir si btn publier existe
if(isset($_POST['btnValider'])){
  $text=mysqli_escape_string($bd,$_POST['poste']);
  if($text!=''){
    $date= date('Y').date('m').date('d');
    $heure= date('H:i:s');
    $sql="INSERT INTO blablas (blIDAuteur,blDate,blHeure,blTexte,blIDOriginal)
          VALUES ({$_SESSION['id']},$date,'$heure','$text',0)";
    $poster=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
    $blID=mysqli_insert_id($bd);
    //recuperer toute les mentiions du blablas
    $mentions=all_mentions($text);

    foreach ($mentions as $key) {
      $key=mysqli_escape_string($bd,$key);
      $sql="SELECT usID , count(usID) FROM users WHERE usPseudo = '$key'";
      $users=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
      $US=mysqli_fetch_assoc($users);
      if($US['count(usID)']!=0){
        $sql="INSERT INTO mentions (meIDUser,meIDBlabla)
            VALUES ({$US['usID']},$blID)";
        $insertMentions=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
      }
    }
    //recup toute les tags du blablas
    $tags=all_tags($text);
    foreach ($tags as $key) {
      $key=mysqli_escape_string($bd,$key);
      $sql="INSERT INTO tags (taID,taIDBlabla)
          VALUES ('$key',$blID)";
      $insertTags=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
    }
    header('location: cuiteur.php');
  }
}

//traitement de supprimer un cuit et de le recuiter
rec_sup($bd);
$rep=repondre($bd);
// verification de l'existance de la variable blablas
if (!isset($_GET['blablas'])) {
    $nbblablas=4;
}else{
  $nbblablas=blablaTest($_GET['blablas']);
}
//REQEUTE QUI VERIFIE SI IL EXISTE DES BLABLAS A AFFICHER
$bl= "SELECT COUNT(blID)
      FROM (blablas
      INNER JOIN users AS auteur ON blIDAuteur = usID)
      LEFT OUTER JOIN users AS origin ON blIDOriginal = origin.usID
      WHERE auteur.usID = {$_SESSION['id']}
      OR auteur.usID IN (SELECT eaIDAbonne
                          FROM estabonne
                          WHERE eaIDUser = {$_SESSION['id']})
      OR blID IN (SELECT blID
                        FROM blablas
                        INNER JOIN mentions ON blID = meIDBlabla
                        WHERE meIDUser = {$_SESSION['id']})                  
      ORDER BY blID DESC";
$existbl=mysqli_query($bd, $bl) or KG_bd_erreur($bd, $bl);
$B=mysqli_fetch_assoc($existbl);
$nombrebl=$B['COUNT(blID)'];

//debut du html
KG_aff_debut('../styles/tp3.css', 'cuiteur');
//header du html
KG_aff_entete('y','','',$rep);
//patie infos du html
KG_aff_infos($bd, $_SESSION['id']);
//listage des blablass
if($nombrebl!=0){
  //Requete pour recuperer tout les blablas a afficher dans cuiteur de l'utilisateur
  $sql= "SELECT auteur.usID AS autID, auteur.usPseudo AS autPseudo, auteur.usNom AS autNom, auteur.usAvecPhoto AS autAvecPhoto, blID, blTexte, blDate, blHeure, origin.usID AS oriID, origin.usPseudo AS oriPseudo, origin.usNom As oriNom, origin.usAvecPhoto AS oriAvecPhoto 
      FROM (blablas
      INNER JOIN users AS auteur ON blIDAuteur = usID)
      LEFT OUTER JOIN users AS origin ON blIDOriginal = origin.usID
      WHERE auteur.usID = {$_SESSION['id']}
      OR auteur.usID IN (SELECT eaIDAbonne
                          FROM estabonne
                          WHERE eaIDUser = {$_SESSION['id']})
      OR blID IN (SELECT blID
                        FROM blablas
                        INNER JOIN mentions ON blID = meIDBlabla
                        WHERE meIDUser = {$_SESSION['id']})                  
      ORDER BY blID DESC";
  $res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
  KG_aff_blablas($bd,$res, $nbblablas , 'cuiteur.php');
}else{
  KG_aff_blablas($bd,$existbl, $nbblablas , 'cuiteur.php', $nombrebl);
}
//affichage du pied de page 
KG_aff_pied();
//fin du html
KG_aff_fin();
exit;
?>