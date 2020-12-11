<?php
ob_start();

require_once 'bibli_generale.php';

//debut du html
KG_aff_debut('#','inscription 1');
echo '<h1>Réception du formulaire<br>Inscription utilisateur</h1>';
foreach( $_POST as $cle=>$value )
{
   echo 'Zone ',$cle,' = ',protect($value),'<br>';
}
//fin du html
KG_aff_fin();
exit;
?>