<?php
/**
 * Classe de base de connaissances AL Métallerie
 */
class ACSP_Knowledge_Base {
    
    /**
     * Données de l'entreprise
     */
    private $company_data;
    
    /**
     * Services
     */
    private $services;
    
    /**
     * Matériaux
     */
    private $materials;
    
    /**
     * Zone d'intervention
     */
    private $locations;
    
    /**
     * Réalisations
     */
    private $realisations;
    
    /**
     * Constructeur
     */
    public function __construct() {
        $this->init_company_data();
        $this->init_services();
        $this->init_materials();
        $this->init_locations();
        $this->init_realisations();
    }
    
    /**
     * Initialiser les données de l'entreprise
     */
    private function init_company_data() {
        $this->company_data = [
            'name' => 'AL Métallerie & Soudure',
            'address' => 'Peschadoires, Thiers, Puy-de-Dôme (63)',
            'region' => 'Auvergne-Rhône-Alpes',
            'phone' => '06 73 33 35 32',
            'email' => 'contact@al-metallerie.fr',
            'website' => 'https://al-metallerie.fr',
            'siret' => '819 018 603 00015',
            'opening_hours' => 'Ouvert 6j/7 - Lundi au Samedi',
            'rating' => '5.0/5',
            'reviews_count' => '8 avis Google',
            'experience' => 'Plus de 10 ans d\'expérience',
            'specialties' => [
                'Fabrication sur-mesure',
                'Devis gratuit sous 48h',
                'Artisan local',
                'Formations soudure uniques',
                'Qualité professionnelle'
            ]
        ];
    }
    
    /**
     * Initialiser les services
     */
    private function init_services() {
        $this->services = [
            [
                'name' => 'Portails',
                'slug' => 'portails',
                'types' => ['coulissants', 'battants', 'semi-ajourés', 'pleins', 'motorisés'],
                'materials' => ['acier', 'acier thermolaqué', 'aluminium', 'inox'],
                'features' => ['motorisation', 'interphonie', 'serrure 3 points', 'telecommande'],
                'description' => 'Portails sur mesure pour sécuriser et embellir votre propriété'
            ],
            [
                'name' => 'Garde-corps',
                'slug' => 'garde-corps',
                'types' => ['escaliers', 'terrasses', 'balcons', 'fenêtres', 'piscines'],
                'styles' => ['contemporain', 'classique', 'design', 'minimaliste'],
                'normes' => ['NF P01-012', 'normes ERP'],
                'description' => 'Garde-corps design alliant sécurité et esthétique'
            ],
            [
                'name' => 'Escaliers',
                'slug' => 'escaliers',
                'types' => ['droits', 'hélicoïdaux', 'quart tournant', 'colimaçon'],
                'materiaux' => ['acier', 'bois', 'verre', 'mixtes'],
                'finitions' => ['brut', 'peint', 'thermolaqué', 'brossé'],
                'description' => 'Escaliers métalliques sur mesure, alliants design et robustesse'
            ],
            [
                'name' => 'Pergolas',
                'slug' => 'pergolas',
                'types' => ['adossées', 'autoportantes', 'bioclimatiques'],
                'toiture' => ['lames orientables', 'toile rétractable', 'verre', 'polycarbonate'],
                'options' => ['éclairage LED', 'stores latéraux', 'motorisation'],
                'description' => 'Pergolas pour profiter de votre extérieur été comme hiver'
            ],
            [
                'name' => 'Verrières',
                'slug' => 'verrieres',
                'styles' => ['atelier', 'industriel', 'design', 'véranda'],
                'structure' => ['acier', 'aluminium', 'mixte'],
                'verre' => ['simple vitrage', 'double vitrage', 'verre sécurit'],
                'description' => 'Verrières d\'atelier pour apporter lumière et caractère'
            ],
            [
                'name' => 'Mobilier métallique',
                'slug' => 'mobilier',
                'pieces' => ['tables', 'chaises', 'étagères', 'bancs', 'rangements'],
                'styles' => ['industriel', 'rustique', 'contemporain', 'vintage'],
                'lieux' => ['cuisine', 'salon', 'bureau', 'extérieur', 'professionnel'],
                'description' => 'Créations de mobilier métallique unique et sur mesure'
            ],
            [
                'name' => 'Soudure',
                'slug' => 'soudure',
                'techniques' => ['TIG', 'MIG', 'MAG', 'ARC', 'soudure aluminium'],
                'applications' => ['réparation', 'fabrication', 'renforcement', 'restauration'],
                'materiaux' => ['acier', 'inox', 'aluminium', 'fonte', 'cuivre'],
                'description' => 'Travaux de soudure professionnels pour tous types de métaux'
            ],
            [
                'name' => 'Formations',
                'slug' => 'formations',
                'niveaux' => ['débutant', 'intermédiaire', 'professionnel'],
                'types' => ['particuliers', 'professionnels', 'initiation', 'perfectionnement'],
                'techniques' => ['TIG', 'MIG', 'soudure tube', 'soudure plaque'],
                'description' => 'Formations soudure uniques en Auvergne, pour tous niveaux'
            ]
        ];
    }
    
