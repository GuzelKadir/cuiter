

<?php
ob_start();
require_once 'bibli_generale.php';
require_once 'bibli-cuiteur.php';
//appel de connection
$bd= KG_bd_connect();
//requete
$sql = 'SELECT auteur.usID AS autID, auteur.usPseudo AS autPseudo, auteur.usNom AS autNom, auteur.usAvecPhoto AS autAvecPhoto,  blTexte, blDate, blHeure, origin.usID AS oriID, origin.usPseudo AS oriPseudo, origin.usNom As oriNom, origin.usAvecPhoto AS oriAvecPhoto
              FROM (blablas
              INNER JOIN users AS auteur ON blIDAuteur = usID)
              LEFT OUTER JOIN users AS origin ON blIDOriginal = origin.usID
              WHERE auteur.usID = 2
              ORDER BY blID DESC';
$enr=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
$res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
$R=mysqli_fetch_assoc($res);
//debut du html
KG_aff_debut('../styles/tp3.css', 'blablas 3');
//header du html
KG_aff_entete('n',protect($R['autPseudo']));
//patie infos du html
KG_aff_infos();
echo '<ul id="blabla">';
//listage des blablas
while($T=mysqli_fetch_assoc($enr)){
	if ($T['oriID']!= 0) {
		echo
		'<li class="lesblablas">',
			'<img class="pp" alt="',protect($T['oriPseudo']),'" src="',protect(profilePicture($T['oriID'],$T['oriAvecPhoto'])),'"/>',
			'<p><a href="utilisateur.php?id=',$T['oriID'],'">',protect($T['oriPseudo']),'</a> ',protect($T['oriNom']),', recuité par <a href="utilisateur.php?id=',$T['autID'],'">',protect($T['autPseudo']),'</a></p>',
			protect($T['blTexte']),
			'<p class="postTiming">',KG_amj_clair($T['blDate']),' à ',KG_heure_clair($T['blHeure']),'</p>',
			'<a class="Recuiter" href="index.html">Recuiter</a>',
			'<a class="Repondre" href="index.html">Répondre</a>',
		'</li>';
	}else{	
		echo
		'<li class="lesblablas">',
			'<img class="pp" alt="',protect($T['autPseudo']),'" src="',protect(profilePicture($T['autID'],$T['autAvecPhoto'])),'"/>',
			'<p><a href="utilisateur.php?id=',$T['autID'],'">',protect($T['autPseudo']),'</a> ',protect($T['autNom']),'</p>',
			protect($T['blTexte']),
			'<p class="postTiming">',KG_amj_clair($T['blDate']),' à ',KG_heure_clair($T['blHeure']),'</p>',
			'<a class="Recuiter" href="index.html">Recuiter</a>',
			'<a class="Repondre" href="index.html">Répondre</a>',
		'</li>';
	}
}
echo 
	'</ul>';
//affichage du pied de page 
KG_aff_pied();
//fin du html
KG_aff_fin();
exit;
?>