<?php
ob_start();
require_once 'bibli_generale.php';
//appel de connection

$bd= KG_bd_connect();

$sql = 'SELECT	users.usPseudo , users.usNom, blablas.blTexte, blablas.blDate , blablas.blHeure
    FROM users , blablas
    WHERE users.usID = blablas.blIDAuteur
	AND blablas.blIDAuteur = 2';

$res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
KG_aff_debut('#','blablas 1');

$T=mysqli_fetch_assoc($res);



echo '<h1>Les blablas de ',$T['usPseudo'],'</h1>';
echo '<ul>',
	 '<li>',protect($T['usPseudo']),'<br>',$T['blTexte'],'<br>',KG_amj_clair($T['blDate']),' à ',KG_heure_clair($T['blHeure']),'</li>';
while($T=mysqli_fetch_assoc($res)){
	echo
			'<li>',protect($T['usPseudo']),'<br>',$T['blTexte'],'<br>',KG_amj_clair($T['blDate']),' à ',KG_heure_clair($T['blHeure']),'</li>';
}
echo '</ul>';



KG_aff_fin();
exit;
?>