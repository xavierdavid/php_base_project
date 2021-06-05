<?php 

// Utilisation de l'Autoloader associé au namespace App
use App\Autoloader;
// Utilisation du MainController associé au namespace Controllers
use App\Controllers\MainController; 

// Démarrage de la session
session_start();

// Chargement de l'autoloader
require_once 'Autoloader.class.php';
Autoloader::register();

// Instanciation du MainController
$mainController = new MainController;

// Définition d'une constante d'URL contenant le chemin absolu du site
define("URL", str_replace("index.php","",(isset($_SERVER['HTTPS']) ? "https" : "http")."://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));

// Mise en place du système de routage du site
try {
    // Si aucun paramètre 'page' n'existe dans l'url
    if(empty($_GET['page'])) {
        // Alors on demande l'affichage de la page d'accueil 
        $page = "accueil";
    } else {
        // Sinon, on scinde et on filtre l'url pour l'analyser
        $url = explode("/", filter_var($_GET['page'], FILTER_SANITIZE_URL));
        // On récupère le 1er élément de l'url et on le stocke dans la variable "$page"
        $page = $url[0];
    }

    // On teste la valeur des éléments de l'url et on appelle les contrôleurs appropriés
    switch ($page) {
        case "accueil": $mainController->home();
            break;
        case "compte" :
            // On teste le 2ème élément de l'url
            switch ($url[1]) {
                case "profil" : $mainController->profile();
                    break;
            }    
        break;  
        default:
            // Dans les autres cas, on lance une exception
            throw new Exception("La page demandée n'existe pas.");
    }
    
} catch (Exception $e) {
    // Récupération des messages d'erreur du bloc try et gestion de l'affichage
    $mainController->errorPage($e->getMessage()); 
}



