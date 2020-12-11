<?php
/**
 * Fonction qui permet d'afficher l'entete du html parametrable pour afficher ou non le formulaire.
 *
 * @param char    $YorN  afficher ou non le formulaire
 * @param string    $pseudo  pseudo de l'utilsateur 
**/
function KG_aff_entete($titre , $pseudo , $usID){
	echo 	'<div id="blocpage">',
			'<header>',
				'<a id="onoff" title="Se déconnecter de cuiteur" href="index.php"></a>',
				'<a id="home" title=" Ma page d\'accueil" href="cuiteur.php?id=',$usID,'"></a>',
				'<a id="cherche" title="Rechercher des personnes à suivre" href="index.html"></a>',
				'<a id="config" title="Modifier mes informations personnelles" href="index.html"></a>';
	if($titre==''){
		echo
					'<form method="post" action="index.html">',
						'<input type="submit" name="hello" value="" id="haut_parleur" title="Publier mon message">',
						'<textarea>  </textarea>',
						'<a id="trombone" title="Ajouter une pièce jointe" href="index.html"></a>',
					'</form>';
	}else{
		echo 
			'<h1 id=titreblablas>',$titre,' ',$pseudo,'</h1>',
			'<img id="lignehead" alt="lignehead" src="../images/trait.png"/>';
	}
	echo
			'</header>';
}
/**
 * Fonction qui permet d'afficher l'aside du code html.
**/
function KG_aff_infos($bd , $T , $id2){
	//test pour avoir le nombre d'abonnement
	$nbabonnement= 'SELECT COUNT(eaIDAbonne)
			FROM estabonne
			WHERE eaIDUser='.$T['usID'];
	$abonnement=mysqli_query($bd, $nbabonnement) or KG_bd_erreur($bd, $nbabonnement);
	$A=mysqli_fetch_assoc($abonnement);
	//--------------------------------------------------------------------//
	
	//test pour avoir le nombre d'abonne
	$nbabonne= 'SELECT COUNT(estabonne.eaIDUser)
	FROM estabonne
	WHERE estabonne.eaIDAbonne ='.$T['usID'];
	$abonne=mysqli_query($bd, $nbabonne) or KG_bd_erreur($bd, $nbabonne);
	$M=mysqli_fetch_assoc($abonne);
	//--------------------------------------------------------------------//
	//requete pour avoir les top tendances
	$toptendances= 'SELECT taID , COUNT(taID)
	FROM tags
	GROUP BY taID
	ORDER BY COUNT(taIDBlabla)DESC
	LIMIT 0,4';
	$tendances=mysqli_query($bd, $toptendances) or KG_bd_erreur($bd, $toptendances);
	//--------------------------------------------------------------------//
	echo 
		'<aside>',
			'<H2 class="minititre">Utilisateur</H2>',
			'<a title="Afficher ma bio" href="utilisateur.php?id=',$id2,'&id2=',$id2,'" id="fpiat">',
				'<img src="',protect(profilePicture($T['usID'] , $T['usAvecPhoto'])),'" alt="fpiat" />',
				'<p id="textefpiat">',protect($T['usPseudo']),'</p>',
			'</a>',
			'<ul id="infoutilisateur">',
				'<li>',
					'<a title="Voir la liste des mes messages" href="blablas.php?id=',$T['usID'],'&id2=',$id2,'" >',$T['COUNT(blID)'],' blablas</a>',
				'</li>',
				'<li>',
					'<a title=" Voir les personnes que je suis" href="index.html">',$A['COUNT(eaIDAbonne)'],' abonnements</a>',
				'</li>',
				'<li>',
					'<a title="Voir les personnes qui me suivent" href="index.html">',$M['COUNT(estabonne.eaIDUser)'],' abonnés</a>',
				'</li>',
			'</ul>',

			'<H2 class="minititre">Tendances</H2>',
			'<ul id="hashtag">';
while($R=mysqli_fetch_assoc($tendances)){
		 	echo '<li>',
					'<a title="Voir les messages" href="index.html">',$R['taID'],'</a>',
				'</li>';
}
 echo
 			'<li>',
				'<a title="Voir les messages" href="index.html">Toutes les tendances</a>',
			'</li>',
			'</ul>',
			'<H2 class="minititre">Suggestions</H2>',
			'<a title="Afficher la bio" href="index.html">',
				'<img id="darck" src="../images/darkzev.jpg" alt="darkzev" />',
				'<p id="pseudodarck">Dark ze V</p>',
			'</a>',
			'<a title="Afficher la bio" href="index.html">',
				'<img id="kdick" src="../images/kdick.jpg" alt="kdick" />',
				'<p id="pseudokdick">Kdick</p>',
			'</a>',
		'</aside>';
}
/**
 * Fonction qui permet d'afficher le pied du code html.
**/
function KG_aff_pied(){
	echo 
	'<footer id="foot">',
				'<a class="lien" href="index.html">A propos</a>',
				'<a class="lien" href="index.html">Publicité</a>',
				'<a class="lien" href="index.html">Patati</a>',
				'<a class="lien" href="index.html">Aide</a>',
				'<a class="lien" href="index.html">Patata</a>',
				'<a class="lien" href="index.html">Stages</a>',
				'<a class="lien" href="index.html">Emplois</a>',
				'<a class="lien" href="index.html">Confidentialité</a>',
			'</footer>',
		'</div>';
}
/**
 * Fonction qui permet de savoir si l'utilisateur existe & si il a des blabals.
 *
 * @param int    $countID  valeur = 0  si l'utilisateur existe pas
 * @param string    $countBL  valeur = 0  si l'utilisateur n'a pas de blablas
**/
function uExist_blExist($countID , $countBL){
	if($countID==0){
		echo 'cette utilisateur n\'existe pas ';
		exit;
	}
	if($countBL==0){
		echo 'cette utilisateur n\'existe pas ';
		exit;
	}
}
/**
 * Fonction qui permet si la variable id existe dans l'url.
 *
 * @param int    $id    valeur = false si la variable id n'est pas presente dans l'url.
**/
function existID($id){
	if ($id==false) {
		echo 'il n\'y a pas d\'ID!!<br>';
		echo 'Tentative de piratage ?';
		exit;
	}
}
/**
 * Fonction qui permet de savoir si la varaible id present dans l'url est valide.
 *
 * @param int    $id  valeur de l'id prensent dans l'url
**/
function validID($id){
	if ($id<=0 || $id==false) {
		echo 'L\'ID n\'est pas bon [<=0]<br>';
		echo 'Tentative de piratage ?';
		exit;
	}
}
/**
 * Fonction qui permet de compter et de varifier le nombre d'argument dans l'url.
 *
 * @param array    $GET   tableau de l'url
 * @param int    $nb    nombre d'argument que l'on veut avoir
**/
function tooManyArg($GET , $nb){
	if (count($GET)>$nb) {
		echo 'Tentative de piratage ?';
		exit;
	}
}

?>