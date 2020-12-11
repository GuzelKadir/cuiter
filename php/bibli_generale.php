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
/**
 * Fonction qui permet de securisé une chaine pour evité tout type de piratage.
 *
 * @param string    $str    la chaine à examiner
 */
function protect($str){
    $res = htmlentities($str, ENT_QUOTES, 'UTF-8');
    return $res;
}
/**
 * Fonction qui permet d'afficher l'entete du code html. Avec des option parametrable.
 *
 * @param string    $css    le chemin d'acces au css utilisé pour le code html
 * @param string    $titre   le titre de l'onglet.
 */
function KG_aff_debut($css , $titre){
    echo 
        '<!doctype html>',
            '<html lang="fr">',
                '<head>',
                    '<meta charset="utf-8">',
                    '<link rel="stylesheet" type="text/css" href="',$css,'">',
                    '<title>',$titre,'</title>',
                '</head>',
                '<body>';
}
/**
 * Fonction qui permet d'afficher la fin du code html.
**/
function KG_aff_fin(){
    echo
            '</body>',
        '</html>';
}
/**
 * Fonction qui permet de transformer une date de la forme aaaammjj sous la forme jours  mois Année.
 *
 * @param int    $date  la date a transformer
**/
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
/**
 * Fonction qui permet de retourner la durée entre deux date
 *
 * @param DateTime    $date1  premiere date
 * @param DateTime    $date2  deuxieme date
 * @return string     $str    la differance des deux dates
**/
function DateDiff($date1,$date2){
    $diff=$date1->diff($date2);
    if($diff->y!=0){
        return $diff->y.' ans';
    }
    if($diff->m!=0){
        return $diff->m.' mois';
    }
    if($diff->d!=0){
        return $diff->d.' jours';
    }
    if($diff->h!=0){
        return $diff->h.' heures';
    }
    if($diff->i!=0){
        return $diff->i.' minutes';
    }
    if($diff->s!=0){
        return $diff->s.' secondes';
    }
    if($diff->s==NULL){
        return '0 secondes';
    }
}
/**
 * Fonction qui permet de transformer les mois de chiffre en lettre.
 *
 * @param string    $mois  le mois à transformer 
 * @return string   $mois  le mois une fois transformer
 */
function moisCtoL($mois){
    $moisC = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
    $moisL = array('janvier', 'fevrier', 'mars', 'avril', 'mai', 'juin', 'juillet', 'aout', 'septembre', 'octobre', 'novembre', 'decembre');
    for ($i=0; $i < 12 ; $i++) { 
    $mois = str_replace($moisC, $moisL, $mois);
    }
    return $mois;
}
/**
 * Fonction qui permet d'afficher l'heure d'une facon clair
 *
 * @param string    $heure   l'heure à transformer
 * @return string   $heure   l'heure une fois transformer
 */
function KG_heure_clair($heure){
    $h=substr($heure, 0,2);
    $m=substr($heure, 3,2);
    $heure=$h.'h';
    $heure.=$m.'mn';
    return $heure;
}
/**
 * Fonction qui permet de determiner si un utilisateur a enregistrer un photo de profil , si oui il retourne son chemin d'acces.
 *
 * @param int    $usID    l'ID de l'utilisateur concerné.
 * @param int    $usAvecPhoto   variable qui permet de savoir si il possede un photo dans la BD
 * @return string   $photo   retourne le chemin d'acces a la photo de profil de l'utilisateur
 */
function profilePicture($usID , $usAvecPhoto){
    if($usAvecPhoto == 1){
        $photo='../upload/';
        $photo.=$usID.'.jpg';
        return $photo;
    }
    $photo='../images/anonyme.jpg';
    return $photo;
}
/**
 * Fonction qui permet de determiner jusuqu'a combien de blablas va afficher la page.
 *
 * @param int $blablas nombre de blablas à afficher dans la page.
 * @return int $blablas le nombre de blalbas a afficher pour la page suivante
 */
function blablaTest($blablas){
    if ($blablas==false) {
        $blablas=4;
    }
    if($blablas<4){
        $blablas=4;
    }
    return $blablas;
}
/**
 * Fonction qui retourne le code html d'une ligne d'un tableau
 *
 * @param string    $argGauche   argument de la premiere cellule.
 * @param string    $argDroite   argument de la deuxieme cellule.
 */
function KG_html_table_ligne($argGauche , $argDroite){
    $ligne='<tr><td>'.$argGauche.'</td><td>'.$argDroite.'</td></tr>';
    return $ligne;
}
/**
 * Fonction qui retourne la code html d'une zone 
 *
 * @param string    $typeZone   type de la zone.
 * @param string    $nomZone    nom de la zone.
 * @param string    $valeurZone valeur de la zone.
 * @param int       $tailleZone taille de la zone.
 */
