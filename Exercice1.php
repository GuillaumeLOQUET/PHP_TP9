<?php
 //Connection BBD
try {
    $dbh = new PDO('pgsql:dbname=notes;host=127.0.0.1;port=5432', 'postgres', 'Isen2018');
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}

//Gestion de l'image et des couleurs
header("Content-type: image/png");
$largeurImage = 1000;
$hauteurImage = 300;

$image = imagecreate($largeurImage, $hauteurImage);

$lightgray = imagecolorallocate($image, 100, 100,100);
$black = imagecolorallocate($image, 0, 0, 0);
$blue = imagecolorallocate($image, 0, 0, 255);
$white = imagecolorallocate($image, 255, 255, 255);

//Recuperation du nombre de note(on suppose que tous les etudiants ont le meme nombre de note

$nombreNotes = $dbh->query('SELECT note FROM notes where etudiant = \'E1\'');
$nbrnotes = 0 ;

foreach ($nombreNotes as $data){
    $nbrnotes +=1 ;
}

$widthLine = $largeurImage/$nbrnotes;


//E1

$moyenne1 = 0 ;

$positionCrayonX = $largeurImage/$nbrnotes/2;
$positionCrayonY = $hauteurImage/2;
$notePrecedente = 0 ;
$premiereNote = true;

$noteE1 = $dbh->query('SELECT note FROM notes where etudiant = \'E1\'');
foreach ($noteE1 as $data){
    $moyenne1 += $data['note'];

    if ($premiereNote) {
        $notePrecedente = $data['note'];
        $premiereNote = false ;
        continue;
    }
    imageline($image, $positionCrayonX, $positionCrayonY-$notePrecedente, $positionCrayonX + $widthLine, $positionCrayonY-$data['note'], $white);

    $notePrecedente = $data['note'];
    $positionCrayonX += $widthLine;
}

$moyenne1 = $moyenne1/ $nombreNotes ;

//E2

$moyenne2 = 0 ;
$positionCrayonX = $largeurImage/$nbrnotes/2 ;
$notePrecedente = 0 ;
$premiereNote = true;

$noteE2 = $dbh->query('SELECT note FROM notes where etudiant = \'E2\'');
foreach ($noteE2 as $data){
    $moyenne2 += $data['note'];

    if ($premiereNote) {
        $notePrecedente = $data['note'];
        $premiereNote = false ;
        continue;
    }
    imageline($image, $positionCrayonX, $positionCrayonY-$notePrecedente, $positionCrayonX + $widthLine, $positionCrayonY-$data['note'], $blue);

    $notePrecedente = $data['note'];
    $positionCrayonX += $widthLine;
}

$moyenne2 = $moyenne2/ $nombreNotes ;


imagestring($image, 20, 400, 10, "Notes des etudiants E1 et E2 !", $black);
imagestring($image, 20, 100, 200, "E1", $white);
imagestring($image, 20, 150, 200, "E2", $blue);
imagestring($image, 20, 700, 250, "Moyenne des notes de E1 : ".$moyenne1, $black);
imagestring($image, 20, 700, 270, "Moyenne des notes de E2 : ".$moyenne2, $black);


imagepng($image);

?>
