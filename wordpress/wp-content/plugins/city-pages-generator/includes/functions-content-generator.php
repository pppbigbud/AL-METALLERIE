<?php
/**
 * Générateur de contenu SEO unique pour les pages ville
 * 
 * Utilise des variations de texte, des synonymes et des structures différentes
 * pour créer du contenu unique optimisé SEO pour chaque ville.
 */

if (!defined('ABSPATH')) exit;

/**
 * Générer un contenu SEO unique pour une ville
 */
function cpg_generate_seo_content($city, $dept) {
    $company = 'AL Métallerie & Soudure';
    $phone = '06 73 33 35 32';
    $email = 'contact@al-metallerie.fr';
    $base_city = 'Peschadoires';
    
    // Calculer un hash unique basé sur le nom de la ville pour la variation
    $hash = crc32($city);
    $variation = $hash % 4; // 4 variations principales
    
    // Données spécifiques à la ville
    $city_data = cpg_get_city_specific_data($city, $dept);
    
    // Générer les sections
    $content = '';
    $content .= cpg_generate_intro_section($city, $dept, $company, $variation, $city_data);
    $content .= cpg_generate_services_section($city, $variation);
    $content .= cpg_generate_expertise_section($city, $dept, $variation);
    $content .= cpg_generate_process_section($city, $variation);
    $content .= cpg_generate_zone_section($city, $dept, $city_data, $variation);
    $content .= cpg_generate_faq_section($city, $dept, $variation);
    $content .= cpg_generate_cta_section($city, $phone, $email, $variation);
    
    return $content;
}

/**
 * Données spécifiques à certaines villes
 */
function cpg_get_city_specific_data($city, $dept) {
    $data = array(
        'distance' => '',
        'travel_time' => '',
        'nearby_cities' => array(),
        'local_info' => '',
        'population' => '',
    );
    
    // Données pour les villes principales
    $cities_data = array(
        'Thiers' => array(
            'distance' => '8 km',
            'travel_time' => '10 minutes',
            'nearby_cities' => array('Peschadoires', 'Celles-sur-Durolle', 'Escoutoux', 'Dorat'),
            'local_info' => 'capitale française de la coutellerie',
            'population' => '11 000',
        ),
        'Clermont-Ferrand' => array(
            'distance' => '45 km',
            'travel_time' => '40 minutes',
            'nearby_cities' => array('Chamalières', 'Royat', 'Beaumont', 'Aubière', 'Cournon'),
            'local_info' => 'capitale auvergnate',
            'population' => '147 000',
        ),
        'Vichy' => array(
            'distance' => '35 km',
            'travel_time' => '35 minutes',
            'nearby_cities' => array('Cusset', 'Bellerive-sur-Allier', 'Abrest', 'Saint-Yorre'),
            'local_info' => 'ville thermale réputée',
            'population' => '25 000',
        ),
        'Riom' => array(
            'distance' => '35 km',
            'travel_time' => '30 minutes',
            'nearby_cities' => array('Mozac', 'Ennezat', 'Châtel-Guyon', 'Volvic'),
            'local_info' => 'cité historique d\'art et d\'histoire',
            'population' => '19 000',
        ),
        'Issoire' => array(
            'distance' => '55 km',
            'travel_time' => '45 minutes',
            'nearby_cities' => array('Parentignat', 'Le Broc', 'Perrier', 'Sauxillanges'),
            'local_info' => 'ville au riche patrimoine roman',
            'population' => '15 000',
        ),
        'Ambert' => array(
            'distance' => '40 km',
            'travel_time' => '45 minutes',
            'nearby_cities' => array('Arlanc', 'Marsac-en-Livradois', 'Saint-Amant-Roche-Savine'),
            'local_info' => 'cœur du Livradois-Forez',
            'population' => '7 000',
        ),
        'Lezoux' => array(
            'distance' => '15 km',
            'travel_time' => '15 minutes',
            'nearby_cities' => array('Ravel', 'Orléat', 'Lempty', 'Seychalles'),
            'local_info' => 'ancienne capitale de la céramique gallo-romaine',
            'population' => '6 000',
        ),
        'Courpière' => array(
            'distance' => '12 km',
            'travel_time' => '12 minutes',
            'nearby_cities' => array('Augerolles', 'Vollore-Ville', 'Sauviat'),
            'local_info' => 'porte du Livradois',
            'population' => '4 500',
        ),
        'Pont-du-Château' => array(
            'distance' => '30 km',
            'travel_time' => '25 minutes',
            'nearby_cities' => array('Lempdes', 'Cournon', 'Dallet', 'Mezel'),
            'local_info' => 'ville dynamique aux portes de Clermont',
            'population' => '12 000',
        ),
        'Billom' => array(
            'distance' => '25 km',
            'travel_time' => '25 minutes',
            'nearby_cities' => array('Saint-Julien-de-Coppel', 'Montmorin', 'Égliseneuve-près-Billom'),
            'local_info' => 'cité médiévale historique',
            'population' => '5 000',
        ),
    );
    
    if (isset($cities_data[$city])) {
        return $cities_data[$city];
    }
    
    // Données par défaut pour les autres villes
    $data['nearby_cities'] = cpg_get_nearby_cities_by_dept($dept);
    
    return $data;
}

