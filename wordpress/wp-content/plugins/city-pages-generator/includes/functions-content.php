<?php
/**
 * Fonctions de génération de contenu - City Pages Generator
 */

if (!defined('ABSPATH')) exit;

// Créer une page ville
function cpg_create_city_page($data) {
    $city = sanitize_text_field($data['city_name']);
    $postal = sanitize_text_field($data['postal_code']);
    $dept = sanitize_text_field($data['department'] ?? 'Puy-de-Dôme');
    $priority = intval($data['priority'] ?? 2);
    $distance = floatval($data['distance_km'] ?? 0);
    $travel = sanitize_text_field($data['travel_time'] ?? '');
    $specifics = sanitize_textarea_field($data['local_specifics'] ?? '');
    $nearby = isset($data['nearby_cities']) ? array_filter(array_map('trim', explode("\n", $data['nearby_cities']))) : [];
    $status = sanitize_text_field($data['post_status'] ?? 'draft');
    
    if (empty($city) || empty($postal)) {
        return new WP_Error('missing', 'Ville et code postal requis.');
    }
    
    // Vérifier doublon
    $exists = get_posts(['post_type'=>'city_page','meta_key'=>'_cpg_city_name','meta_value'=>$city,'posts_per_page'=>1]);
    if (!empty($exists)) {
        return new WP_Error('exists', 'Cette ville existe déjà.');
    }
    
    // Générer contenu
    $variation = rand(1, 4);
    $content = cpg_generate_content($city, $postal, $dept, $variation, $specifics, $nearby, $distance, $travel);
    
    $post_id = wp_insert_post([
        'post_title'   => 'Métallier Ferronnier à ' . $city,
        'post_name'    => sanitize_title($city),
        'post_content' => $content,
        'post_status'  => $status,
        'post_type'    => 'city_page',
        'post_excerpt' => "Artisan métallier à {$city} ({$postal}). Portails, garde-corps, escaliers sur mesure. Devis gratuit.",
    ]);
    
    if (is_wp_error($post_id)) return $post_id;
    
    // Metas
    update_post_meta($post_id, '_cpg_city_name', $city);
    update_post_meta($post_id, '_cpg_postal_code', $postal);
    update_post_meta($post_id, '_cpg_department', $dept);
    update_post_meta($post_id, '_cpg_priority', $priority);
    update_post_meta($post_id, '_cpg_distance_km', $distance);
    update_post_meta($post_id, '_cpg_travel_time', $travel);
    update_post_meta($post_id, '_cpg_local_specifics', $specifics);
    update_post_meta($post_id, '_cpg_nearby_cities', $nearby);
    update_post_meta($post_id, '_cpg_variation', $variation);
    
    return $post_id;
}

