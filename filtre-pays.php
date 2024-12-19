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
    wp_enqueue_script('filtre-pays-script', plugin_dir_url(__FILE__) . 'js/filtre-pays.js', array(), null, true);
}

add_action('wp_enqueue_scripts', 'filtre_pays_scripts');

// Shortcode pour afficher les boutons de pays
function genere_boutons_filtre_pays() {
    // Liste des pays à afficher (les catégories existantes)
    $pays = array(
        "France", "États-Unis", "Canada", "Argentine", "Chili", "Belgique", 
        "Maroc", "Mexique", "Japon", "Italie", "Islande", "Chine", "Grèce", "Suisse"
    );

    $contenu = '<div class="filtre__bouton">';
    foreach ($pays as $nom_pays) {
        // Crée un lien pour chaque pays, en utilisant le nom du pays comme paramètre dans l'URL
        $contenu .= '<button data-pays="' . esc_attr($nom_pays) . '" class="filtrepays__bouton">' . esc_html($nom_pays) . '</button>';
    }
    $contenu .= '</div>';

    return $contenu;
}

add_shortcode('filtre_pays', 'genere_boutons_filtre_pays');

// Fonction pour récupérer les articles par pays via la REST API
function recuperer_articles_par_pays($pays) {
    // Rechercher la catégorie par nom
    $categorie = get_term_by('name', $pays, 'category');
    if (!$categorie) {
        return 'Aucune catégorie trouvée pour ce pays.';
    }

    // Construire l'URL de la requête REST API avec l'ID de la catégorie
    $url = get_site_url() . "/31W/wp-json/wp/v2/posts?search=" . $categorie->term_id . "&per_page=30";

    // Utiliser wp_remote_get pour effectuer la requête
    $response = wp_remote_get($url);

    // Vérifier s'il y a une erreur dans la requête
    if (is_wp_error($response)) {
        return 'Erreur lors de la récupération des articles.';
    }

    // Décoder la réponse JSON
    $posts = json_decode(wp_remote_retrieve_body($response));

    // Si aucun article trouvé, retourner un message
    if (empty($posts)) {
        return 'Aucune destination trouvée pour ce pays.';
    }

    // Affichage des articles récupérés
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