/**
 * Villes proches par département
 */
function cpg_get_nearby_cities_by_dept($dept) {
    $nearby = array(
        'Puy-de-Dôme' => array('Thiers', 'Clermont-Ferrand', 'Riom', 'Lezoux'),
        'Allier' => array('Vichy', 'Cusset', 'Gannat', 'Saint-Pourçain-sur-Sioule'),
        'Loire' => array('Roanne', 'Montbrison', 'Feurs', 'Saint-Just-Saint-Rambert'),
        'Haute-Loire' => array('Le Puy-en-Velay', 'Brioude', 'Yssingeaux'),
        'Cantal' => array('Aurillac', 'Saint-Flour', 'Mauriac'),
    );
    
    return isset($nearby[$dept]) ? $nearby[$dept] : array('Thiers', 'Clermont-Ferrand');
}

/**
 * Section Introduction - 4 variations
 */
function cpg_generate_intro_section($city, $dept, $company, $variation, $city_data) {
    $local_info = !empty($city_data['local_info']) ? ", {$city_data['local_info']}" : '';
    $distance = !empty($city_data['distance']) ? " À seulement {$city_data['distance']} de notre atelier," : '';
    
    $intros = array(
        // Variation 0 : Focus proximité
        "<h2>Métallier soudeur à {$city} : votre artisan de proximité</h2>
<p>Vous recherchez un <strong>métallier professionnel à {$city}</strong>{$local_info} ? <strong>{$company}</strong>, artisan métallier-soudeur établi à Peschadoires, met son expertise au service des habitants de {$city} et de tout le {$dept}.{$distance} nous intervenons rapidement pour tous vos projets de métallerie sur mesure.</p>
<p>Depuis notre création, nous accompagnons les particuliers et professionnels de {$city} dans la réalisation de leurs projets : <em>portails</em>, <em>garde-corps</em>, <em>escaliers métalliques</em>, <em>pergolas</em> et bien plus encore. Chaque réalisation est unique, conçue selon vos besoins et fabriquée dans notre atelier.</p>",

        // Variation 1 : Focus expertise
        "<h2>Artisan métallier à {$city} : l'expertise au service de vos projets</h2>
<p><strong>{$company}</strong> est votre partenaire privilégié pour tous vos travaux de <strong>métallerie à {$city}</strong>{$local_info}. Notre savoir-faire artisanal, allié à des techniques modernes, nous permet de répondre à toutes vos exigences en matière de fabrication métallique sur mesure.</p>
<p>Basés à Peschadoires, nous rayonnons sur l'ensemble du {$dept} et intervenons régulièrement à {$city} pour des projets variés : création de <em>portails design</em>, installation de <em>garde-corps sécurisés</em>, fabrication d'<em>escaliers contemporains</em> ou traditionnels. Notre engagement : un travail soigné et des finitions impeccables.</p>",

        // Variation 2 : Focus qualité
        "<h2>Ferronnerie et métallerie sur mesure à {$city}</h2>
<p>Pour vos projets de <strong>ferronnerie et métallerie à {$city}</strong>{$local_info}, faites confiance à <strong>{$company}</strong>. Artisan passionné basé à Peschadoires, nous mettons notre expertise au service des habitants du {$dept} depuis plusieurs années.</p>
<p>Que vous souhaitiez sécuriser votre propriété avec un <em>portail robuste</em>, embellir votre intérieur avec une <em>verrière style atelier</em>, ou créer un <em>escalier métallique unique</em>, nous vous accompagnons de la conception à la pose. À {$city}, nous avons déjà réalisé de nombreux projets qui témoignent de notre savoir-faire.</p>",

        // Variation 3 : Focus personnalisation
        "<h2>Votre métallier à {$city} : des créations 100% sur mesure</h2>
<p>À {$city}{$local_info}, <strong>{$company}</strong> est la référence pour tous vos projets de <strong>métallerie personnalisée</strong>. Depuis notre atelier de Peschadoires, nous concevons et fabriquons des ouvrages métalliques uniques, adaptés à vos goûts et à votre budget.</p>
<p>Chaque projet à {$city} est une nouvelle aventure : nous étudions vos besoins, proposons des solutions créatives et réalisons des pièces qui vous ressemblent. <em>Portails</em>, <em>rambardes</em>, <em>pergolas</em>, <em>mobilier métallique</em>... Tout est possible avec AL Métallerie !</p>",
    );
    
    return $intros[$variation];
}