// Générer le contenu avec variations
function cpg_generate_content($city, $postal, $dept, $var = 1, $specifics = '', $nearby = [], $distance = 0, $travel = '') {
    $s = get_option('cpg_settings', []);
    $company = $s['company_name'] ?? 'AL Métallerie & Soudure';
    $workshop = $s['workshop_city'] ?? 'Peschadoires';
    $phone = $s['phone'] ?? '06 73 33 35 32';
    $email = $s['email'] ?? 'contact@al-metallerie.fr';
    
    // Variations intro
    $intros = [
        1 => "<p><strong>{$company}</strong>, artisan métallier ferronnier basé à {$workshop}, intervient à <strong>{$city} ({$postal})</strong> et dans tout le <strong>{$dept}</strong> pour tous vos projets de métallerie sur mesure.</p>",
        2 => "<p>Vous recherchez un <strong>métallier qualifié à {$city}</strong> ? {$company}, installé à {$workshop}, se déplace dans tout le {$dept} pour réaliser vos projets. Du portail sur mesure à l'escalier design, nous donnons vie à vos idées.</p>",
        3 => "<p>Spécialiste de la métallerie artisanale, <strong>{$company}</strong> accompagne les habitants de <strong>{$city}</strong> dans tous leurs projets. Depuis notre atelier de {$workshop}, nous concevons des ouvrages métalliques sur mesure.</p>",
        4 => "<p>Pour vos travaux de métallerie à <strong>{$city} ({$postal})</strong>, faites confiance à <strong>{$company}</strong>. Artisan passionné basé à {$workshop}, nous créons des pièces uniques : portails, garde-corps, escaliers.</p>",
    ];
    
    // Variations "pourquoi nous"
    $whyus = [
        1 => ['Artisan local, intervention rapide','Fabrication 100% sur mesure','Devis gratuit sans engagement','Finitions soignées et durables','Conseils personnalisés'],
        2 => ['Plus de 15 ans d\'expérience','Matériaux de qualité pro','Respect des délais','Garantie décennale','SAV réactif'],
        3 => ['Proximité et réactivité','Créations uniques','Prix justes et transparents','Pose par nos équipes','Accompagnement de A à Z'],
        4 => ['Savoir-faire artisanal','Solutions adaptées à votre budget','Large choix de styles','Travail soigné','Conseil expert'],
    ];
    
    $v = min(max($var, 1), 4);
    
    $content = "<!-- Variation {$v} -->\n\n";
    
    // Intro
    $content .= "<h2>Votre artisan métallier à {$city}</h2>\n";
    $content .= $intros[$v] . "\n\n";
    
    // Spécificités
    if ($specifics) {
        $content .= "<p>Nous connaissons bien {$city} : {$specifics}.</p>\n\n";
    }
    
    // Distance
    if ($distance > 0 || $travel) {
        $content .= "<p>📍 <strong>Intervention rapide</strong> : {$city} est ";
        if ($distance > 0) $content .= "à {$distance} km de notre atelier";
        if ($travel) $content .= " (environ {$travel})";
        $content .= ".</p>\n\n";
    }
    
    // Services
    $content .= "<h2>Nos services de métallerie à {$city}</h2>\n";
    $services = [
        ['Portails sur mesure', 'Coulissants, battants, en acier ou fer forgé'],
        ['Garde-corps et rambardes', 'Sécurisez escaliers et terrasses'],
        ['Escaliers métalliques', 'Droits, quart tournant, hélicoïdaux'],
        ['Pergolas et auvents', 'Structures extérieures sur mesure'],
        ['Verrières d\'intérieur', 'Style atelier pour vos espaces'],
        ['Grilles de sécurité', 'Protection de vos ouvertures'],
        ['Ferronnerie d\'art', 'Créations décoratives uniques'],
    ];
    $content .= "<ul>\n";
    foreach ($services as $srv) {
        $content .= "<li><strong>{$srv[0]}</strong> : {$srv[1]}</li>\n";
    }
    $content .= "</ul>\n\n";
    
    // Pourquoi nous
    $content .= "<h2>Pourquoi choisir {$company} ?</h2>\n<ul>\n";
    foreach ($whyus[$v] as $r) {
        $content .= "<li>✓ <strong>{$r}</strong></li>\n";
    }
    $content .= "</ul>\n\n";
    
    // Communes proches
    if (!empty($nearby)) {
        $content .= "<h2>Nous intervenons aussi autour de {$city}</h2>\n";
        $content .= "<p>Communes desservies : <strong>" . implode('</strong>, <strong>', array_map('esc_html', $nearby)) . "</strong>.</p>\n\n";
    }
    
    // Contact
    $content .= "<h2>Contactez votre métallier à {$city}</h2>\n";
    $content .= "<p>📞 <strong><a href=\"tel:+33".preg_replace('/[^0-9]/','',$phone)."\">{$phone}</a></strong><br>\n";
    $content .= "📧 <a href=\"mailto:{$email}\">{$email}</a></p>\n";
    $content .= "<p><a href=\"/contact/\" class=\"button\">Demander un devis gratuit</a></p>\n\n";
    
    // FAQ
    $content .= "<h2>Questions fréquentes</h2>\n";
    $faqs = [
        ["Intervenez-vous à {$city} ?", "Oui, nous intervenons régulièrement à {$city} et dans tout le {$dept}."],
        ["Quel délai pour un devis ?", "Généralement sous 48h après votre demande."],
        ["Proposez-vous la pose ?", "Oui, nous assurons fabrication ET pose de tous nos ouvrages."],
        ["Quels matériaux ?", "Acier, inox et aluminium selon votre projet et budget."],
    ];
    foreach ($faqs as $faq) {
        $content .= "<h3>{$faq[0]}</h3>\n<p>{$faq[1]}</p>\n";
    }
    
    return $content;
}
