<?php 
    define('BS_SERVER','localhost');// nom d'hôte ou adresse IP du serveur MySQL
    define('BS_DB','cuiteur_bd'); // nom de la base sur le serveur MySQL
    define('BS_USER','cuiteur_userl'); // nom de l'utilisateur de la base
    define('BS_PASS','cuiteur_passl'); // mot de passe de l'utilisateur de la base

//____________________________________________________________________________
/** 
 *	Ouverture de la connexion à la base de données
 *
 *	@return objet 	connecteur à la base de données
 */
function KG_bd_connect() {
    $conn = mysqli_connect(BS_SERVER, BS_USER, BS_PASS, BS_DB);
    if ($conn !== FALSE) {
        //mysqli_set_charset() définit le jeu de caractères par défaut à utiliser lors de l'envoi
        //de données depuis et vers le serveur de base de données.
        mysqli_set_charset($conn, 'utf8') 
        or KG_bd_erreurExit('<h4>Erreur lors du chargement du jeu de caractères utf8</h4>');
        return $conn;     // ===> Sortie connexion OK
    }
    // Erreur de connexion
    // Collecte des informations facilitant le debugage
    $msg = '<h4>Erreur de connexion base MySQL</h4>'
            .'<div style="margin: 20px auto; width: 350px;">'
            .'BD_SERVER : '. BS_SERVER
            .'<br>BS_USER : '. BS_USER
            .'<br>BS_PASS : '. BS_PASS
            .'<br>BS_DB : '. BS_DB
            .'<p>Erreur MySQL numéro : '.mysqli_connect_errno()
            .'<br>'.htmlentities(mysqli_connect_error(), ENT_QUOTES, 'ISO-8859-1')  
            //appel de htmlentities() pour que les éventuels accents s'affiche correctement
            .'</div>';
    KG_bd_erreur_exit($msg);
}

//____________________________________________________________________________
/**
 * Arrêt du script si erreur base de données 
 *
 * Affichage d'un message d'erreur, puis arrêt du script
 * Fonction appelée quand une erreur 'base de données' se produit :
 * 		- lors de la phase de connexion au serveur MySQL
 *		- ou indirectement lorsque l'envoi d'une requête échoue
 *
 * @param string	$msg	Message d'erreur à afficher
 */
function KG_bd_erreur_exit($msg) {
    ob_end_clean();	// Supression de tout ce qui a pu être déja généré

    echo    '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>',
            'Erreur base de données</title>',
            '<style>table{border-collapse: collapse;}td{border: 1px solid black;padding: 4px 10px;}</style>',
            '</head><body>',
            $msg,
            '</body></html>';
    exit(1);
}


//____________________________________________________________________________
/**
 * Gestion d'une erreur de requête à la base de données.
 *
 * A appeler impérativement quand un appel de mysqli_query() échoue 
 * Appelle la fonction xx_bd_erreurExit() qui affiche un message d'erreur puis termine le script
 *
 * @param objet		$bd		Connecteur sur la bd ouverte
 * @param string	$sql	requête SQL provoquant l'erreur
 */
function KG_bd_erreur($bd, $sql) {
    $errNum = mysqli_errno($bd);
    $errTxt = mysqli_error($bd);

    // Collecte des informations facilitant le debugage
    $msg =  '<h4>Erreur de requête</h4>'
            ."<pre><b>Erreur mysql :</b> $errNum"
            ."<br> $errTxt"
            ."<br><br><b>Requête :</b><br> $sql"
            .'<br><br><b>Pile des appels de fonction</b></pre>';

    // Récupération de la pile des appels de fonction
    $msg .= '<table>'
            .'<tr><td>Fonction</td><td>Appelée ligne</td>'
            .'<td>Fichier</td></tr>';

    $appels = debug_backtrace();
    for ($i = 0, $iMax = count($appels); $i < $iMax; $i++) {
        $msg .= '<tr style="text-align: center;"><td>'
                .$appels[$i]['function'].'</td><td>'
                .$appels[$i]['line'].'</td><td>'
                .$appels[$i]['file'].'</td></tr>';
    }

    $msg .= '</table>';

    KG_bd_erreur_exit($msg);	// => ARRET DU SCRIPT
}