/**
 * Section Services - 4 variations de présentation
 */
function cpg_generate_services_section($city, $variation) {
    $services_data = array(
        array(
            'title' => 'Portails sur mesure',
            'desc' => 'Portails battants ou coulissants, motorisés ou manuels. Acier, fer forgé ou aluminium selon vos préférences.',
            'keywords' => 'portail sur mesure, portail coulissant, portail battant',
        ),
        array(
            'title' => 'Garde-corps et rambardes',
            'desc' => 'Sécurisation de vos escaliers, balcons et terrasses. Design moderne ou classique, conformes aux normes.',
            'keywords' => 'garde-corps, rambarde, balustrade',
        ),
        array(
            'title' => 'Escaliers métalliques',
            'desc' => 'Escaliers droits, quart tournant ou hélicoïdaux. Structure acier avec marches métal, bois ou verre.',
            'keywords' => 'escalier métallique, escalier acier, escalier design',
        ),
        array(
            'title' => 'Pergolas et auvents',
            'desc' => 'Structures extérieures pour profiter de votre jardin. Pergolas bioclimatiques ou traditionnelles.',
            'keywords' => 'pergola, auvent, abri terrasse',
        ),
        array(
            'title' => 'Verrières d\'intérieur',
            'desc' => 'Verrières style atelier pour séparer vos espaces tout en conservant la luminosité.',
            'keywords' => 'verrière, verrière atelier, cloison vitrée',
        ),
        array(
            'title' => 'Ferronnerie d\'art',
            'desc' => 'Créations décoratives uniques : grilles, luminaires, mobilier d\'art, pièces sur commande.',
            'keywords' => 'ferronnerie art, fer forgé, création artistique',
        ),
    );
    
    $titles = array(
        "Nos prestations de métallerie à {$city}",
        "Services de métallerie disponibles à {$city}",
        "Ce que nous réalisons à {$city}",
        "Métallerie à {$city} : nos spécialités",
    );
    
    $content = "<h2>{$titles[$variation]}</h2>\n";
    
    // Varier l'ordre des services selon la ville
    $order = range(0, count($services_data) - 1);
    shuffle($order);
    
    if ($variation % 2 == 0) {
        // Présentation en liste
        $content .= "<ul class=\"services-list\">\n";
        foreach ($order as $i) {
            $s = $services_data[$i];
            $content .= "<li><strong>{$s['title']}</strong> : {$s['desc']}</li>\n";
        }
        $content .= "</ul>\n";
    } else {
        // Présentation en paragraphes
        foreach ($order as $i) {
            $s = $services_data[$i];
            $content .= "<h3>{$s['title']}</h3>\n<p>{$s['desc']}</p>\n";
        }
    }
    
    return $content;
}

