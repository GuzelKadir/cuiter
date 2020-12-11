<?php
ob_start();

require_once 'bibli_generale.php';
require_once 'bibli-index-inscription.php';

$bd= KG_bd_connect();
session_start();
$sql="SELECT * FROM users WHERE usID=".$_SESSION['id'];
$res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
$T=mysqli_fetch_assoc($res);
//debut du html
KG_aff_debut('#','protegée');
$id=session_id();
echo '<a href="deconnexion.php">deconnexion</a><br>';
echo '<a href="inscription.php">inscription</a><br>';
echo '<h1>Variable de session & Champs de la table users</h1>',
	'<ul>',
		'<li><p>SID=',$id,'</p></li>';
		foreach ($_SESSION as $key => $value) {
			echo
			'<li><p>',$key,'=',$value,'</p></li>';
		}
		echo
		'<li><p>Nom=',$T['usNom'],'</p></li>',
		'<li><p>Ville=',$T['usVille'],'</p></li>',
		'<li><p>Web=',$T['usWeb'],'</p></li>',
		'<li><p>Mail=',$T['usMail'],'</p></li>',
		'<li><p>MDP=',$T['usPasse'],'</p></li>',
		'<li><p>BIO=',$T['usBio'],'</p></li>',
		'<li><p>DateN=',KG_amj_clair($T['usDateNaissance']),'</p></li>',
		'<li><p>DateI=',KG_amj_clair($T['usDateInscription']),'</p></li>',
		'<li><p>Photo=',$T['usAvecPhoto'],'</p></li>',
	'</ul>';
//fin du html
KG_aff_fin();
exit;
?>