    /**
     * Initialiser les matériaux
     */
    private function init_materials() {
        $this->materials = [
            [
                'name' => 'Acier',
                'properties' => ['robuste', 'économique', 'facile à travailler'],
                'uses' => ['portails', 'escaliers', 'structures', 'mobilier'],
                'finitions' => ['brut', 'peint', 'galvanisé'],
                'description' => 'Matériau traditionnel de la métallerie, alliant solidité et prix abordable'
            ],
            [
                'name' => 'Acier thermolaqué',
                'properties' => ['anti-corrosion', 'couleurs variées', 'facile entretien'],
                'colors' => ['noir', 'gris', 'blanc', 'anthracite', 'couleurs RAL'],
                'guarantee' => '10 ans contre la corrosion',
                'description' => 'Acier avec revêtement polyester pour une protection optimale'
            ],
            [
                'name' => 'Inox 304',
                'properties' => ['inoxydable', 'hygiénique', 'aspect moderne'],
                'uses' => ['cuisine', 'extérieur', 'mobilier design', 'garde-corps'],
                'finitions' => ['brillant', 'brossé', 'satiné'],
                'description' => 'Acier inoxydable pour un look moderne et une durabilité exceptionnelle'
            ],
            [
                'name' => 'Inox 316',
                'properties' => ['marine', 'résistant sel', 'haute résistance'],
                'uses' => ['milieu humide', 'piscine', 'bord de mer', 'industrie'],
                'description' => 'Inox marine pour les environnements exigeants'
            ],
            [
                'name' => 'Aluminium',
                'properties' => ['léger', 'non corrosif', 'recyclable'],
                'uses' => ['portails', 'vérandas', 'mobilier', 'structures légères'],
                'description' => 'Matériau léger et résistant, idéal pour les grandes surfaces'
            ],
            [
                'name' => 'Fer forgé',
                'properties' => ['traditionnel', 'décoratif', 'artisanal'],
                'uses' => ['portails', 'grilles', 'mobilier classique'],
                'description' => 'Matériau noble pour des créations authentiques et décoratives'
            ]
        ];
    }
    
    /**
     * Initialiser les localisations
     */
    private function init_locations() {
        $this->locations = [
            'primary' => [
                'city' => 'Thiers',
                'postal_code' => '63300',
                'department' => 'Puy-de-Dôme',
                'region' => 'Auvergne-Rhône-Alpes',
                'coordinates' => ['45.8566', '3.5529']
            ],
            'intervention_radius' => '50 km',
            'cities' => [
                ['Thiers', '63300', 'Siège social'],
                ['Clermont-Ferrand', '63000', 'Préfecture - 35km'],
                ['Riom', '63200', 'Ville historique - 25km'],
                ['Issoire', '63500', 'Ville d\'Auvergne - 30km'],
                ['Vichy', '03200', 'Station thermale - 45km'],
                ['Peschadoires', '63570', 'Localisation atelier'],
                ['Lezoux', '63190', 'Cité de la céramique - 20km'],
                ['Courpière', '63120', 'Ville industrielle - 15km'],
                ['Ambert', '63600', 'Ville papetière - 40km'],
                ['Cournon-d\'Auvergne', '63800', 'Sud de Clermont - 40km'],
                ['Aubière', '63170', 'Agglo de Clermont - 35km'],
                ['Billom', '63160', 'Ville médiévale - 30km'],
                ['Pont-du-Château', '63440', 'Confluence Allier-Ambert - 25km']
            ]
        ];
    }
    