//fonction de protection chaine 
function protect($str){
    $res = htmlentities($str, ENT_QUOTES, 'UTF-8');
    return $res;
}
//fonctions debut et fin du html
function KG_aff_debut($css){
    echo 
        '<!doctype html>',
            '<html lang="fr"',
                '<head>',
                    '<meta charset="utf-8">',
                    '<link rel="stylesheet" type="text/css" href="',$css,'">',
                '</head>',
                '<body>';
}
function KG_aff_fin(){
    echo
            '</body>',
        '</html>';
}
//fonction naissance clair
function KG_amj_clair($date){
    $annee= substr($date, 0 , 4);
    $mois=substr($date, 4,2);
    $jours=substr($date, -2,2);
    $mois=moisCtoL($mois);
    $blabla=$jours . ' '; 
    $blabla.=$mois . ' ';
    $blabla.=$annee;
    echo $blabla;
}
//fonction mois chiffre --> lettre
function moisCtoL($mois){
    $moisC = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
    $moisL = array('janvier', 'fevrier', 'mars', 'avril', 'mai', 'juin', 'juillet', 'aout', 'septembre', 'octobre', 'novembre', 'decembre');
    for ($i=0; $i < 12 ; $i++) { 
    $mois = str_replace($moisC, $moisL, $mois);
    }
    return $mois;
}
//fonction heure clair
function KG_heure_clair($heure){
    $h=substr($heure, 0,2);
    $m=substr($heure, 3,2);
    $s=substr($heure, 6,2);
    $heure=$h.'h';
    $heure.=$m.'mn';
    if($s!='00'){
      $heure.=$s.'s';  
    }
    return $heure;
}
//fonction pour savoir si il a une photo de profil
function profilePicture($usID , $intPhoto){
    if($intPhoto == 1){
        $photo='../upload/';
        $photo.=$usID.'.jpg';
        return $photo;
    }
    $photo='../images/anonyme.jpg';
    return $photo;
}
//fonction qui test la variable blabla
function blablaTest($blablas){
    if ($blablas==false) {
        $blablas=4;
    }
    if($blablas<4){
        $blablas=4;
    }
    return $blablas;
}
//fonciton afficher blablas
function KG_aff_blablas($res , $enr , $bd , $nbblablas , $php , $id , $id2){
    $count=1;
    $fin=false;
    $sql='SELECT usPseudo, usNom , usAvecPhoto
                FROM users , mentions , estabonne
                WHERE users.usID='.$id2;
    $utilisateur=mysqli_query($bd, $sql) or KG_bd_erreur($bd, $sql);
    $K=mysqli_fetch_assoc($utilisateur);

    echo '<ul id="blabla">';
    while($R=mysqli_fetch_assoc($res)){
        if (($count==1 && $php=="blablas.php")|| $php=="utilisateur.php") {
            echo 
            '<li class="lesblablas" id="infoblablas">',
                    '<img class="pp" alt="',protect($K['usPseudo']),'" src="',protect(profilePicture($id2,$K['usAvecPhoto'])),'"/>',
                    '<p class="ligneblabla"><a href="utilisateur.php?id=',$id,'&id2=',$id2,'">',protect($K['usPseudo']),'</a> ',protect($K['usNom']),'</p>',       
            '</li>';
            if ($php=="utilisateur.php") {
                break;
            }
        }
        if ($count >= $nbblablas+1) {
            echo 
            '<li id="dernierblabla">',
                '<a href="',$php,'?id=',$id,'&id2=',$id2,'&blablas=',$nbblablas+4,'#foot" id="Plusdeblablas">Plus de blablas</a>',
            '</li>';
            break;
        }else{
            if (isRecuit($R['blIDOriginal'],$R['usID'])==true) {
                $sql1 = 'SELECT usPseudo , usNom, usID , usAvecPhoto
                        FROM users
                        WHERE usID = '.$R['blIDOriginal'];
                $enr=mysqli_query($bd, $sql1) or KG_bd_erreur($bd, $sql1);
                $T=mysqli_fetch_assoc($enr);
                echo
                '<li class="lesblablas">',
                    '<img class="pp" alt="',protect($T['usPseudo']),'" src="',protect(profilePicture($T['usID'],$T['usAvecPhoto'])),'"/>',
                    '<p class="ligneblabla"><a href="utilisateur.php?id=',$R['usID'],'&id2=',$id2,'">',protect($T['usPseudo']),'</a> ',protect($T['usNom']),', recuité par <a href="utilisateur.php?id=',$R['usID'],'">',protect($R['usPseudo']),'</a></p>',
                    protect($R['blTexte']),
                    '<p class="postTiming">',KG_amj_clair($R['blDate']),' à ',KG_heure_clair($R['blHeure']),'</p>',
                    '<a class="Recuiter" href="index.html">Recuiter</a>',
                    '<a class="Repondre" href="index.html">Répondre</a>',
                '</li>';
            }else{
                echo
                '<li class="lesblablas">',
                    '<img class="pp" alt="',protect($R['usPseudo']),'" src="',protect(profilePicture($R['usID'],$R['usAvecPhoto'])),'"/>',
                    '<p class="ligneblabla"><a href="utilisateur.php?id=',$id,'&id2=',$R['usID'],'">',protect($R['usPseudo']),'</a> ',protect($R['usNom']),'</p>',
                    protect($R['blTexte']),
                    '<p class="postTiming">',KG_amj_clair($R['blDate']),' à ',KG_heure_clair($R['blHeure']),'</p>',
                    '<a class="Recuiter" href="index.html">Recuiter</a>',
                    '<a class="Repondre" href="index.html">Répondre</a>',
                '</li>';
            }
        }
        $count++;
    }
    echo '</ul>';
}







?>