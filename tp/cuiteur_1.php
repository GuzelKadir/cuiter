<?php
ob_start();
require_once 'bibli_generale.php';
require_once 'bibli-cuiteur.php';
//appel de connection
$bd= KG_bd_connect();

//--------------------------------------------------------------------//
if (!isset($_GET['blablas'])) {
    $nbblablas=4;
}else{
	$nbblablas=blablaTest($_GET['blablas']);
}
//requete
$usID=23;
//test pour ssavoir si l'utilsateur existe & savoir si il a des blablas
$Exist= 'SELECT COUNT(blID), COUNT(usID)
		FROM blablas , users
		WHERE blIDAuteur = users.usID
		AND users.usID = '.$usID;
$test=mysqli_query($bd, $Exist) or KG_bd_erreur($bd, $Exist);
$G=mysqli_fetch_assoc($test);

uExist_blExist($G['COUNT(usID)'],$G['COUNT(blID)']);
//--------------------------------------------------------------------//
$sql= 'SELECT auteur.usID AS autID, auteur.usPseudo AS autPseudo, auteur.usNom AS autNom, auteur.usAvecPhoto AS autAvecPhoto,  blTexte, blDate, blHeure, origin.usID AS oriID, origin.usPseudo AS oriPseudo, origin.usNom As oriNom, origin.usAvecPhoto AS oriAvecPhoto 
      FROM (blablas
      INNER JOIN users AS auteur ON blIDAuteur = usID)
      LEFT OUTER JOIN users AS origin ON blIDOriginal = origin.usID
      WHERE auteur.usID = 23
      OR auteur.usID IN (SELECT eaIDAbonne
                          FROM estabonne
                          WHERE eaIDUser = '.$usID.')
      OR blID IN (SELECT blID
                        FROM blablas
                        INNER JOIN mentions ON blID = meIDBlabla
                        WHERE meIDUser = '.$usID.')                  
      ORDER BY blID DESC';
$res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);

//debut du html
KG_aff_debut('../styles/tp3.css', 'cuiteur_1');
//header du html
KG_aff_entete('y','');
//patie infos du html
KG_aff_infos();
//listage des blablas
KG_aff_blablas($res, $nbblablas , 'cuiteur_1.php', $usID);
//affichage du pied de page 
KG_aff_pied();
//fin du html
KG_aff_fin();
exit;
?>