    /**
     * Initialiser les réalisations
     */
    private function init_realisations() {
        $this->realisations = [
            [
                'title' => 'Portail coulissant moderne à Clermont-Ferrand',
                'type' => 'portail',
                'materials' => ['acier thermolaqué noir'],
                'features' => ['motorisation', 'interphonie vidéo', '5m de largeur'],
                'year' => '2024',
                'location' => 'Clermont-Ferrand'
            ],
            [
                'title' => 'Garde-corps design pour escalier à Riom',
                'type' => 'garde-corps',
                'materials' => ['acier inox brossé'],
                'features' => ['verre trempé', 'fixations invisibles', 'normes ERP'],
                'year' => '2024',
                'location' => 'Riom'
            ],
            [
                'title' => 'Escalier hélicoïdal sur mesure à Issoire',
                'type' => 'escalier',
                'materials' => ['acier et bois'],
                'features' => ['diamètre 2m', 'marches bois chêne', 'limon central'],
                'year' => '2023',
                'location' => 'Issoire'
            ],
            [
                'title' => 'Pergola bioclimatique à Vichy',
                'type' => 'pergola',
                'materials' => ['aluminium anthracite'],
                'features' => ['lames orientables', 'éclairage LED', '30m²'],
                'year' => '2024',
                'location' => 'Vichy'
            ],
            [
                'title' => 'Verrière d\'atelier style industriel',
                'type' => 'verrière',
                'materials' => ['acier brut, double vitrage'],
                'features' => ['structure apparente', '50m²', 'pente 15°'],
                'year' => '2023',
                'location' => 'Thiers'
            ],
            [
                'title' => 'Ensemble mobilier pour restaurant',
                'type' => 'mobilier',
                'materials' => ['acier et bois massif'],
                'features' => ['10 tables + 40 chaises', 'design industriel', 'traitement alimentaire'],
                'year' => '2024',
                'location' => 'Clermont-Ferrand'
            ],
            [
                'title' => 'Grille de sécurité sur mesure',
                'type' => 'sécurité',
                'materials' => ['acier galvanisé'],
                'features' => ['3m x 2.5m', 'soudure points', 'peinture grise'],
                'year' => '2023',
                'location' => 'Lezoux'
            ],
            [
                'title' => 'Porte à galandage design',
                'type' => 'porte',
                'materials' => ['aluminium verre'],
                'features' => ['système encastré', 'poignée design', '2.4m x 3m'],
                'year' => '2024',
                'location' => 'Courpière'
            ]
        ];
    }
    
    /**
     * Obtenir les données de l'entreprise
     */
    public function get_company_data() {
        return $this->company_data;
    }
    
    /**
     * Obtenir les services
     */
    public function get_services() {
        return $this->services;
    }
    
    /**
     * Obtenir un service par slug
     */
    public function get_service($slug) {
        foreach ($this->services as $service) {
            if ($service['slug'] === $slug) {
                return $service;
            }
        }
        return null;
    }
    
    /**
     * Obtenir les matériaux
     */
    public function get_materials() {
        return $this->materials;
    }
    
    /**
     * Obtenir les localisations
     */
    public function get_locations() {
        return $this->locations;
    }
    
    /**
     * Obtenir une localisation aléatoire
     */
    public function get_random_location() {
        $cities = $this->locations['cities'];
        return $cities[array_rand($cities)];
    }
    
    /**
     * Obtenir les réalisations
     */
    public function get_realisations() {
        return $this->realisations;
    }
    
    /**
     * Obtenir une réalisation aléatoire
     */
    public function get_random_realisation() {
        return $this->realisations[array_rand($this->realisations)];
    }
    
    /**
     * Obtenir les réalisations par type
     */
    public function get_realisations_by_type($type) {
        return array_filter($this->realisations, function($realisation) use ($type) {
            return $realisation['type'] === $type;
        });
    }
    
    /**
     * Obtenir les arguments de vente
     */
    public function get_selling_points() {
        return [
            'Artisan local de confiance depuis plus de 10 ans',
            'Fabrication 100% sur-mesure dans nos ateliers de Thiers',
            'Devis gratuit et personnalisé sous 48h',
            'Ouvert 6 jours sur 7 pour vous conseiller',
            'Formations soudure uniques en Auvergne',
            'Note exceptionnelle 5.0/5 sur Google (8 avis)',
            'Intervention dans un rayon de 50km autour de Thiers',
            'Tous matériaux : acier, inox, aluminium, thermolaqué',
            'Garantie décennale sur toutes nos réalisations',
            'Références solides dans toute la région Auvergne'
        ];
    }
    
    /**
     * Obtenir les certifications
     */
    public function get_certifications() {
        return [
            'Qualification professionnelle en soudure',
            'Certification RGE (Reconnu Garant de l\'Environnement)',
            'Assurance professionnelle décennale',
            'Normes NF P01-012 pour garde-corps',
            'Marque NF pour portails et motorisation'
        ];
    }
}
