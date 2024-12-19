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

// Fonction pour récupérer les articles par pays via la REST API
function recuperer_articles_par_pays($pays) {
    // Effectuer la requête REST pour récupérer les articles par pays
    $url = get_site_url() . "/wp-json/wp/v2/posts?search=" . urlencode($pays) . "&per_page=30";

    // Récupérer les données avec wp_remote_get
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        return 'Erreur lors de la récupération des articles.';
    }

    // Décoder la réponse JSON
    $posts = json_decode(wp_remote_retrieve_body($response));

    // Si aucun article trouvé, retourner un message
    if (empty($posts)) {
        return 'Aucune destination trouvée pour ce pays.';
    }

    // Afficher les articles récupérés
    $contenu = '<div class="filtre__conteneur">';
    foreach ($posts as $post) {
        $contenu .= '<div class="filtre__item">';
        $contenu .= '<a href="' . esc_url(get_permalink($post->id)) . '">';
        $contenu .= '<h3>' . esc_html($post->title->rendered) . '</h3>';
        $contenu .= '</a>';
        $contenu .= '</div>';
    }
    $contenu .= '</div>';

    return $contenu;
}

// Afficher les articles en fonction du pays sélectionné
function afficher_filtre_resultats() {
    if (isset($_GET['pays'])) {
        $pays = sanitize_text_field($_GET['pays']);
        echo recuperer_articles_par_pays($pays);
    }
}

add_action('wp_footer', 'afficher_filtre_resultats'); 