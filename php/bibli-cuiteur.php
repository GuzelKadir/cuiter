<?php
/**
 * Fonction qui permet d'afficher l'entete du html parametrable pour afficher ou non le formulaire.
 *
 * @param char    $YorN  afficher ou non le formulaire
 * @param string    $titre  titre de la page
 * @param string    $php  	nom de la page php utiliser
 * @param string    $rep  	nom de l'utilisateur a mentionner
**/
function KG_aff_entete($YorN , $titre , $php='',$rep=''){
	$id='';
	if($php=='compte.php'){
		$id='id=compte';
	}
	echo 	'<div id="blocpage">',
			'<header ',$id,'>',
				'<a id="onoff" title="Se déconnecter de cuiteur" href="deconnexion.php"></a>',
				'<a id="home" title=" Ma page d\'accueil" href="cuiteur.php"></a>',
				'<a id="cherche" title="Rechercher des personnes à suivre" href="recherche.php"></a>',
				'<a id="config" title="Modifier mes informations personnelles" href="compte.php"></a>';
	if($YorN=='y'){
		if($rep!=''){
			$rep='@'.$rep.' ';
		}
		echo
					'<form id=post method="post" action=#>',
						'<input type="submit" name="btnValider" value="" id="haut_parleur" title="Publier mon message">',
						'<textarea name="poste">',protect($rep),'</textarea>',
						'<a id="trombone" title="Ajouter une pièce jointe" href="index.html"></a>',
					'</form>';
	}else{
		if($titre != ''){
			echo 
			'<h1 id=titreblablas>',$titre,'</h1>';
		}
		echo
			'<img id="lignehead" alt="lignehead" src="../images/trait.png"/>';
	}
	echo
			'</header>';
}
/**
 * Fonction qui permet d'afficher l'aside du code html.
 * @param BD        $bd  	connexion a la bd
**/
function KG_aff_infos($bd){
	$profil= "SELECT * , COUNT(blID)
			FROM users , blablas
			WHERE users.usID = blablas.blIDAuteur
			AND usID={$_SESSION['id']}";
	$pprofil=mysqli_query($bd, $profil) or KG_bd_erreur($bd, $profil);
	$T=mysqli_fetch_assoc($pprofil);
	//test pour avoir le nombre d'abonnement
	$nbabonnement= "SELECT COUNT(eaIDAbonne)
			FROM estabonne
			WHERE eaIDUser={$_SESSION['id']}";
	$abonnement=mysqli_query($bd, $nbabonnement) or KG_bd_erreur($bd, $nbabonnement);
	$A=mysqli_fetch_assoc($abonnement);
	//--------------------------------------------------------------------//
	//test pour avoir le nombre d'abonne
	$nbabonne= "SELECT COUNT(estabonne.eaIDUser)
	FROM estabonne
	WHERE estabonne.eaIDAbonne ={$_SESSION['id']}";
	$abonne=mysqli_query($bd, $nbabonne) or KG_bd_erreur($bd, $nbabonne);
	$M=mysqli_fetch_assoc($abonne);
	//--------------------------------------------------------------------//
	//requete pour avoir les top tendances
	$toptendances= "SELECT taID , COUNT(taID)
	FROM tags
	GROUP BY taID
	ORDER BY COUNT(taIDBlabla)DESC
	LIMIT 0,4";
	$tendances=mysqli_query($bd, $toptendances) or KG_bd_erreur($bd, $toptendances);
	//--------------------------------------------------------------------//
	//requete pour avoir les suggestions
	$suggestions= "SELECT *
					FROM users
					WHERE usID IN
					            (SELECT eaIDAbonne
					            FROM estabonne
					            WHERE eaIDUser IN
					                            (SELECT eaIDAbonne
					                            FROM estabonne
					                            WHERE eaIDUser = {$_SESSION['id']}))
					AND usID NOT IN(SELECT eaIDAbonne
					                            FROM estabonne
					                            WHERE eaIDUser = {$_SESSION['id']})
					AND usID != {$_SESSION['id']}
					LIMIT 2";
	$sugg=mysqli_query($bd, $suggestions) or KG_bd_erreur($bd, $suggestions);
	//--------------------------------------------------------------------//

	echo 
		'<aside>',
			'<H2 class="minititre">Utilisateur</H2>',
			'<img id="textefpiat" src="',protect(profilePicture($T['usID'] , $T['usAvecPhoto'])),'" alt="fpiat" />',
			'<label>',
				'<a title="Afficher ma bio" href="utilisateur.php?id=',cryptage(protect($T['usID'])),'">',
				protect($T['usPseudo']) ,
				'</a>',
				'  ',
				protect($T['usNom']),
			'</label>',

			'<ul id="infoutilisateur">',
				'<li>',
					'<a title="Voir la liste des mes messages" href="blablas.php?id=',cryptage(protect($T['usID'])),'" >',protect($T['COUNT(blID)']),' blablas</a>',
				'</li>',
				'<li>',
					'<a title=" Voir les personnes que je suis" href="abonnement.php?id=',cryptage($_SESSION['id']),'">',protect($A['COUNT(eaIDAbonne)']),' abonnements</a>',
				'</li>',
				'<li>',
					'<a title="Voir les personnes qui me suivent" href="abonnes.php?id=',cryptage(protect($_SESSION['id'])),'">',protect($M['COUNT(estabonne.eaIDUser)']),' abonnés</a>',
				'</li>',
			'</ul>',
			'<H2 class="minititre">Tendances</H2>',
			'<ul id="hashtag">';
	while($R=mysqli_fetch_assoc($tendances)){
			 	echo '<li>',
						'<a title="Voir les messages" href="tendances.php?tags=',cryptage(protect($R['taID'])),'">',protect($R['taID']),'</a>',
					'</li>';
	}
    echo
 			'<li>',
				'<a title="Voir les messages" href="tendances.php">Toutes les tendances</a>',
			'</li>',
			'</ul>',
			'<H2 class="minititre">Suggestions</H2>',


			'<ul id=suggestions>';
	$count=0;
	while($S=mysqli_fetch_assoc($sugg)){
		$sql="SELECT count(eaDate) FROM estabonne WHERE eaIDAbonne= {$S['usID']} AND eaIDUser={$_SESSION['id']}";
		$estabo=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
		$EA=mysqli_fetch_assoc($estabo);
		if($EA['count(eaDate)']==0){
				echo
			'<li>',
				'<img class="darck" src="',protect(profilePicture($S['usID'] , $S['usAvecPhoto'])),'" alt="',protect($S['usNom']),'" />',
				'<label>',
					'<a title="Afficher ma bio" href="utilisateur.php?id=',cryptage(protect($S['usID'])),'">',
					protect($S['usPseudo']),
					'</a>',
					'  ',
					protect($S['usNom']),
				'</label>',
			'</li>';
			$count=$count+1;
		}
	}
	$diff=2-$count;
	$sql="SELECT * FROM users , estabonne
				WHERE eaIDAbonne = usID
				GROUP BY usID
				ORDER BY COUNT(eaIDUser) DESC
				LIMIT $diff";
	$topabo=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
	while($TA=mysqli_fetch_assoc($topabo)){
			$sql="SELECT count(eaDate) FROM estabonne WHERE eaIDAbonne= {$TA['usID']} AND eaIDUser={$_SESSION['id']}";
			$estabo=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
			$EA=mysqli_fetch_assoc($estabo);
			if($EA['count(eaDate)']==0){
				echo
				'<li>',
					'<img class="darck" src="',protect(profilePicture($TA['usID'] , $TA['usAvecPhoto'])),'" alt="',protect($TA['usNom']),'" />',
					'<label>',
						'<a title="Afficher ma bio" href="utilisateur.php?id=',cryptage(protect($TA['usID'])),'">',
						protect($TA['usPseudo']),
						'</a>',
						'  ',
						protect($TA['usNom']),
					'</label>',
				'</li>';
			}
	}
	echo
					'<li>',
						'<a href=suggestions.php>Plus de suggestions</a>',
					'</li>',
				'</ul>',	
			'</aside>';
}
/**
 * Fonction qui permet d'afficher le pied du code html.
**/
function KG_aff_pied(){
	echo 
	'<footer id="foot">',
				'<a class="lien" href=#>A propos</a>',
				'<a class="lien" href=#>Publicité</a>',
				'<a class="lien" href=#>Patati</a>',
				'<a class="lien" href=#>Aide</a>',
				'<a class="lien" href=#>Patata</a>',
				'<a class="lien" href=#>Stages</a>',
				'<a class="lien" href="https://www.pole-emploi.fr/accueil"/>Emplois</a>',
				'<a class="lien" href=#>Confidentialité</a>',
			'</footer>',
		'</div>';
}
/**
 * Fonction qui permet de compter et de varifier le nombre d'argument dans l'url.
 *
 * @param array    $GET   tableau de l'url
 * @param int    $nb    nombre d'argument que l'on veut avoir
**/
function tooManyArg($GET , $nb){
	if (count($GET)>$nb) {
		header('location: cuiteur.php');
	}
}
/**
 * Fonction qui permet d'afficher le profil d'un utilisateur
 *
 * @param bd     $bd    base de donnée
 * @param int    $id    id de l'utilisateur a afficher
**/
function KG_afficher_profil($bd, $id ){
	/*REQUETE POUR AVOIR LES INFOS DE LA TABLE USERS*/
	$sql= "SELECT * , COUNT(blID)
			FROM users , blablas
			WHERE users.usID = blablas.blIDAuteur
			AND usID='$id'";
	$res=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
	$T=mysqli_fetch_assoc($res);
	/*REQUETE POUR AVOIR LE NOMBRE D'ABONNEMENT*/
	$nbabonnement= "SELECT COUNT(eaIDAbonne)
			FROM estabonne
			WHERE eaIDUser='$id'";
	$abo=mysqli_query($bd, $nbabonnement) or KG_bd_erreur($bd, $nbabonnement);
	$A=mysqli_fetch_assoc($abo);
	//--------------------------------------------------------------------//
	/*REQUETE POUR AVOIR LE NOMBRE D'ABONNEE*/
	$nbabonne= "SELECT *, COUNT(estabonne.eaIDUser)
	FROM estabonne
	WHERE estabonne.eaIDAbonne ='$id'";
	$nbabo=mysqli_query($bd, $nbabonne) or KG_bd_erreur($bd, $nbabonne);
	$Abo=mysqli_fetch_assoc($nbabo);


	//test pour savoir si on est deja abonné
	$estabonner= "SELECT *
	FROM estabonne
	WHERE estabonne.eaIDAbonne ='$id'";
	$testabo=mysqli_query($bd, $estabonner) or KG_bd_erreur($bd, $estabonner);
	$estabo=false;
	while ($TESTABO=mysqli_fetch_assoc($testabo)) {
		if($TESTABO['eaIDUser']==$_SESSION['id']){
			$estabo=true;
			break;
		}
	}
	/*REQUETE POUR AVOIR LE NOMBRE DE MENTIONS*/
	$mentions= "SELECT COUNT(meIDBlabla)
	FROM mentions
	WHERE meIDUser ='$id'";
	$nbmentions=mysqli_query($bd, $mentions) or KG_bd_erreur($bd, $mentions);
	$M=mysqli_fetch_assoc($nbmentions);

	/*INITIALISATION DE VARIABLE*/
	$nbment=protect($M['COUNT(meIDBlabla)']);
	$nbabos=protect($Abo['COUNT(estabonne.eaIDUser)']);
	$abonnement=protect($A['COUNT(eaIDAbonne)']);
	$pp=profilePicture($id, $T['usAvecPhoto']);
	$pseudo=protect($T['usPseudo']);
	$ville='Non renseigné';
	if($T['usVille']!=''){
		$ville=protect($T['usVille']);
	}
	$web='Non renseigné';
	if($T['usWeb']!=''){
		$web=protect($T['usWeb']);
	}
	$nbblablas=protect($T['COUNT(blID)']);
	$monprofil=false;
	if($_SESSION['id']==$id){
		$monprofil=true;
	}
	$nom=protect($T['usNom']);
	/*AFFICHER LE CONTENU*/
	echo 
	'<div id=divconnexion>',
		'<div id=soustitre>',
			'<img src="',$pp,'" alt="',$pseudo,'">
			<p><a href=#>',$pseudo,'</a> ',$nom,'</p>',
			'<ul id=infoUtilisateur>',
			'<li><a href="blablas.php?id=',cryptage(protect($id)),'">',$nbblablas,' blablas</a> - </li>',
			'<li><a href="mentions.php?id=',cryptage(protect($id)),'">',$nbment,' mentions</a> - </li>',
			'<li><a href="abonnes.php?id=',cryptage(protect($id)),' ">',$nbabos,' abonnés</a> - </li>',
			'<li><a href="abonnement.php?id=',cryptage(protect($id)),' ">',$abonnement,' abonnement</a></li>',
			'</ul>',
		'</div>',
		

		'<p id=descriptionUtilisateur>',
			'<STRONG>Ville de résidence :</STRONG> ',$ville,'<br>',
			'<STRONG>Site web :</STRONG> ',$web,'<br>',
			'<STRONG>Date de naissance :</STRONG> ',KG_amj_clair($T['usDateNaissance']),'<br>',
		'</p>';
	if($estabo==true){
		echo
			'<form action=utilisateur.php method=post>',
			'<input type=submit name=desabonne value="se desabonner" size=10 />',
			'<input type=hidden name=id value=',protect($id),' />',
			'</form>';
	}else{
		if($monprofil==false){
			echo
			'<form action=utilisateur.php method=post>',
			'<input type=submit name=sabonner value="s\'abonner" size=10 />',
			'<input type=hidden name=id value=',protect($id),' >',
			'</form>';
		}
	}
	echo
	'</div>';
}
/**
 * Fonction qui permet d'afficher les blablas
 *
 * @param string    $css    le chemin d'acces au css utilisé pour le code html
 * @param string    $titre   le titre de l'onglet.
 */