/**
 * Section Expertise - 4 variations
 */
function cpg_generate_expertise_section($city, $dept, $variation) {
    $arguments = array(
        array('icon' => '✓', 'title' => 'Artisan local', 'desc' => "Basés à Peschadoires, nous intervenons rapidement à {$city}"),
        array('icon' => '✓', 'title' => 'Fabrication française', 'desc' => 'Tout est conçu et fabriqué dans notre atelier'),
        array('icon' => '✓', 'title' => 'Sur mesure uniquement', 'desc' => 'Chaque projet est unique et personnalisé'),
        array('icon' => '✓', 'title' => 'Devis gratuit', 'desc' => 'Étude de votre projet sans engagement'),
        array('icon' => '✓', 'title' => 'Pose incluse', 'desc' => 'Installation professionnelle par nos soins'),
        array('icon' => '✓', 'title' => 'Garantie décennale', 'desc' => 'Travaux assurés pour votre tranquillité'),
    );
    
    $titles = array(
        "Pourquoi choisir AL Métallerie à {$city} ?",
        "Les avantages de faire appel à notre atelier",
        "Notre engagement qualité à {$city}",
        "Ce qui nous différencie à {$city}",
    );
    
    $content = "<h2>{$titles[$variation]}</h2>\n<ul>\n";
    
    // Sélectionner 4 arguments selon la variation
    $selected = array_slice($arguments, $variation, 4);
    if (count($selected) < 4) {
        $selected = array_merge($selected, array_slice($arguments, 0, 4 - count($selected)));
    }
    
    foreach ($selected as $arg) {
        $content .= "<li>{$arg['icon']} <strong>{$arg['title']}</strong> : {$arg['desc']}</li>\n";
    }
    
    $content .= "</ul>\n";
    
    return $content;
}

/**
 * Section Processus - 4 variations
 */
function cpg_generate_process_section($city, $variation) {
    if ($variation == 0 || $variation == 2) {
        return "<h2>Comment se déroule votre projet à {$city} ?</h2>
<ol>
<li><strong>Premier contact</strong> : Vous nous appelez ou remplissez le formulaire. Nous échangeons sur votre projet.</li>
<li><strong>Visite sur place</strong> : Nous venons à {$city} prendre les mesures et discuter des détails.</li>
<li><strong>Devis détaillé</strong> : Vous recevez un devis clair et complet sous 48h.</li>
<li><strong>Fabrication</strong> : Votre ouvrage est réalisé dans notre atelier à Peschadoires.</li>
<li><strong>Installation</strong> : Nous posons votre réalisation et vous expliquons l'entretien.</li>
</ol>\n";
    } else {
        return "<h2>Votre projet en 5 étapes simples</h2>
<p>De votre première idée à la réalisation finale, nous vous accompagnons à chaque étape :</p>
<p><strong>1. Échange initial</strong> → <strong>2. Visite à {$city}</strong> → <strong>3. Devis gratuit</strong> → <strong>4. Fabrication sur mesure</strong> → <strong>5. Pose professionnelle</strong></p>
<p>Délai moyen : 3 à 6 semaines selon la complexité du projet.</p>\n";
    }
}

/**
 * Section Zone d'intervention
 */
