<?php
// récupération et enregistrement des informations du flux
require('../class/Meteo.php');
$host = $_SERVER['HTTP_HOST'];
$url = "http://$host/MW0915DV01CD02/paris.csv";

$meteo = new Meteo();
// récupération contenu du flux
$content = $meteo->getContent($url);
// scindement contenu par saut de ligne
$lines = $meteo->explodeData($content, "\n");
// pour chaque ligne, création tableau de données
// à noter que la dernière ligne est vide suite à la scission : nous éviterons de la traiter.
foreach ($lines as $line) {
    $infos[] = $meteo->explodeData($line, ';');
}
// le tableau est utilisé pour l'enregistrement dans la base
// si la donnée existe, elle sera mise à jour
foreach ($infos as $info) {
    // évitement de la ligne vide
    if (!empty($info[0])) {
        // création des variables pour appel des fonctions
        $date = $info[0];
        $city = $info[1];
        $period = $info[2];
        $resume = $info[3];
        $resume_id = $info[4];
        $temp_min = $info[5];
        $temp_max = $info[6];
        $comment = $info[7];

        // pour chaque élément du tableau, on vérifie si la donnée est déjà stockée en base
        $forecast = ['date' => $date, 'city' => $city, 'period' => $period];
        $cityDateMeteo = $meteo->getMeteo($forecast);
        // si la donnée n'est pas présente nous l'insérons
        // sinon mise à jour
        if ($cityDateMeteo === false ) {
            $meteo->insertMeteo($date, $period, $city, $resume, $resume_id, $temp_min, $temp_max, $comment);
        } else {
            // récupération id de la donnée météo
            $id = $cityDateMeteo[0]['id'];
            $meteo->updateMeteo($id, $date, $period, $city, $resume, $resume_id, $temp_min, $temp_max, $comment);
        }
    }
}

//var_dump($infos);
