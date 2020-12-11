<?php
ob_start();
require_once 'bibli_generale.php';
//appel de connection

$bd= KG_bd_connect();

$sql = 'SELECT *
		FROM users
		ORDER BY usID';
$res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);




KG_aff_debut('#' , 'Liste users 2');
echo 
			'<h1>Liste des utilisateurs de cuiteur</h1>';
while($T=mysqli_fetch_assoc($res)){
	echo
			'<h2>Utilisateurs', $T['usID'],'</h2>',
			'<ul>',
				'<li>Pseudo:', protect($T['usPseudo']),'</li>',
			'</ul>',
			'<ul>',
				'<li>Nom:', protect($T['usNom']),'</li>',
			'</ul>',
			'<ul>',
				'<li>Inscription:', $T['usDateInscription'],'</li>',
			'</ul>',
			'<ul>',
				'<li>Ville:', protect($T['usVille']),'</li>',
			'</ul>',
			'<ul>',
				'<li>Web:', protect($T['usWeb']),'</li>',
			'</ul>',
			'<ul>',
				'<li>Mail:', protect($T['usMail']),'</li>',
			'</ul>',
			'<ul>',
				'<li>Naissance:', KG_amj_clair($T['usDateNaissance']),'</li>',
			'</ul>',
			'<ul>',
				'<li>Bio:', protect($T['usBio']),'</li>',
			'</ul>';
}

KG_aff_fin();

exit;

?>