<?php
//Connection BBD
try {
    $dbh = new PDO('pgsql:dbname=statistique;host=127.0.0.1;port=5432', 'postgres', 'Isen2018');
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}

//Gestion de l'image et des couleurs
header("Content-type: image/png");
$largeurImage = 500;
$hauteurImage = 300;

$image = imagecreate($largeurImage, $hauteurImage);

$lightgray = imagecolorallocate($image, 100, 100,100);
$green = imagecolorallocate($image, 0, 255, 0);
$red = imagecolorallocate($image, 255, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);

//Recuperation du nombre de note(on suppose que tous les etudiants ont le meme nombre de note

$widthLine = $largeurImage/12;


//E1

$positionCrayonX = $largeurImage/12/2;
$positionCrayonY = $hauteurImage/2;
$valeurPrecedente = 0 ;
$premiereValeur = true;

$action1 = $dbh->query('SELECT valeur FROM statistique where action = \'Als\'');
foreach ($action1 as $data){
    $action1 += $data['valeur'];

    if ($premiereValeur) {
        $valeurPrecedente = $data['valeur'];
        $premiereValeur = false ;
        continue;
    }
    imageline($image, $positionCrayonX, $positionCrayonY-$valeurPrecedente*2, $positionCrayonX + $widthLine, $positionCrayonY-$data['valeur']*2, $white);

    $valeurPrecedente = $data['valeur'];
    $positionCrayonX += $widthLine;
}


//E2

$positionCrayonX = $largeurImage/12/2;
$positionCrayonY = $hauteurImage/2;
$valeurPrecedente = 0 ;
$premiereValeur = true;

$action2 = $dbh->query('SELECT valeur FROM statistique where action = \'For\'');
foreach ($action2 as $data){
    $action2 += $data['valeur'];

    if ($premiereValeur) {
        $valeurPrecedente = $data['valeur'];
        $premiereValeur = false ;
        continue;
    }
    imageline($image, $positionCrayonX, $positionCrayonY-$valeurPrecedente*2, $positionCrayonX + $widthLine, $positionCrayonY-$data['valeur']*2, $red);

    $valeurPrecedente = $data['valeur'];
    $positionCrayonX += $widthLine;
}


imagestring($image, 20, 50, 10, "Cours des actions Als et For en 2010", $green);
imagestring($image, 20, 50, 280, "For", $red);
imagestring($image, 20, 100, 280, "Als", $white);


imagepng($image);

?>