function KG_html_form_input($typeZone , $nomZone , $valeurZone , $tailleZone=0){
    $input='<input  type="'.$typeZone.'" name="'.$nomZone.'" value="'.$valeurZone.'" size="'.$tailleZone.'"/>';
    return $input;
}
/**
 * Fonction qui retourne la code html de la zone date
 *
 * @param string    $nomZone    nom de la zone.
 * @param string    $nbAnneeAff nombre d'annee a afficher.
 * @param int       $jPre       jours predefinis.
 * @param int       $mPre       mois predefinis.
 * @param int       $aPre       annee predefinis.
 */
function KG_html_form_date($nomZone , $nbAnneeAff , $jPre=0 , $mPre=0 , $aPre=0){
    //JOURS
    $nomJ=$nomZone.'_j';
    $optionJour='';
    if($jPre == 0){
        $jPre=date('d');
    }
    for($jours= 1; $jours<=31; $jours++)
    {
        if($jours == $jPre){
            $optionJour=$optionJour.'<option value="'.$jours.'" selected>'.$jours.'</option>';
        }else{
            $optionJour=$optionJour.'<option value="'.$jours.'">'.$jours.'</option>';
        }
    }
    $selectJ='<select name="'.$nomJ.'">'.$optionJour.'</select>';

    //MOIS
    $nomM=$nomZone.'_m';
    $optionMois='';
    if($mPre==0){
        $mPre=date('n');
    }
    $mois = array(1=>'Janvier', 2=>'Février', 3=>'Mars',
              4=>'Avril', 5=>'Mai', 6=>'Juin',
          7=>'Juillet', 8=>'Aout', 9=>'Septembre',
          10=>'Octobre', 11=>'Novembre', 12=>'Decembre');
    foreach($mois as $key => $moi)
    {
        if($key == $mPre){
            $optionMois=$optionMois.'<option value="'.$key.'" selected>'.$moi.'</option>';
        }else{
            $optionMois=$optionMois.'<option value="'.$key.'">'.$moi.'</option>';
        }
    }
    $selectM='<select name="'.$nomM.'">'.$optionMois.'</select>';

    //ANNEE
    $optionAnnee='';
    $nomA= $nomZone.'_a';
    if($aPre==0){
        $aPre=date('Y');
    }
    $aff= date('Y')-$nbAnneeAff;
    for($annee= date('Y'); $annee>=$aff; $annee--)
    {
        if($annee == $aPre){
            $optionAnnee=$optionAnnee.'<option value="'.$annee.'" selected>'.$annee.'</option>';
        }else{
            $optionAnnee=$optionAnnee.'<option value="'.$annee.'">'.$annee.'</option>';
        }
    }
    $selectA='<select name="'.$nomA.'">'.$optionAnnee.'</select>';
    $selectALL=$selectJ.$selectM.$selectA;
    return $selectALL;
}
/**
 * Fonction qui quitte une session.
 *
 */
function KG_session_exit(){
    session_unset();
    session_destroy();
    $cookieParams = session_get_cookie_params();
    setcookie(session_name(),
            '',
            time() - 86400,
            $cookieParams['path'],
            $cookieParams['domain'],
            $cookieParams['secure'],
            $cookieParams['httponly']);
    header('location: ../index.php');
}
/**
 * Fonction qui vérifie si un utilisateur est connecté.
 *
 */
function KG_verifie_authentification(){
    if (!isset($_SESSION['id']) || !isset($_SESSION['pseudo'])) {
        KG_session_exit();
    }
}
/**
 * Fonction qui retourne une valeur crypter pour securiter les liens
 *
 * @param string    $texte   la variable a crypter
 * @return  string  $str     retour de la varuable crypter
 */
function cryptage($texte){
    $plaintext = $texte;
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $key = 'toto';
    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
    $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
    return urlencode($ciphertext);
}
/**
 * Fonction qui retourne une valeur decrypter pour utiliser les valeurs passer en parametres dans les liens (method $_GET)
 *
 * @param   string  $texte   la variable a decrypter
 * @return  string  $str     retour de la varuable decrypter
 */
function decryptage($ciphertext){
    $c = base64_decode($ciphertext);
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len=32);
    $ciphertext_raw = substr($c, $ivlen+$sha2len);
    $key = 'toto';
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
    if (hash_equals($hmac, $calcmac)){
        return $original_plaintext;
    }
    header('location: cuiteur.php');
}
?>