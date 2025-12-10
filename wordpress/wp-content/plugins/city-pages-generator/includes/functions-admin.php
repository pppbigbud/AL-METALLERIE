<?php
/**
 * Fonctions Admin - City Pages Generator
 */

if (!defined('ABSPATH')) exit;

// Menu admin
add_action('admin_menu', 'cpg_admin_menu');
function cpg_admin_menu() {
    add_submenu_page('edit.php?post_type=city_page', 'Générateur', 'Générateur', 'manage_options', 'cpg-generator', 'cpg_render_generator_page');
    add_submenu_page('edit.php?post_type=city_page', 'Import/Export', 'Import/Export', 'manage_options', 'cpg-import', 'cpg_render_import_page');
    add_submenu_page('edit.php?post_type=city_page', 'Paramètres', 'Paramètres', 'manage_options', 'cpg-settings', 'cpg_render_settings_page');
}

// Styles admin
add_action('admin_enqueue_scripts', 'cpg_admin_styles');
function cpg_admin_styles($hook) {
    if (strpos($hook, 'city_page') === false && strpos($hook, 'cpg-') === false) return;
    wp_add_inline_style('wp-admin', '
        .cpg-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:15px; margin:20px 0; }
        .cpg-stat { background:#fff; padding:20px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,.1); text-align:center; }
        .cpg-stat-num { font-size:2.5em; font-weight:700; color:#F08B18; }
        .cpg-box { background:#fff; padding:20px; margin:20px 0; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,.1); }
        .cpg-box h2 { margin-top:0; border-bottom:2px solid #F08B18; padding-bottom:10px; }
        .cpg-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
        @media(max-width:782px) { .cpg-stats,.cpg-grid { grid-template-columns:1fr; } }
    ');
}

// Page Générateur
function cpg_render_generator_page() {
    $total = wp_count_posts('city_page');
    $msg = '';
    
    if (isset($_POST['cpg_generate']) && wp_verify_nonce($_POST['cpg_nonce'], 'cpg_gen')) {
        $result = cpg_create_city_page($_POST);
        $msg = is_wp_error($result) 
            ? '<div class="notice notice-error"><p>'.$result->get_error_message().'</p></div>'
            : '<div class="notice notice-success"><p>Page créée ! <a href="'.get_edit_post_link($result).'">Modifier</a> | <a href="'.get_permalink($result).'" target="_blank">Voir</a></p></div>';
    }
    ?>
    <div class="wrap">
        <h1><span class="dashicons dashicons-location-alt" style="color:#F08B18"></span> Générateur de Pages Ville</h1>
        
        <div class="cpg-stats">
            <div class="cpg-stat"><div class="cpg-stat-num"><?php echo $total->publish ?? 0; ?></div><div>Publiées</div></div>
            <div class="cpg-stat"><div class="cpg-stat-num"><?php echo $total->draft ?? 0; ?></div><div>Brouillons</div></div>
            <div class="cpg-stat"><div class="cpg-stat-num"><?php echo ($total->publish ?? 0) + ($total->draft ?? 0); ?></div><div>Total</div></div>
            <div class="cpg-stat"><div class="cpg-stat-num">4</div><div>Variations</div></div>
        </div>
        
        <?php echo $msg; ?>
        
        <div class="cpg-grid">
            <div class="cpg-box">
                <h2>Nouvelle page ville</h2>
                <form method="post">
                    <?php wp_nonce_field('cpg_gen', 'cpg_nonce'); ?>
                    <table class="form-table">
                        <tr><th>Ville *</th><td><input type="text" name="city_name" class="regular-text" required></td></tr>
                        <tr><th>Code postal *</th><td><input type="text" name="postal_code" class="small-text" maxlength="5" required></td></tr>
                        <tr><th>Département</th><td>
                            <select name="department">
                                <option value="Puy-de-Dôme">Puy-de-Dôme (63)</option>
                                <option value="Allier">Allier (03)</option>
                                <option value="Cantal">Cantal (15)</option>
                                <option value="Haute-Loire">Haute-Loire (43)</option>
                                <option value="Loire">Loire (42)</option>
                            </select>
                        </td></tr>
                        <tr><th>Priorité</th><td>
                            <select name="priority">
                                <option value="1">1 - Haute</option>
                                <option value="2" selected>2 - Moyenne</option>
                                <option value="3">3 - Basse</option>
                            </select>
                        </td></tr>
                        <tr><th>Distance (km)</th><td><input type="number" name="distance_km" class="small-text" step="0.1"></td></tr>
                        <tr><th>Temps trajet</th><td><input type="text" name="travel_time" placeholder="25 minutes"></td></tr>
                        <tr><th>Spécificités</th><td><textarea name="local_specifics" rows="2" class="large-text"></textarea></td></tr>
                        <tr><th>Communes proches</th><td><textarea name="nearby_cities" rows="2" class="large-text" placeholder="Une par ligne"></textarea></td></tr>
                        <tr><th>Statut</th><td>
                            <select name="post_status">
                                <option value="draft">Brouillon</option>
                                <option value="publish">Publier</option>
                            </select>
                        </td></tr>
                    </table>
                    <p class="submit"><input type="submit" name="cpg_generate" class="button button-primary button-large" value="Générer la page"></p>
                </form>
            </div>
            
            <div class="cpg-box">
                <h2>Dernières pages</h2>
                <?php
                $recent = get_posts(['post_type'=>'city_page','posts_per_page'=>10,'orderby'=>'date','order'=>'DESC']);
                if ($recent) {
                    echo '<table class="widefat striped"><thead><tr><th>Ville</th><th>Statut</th><th>Actions</th></tr></thead><tbody>';
                    foreach ($recent as $p) {
                        $city = get_post_meta($p->ID, '_cpg_city_name', true) ?: $p->post_title;
                        $st = $p->post_status === 'publish' ? '<span style="color:green">Publié</span>' : '<span style="color:orange">Brouillon</span>';
                        echo "<tr><td><strong>".esc_html($city)."</strong></td><td>$st</td><td><a href='".get_edit_post_link($p->ID)."'>Modifier</a></td></tr>";
                    }
                    echo '</tbody></table>';
                } else echo '<p>Aucune page.</p>';
                ?>
            </div>
        </div>
    </div>
    <?php
}

// Page Import/Export
function cpg_render_import_page() {
    if (isset($_POST['cpg_export']) && wp_verify_nonce($_POST['cpg_nonce'], 'cpg_exp')) {
        cpg_export_csv();
    }
    
    $msg = '';
    if (isset($_POST['cpg_import']) && wp_verify_nonce($_POST['cpg_nonce'], 'cpg_imp') && !empty($_FILES['csv_file']['tmp_name'])) {
        $count = cpg_import_csv($_FILES['csv_file'], isset($_POST['as_draft']));
        $msg = '<div class="notice notice-success"><p>'.$count.' ville(s) importée(s).</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Import / Export</h1>
        <?php echo $msg; ?>
        <div class="cpg-grid">
            <div class="cpg-box">
                <h2>Exporter</h2>
                <form method="post"><?php wp_nonce_field('cpg_exp', 'cpg_nonce'); ?>
                    <p><input type="submit" name="cpg_export" class="button button-primary" value="Télécharger CSV"></p>
                </form>
            </div>
            <div class="cpg-box">
                <h2>Importer</h2>
                <form method="post" enctype="multipart/form-data"><?php wp_nonce_field('cpg_imp', 'cpg_nonce'); ?>
                    <p><input type="file" name="csv_file" accept=".csv" required></p>
                    <p><label><input type="checkbox" name="as_draft" checked> En brouillon</label></p>
                    <p><input type="submit" name="cpg_import" class="button" value="Importer"></p>
                </form>
                <p style="margin-top:20px"><strong>Format :</strong> <code>ville;code_postal;departement;priorite;distance;temps</code></p>
            </div>
        </div>
    </div>
    <?php
}

// Page Paramètres
function cpg_render_settings_page() {
    if (isset($_POST['cpg_save']) && wp_verify_nonce($_POST['cpg_nonce'], 'cpg_set')) {
        update_option('cpg_settings', [
            'company_name' => sanitize_text_field($_POST['company_name']),
            'workshop_city' => sanitize_text_field($_POST['workshop_city']),
            'phone' => sanitize_text_field($_POST['phone']),
            'email' => sanitize_email($_POST['email']),
            'address' => sanitize_text_field($_POST['address']),
        ]);
        echo '<div class="notice notice-success"><p>Enregistré.</p></div>';
    }
    
    $s = get_option('cpg_settings', [
        'company_name'=>'AL Métallerie & Soudure',
        'workshop_city'=>'Peschadoires',
        'phone'=>'06 73 33 35 32',
        'email'=>'contact@al-metallerie.fr',
        'address'=>'14 route de Maringues, 63920 Peschadoires'
    ]);
    ?>
    <div class="wrap">
        <h1>Paramètres</h1>
        <div class="cpg-box" style="max-width:600px">
            <h2>Entreprise</h2>
            <form method="post"><?php wp_nonce_field('cpg_set', 'cpg_nonce'); ?>
                <table class="form-table">
                    <tr><th>Nom</th><td><input type="text" name="company_name" value="<?php echo esc_attr($s['company_name']); ?>" class="regular-text"></td></tr>
                    <tr><th>Ville atelier</th><td><input type="text" name="workshop_city" value="<?php echo esc_attr($s['workshop_city']); ?>" class="regular-text"></td></tr>
                    <tr><th>Adresse</th><td><input type="text" name="address" value="<?php echo esc_attr($s['address']); ?>" class="large-text"></td></tr>
                    <tr><th>Téléphone</th><td><input type="text" name="phone" value="<?php echo esc_attr($s['phone']); ?>" class="regular-text"></td></tr>
                    <tr><th>Email</th><td><input type="email" name="email" value="<?php echo esc_attr($s['email']); ?>" class="regular-text"></td></tr>
                </table>
                <p class="submit"><input type="submit" name="cpg_save" class="button button-primary" value="Enregistrer"></p>
            </form>
        </div>
    </div>
    <?php
}

// Export CSV
function cpg_export_csv() {
    $pages = get_posts(['post_type'=>'city_page','posts_per_page'=>-1,'post_status'=>'any']);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=villes-'.date('Y-m-d').'.csv');
    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
    fputcsv($out, ['ville','code_postal','departement','priorite','distance','temps','statut','url'], ';');
    foreach ($pages as $p) {
        fputcsv($out, [
            get_post_meta($p->ID,'_cpg_city_name',true),
            get_post_meta($p->ID,'_cpg_postal_code',true),
            get_post_meta($p->ID,'_cpg_department',true),
            get_post_meta($p->ID,'_cpg_priority',true),
            get_post_meta($p->ID,'_cpg_distance_km',true),
            get_post_meta($p->ID,'_cpg_travel_time',true),
            $p->post_status,
            get_permalink($p->ID)
        ], ';');
    }
    fclose($out);
    exit;
}

// Import CSV
function cpg_import_csv($file, $draft = true) {
    $h = fopen($file['tmp_name'], 'r');
    $n = 0; $i = 0;
    while (($r = fgetcsv($h, 0, ';')) !== false) {
        $n++;
        if ($n === 1 && $r[0] === 'ville') continue;
        if (empty($r[0])) continue;
        $result = cpg_create_city_page([
            'city_name'=>$r[0],
            'postal_code'=>$r[1] ?? '',
            'department'=>$r[2] ?? 'Puy-de-Dôme',
            'priority'=>$r[3] ?? 2,
            'distance_km'=>$r[4] ?? 0,
            'travel_time'=>$r[5] ?? '',
            'post_status'=>$draft ? 'draft' : 'publish'
        ]);
        if (!is_wp_error($result)) $i++;
    }
    fclose($h);
    return $i;
}

// Metabox
add_action('add_meta_boxes', function() {
    add_meta_box('cpg_info', 'Infos ville', 'cpg_metabox_render', 'city_page', 'side', 'high');
});

function cpg_metabox_render($post) {
    wp_nonce_field('cpg_meta', 'cpg_meta_nonce');
    $fields = ['city_name'=>'Ville','postal_code'=>'Code postal','department'=>'Département','priority'=>'Priorité','distance_km'=>'Distance (km)','travel_time'=>'Temps trajet'];
    foreach ($fields as $k=>$l) {
        $v = get_post_meta($post->ID, '_cpg_'.$k, true);
        echo "<p><label><strong>$l</strong></label><br><input type='text' name='cpg_$k' value='".esc_attr($v)."' class='widefat'></p>";
    }
}

add_action('save_post_city_page', function($id) {
    if (!isset($_POST['cpg_meta_nonce']) || !wp_verify_nonce($_POST['cpg_meta_nonce'], 'cpg_meta')) return;
    foreach (['city_name','postal_code','department','priority','distance_km','travel_time'] as $f) {
        if (isset($_POST['cpg_'.$f])) update_post_meta($id, '_cpg_'.$f, sanitize_text_field($_POST['cpg_'.$f]));
    }
});
