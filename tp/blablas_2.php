<?php
ob_start();
require_once 'bibli_generale.php';
require_once 'bibli-cuiteur.php';
//appel de connection
$bd= KG_bd_connect();
//requete
$sql = 'SELECT	users.usPseudo , users.usNom, blablas.blTexte, blablas.blDate , blablas.blHeure , users.usID , users.usAvecPhoto
    FROM users , blablas
    WHERE users.usID = blablas.blIDAuteur
	AND blablas.blIDAuteur = 2
	ORDER BY blablas.blDate DESC , blablas.blHeure DESC';
$res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
$enr=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
$T=mysqli_fetch_assoc($enr);

//debut du html
KG_aff_debut('../styles/tp3.css', 'blablas 2');
//header du html
KG_aff_entete('n',protect($T['usPseudo']));
//patie infos du html
KG_aff_infos();
echo '<ul id="blabla">';
//listage des blablas
while($R=mysqli_fetch_assoc($res)){
	echo
			'<li class="lesblablas">',
				'<img class="pp" alt="',protect($R['usPseudo']),'" src="',protect(profilePicture($R['usID'],$R['usAvecPhoto'])),'"/>',
				'<p>',protect($R['usPseudo']),' ',protect($R['usNom']),'</p>',
				protect($R['blTexte']),
				'<p class="postTiming">',KG_amj_clair($R['blDate']),' à ',KG_heure_clair($R['blHeure']),'</p>',
				'<a class="Recuiter" href="index.html">Recuiter</a>',
				'<a class="Repondre" href="index.html">Répondre</a>',
			'</li>';
}
echo 
	'</ul>';
//affichage du pied de page 
KG_aff_pied();
//fin du html
KG_aff_fin();
exit;
?>