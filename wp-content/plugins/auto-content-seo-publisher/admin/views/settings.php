<?php
/**
 * Settings View
 */

// Obtenir les options actuelles
$settings = [
    'enable_auto_generation' => get_option('acsp_enable_auto_generation', 1),
    'frequency' => get_option('acsp_frequency', 'weekly'),
    'publish_day' => get_option('acsp_publish_day', 'monday'),
    'publish_time' => get_option('acsp_publish_time', '08:00'),
    'post_status' => get_option('acsp_post_status', 'draft'),
    'min_word_count' => get_option('acsp_min_word_count', 1000),
    'max_word_count' => get_option('acsp_max_word_count', 1500),
    'min_seo_score' => get_option('acsp_min_seo_score', 70),
    'tone' => get_option('acsp_tone', 'professional'),
    'author_name' => get_option('acsp_author_name', 'Équipe AL Métallerie'),
    'signature_enabled' => get_option('acsp_signature_enabled', 1),
    'image_source' => get_option('acsp_image_source', 'unsplash'),
    'image_width' => get_option('acsp_image_width', 1200),
    'image_height' => get_option('acsp_image_height', 630),
    'notification_email' => get_option('acsp_notification_email', 'contact@al-metallerie.fr'),
    'notify_on_publish' => get_option('acsp_notify_on_publish', 1),
    'notify_on_error' => get_option('acsp_notify_on_error', 1)
];

// Types d'articles activés
$enabled_types = get_option('acsp_enabled_types', [
    'guide', 'trend', 'tutorial', 'case_study', 'faq', 'comparison', 'inspiration'
]);

// Mots-clés principaux
$primary_keywords = get_option('acsp_primary_keywords', []);

// Localisations
$locations = get_option('acsp_locations', []);
?>

