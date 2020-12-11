<?php
//fonction afffichage entete
function KG_aff_entete(){
	echo 	'<div id="blocpage">',
			'<header>',
			'<h1>Connectez-vous</h1>',
			'<img id="lignehead" alt="lignehead" src="../images/trait.png"/>',
			'<p id="texteSoustitre">Pour vous connecter à Cuiteur, il faut vous identifier:</p>',
			'</header>';
}
function KG_aff_entete_inscription(){
	echo 	'<div id="blocpage">',
			'<header>',
			'<h1>Inscription</h1>',
			'<img id="lignehead" alt="lignehead" src="../images/trait.png"/>',
			'</header>';
}
//fonction affciher aside

function KG_aff_infos(){
	echo 
		'<aside>',

		'</aside>';
}
//fonction pied de page
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
//fonciton afficher la connection
function KG_aff_connection(){
	echo '<div id="divconnection">',
			'<form method="post" action="index.html">',
	   			'<p id="saisiePseudo">',
	        		'<label for="pseudo">Pseudo : </label>',
					'<input type="text" name="pseudo" id="pseudo" placeholder="Ex : Kebir le gremelins" size="15" maxlength="20" />',
				'</p>',
				'<p id="saisieMdp">',
					'<label for="pass">Mot de passe : </label>',
       				'<input type="password" placeholder="********" name="pass" id="pass" size="15"/>',
				 '</p>',
				 '<input type="submit" id="boutton" value="Connection"  />',
			'</form>',
			'<p id="texteBaspage">Pas encore de compte ? <a href="inscription.php">Inscrivez-vous</a> sans plus tarder!<br><br>Vous hésitez à vous inscrire ? Laissez-vous séduire par une <a href="../html/presentation.html">présentation</a> des possibilités de Cuiteur.</p>',
			'</div>';
}
// fonciton afficher inscription
function KG_aff_inscription(){
	$mois = array(1=>'Janvier', 2=>'Février', 3=>'Mars',
              4=>'Avril', 5=>'Mai', 6=>'Juin',
          7=>'Juillet', 8=>'Aout', 9=>'Septembre',
          10=>'Octobre', 11=>'Novembre', 12=>'Decembre');
	echo '<div id="divconnection">',
	'<p id="texteinscription">Pour vous inscrire, il suffit de :</p>',
			'<form method="POST" action="inscription_5.php">',
				'<table>',
					'<tr>',
						'<td>Choisir un pseudo</td>',
						'<td><input type="text" name="txtPseudo" size="20"/></td>',
					'</tr>',
					
					'<tr >',
						'<td>Choisir un mot de passe</td>',
						'<td><input type="password" name="txtPasse" size="20" /></td>',
					'</tr >',
					'<tr >',
						'<td>Répéter le mot de passe</td>',
						'<td><input type="password" name="txtVerif" size="20"/></td>',
					'</tr>',
					'<tr >',
						'<td>Indiquer votre nom</td>',
						'<td><input type="text" name="txtNom" size="40"/></td>',
					'</tr>',
					'<tr >',
						'<td>Donner une adresse mail</td>',
						'<td><input type="text" name="txtMail" size="40"/></td>',
					'</tr>',
					'<tr >',
						'<td>Votre date de naissance</td>',
						'<td>',
							'<select name="selNais_j"/>';
	for($jours= 1; $jours<=31; $jours++)
	{
	  echo '<option value="'.$jours.'"">'.$jours.'</option>';
	}
    echo
                    '</select>',
                    '<select name="selNais_m"/>';
     foreach($mois as $key => $moi)
	{
	  echo '<option value="' . $key . '">' . $moi . '</option>';
	}       
    echo
                   ' </select>',
                   ' <select name="selNais_a"/>';
    for($annee= 2019; $annee>=1900; $annee--)
	{
	  echo '<option value="'.$annee.'"">'.$annee.'</option>';
	}
	echo
                    		'</select>',
						'</td>',
					'</tr>',
					'<tr>',
						'<td></td>',
						'<td align="right"><input id="inscrire" type="submit" name="btnValider" value="Je m\'inscris"  /></td>',
					'</tr>',
				'</table>',
			'</form>',
			'</div>';
}







?>