function KG_aff_blablas($bd,$res, $nbblablas , $php , $nombrebl=1,$usID=''){
    if ($php=='blablas.php' ||  $php=='mentions.php') {
        /*REQUETE POUR AVOIR LES INFOS DE LA TABLE USERS*/
        $sql= "SELECT * , COUNT(blID)
                FROM users , blablas
                WHERE users.usID = blablas.blIDAuteur
                AND usID='$usID'";
        $info=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
        $T=mysqli_fetch_assoc($info);
        /*REQUETE POUR AVOIR LE NOMBRE D'ABONNEMENT*/
        $nbabonnement= "SELECT COUNT(eaIDAbonne)
                FROM estabonne
                WHERE eaIDUser='$usID'";
        $abo=mysqli_query($bd, $nbabonnement) or KG_bd_erreur($bd, $nbabonnement);
        $A=mysqli_fetch_assoc($abo);
        //--------------------------------------------------------------------//
        /*REQUETE POUR AVOIR LE NOMBRE D'ABONNEE*/
        $nbabonne= "SELECT *, COUNT(estabonne.eaIDUser)
        FROM estabonne
        WHERE estabonne.eaIDAbonne ='$usID'";
        $nbabo=mysqli_query($bd, $nbabonne) or KG_bd_erreur($bd, $nbabonne);
        $Abo=mysqli_fetch_assoc($nbabo);
        /*REQUETE POUR AVOIR LE NOMBRE DE MENTIONS*/
        $mentions= "SELECT COUNT(meIDBlabla)
        FROM mentions
        WHERE meIDUser ='$usID'";
        $nbmentions=mysqli_query($bd, $mentions) or KG_bd_erreur($bd, $mentions);
        $M=mysqli_fetch_assoc($nbmentions);

        /*INITIALISATION DE VARIABLE*/
        $nbbla=protect($T['COUNT(blID)']);
        $nbment=protect($M['COUNT(meIDBlabla)']);
        $nbabos=protect($Abo['COUNT(estabonne.eaIDUser)']);
        $abonnement=protect($A['COUNT(eaIDAbonne)']);
        $pp=profilePicture($usID, $T['usAvecPhoto']);
        $pseudo=protect($T['usPseudo']);
        $nom=protect($T['usNom']);
        echo
       '<div id=soustitre>',
            '<img src="',$pp,'" alt="',$pseudo,'">
            <p><a href=#>',$pseudo,'</a> ',$nom,'</p>',
            '<ul id=infoUtilisateur>',
            '<li><a href="blablas.php?id=',cryptage(protect($usID)),'">',$nbbla,' blablas</a> - </li>',
            '<li><a href="mentions.php?id=',cryptage(protect($usID)),'">',$nbment,' mentions</a> - </li>',
            '<li><a href="abonnes.php?id=',cryptage(protect($usID)),'">',$nbabos,' abonnés</a> - </li>',
            '<li><a href="abonnement.php?id=',cryptage(protect($usID)),'">',$abonnement,' abonnement</a></li>',
            '</ul>',
        '</div>';   
    }

    if ($nombrebl==0) {
       echo '<div id="blablavide">',
                '<p>Aucun blablas a afficher</p>',
            '</div>';
    }else{
        $count=1;
        $ul='cuiteur';
        if ($php=='blablas.php' || $php=='mentions.php'){
            $ul='blabla';
        }
        echo '<ul id="',$ul,'">';
        while($T=mysqli_fetch_assoc($res)){
        	$date1= new DateTime(date('Ymd H:i:s'));
			$date2= new DateTime($T['blDate'].' '.$T['blHeure']);
			$diff=DateDiff($date1,$date2);
            $id='';
            if($php=='blablas.php'){
                $id='&id='.cryptage(protect($T['autID']));
            }
            if ($count >= $nbblablas+1) {
                if($php=='mentions.php'){
                    $id='&id='.cryptage(protect($usID));
                }
                if($php=='tendances.php'){
                    $id='&tags='.cryptage(protect($usID));
                }
                echo 
                '<li id="dernierblabla">',
                    '<a href="',$php,'?blablas=',$nbblablas+4,$id,'#foot" id="Plusdeblablas">Plus de blablas</a>',
                '</li>';
                break;
            }else{
                $text=blablasTagAndMentionsLink($bd,protect($T['blTexte']));
                if ($T['autID']== $_SESSION['id']) {
                     if ($T['oriID']!= 0) {
                        echo
                        '<li class="lesblablas">',
                            '<img class="pp" alt="',protect($T['oriPseudo']),'" src="',protect(profilePicture($T['oriID'],$T['oriAvecPhoto'])),'"/>',
                            '<p><a href="utilisateur.php?id=',cryptage(protect($T['oriID'])),'">',protect($T['oriPseudo']),'</a> ',protect($T['oriNom']),', recuité par <a href="utilisateur.php?id=',cryptage(protect($T['autID'])),'">',protect($T['autPseudo']),'</a></p>',
                            $text,
                            '<p class="postTiming">Il y a ',$diff,'</p>',
                            '<a class="Repondre" href="cuiteur.php?supp=',cryptage(protect($T['blID'])),$id,'">Supprimer</a>',
                        '</li>';
                    }else{  
                        echo
                        '<li class="lesblablas">',
                            '<img class="pp" alt="',protect($T['autPseudo']),'" src="',protect(profilePicture($T['autID'],$T['autAvecPhoto'])),'"/>',
                            '<p><a href="utilisateur.php?id=',cryptage(protect($T['autID'])),'">',protect($T['autPseudo']),'</a> ',protect($T['autNom']),'</p>',
                            $text,
                            '<p class="postTiming">Il y a ',$diff,'</p>',
                            '<a class="Repondre" href="cuiteur.php?supp=',cryptage(protect($T['blID'])),$id,'">Supprimer</a>',
                        '</li>';
                    }
                }else{
                    if ($T['oriID']!= 0) {
                        echo
                        '<li class="lesblablas">',
                            '<img class="pp" alt="',protect($T['oriPseudo']),'" src="',protect(profilePicture($T['oriID'],$T['oriAvecPhoto'])),'"/>',
                            '<p><a href="utilisateur.php?id=',cryptage(protect($T['oriID'])),'">',protect($T['oriPseudo']),'</a> ',protect($T['oriNom']),', recuité par <a href="utilisateur.php?id=',cryptage(protect($T['autID'])),'">',protect($T['autPseudo']),'</a></p>',
                            $text,
                            '<p class="postTiming">Il y a ',$diff,'</p>',
                            '<a class="Recuiter" href="cuiteur.php?rec=',cryptage(protect($T['blID'])),$id,'">Recuiter</a>',
                            '<a class="Repondre" href="cuiteur.php?rep=',cryptage(protect($T['autPseudo'])),'">Répondre</a>',
                        '</li>';
                    }else{  
                        echo
                        '<li class="lesblablas">',
                            '<img class="pp" alt="',protect($T['autPseudo']),'" src="',protect(profilePicture($T['autID'],$T['autAvecPhoto'])),'"/>',
                            '<p><a href="utilisateur.php?id=',cryptage(protect($T['autID'])),'">',protect($T['autPseudo']),'</a> ',protect($T['autNom']),'</p>',
                            $text,
                            '<p class="postTiming">Il y a ',$diff,'</p>',
                            '<a class="Recuiter" href="cuiteur.php?rec=',cryptage(protect($T['blID'])),$id,'">Recuiter</a>',
                            '<a class="Repondre" href="cuiteur.php?rep=',cryptage(protect($T['autPseudo'])),'">Répondre</a>',
                        '</li>';
                    }
                }
            }
            $count++;
        }
        echo '</ul>';
    }   
}
/**
 * Fonction qui affiche les checkbox pour la list des utilisateur (s'abonner / se desabonner)
 *
 * @param bd     $bd    base de donnée
 * @param int    $id    id de l'utilisateur concerné
**/
function MLM_GK_checkbox($bd,$id){
	$sql="SELECT count(eaIDUser) FROM estabonne WHERE eaIDUser= {$_SESSION['id']} AND eaIDAbonne=$id";
	$checkbox=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
	$check=mysqli_fetch_assoc($checkbox);
	if ($check['count(eaIDUser)']==0) {
		echo
		'<p class=abodesabo>',
		'<input type=checkbox id=',$id,' name=',$id,' value=0 /><label for=',$id,'>S\'abonner</label>',
		'</p>';
	}else{
		echo
		'<p class=abodesabo>',
		'<input type=checkbox id=',$id,' name=',$id,' value=1 /><label for=',$id,'>Se desabonner</label>',
		'</p>';
	}
}
/**
 * Fonction qui affiche la liste des utilisateur que l'on souhaite
 *
 * @param bd     $bd    base de donnée
 * @param retour sql    $res    resultat de la requete sql
**/
function MLM_GK_aff_recherche($bd,$res,$php='',$nb=''){
	echo
	'<form action=# method=post>',
		'<ul>';
		$count=0;
		$arrayID=array();
		while ($RECH=mysqli_fetch_assoc($res)) {
			$pseudo=$RECH['usPseudo'];
			$nom=$RECH['usNom'];
			$id=$RECH['usID'];
			$photo=protect(profilePicture($RECH['usID'],$RECH['usAvecPhoto']));
			$lienprofil='utilisateur.php?id='.cryptage(protect($RECH['usID']));
			$lienblabla='blablas.php?id='.cryptage(protect($RECH['usID']));
			$lienabonnement='abonnement.php?id='.cryptage(protect($RECH['usID']));
			$lienabooné='abonnes.php?id='.cryptage(protect($RECH['usID']));
			$lienmentions='mentions.php?id='.cryptage(protect($RECH['usID']));
			//requete pour avoir nb blablas & nb mentions & nb abonnés & nb abonnement
			$bl= "SELECT * , COUNT(blID)
			FROM users , blablas
			WHERE users.usID = blablas.blIDAuteur
			AND usID='$id'";
			$nbbl=mysqli_query($bd, $bl) or KG_bd_erreur($bd, $bl);
			$T=mysqli_fetch_assoc($nbbl);
			/*REQUETE POUR AVOIR LE NOMBRE D'ABONNEMENT*/
			$nbabonnement= "SELECT COUNT(eaIDAbonne)
					FROM estabonne
					WHERE eaIDUser='$id'";
			$abo=mysqli_query($bd, $nbabonnement) or KG_bd_erreur($bd, $nbabonnement);
			$A=mysqli_fetch_assoc($abo);
			//--------------------------------------------------------------------//
			/*REQUETE POUR AVOIR LE NOMBRE D'ABONNEE*/
			$nbabonne= "SELECT COUNT(estabonne.eaIDUser)
			FROM estabonne
			WHERE estabonne.eaIDAbonne ='$id'";
			$nbabo=mysqli_query($bd, $nbabonne) or KG_bd_erreur($bd, $nbabonne);
			$Abo=mysqli_fetch_assoc($nbabo);
			/*REQUETE POUR AVOIR LE NOMBRE DE MENTIONS*/
			$mentions= "SELECT COUNT(meIDBlabla)
			FROM mentions
			WHERE meIDUser ='$id'";
			$nbmentions=mysqli_query($bd, $mentions) or KG_bd_erreur($bd, $mentions);
			$M=mysqli_fetch_assoc($nbmentions);
			$nbblablas=protect($T['COUNT(blID)']);
			$nbment=protect($M['COUNT(meIDBlabla)']);
			$nbabo=protect($Abo['COUNT(estabonne.eaIDUser)']);
			$nbabonnement=protect($A['COUNT(eaIDAbonne)']);

			if($php!='suggestions.php'){
			echo
			'<li class="resultatRecherche">',
				'<img class="pp" alt="',$nom,'" src="',$photo,'"/>',
                '<p><a href="',$lienprofil,'">',$pseudo,'</a> ',$nom,'</p>',
                '<ul>',
	            	'<li><a href="',$lienblabla,'">',$nbblablas,' blablas</a> - </li>',
					'<li><a href="',$lienmentions,'">',$nbment,' mentions</a> - </li>',
					'<li><a href="',$lienabooné,'">',$nbabo,' abonnés</a> - </li>',
					'<li><a href="',$lienabonnement,'">',$nbabonnement,' abonnement</a></li>',
				'</ul>';
				if($_SESSION['id']!=$RECH['usID']){
					MLM_GK_checkbox($bd,$RECH['usID'],$nom);
				}
			}else{
				$sql="SELECT count(eaDate) FROM estabonne WHERE eaIDAbonne= {$RECH['usID']} AND eaIDUser={$_SESSION['id']}";
				$estabo=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
				$EA=mysqli_fetch_assoc($estabo);
				if($EA['count(eaDate)']==0){
					echo
					'<li class="resultatRecherche">',
						'<img class="pp" alt="',$nom,'" src="',$photo,'"/>',
		                '<p><a href="',$lienprofil,'">',$pseudo,'</a> ',$nom,'</p>',
		                '<ul>',
			            	'<li><a href="',$lienblabla,'">',$nbblablas,' blablas</a> - </li>',
							'<li><a href="',$lienmentions,'">',$nbment,' mentions</a> - </li>',
							'<li><a href="',$lienabooné,'">',$nbabo,' abonnés</a> - </li>',
							'<li><a href="',$lienabonnement,'">',$nbabonnement,' abonnement</a></li>',
						'</ul>';
						if($_SESSION['id']!=$RECH['usID']){
							MLM_GK_checkbox($bd,$RECH['usID'],$nom);
						}
				}
				$arrayID[]=$RECH['usID'];
			}
			$count=$count+1;
		}
		if($php=='suggestions.php'){
			$sql="SELECT * FROM users , estabonne
						WHERE eaIDAbonne = usID
						GROUP BY usID
						ORDER BY COUNT(eaIDUser) DESC
						LIMIT 10";
			$topabo=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
			while($TA=mysqli_fetch_assoc($topabo)){
				if($count==$nb){
					break;
				}
				$pseudo=$TA['usPseudo'];
				$nom=$TA['usNom'];
				$id=$TA['usID'];
				$photo=protect(profilePicture($TA['usID'],$TA['usAvecPhoto']));
				$lienprofil='utilisateur.php?id='.cryptage(protect($TA['usID']));
				$lienblabla='blablas.php?id='.cryptage(protect($TA['usID']));
				$lienabonnement='abonnement.php?id='.cryptage(protect($TA['usID']));
				$lienabooné='abonnes.php?id='.cryptage(protect($TA['usID']));
				$lienmentions='mentions.php?id='.cryptage(protect($TA['usID']));
				//requete pour avoir nb blablas & nb mentions & nb abonnés & nb abonnement
				$bl= "SELECT * , COUNT(blID)
				FROM users , blablas
				WHERE users.usID = blablas.blIDAuteur
				AND usID='$id'";
				$nbbl=mysqli_query($bd, $bl) or KG_bd_erreur($bd, $bl);
				$T=mysqli_fetch_assoc($nbbl);
				/*REQUETE POUR AVOIR LE NOMBRE D'ABONNEMENT*/
				$nbabonnement= "SELECT COUNT(eaIDAbonne)
						FROM estabonne
						WHERE eaIDUser='$id'";
				$abo=mysqli_query($bd, $nbabonnement) or KG_bd_erreur($bd, $nbabonnement);
				$A=mysqli_fetch_assoc($abo);
				//--------------------------------------------------------------------//
				/*REQUETE POUR AVOIR LE NOMBRE D'ABONNEE*/
				$nbabonne= "SELECT COUNT(estabonne.eaIDUser)
				FROM estabonne
				WHERE estabonne.eaIDAbonne ='$id'";
				$nbabo=mysqli_query($bd, $nbabonne) or KG_bd_erreur($bd, $nbabonne);
				$Abo=mysqli_fetch_assoc($nbabo);
				/*REQUETE POUR AVOIR LE NOMBRE DE MENTIONS*/
				$mentions= "SELECT COUNT(meIDBlabla)
				FROM mentions
				WHERE meIDUser ='$id'";
				$nbmentions=mysqli_query($bd, $mentions) or KG_bd_erreur($bd, $mentions);
				$M=mysqli_fetch_assoc($nbmentions);
				$nbblablas=protect($T['COUNT(blID)']);
				$nbment=protect($M['COUNT(meIDBlabla)']);
				$nbabo=protect($Abo['COUNT(estabonne.eaIDUser)']);
				$nbabonnement=protect($A['COUNT(eaIDAbonne)']);

				$sql="SELECT count(eaDate) FROM estabonne WHERE eaIDAbonne= {$TA['usID']} AND eaIDUser={$_SESSION['id']}";
				$estabo=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
				$EA=mysqli_fetch_assoc($estabo);
				if($EA['count(eaDate)']==0 && !in_array($TA['usID'],$arrayID) && $TA['usID']!=$_SESSION['id']){
					$count=$count+1;
					echo
					'<li class="resultatRecherche">',
						'<img class="pp" alt="',$nom,'" src="',$photo,'"/>',
		                '<p><a href="',$lienprofil,'">',$pseudo,'</a> ',$nom,'</p>',
		                '<ul>',
			            	'<li><a href="',$lienblabla,'">',$nbblablas,' blablas</a> - </li>',
							'<li><a href="',$lienmentions,'">',$nbment,' mentions</a> - </li>',
							'<li><a href="',$lienabooné,'">',$nbabo,' abonnés</a> - </li>',
							'<li><a href="',$lienabonnement,'">',$nbabonnement,' abonnement</a></li>',
						'</ul>';
						if($_SESSION['id']!=$TA['usID']){
							MLM_GK_checkbox($bd,$TA['usID'],$nom);
						}
				}
			}
			

		}
	echo
		'</li>',
		'</ul>',
		'<input type=submit name=btnValider value=Valider />',
	'</form>';
}
/**
 * Fonction qui permet de gerer les recuit et les suppression de cuit.
 *
 * @param bd    $bd     connexion a la base
**/
function rec_sup($bd){
	//test pour savoir si le blablas que l'utilisateur veut supprimer est a bien a lui
	if(isset($_GET['supp'])){
		tooManyArg($_GET, 2);
		$blid=mysqli_escape_string($bd,decryptage($_GET['supp']));
		$sql="SELECT * FROM blablas WHERE blID=$blid";
		$test=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $test);
		$UT=mysqli_fetch_assoc($test);
		if ($_SESSION['id']!=$UT['blIDAuteur']) {
		   header('location: cuiteur.php');
		}
		$sql="DELETE FROM blablas WHERE blID=$blid";
		$del=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
		$text=mysqli_escape_string($bd,$UT['blTexte']);
		//recuperer toute les mentiions du blablas
	   	$mentions=all_mentions($text);
	    foreach ($mentions as $key) {
	      $key=mysqli_escape_string($bd,$key);
	      $sql="SELECT usID, count(usID) FROM users WHERE usPseudo = '$key'";
	      $users=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
	      $US=mysqli_fetch_assoc($users);
	      if($US['count(usID)']!=0){
		      $sql="DELETE FROM mentions WHERE meIDUser={$US['usID']}
		      		AND meIDBlabla=$blid";
		      $deleteMentions=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
	  		}
	  	}
	    //recup toute les tags du blablas
	    $tags=all_tags($text);
	    foreach ($tags as $key) {
	      $key=mysqli_escape_string($bd,$key);
	      $sql="DELETE FROM tags WHERE taID='$key' AND taIDBlabla=$blid";
	      $deleteTags=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
	    }
		header('location: cuiteur.php');
	}
	//test pour verification du recuite
	if(isset($_GET['rec'])){
		$blid=mysqli_escape_string($bd,decryptage($_GET['rec']));
	    tooManyArg($_GET, 2);
		$sql="SELECT * FROM blablas WHERE blID=$blid";
		$test=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $test);
		$REC=mysqli_fetch_assoc($test);
		$auteur=$REC['blIDOriginal'];
		$text=mysqli_escape_string($bd,$REC['blTexte']);
		$original=$REC['blIDAuteur'];
		$date= date('Y').date('m').date('d');
		$heure= date('H:i:s');
	    if($_SESSION['id']== $original || $_SESSION['id']==$auteur){
	    	header('location: cuiteur.php');
	  	}
	    $sql="INSERT INTO blablas (blIDAuteur,blDate,blHeure,blTexte,blIDOriginal)
	        VALUES ({$_SESSION['id']},$date,'$heure','$text',$original)";
	    $poster=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
	    $blID=mysqli_insert_id($bd);
	    //recuperer toute les mentiions du blablas
	    $mentions=all_mentions($text);
        foreach ($mentions as $key) {
            $key=mysqli_escape_string($bd,$key);
      		$sql="SELECT usID, count(usID) FROM users WHERE usPseudo = '$key'";
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
/**
 * Fonction qui permet de gerer les reponses a un blablas 
 * @param bd    $bd     connexion a la base
**/
function repondre($bd){
	if (isset($_GET['rep'])) {
		$pseudo=mysqli_escape_string($bd,decryptage($_GET['rep']));
		$sql="SELECT COUNT(usID),usPseudo FROM users WHERE usPseudo='$pseudo'";
	  	$rep=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
	  	$R=mysqli_fetch_assoc($rep);
	  	if($R['COUNT(usID)']==0){
	  		header('location: cuiteur.php');
	  	}
	  	return $R['usPseudo'];
	}
	return '';
}
/**
 * Fonction qui permet de renvoyer toute les mentions d'un blablas sous forme de tableau
 * @param string   $blablas  equivalent de blText de la base de donnée
**/
function all_mentions($blablas){
	$res=preg_match_all("/[@]([\p{L}w\.]+)/", $blablas , $matches);
	$empty_array=array();
	foreach ($matches[1] as $key) {
		if (!in_array($key,$empty_array)) {
			$empty_array[]=$key;
		}
	}
	return $empty_array;
}
/**
 * Fonction qui permet de renvoyer tout les tags d'un blablas sous forme de tableau
 * @param string   $blablas  equivalent de blText de la base de donnée
**/
function all_tags($blablas){
	$res=preg_match_all("/[#]([\p{L}?w\.]+)/", $blablas , $matches);
	$empty_array=array();
	foreach ($matches[1] as $key) {
		if (!in_array($key,$empty_array)) {
			$empty_array[]=$key;
		}
	}
	return $empty_array;
}
/**
 * Fonction qui permet de renvoyer un blablas avec en transformant les mentions et tags en lien
 * @param bd 	   $bd       connexion a la base de donnée
 * @param string   $blablas  equivalent de blText de la base de donnée
**/
function blablasTagAndMentionsLink($bd,$blablas){
	//gereration des liens pours le mentions
	$mentions=all_mentions($blablas);
	$res=$blablas;
	foreach ($mentions as $key) {
		$sql="SELECT usID, count(usID) FROM users WHERE usPseudo = '$key'";
  		$users=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
  		$US=mysqli_fetch_assoc($users);
      	if($US['count(usID)']!=0){
			$replace='@<a href="utilisateur.php?id='.cryptage(protect($US['usID'])).'">'.protect($key).'</a>';
			$res=str_replace('@'.$key, $replace, $res);
		}
	}
	//gereration des liens pour les tags
	$tags=all_tags($blablas);
	foreach ($tags as $key) {
		$replace='#<a href="tendances.php?tags='.cryptage(protect($key)).'">'.protect($key).'</a>';
		$res=str_replace('#'.$key, $replace, $res);
	}
	return $res;
	
	
}






?>