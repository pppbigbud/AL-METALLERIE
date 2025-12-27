<?php
/**
 * Classe d'optimisation SEO
 */
class ACSP_SEO_Optimizer {
    
    /**
     * Optimiser le contenu
     */
    public function optimize_content($content, $keyword) {
        // Calculer le score SEO
        $content['seo_score'] = $this->calculate_seo_score($content, $keyword);
        
        // Optimiser la densité de mots-clés
        $content['content'] = $this->optimize_keyword_density($content['content'], $keyword->keyword);
        
        // Ajouter des variations de mots-clés
        $content['content'] = $this->add_keyword_variations($content['content'], $keyword);
        
        // Structurer les balises
        $content['content'] = $this->optimize_headings($content['content']);
        
        // Ajouter le fil d'Ariane
        $content['content'] = $this->add_breadcrumb($content);
        
        // Ajouter les balises Schema.org
        $content['schema'] = $this->generate_schema($content, $keyword);
        
        return $content;
    }
    
    /**
     * Calculer le score SEO
     */
    private function calculate_seo_score($content, $keyword) {
        $score = 0;
        $max_score = 100;
        
        // Longueur du contenu (20 points)
        $word_count = str_word_count(strip_tags($content['content']));
        $min_words = get_option('acsp_min_word_count', 1000);
        $max_words = get_option('acsp_max_word_count', 1500);
        
        if ($word_count >= $min_words && $word_count <= $max_words) {
            $score += 20;
        } elseif ($word_count >= $min_words * 0.8) {
            $score += 10;
        }
        
        // Densité de mots-clés (15 points)
        $density = $this->get_keyword_density($content['content'], $keyword->keyword);
        if ($density >= 1 && $density <= 2) {
            $score += 15;
        } elseif ($density >= 0.5 && $density < 1) {
            $score += 10;
        }
        
        // Titre optimisé (15 points)
        if (strlen($content['title']) <= 60 && stripos($content['title'], $keyword->keyword) !== false) {
            $score += 15;
        } elseif (stripos($content['title'], $keyword->keyword) !== false) {
            $score += 10;
        }
        
        // Meta description (15 points)
        if (strlen($content['meta_description']) >= 150 && strlen($content['meta_description']) <= 160) {
            $score += 10;
        }
        if (stripos($content['meta_description'], $keyword->keyword) !== false) {
            $score += 5;
        }
        
        // Structure H1-H6 (15 points)
        if (preg_match('/<h1[^>]*>/', $content['content']) && !preg_match('/<h1[^>]*>.*<h1[^>]*>/s', $content['content'])) {
            $score += 5; // H1 unique
        }
        
        $h2_count = preg_match_all('/<h2[^>]*>/', $content['content']);
        if ($h2_count >= 2 && $h2_count <= 5) {
            $score += 5;
        }
        
        $h3_count = preg_match_all('/<h3[^>]*>/', $content['content']);
        if ($h3_count >= 1) {
            $score += 5;
        }
        
        // Liens internes (10 points)
        $internal_links = $this->count_internal_links($content['content']);
        if ($internal_links >= 3 && $internal_links <= 5) {
            $score += 10;
        } elseif ($internal_links >= 2) {
            $score += 5;
        }
        
        // Images avec ALT (10 points)
        $img_count = preg_match_all('/<img[^>]*>/', $content['content']);
        if ($img_count > 0) {
            $img_with_alt = preg_match_all('/<img[^>]*alt="[^"]*"[^>]*>/', $content['content']);
            if ($img_with_alt === $img_count) {
                $score += 10;
            } elseif ($img_with_alt > 0) {
                $score += 5;
            }
        }
        
        return min($score, $max_score);
    }
    
    /**
     * Optimiser la densité de mots-clés
     */
    private function optimize_keyword_density($content, $keyword) {
        $word_count = str_word_count(strip_tags($content));
        $keyword_count = substr_count(strtolower(strip_tags($content)), strtolower($keyword));
        $current_density = ($keyword_count / $word_count) * 100;
        
        // Si la densité est trop faible, ajouter le mot-clé
        if ($current_density < 1) {
            // Ajouter dans le premier paragraphe si possible
            $content = preg_replace('/<p>(.*?)<\/p>/s', '<p>$1 ' . ucfirst($keyword) . '.</p>', $content, 1);
        }
        
        return $content;
    }
    
