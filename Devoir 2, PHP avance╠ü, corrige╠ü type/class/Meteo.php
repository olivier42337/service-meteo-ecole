<?php

class Meteo {
    // récupération contenu du flux
    public function getContent($url) {
        return file_get_contents($url);
    }

    // scindement d'une chaîne de caractères en segments
    public function explodeData($data, $delimiter) {
        return explode($delimiter, $data);
    }

    // enregistrement des prévisions météo. en base de donnée
    public function insertMeteo($date, $period, $city, $resume, $resume_id, $temp_min, $temp_max, $comment) {
        // connexion à la base
        $mysqli = new mysqli('localhost', 'root', 'root', 'projet_meteo');
        // vérification de la connexion à la base
        if ($mysqli->connect_errno) {
            echo "Echec de la connexion : $mysqli->connect_error";
            exit();
        }
        // insertion des données dans la table meteo
        if (!$mysqli->query("INSERT INTO meteo (date, period, city, resume, resume_id, temp_min, temp_max, comment) VALUES ('$date', '$period', '$city', '$resume', $resume_id, $temp_min, $temp_max, '$comment')")) {
            echo "Une erreur est survenue lors de l'insertion des données. Message d'erreur : $mysqli->error";
            return false;
        } else {
            return true;
        }
    }

    // récupération des prévisions météo.
    public function getMeteo($infos = "") {
        // connexion à la base
        $mysqli = new mysqli('localhost', 'root', 'root', 'projet_meteo');

        // vérification de la connexion à la base
        if ($mysqli->connect_errno) {
            echo "Echec de la connexion : $mysqli->connect_error";
            exit();
        }

        if (empty($infos)) {
            $sql = "SELECT * FROM meteo ORDER BY id DESC";
        } else {
            $date = $infos['date'];
            $period = $infos['period'];
            $city = $infos['city'];
            $sql = "SELECT * FROM meteo WHERE date = '$date' AND period LIKE '$period' AND city LIKE '$city'";
        }

        //var_dump($sql);
        $result = $mysqli->query($sql);

        if (!$result) {
            echo "Une erreur est survenue lors de la récupération des données. Message d'erreur : $mysqli->error";
            return false;
        } else {
            while ($row = $result->fetch_array()) {
                $data[] = $row;
            }
            if (isset($data)) {
                return $data;
            } else {
                return false;
            }
        }
    }

    public function updateMeteo($id, $date, $period, $city, $resume, $resume_id, $temp_min, $temp_max, $comment) {
        // connexion à la base
        $mysqli = new mysqli('localhost', 'root', 'root', 'projet_meteo');

        // vérification de la connexion à la base
        if ($mysqli->connect_errno) {
            echo "Echec de la connexion : $mysqli->connect_error";
            exit();
        }

        $sql = "UPDATE meteo SET date = '$date', period = '$period', city = '$city', resume = '$resume', resume_id = $resume_id, temp_min = $temp_min, temp_max = $temp_max, comment = '$comment' WHERE id = $id";

        //var_dump($sql);

        if (!$mysqli->query($sql)) {
            echo "Une erreur est survenue lors de la mise à jour des données. Message d'erreur : $mysqli->error";
            return false;
        } else {
            return true;
        }
    }
}
