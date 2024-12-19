<?php 
/**
 * Plugin Name: Filtre Destinations par Pays
 * Author: Pamela Limoges
 * Description: Extension pour filtrer les destinations par pays.
 * Version: 1.0.0
 * Author URI: https://referenced.ca
 */


function filtre_pays_scripts() {
    wp_enqueue_style('filtre-pays-style', plugin_dir_url(__FILE__) . 'style.css');
    wp_enqueue_style('filtre-pays-script', plugin_dir_url(__FILE__) . '/js/filtre-pays.js');
}

add_action('wp_enqueue_scripts', 'filtre_pays_scripts');

// Shortcode pour afficher les boutons de pays
function genere_boutons_filtre_pays() {
    // Liste des pays à afficher
    $pays = array(
        "France", "États-Unis", "Canada", "Argentine", "Chili", "Belgique", 
        "Maroc", "Mexique", "Japon", "Italie", "Islande", "Chine", "Grèce", "Suisse"
    );

    $contenu = '<div class="filtre__bouton">';
    foreach ($pays as $nom_pays) {
        $contenu .= '<a href="' . esc_url(add_query_arg('pays', $nom_pays)) . '" class="filtrepays__bouton">' . esc_html($nom_pays) . '</a>';
    }
    $contenu .= '</div>';

    return $contenu;
}

add_shortcode('filtre_pays', 'genere_boutons_filtre_pays');

