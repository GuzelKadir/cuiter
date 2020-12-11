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
/**
 * Fonction qui permet de soustraire un intervalle a une date
 *
 * @param string  $diff  durrées dans le format requis pour la constructeur DateInterval
 * @return string $res   retourne la date soustraire sous la forme (Ymd)
**/
function dateSub($diff=''){
	if($diff==''){
		$dateDuJour=date('Ymd');
		return $dateDuJour;
	}
	$date=new DateTime(date('Y-m-d'));
	$sub=new DateInterval($diff);
	$date->sub($sub);
	return $date->format('Ymd');
}
/**
 * Fonction qui affiche toute les tendances
 *
 * @param bd  $bd connexion a la base de donnée
**/
function allTendance($bd){
	$date=dateSub();
	$sql10jours="SELECT taID, count(taIDBlabla) as NB FROM tags,blablas WHERE blID = taIDBlabla
	AND blDate >= '$date'
	GROUP BY taID
	ORDER BY count(taIDBlabla) DESC
	LIMIT 9";
	$Topjour=mysqli_query($bd, $sql10jours) or KG_bd_erreur($bd, $sql10jours);

	$date=dateSub('P7D');
	$sql10semaine="SELECT taID, count(taIDBlabla) as NB FROM tags,blablas WHERE blID = taIDBlabla
	AND blDate >= '$date'
	GROUP BY taID
	ORDER BY count(taIDBlabla) DESC
	LIMIT 9";
	$Topsemaine=mysqli_query($bd, $sql10semaine) or KG_bd_erreur($bd, $sql10semaine);

	$date=dateSub('P1M');
	$sql10mois="SELECT taID, count(taIDBlabla) as NB FROM tags,blablas WHERE blID = taIDBlabla
	AND blDate >= '$date'
	GROUP BY taID
	ORDER BY count(taIDBlabla) DESC
	LIMIT 9";
	$Topmois=mysqli_query($bd, $sql10mois) or KG_bd_erreur($bd, $sql10mois);


	$date=dateSub('P1Y');
	$sql10anne="SELECT taID, count(taIDBlabla) as NB FROM tags,blablas WHERE blID = taIDBlabla
	AND blDate >= '$date'
	GROUP BY taID
	ORDER BY count(taIDBlabla) DESC
	LIMIT 9";
	$Topannee=mysqli_query($bd, $sql10anne) or KG_bd_erreur($bd, $sql10anne);
	echo '<div id=tendance>',
			'<h2>Top 10 du jour</h2>',
			'<ol>';
			$exist=true;
			while($TJ=mysqli_fetch_assoc($Topjour)){
				$tag=protect($TJ['taID']);
				echo
				'<li><a href="tendances.php?tags=',cryptage($tag),'">',$tag,'(',protect($TJ['NB']),')','</a></li>';
				$exist=false;
			}
			if($exist){
				echo
				'<p>aucune tendance ...</p>';
			}	
	echo
			'</ol>',
			'<h2>Top 10 de la semaine</h2>',
			'<ol>';
			$exist=true;
			while($TS=mysqli_fetch_assoc($Topsemaine)){
				$tag=protect($TS['taID']);
				echo
				'<li><a href="tendances.php?tags=',cryptage($tag),'">',$tag,'(',protect($TS['NB']),')','</a></li>';
				$exist=false;
			}
			if($exist){
				echo
				'<p>aucune tendance ...</p>';
			}
			echo
			'</ol>',
			'<h2>Top 10 du mois</h2>',
			'<ol>';
			$exist=true;
			while($TM=mysqli_fetch_assoc($Topmois)){
				$tag=protect($TM['taID']);
				echo
				'<li><a href="tendances.php?tags=',cryptage($tag),'">',$tag,'(',protect($TM['NB']),')','</a></li>';
				$exist=false;
			}
			if($exist){
				echo
				'<p>aucune tendance ...</p>';
			}	
	echo
			'</ol>',
			'<h2>Top 10 de l\'année</h2>',
				'<ol>';
			$exist=true;
			while($TA=mysqli_fetch_assoc($Topannee)){
				$tag=protect($TA['taID']);
				echo
				'<li><a href="tendances.php?tags=',cryptage($tag),'">',$tag,'(',protect($TA['NB']),')','</a></li>';
				$exist=false;
			}
			if($exist){
				echo
				'<p>aucune tendance ...</p>';
			}
	echo
				'</ol>',
			'</div>';
}

if (!isset($_GET['blablas'])) {
  	$nbblablas=4;
}else{
	$nbblablas=blablaTest($_GET['blablas']);
}

///////////HTML//////////////
//debut du html
KG_aff_debut('../styles/tp3.css' , 'tendances');
//verfiication de l'existance du tag
if (!isset($_GET['tags'])) {
	//header du html
	KG_aff_entete('n','');
	//patie infos de cuiteur
	KG_aff_infos($bd , $_SESSION['id']);
	//toute les tendances
	allTendance($bd);
}else{
	//header du html
	KG_aff_entete('n',decryptage(protect($_GET['tags'])));
	//patie infos de cuiteur
	KG_aff_infos($bd , $_SESSION['id']);
	$tag=mysqli_escape_string($bd,decryptage($_GET['tags']));
	//requete pour recuperer le nombre de blabla
	$sql="SELECT count(taIDBlabla) FROM tags WHERE taID='$tag'";
	$nb=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
	$NB=mysqli_fetch_assoc($nb);
	$nombrebl=protect($NB['count(taIDBlabla)']);
	if ($nombrebl==0) {
		//aucun blabla
		KG_aff_blablas($bd,'',0, 'tendance.php',$nombrebl,'');
	}else{
		//afficher les blablas concercés
		$sql="SELECT auteur.usID AS autID, auteur.usPseudo AS autPseudo, auteur.usNom AS autNom, auteur.usAvecPhoto AS autAvecPhoto,  blID, blTexte, blDate, blHeure, origin.usID AS oriID, origin.usPseudo AS oriPseudo, origin.usNom As oriNom, origin.usAvecPhoto AS oriAvecPhoto
			FROM blablas 
			INNER JOIN tags ON taIDBlabla = blID
			INNER JOIN users AS auteur ON blIDAuteur=usID
			LEFT OUTER JOIN users AS origin ON blIDOriginal=origin.usID
			WHERE taID='$tag'
			ORDER BY blID DESC";
		$blabla=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
		KG_aff_blablas($bd,$blabla,$nbblablas, 'tendances.php',$nombrebl,$tag);
	}
}
//affichage du pied de page 
KG_aff_pied();
//fin du html
KG_aff_fin();
exit;
?>