function cpg_generate_zone_section($city, $dept, $city_data, $variation) {
    $nearby = !empty($city_data['nearby_cities']) ? implode(', ', $city_data['nearby_cities']) : '';
    $distance = !empty($city_data['distance']) ? $city_data['distance'] : '50 km';
    
    $content = "<h2>Zone d'intervention autour de {$city}</h2>\n";
    $content .= "<p>Depuis notre atelier de Peschadoires, nous intervenons à {$city} et dans toutes les communes environnantes du {$dept}.</p>\n";
    
    if (!empty($nearby)) {
        $content .= "<p><strong>Communes proches desservies</strong> : {$nearby}, et toutes les localités dans un rayon de {$distance}.</p>\n";
    }
    
    return $content;
}

/**
 * Section FAQ - Questions uniques par ville
 */
function cpg_generate_faq_section($city, $dept, $variation) {
    $faqs = array(
        array(
            'q' => "Quel est le délai pour un portail sur mesure à {$city} ?",
            'a' => "Le délai moyen est de 4 à 6 semaines entre la validation du devis et la pose. Ce délai peut varier selon la complexité du projet et notre charge de travail.",
        ),
        array(
            'q' => "Proposez-vous des devis gratuits à {$city} ?",
            'a' => "Oui, nous nous déplaçons gratuitement à {$city} pour étudier votre projet, prendre les mesures et vous remettre un devis détaillé sans engagement.",
        ),
        array(
            'q' => "Quels matériaux utilisez-vous pour vos réalisations ?",
            'a' => "Nous travaillons principalement l'acier, l'inox et l'aluminium. Le choix dépend de l'usage, de l'esthétique souhaitée et de votre budget.",
        ),
        array(
            'q' => "Assurez-vous la pose à {$city} ?",
            'a' => "Absolument. Nous assurons la fabrication dans notre atelier ET la pose sur site. C'est inclus dans nos prestations pour garantir un résultat parfait.",
        ),
        array(
            'q' => "Travaillez-vous avec les particuliers et les professionnels ?",
            'a' => "Oui, nous intervenons aussi bien pour des projets résidentiels que pour des entreprises, commerces ou collectivités du {$dept}.",
        ),
    );
    
    // Sélectionner 3 FAQ selon la variation
    $selected_indices = array($variation % 5, ($variation + 1) % 5, ($variation + 2) % 5);
    
    $content = "<h2>Questions fréquentes - Métallerie à {$city}</h2>\n";
    
    foreach ($selected_indices as $i) {
        $faq = $faqs[$i];
        $content .= "<h3>{$faq['q']}</h3>\n<p>{$faq['a']}</p>\n";
    }
    
    return $content;
}

/**
 * Section CTA finale
 */
function cpg_generate_cta_section($city, $phone, $email, $variation) {
    $ctas = array(
        "<h2>Contactez votre métallier à {$city}</h2>
<p>Prêt à concrétiser votre projet ? Contactez-nous dès maintenant :</p>
<p>📞 <strong><a href=\"tel:+33{$phone}\">{$phone}</a></strong><br>
📧 <a href=\"mailto:{$email}\">{$email}</a></p>
<p><a href=\"/contact/\" class=\"button\">Demander un devis gratuit</a></p>",

        "<h2>Un projet de métallerie à {$city} ?</h2>
<p>Appelez-nous au <strong><a href=\"tel:+33{$phone}\">{$phone}</a></strong> ou envoyez-nous un message. Réponse garantie sous 24h !</p>
<p><a href=\"/contact/\" class=\"button\">Obtenir mon devis gratuit</a></p>",

        "<h2>Démarrez votre projet à {$city}</h2>
<p>Devis gratuit et sans engagement. Intervention rapide dans tout le secteur.</p>
<p>📞 <strong>{$phone}</strong> | 📧 {$email}</p>
<p><a href=\"/contact/\" class=\"button\">Nous contacter</a></p>",

        "<h2>Besoin d'un métallier à {$city} ?</h2>
<p>Notre équipe est à votre écoute pour étudier votre projet et vous proposer la meilleure solution.</p>
<p><strong>Téléphone</strong> : {$phone}<br><strong>Email</strong> : {$email}</p>
<p><a href=\"/contact/\" class=\"button\">Demande de devis</a></p>",
    );
    
    return $ctas[$variation];
}
