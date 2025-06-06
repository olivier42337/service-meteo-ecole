<?php
// affichage des informations météos depuis la base de donnée
require('class/Meteo.php');
// instanciation classe Meteo
$meteo = new Meteo();
// appel de la méthode pour affichage des données
// argument vide : récupération de toutes les données
$forecasts = $meteo->getMeteo();

// appel du fichier template destiné à afficher les informations
// Utilisation de la constante PHP __FILE__ qui restitue le chemin et le nom du fichier en cours et de basename qui permet de récupérer uniquement le nom du fichier. Il s'agit donc d'un moyen simple d'être sur d'appeler le bon template, celui-ci ayant le même nom que le fichier en cours. A noter que le chemin du répertoire template pourrait être placé dans une constante déclarée dans un fichier de configuration placé à la racine du serveur.

$template = basename(__FILE__);
require_once("template/$template");