<div class="acsp-wrapper">
    <div class="acsp-header">
        <h1>Auto Content SEO Publisher</h1>
        <span class="version">v<?php echo ACSP_VERSION; ?></span>
    </div>
    
    <div class="acsp-dashboard">
        <h2>Paramètres du plugin</h2>
        
        <form id="acsp-settings-form" method="post">
            <!-- Onglets -->
            <div class="acsp-settings-tabs">
                <button type="button" class="acsp-tab active" data-tab="tab-scheduling">
                    Planification
                </button>
                <button type="button" class="acsp-tab" data-tab="tab-seo">
                    SEO
                </button>
                <button type="button" class="acsp-tab" data-tab="tab-content">
                    Contenu
                </button>
                <button type="button" class="acsp-tab" data-tab="tab-images">
                    Images
                </button>
                <button type="button" class="acsp-tab" data-tab="tab-notifications">
                    Notifications
                </button>
            </div>
            
            <!-- Onglet Planification -->
            <div id="tab-scheduling" class="acsp-tab-content active">
                <div class="acsp-form-section">
                    <h3>Planification automatique</h3>
                    
                    <div class="acsp-form-row">
                        <div class="acsp-form-group">
                            <label for="enable_auto_generation">
                                <input type="checkbox" name="enable_auto_generation" 
                                       value="1" <?php checked($settings['enable_auto_generation']); ?>>
                                Activer la génération automatique
                            </label>
                            <p class="description">
                                Cochez pour activer la génération automatique d'articles
                            </p>
                        </div>
                    </div>
                    
                    <div class="acsp-form-row">
                        <div class="acsp-form-group">
                            <label for="frequency">Fréquence</label>
                            <select name="frequency" id="frequency">
                                <option value="weekly" <?php selected($settings['frequency'], 'weekly'); ?>>
                                    Hebdomadaire
                                </option>
                                <option value="biweekly" <?php selected($settings['frequency'], 'biweekly'); ?>>
                                    Bi-hebdomadaire
                                </option>
                                <option value="monthly" <?php selected($settings['frequency'], 'monthly'); ?>>
                                    Mensuelle
                                </option>
                            </select>
                        </div>
                        
                        <div class="acsp-form-group">
                            <label for="publish_day">Jour de publication</label>
                            <select name="publish_day" id="publish_day">
                                <option value="monday" <?php selected($settings['publish_day'], 'monday'); ?>>
                                    Lundi
                                </option>
                                <option value="tuesday" <?php selected($settings['publish_day'], 'tuesday'); ?>>
                                    Mardi
                                </option>
                                <option value="wednesday" <?php selected($settings['publish_day'], 'wednesday'); ?>>
                                    Mercredi
                                </option>
                                <option value="thursday" <?php selected($settings['publish_day'], 'thursday'); ?>>
                                    Jeudi
                                </option>
                                <option value="friday" <?php selected($settings['publish_day'], 'friday'); ?>>
                                    Vendredi
                                </option>
                                <option value="saturday" <?php selected($settings['publish_day'], 'saturday'); ?>>
                                    Samedi
                                </option>
                                <option value="sunday" <?php selected($settings['publish_day'], 'sunday'); ?>>
                                    Dimanche
                                </option>
                            </select>
                        </div>
                        
                        <div class="acsp-form-group">
                            <label for="publish_time">Heure de publication</label>
                            <input type="time" name="publish_time" id="publish_time" 
                                   value="<?php echo esc_attr($settings['publish_time']); ?>">
                        </div>
                    </div>
                    
                    <div class="acsp-form-row">
                        <div class="acsp-form-group">
                            <label for="post_status">Statut de publication</label>
                            <select name="post_status" id="post_status">
                                <option value="draft" <?php selected($settings['post_status'], 'draft'); ?>>
                                    Brouillon (recommandé)
                                </option>
                                <option value="publish" <?php selected($settings['post_status'], 'publish'); ?>>
                                    Publication directe
                                </option>
                                <option value="private" <?php selected($settings['post_status'], 'private'); ?>>
                                    Privé
                                </option>
                            </select>
                            <p class="description">
                                Publication directe non recommandée pour vérifier le contenu
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Onglet SEO -->
            <div id="tab-seo" class="acsp-tab-content">
                <div class="acsp-form-section">
                    <h3>Optimisation SEO</h3>
                    
                    <div class="acsp-form-row">
                        <div class="acsp-form-group">
                            <label for="min_word_count">Nombre de mots minimum</label>
                            <input type="number" name="min_word_count" id="min_word_count" 
                                   value="<?php echo esc_attr($settings['min_word_count']); ?>" min="300" max="3000">
                        </div>
                        
                        <div class="acsp-form-group">
                            <label for="max_word_count">Nombre de mots maximum</label>
                            <input type="number" name="max_word_count" id="max_word_count" 
                                   value="<?php echo esc_attr($settings['max_word_count']); ?>" min="500" max="5000">
                        </div>
                        
                        <div class="acsp-form-group">
                            <label for="min_seo_score">Score SEO minimum requis</label>
                            <input type="number" name="min_seo_score" id="min_seo_score" 
                                   value="<?php echo esc_attr($settings['min_seo_score']); ?>" min="0" max="100">
                            <p class="description">
                                Articles avec score < ce seuil marqués comme "à améliorer"
                            </p>
                        </div>
                    </div>
                    
                    <div class="acsp-form-section">
                        <h3>Mots-clés principaux</h3>
                        <p class="description">
                            Ajoutez les mots-clés sur lesquels vous souhaitez vous positionner
                        </p>
                        
                        <div class="acsp-form-row">
                            <div class="acsp-form-group">
                                <label for="new_keyword">Nouveau mot-clé</label>
                                <input type="text" id="new_keyword" placeholder="Ex: portail acier thiers">
                            </div>
                            <div class="acsp-form-group">
                                <label for="keyword_category">Catégorie</label>
                                <select id="keyword_category">
                                    <option value="portails">Portails</option>
                                    <option value="garde_corps">Garde-corps</option>
                                    <option value="escaliers">Escaliers</option>
                                    <option value="pergolas">Pergolas</option>
                                    <option value="mobilier">Mobilier</option>
                                    <option value="soudure">Soudure</option>
                                    <option value="general">Général</option>
                                </select>
                            </div>
                            <div class="acsp-form-group" style="justify-content: flex-end;">
                                <button type="button" id="acsp-add-keyword" class="button">
                                    Ajouter
                                </button>
                            </div>
                        </div>
                        
                        <table class="wp-list-table widefat fixed striped" id="acsp-keywords-list">
                            <thead>
                                <tr>
                                    <th>Mot-clé</th>
                                    <th>Catégorie</th>
                                    <th>Utilisations</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                global $wpdb;
                                $table_keywords = $wpdb->prefix . 'acsp_keywords';
                                $keywords = $wpdb->get_results("SELECT * FROM $table_keywords ORDER BY times_used ASC");
                                
                                foreach ($keywords as $keyword): 
                                ?>
                                    <tr>
                                        <td><?php echo esc_html($keyword->keyword); ?></td>
                                        <td><?php echo esc_html($keyword->category); ?></td>
                                        <td><?php echo $keyword->times_used; ?></td>
                                        <td>
                                            <button type="button" class="button button-small acsp-delete-keyword" 
                                                    data-id="<?php echo $keyword->id; ?>">
                                                Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="acsp-form-section">
                        <h3>Localisation</h3>
                        <p class="description">
                            Villes et régions à cibler dans les articles
                        </p>
                        
                        <div class="acsp-form-row">
                            <div class="acsp-form-group">
                                <label for="locations">Localisations (une par ligne)</label>
                                <textarea name="locations" id="locations" rows="5" 
                                          placeholder="Thiers&#10;Clermont-Ferrand&#10;Riom&#10;Issoire&#10;Vichy"><?php 
                                    echo esc_textarea(implode("\n", $locations)); 
                                ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Onglet Contenu -->
            <div id="tab-content" class="acsp-tab-content">
                <div class="acsp-form-section">
                    <h3>Type de contenu</h3>
                    
                    <div class="acsp-form-row">
                        <div class="acsp-form-group">
                            <label for="tone">Ton des articles</label>
                            <select name="tone" id="tone">
                                <option value="professional" <?php selected($settings['tone'], 'professional'); ?>>
                                    Professionnel
                                </option>
                                <option value="casual" <?php selected($settings['tone'], 'casual'); ?>>
                                    Décontracté
                                </option>
                                <option value="mixed" <?php selected($settings['tone'], 'mixed'); ?>>
                                    Mixte
                                </option>
                            </select>
                        </div>
                        
                        <div class="acsp-form-group">
                            <label for="author_name">Nom de l'auteur</label>
                            <input type="text" name="author_name" id="author_name" 
                                   value="<?php echo esc_attr($settings['author_name']); ?>">
                        </div>
                    </div>
                    
                    <div class="acsp-form-row">
                        <div class="acsp-form-group">
                            <label>
                                <input type="checkbox" name="signature_enabled" 
                                       value="1" <?php checked($settings['signature_enabled']); ?>>
                                Ajouter la signature AL Métallerie
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="acsp-form-section">
                    <h3>Types d'articles activés</h3>
                    <p class="description">
                        Cochez les types d'articles à générer automatiquement
                    </p>
                    
                    <div class="acsp-checkbox-group">
                        <div class="acsp-checkbox-item">
                            <label>
                                <input type="checkbox" name="enabled_types[]" value="guide"
                                       <?php checked(in_array('guide', $enabled_types)); ?>>
                                Guides pratiques
                            </label>
                        </div>
                        <div class="acsp-checkbox-item">
                            <label>
                                <input type="checkbox" name="enabled_types[]" value="trend"
                                       <?php checked(in_array('trend', $enabled_types)); ?>>
                                Tendances
                            </label>
                        </div>
                        <div class="acsp-checkbox-item">
                            <label>
                                <input type="checkbox" name="enabled_types[]" value="tutorial"
                                       <?php checked(in_array('tutorial', $enabled_types)); ?>>
                                Tutoriels
                            </label>
                        </div>
                        <div class="acsp-checkbox-item">
                            <label>
                                <input type="checkbox" name="enabled_types[]" value="case_study"
                                       <?php checked(in_array('case_study', $enabled_types)); ?>>
                                Études de cas
                            </label>
                        </div>
                        <div class="acsp-checkbox-item">
                            <label>
                                <input type="checkbox" name="enabled_types[]" value="faq"
                                       <?php checked(in_array('faq', $enabled_types)); ?>>
                                FAQ
                            </label>
                        </div>
                        <div class="acsp-checkbox-item">
                            <label>
                                <input type="checkbox" name="enabled_types[]" value="comparison"
                                       <?php checked(in_array('comparison', $enabled_types)); ?>>
                                Comparatifs
                            </label>
                        </div>
                        <div class="acsp-checkbox-item">
                            <label>
                                <input type="checkbox" name="enabled_types[]" value="inspiration"
                                       <?php checked(in_array('inspiration', $enabled_types)); ?>>
                Inspirations
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Onglet Images -->
            <div id="tab-images" class="acsp-tab-content">
                <div class="acsp-form-section">
                    <h3>Source des images</h3>
                    
                    <div class="acsp-form-row">
                        <div class="acsp-form-group">
                            <label for="image_source">Source principale</label>
                            <select name="image_source" id="image_source">
                                <option value="unsplash" <?php selected($settings['image_source'], 'unsplash'); ?>>
                                    Unsplash (gratuit)
                                </option>
                                <option value="pexels" <?php selected($settings['image_source'], 'pexels'); ?>>
                                    Pexels (gratuit)
                                </option>
                                <option value="site" <?php selected($settings['image_source'], 'site'); ?>>
                                    Réalisations du site
                                </option>
                                <option value="generated" <?php selected($settings['image_source'], 'generated'); ?>>
                                    Générées
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="acsp-form-row">
                        <div class="acsp-form-group">
                            <label for="image_width">Largeur (px)</label>
                            <input type="number" name="image_width" id="image_width" 
                                   value="<?php echo esc_attr($settings['image_width']); ?>" min="800" max="2000">
                        </div>
                        
                        <div class="acsp-form-group">
                            <label for="image_height">Hauteur (px)</label>
                            <input type="number" name="image_height" id="image_height" 
                                   value="<?php echo esc_attr($settings['image_height']); ?>" min="400" max="1200">
                        </div>
                    </div>
                </div>
                
                <div class="acsp-form-section">
                    <h3>Importer des images</h3>
                    <p class="description">
                        Importez des images depuis Unsplash pour enrichir vos articles
                    </p>
                    
                    <div class="acsp-form-row">
                        <div class="acsp-form-group">
                            <label for="image_query">Terme de recherche</label>
                            <input type="text" id="image_query" placeholder="Ex: metal gate welding">
                        </div>
                        
                        <div class="acsp-form-group">
                            <label for="image_count">Nombre d'images</label>
                            <input type="number" id="image_count" value="5" min="1" max="20">
                        </div>
                        
                        <div class="acsp-form-group" style="justify-content: flex-end;">
                            <button type="button" id="acsp-import-images" class="button">
                                Importer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Onglet Notifications -->
            <div id="tab-notifications" class="acsp-tab-content">
                <div class="acsp-form-section">
                    <h3>Email de notification</h3>
                    
                    <div class="acsp-form-row">
                        <div class="acsp-form-group">
                            <label for="notification_email">Email destinataire</label>
                            <input type="email" name="notification_email" id="notification_email" 
                                   value="<?php echo esc_attr($settings['notification_email']); ?>">
                        </div>
                    </div>
                    
                    <div class="acsp-form-row">
                        <div class="acsp-form-group">
                            <label>
                                <input type="checkbox" name="notify_on_publish" 
                                       value="1" <?php checked($settings['notify_on_publish']); ?>>
                                Notifier lors de la publication
                            </label>
                        </div>
                        
                        <div class="acsp-form-group">
                            <label>
                                <input type="checkbox" name="notify_on_error" 
                                       value="1" <?php checked($settings['notify_on_error']); ?>>
                                Notifier en cas d'erreur
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bouton de sauvegarde -->
            <p class="submit">
                <button type="submit" class="button button-primary">
                    Sauvegarder les modifications
                </button>
            </p>
        </form>
    </div>
</div>