    /**
     * Ajouter des variations de mots-clés
     */
    private function add_keyword_variations($content, $keyword) {
        $variations = $this->get_keyword_variations($keyword);
        
        foreach ($variations as $variation) {
            if (strpos(strtolower($content), strtolower($variation)) === false) {
                // Ajouter la variation naturellement
                $content = str_replace('.', '. ' . ucfirst($variation) . '.', $content, 1);
                break; // Une seule variation pour éviter le sur-optimisation
            }
        }
        
        return $content;
    }
    
    /**
     * Optimiser les titres
     */
    private function optimize_headings($content) {
        // S'assurer que H1 est unique et premier
        if (preg_match('/<h1[^>]*>(.*?)<\/h1>/', $content, $matches)) {
            $h1_content = $matches[1];
            $content = preg_replace('/<h1[^>]*>.*?<\/h1>/s', '', $content, 1);
            $content = '<h1>' . $h1_content . '</h1>' . "\n" . $content;
        }
        
        return $content;
    }
    
    /**
     * Ajouter le fil d'Ariane
     */
    private function add_breadcrumb($content) {
        $breadcrumb = '<nav class="breadcrumb" aria-label="Fil d\'Ariane">
            <a href="/">Accueil</a> › 
            <a href="/blog/">Blog</a> › 
            <span class="current">' . strip_tags($content['title']) . '</span>
        </nav>';
        
        return $breadcrumb . "\n" . $content;
    }
    
    /**
     * Générer le Schema.org
     */
    private function generate_schema($content, $keyword) {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $content['title'],
            'description' => $content['meta_description'],
            'author' => [
                '@type' => 'Organization',
                'name' => 'AL Métallerie & Soudure',
                'url' => 'https://al-metallerie.fr'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'AL Métallerie & Soudure',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => 'https://al-metallerie.fr/wp-content/themes/almetal/assets/images/logo.png'
                ]
            ],
            'datePublished' => current_time('c'),
            'dateModified' => current_time('c'),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => home_url($content['slug'])
            ],
            'image' => $content['image_url'] ?? null,
            'keywords' => implode(', ', $content['keywords'])
        ];
        
        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Obtenir la densité de mots-clés
     */
    private function get_keyword_density($content, $keyword) {
        $word_count = str_word_count(strip_tags($content));
        $keyword_count = substr_count(strtolower(strip_tags($content)), strtolower($keyword));
        
        return $word_count > 0 ? ($keyword_count / $word_count) * 100 : 0;
    }
    
    /**
     * Compter les liens internes
     */
    private function count_internal_links($content) {
        $home_url = home_url();
        preg_match_all('/<a[^>]*href="([^"]*)"[^>]*>/', $content, $matches);
        
        $count = 0;
        foreach ($matches[1] as $url) {
            if (strpos($url, $home_url) === 0 || strpos($url, '/') === 0) {
                $count++;
            }
        }
        
        return $count;
    }
    
    /**
     * Obtenir les variations de mots-clés
     */
    private function get_keyword_variations($keyword) {
        $variations = [];
        
        // Variations basées sur le type de mot-clé
        if (strpos($keyword->keyword, 'portail') !== false) {
            $variations = [
                'portail sur mesure',
                'portail automatique',
                'portail sécurisé',
                'entrée de maison'
            ];
        } elseif (strpos($keyword->keyword, 'garde corps') !== false) {
            $variations = [
                'rampes de sécurité',
                'balustrade métallique',
                'protection balcon',
                'barrière de sécurité'
            ];
        } elseif (strpos($keyword->keyword, 'escalier') !== false) {
            $variations = [
                'escalier design',
                'escalier sur mesure',
                'circulation verticale',
                'accès étage'
            ];
        } elseif (strpos($keyword->keyword, 'soudure') !== false) {
            $variations = [
                'assemblage métallique',
                'travail des métaux',
                'technique de soudage',
                'fabrication sur mesure'
            ];
        }
        
        return $variations;
    }
}
