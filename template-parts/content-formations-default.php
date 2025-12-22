<?php
/**
 * Contenu par défaut de la page Formations
 * Utilisé comme fallback quand la page n'a pas encore de contenu dans l'éditeur.
 *
 * @package ALMetallerie
 * @since 1.0.0
 */
?>

<div class="archive-page formations-archive">
    <div class="archive-hero">
        <div class="container">
            <h1 class="archive-title">
                <svg class="archive-icon" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                </svg>
                <?php esc_html_e('Nos Formations en Ferronnerie', 'almetal'); ?>
            </h1>
            <p class="archive-subtitle">
                <?php
                printf(
                    /* translators: 1: strong tag start, 2: strong tag end */
                    __('Découvrez nos %1$sformations professionnelles en ferronnerie d\'art%2$s et métallerie. Que vous soyez %3$sparticulier passionné%4$s ou %5$sprofessionnel en reconversion%6$s, nous vous accompagnons dans l\'apprentissage des %7$stechniques traditionnelles%8$s et %9$stechniques modernes%10$s.', 'almetal'),
                    '<strong>',
                    '</strong>',
                    '<strong>',
                    '</strong>',
                    '<strong>',
                    '</strong>',
                    '<em>',
                    '</em>',
                    '<em>',
                    '</em>'
                );
                ?>
            </p>
        </div>
    </div>

    <div class="archive-content">
        <div class="container">
            <section class="formations-upcoming-section">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <?php esc_html_e('Prochaines sessions disponibles', 'almetal'); ?>
                </h2>
                <div class="formations-upcoming-wrapper">
                    <?php
                    if (shortcode_exists('training_upcoming')) {
                        echo do_shortcode('[training_upcoming count="4"]'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    } else {
                        echo '<p class="no-formations">' . esc_html__('Aucune formation programmée pour le moment. Contactez-nous pour connaître les prochaines dates.', 'almetal') . '</p>';
                    }
                    ?>
                </div>
            </section>

            <section class="formations-categories-section">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <?php esc_html_e('Nos types de formations', 'almetal'); ?>
                </h2>

                <div class="formations-categories-grid">
                    <a href="<?php echo esc_url(home_url('/formations-particuliers')); ?>" class="formation-category-card">
                        <div class="formation-category-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div class="formation-category-content">
                            <h3><?php esc_html_e('Formations Particuliers', 'almetal'); ?></h3>
                            <p><?php esc_html_e('Stages découverte et perfectionnement pour les passionnés. Initiez-vous à la ferronnerie d\'art dans notre atelier.', 'almetal'); ?></p>
                            <ul class="formation-category-features">
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <?php esc_html_e('Stages de 1 à 5 jours', 'almetal'); ?>
                                </li>
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <?php esc_html_e('Petits groupes (max 6)', 'almetal'); ?>
                                </li>
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <?php esc_html_e('Tous niveaux acceptés', 'almetal'); ?>
                                </li>
                            </ul>
                            <span class="formation-category-cta">
                                <?php esc_html_e('Voir les formations', 'almetal'); ?>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                            </span>
                        </div>
                    </a>

                    <a href="<?php echo esc_url(home_url('/formations-professionnelles/')); ?>" class="formation-category-card">
                        <div class="formation-category-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        </div>
                        <div class="formation-category-content">
                            <h3><?php esc_html_e('Formations Professionnels', 'almetal'); ?></h3>
                            <p><?php esc_html_e('Formations certifiantes et qualifiantes pour les professionnels du bâtiment et artisans en reconversion.', 'almetal'); ?></p>
                            <ul class="formation-category-features">
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <?php esc_html_e('Certification professionnelle', 'almetal'); ?>
                                </li>
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <?php esc_html_e('Formation de 3 à 12 mois', 'almetal'); ?>
                                </li>
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <?php esc_html_e('Financement CPF/Pôle Emploi', 'almetal'); ?>
                                </li>
                            </ul>
                            <span class="formation-category-cta">
                                <?php esc_html_e('Voir les formations', 'almetal'); ?>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                            </span>
                        </div>
                    </a>
                </div>
            </section>

            <section class="formations-calendar-section">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <?php esc_html_e('Calendrier des formations', 'almetal'); ?>
                </h2>
                <div class="formations-calendar-wrapper">
                    <?php
                    if (shortcode_exists('training_calendar')) {
                        echo do_shortcode('[training_calendar]'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    } else {
                        echo '<p class="no-calendar">' . esc_html__('Le calendrier des formations sera bientôt disponible.', 'almetal') . '</p>';
                    }
                    ?>
                </div>
            </section>

            <section class="formations-advantages-section">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <?php esc_html_e('Pourquoi choisir nos formations ?', 'almetal'); ?>
                </h2>
                <div class="formations-advantages-grid">
                    <?php
                    $advantages = array(
                        array(
                            'title'       => __('Atelier équipé', 'almetal'),
                            'description' => __('Accès à un atelier professionnel avec tout le matériel nécessaire : forge, enclume, outils de découpe et de soudure.', 'almetal'),
                            'icon'        => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>',
                        ),
                        array(
                            'title'       => __('Formateurs experts', 'almetal'),
                            'description' => __('Apprenez auprès d\'artisans ferronniers expérimentés, passionnés par la transmission de leur savoir-faire.', 'almetal'),
                            'icon'        => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
                        ),
                        array(
                            'title'       => __('Pratique intensive', 'almetal'),
                            'description' => __('80% de pratique pour maîtriser rapidement les gestes techniques et créer vos propres réalisations.', 'almetal'),
                            'icon'        => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>',
                        ),
                        array(
                            'title'       => __('Suivi personnalisé', 'almetal'),
                            'description' => __('Groupes restreints pour un accompagnement individualisé et adapté à votre niveau et vos objectifs.', 'almetal'),
                            'icon'        => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>',
                        ),
                        array(
                            'title'       => __('Repartez avec votre création', 'almetal'),
                            'description' => __('À la fin de chaque stage, repartez avec l\'objet que vous avez fabriqué : un souvenir unique.', 'almetal'),
                            'icon'        => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line>',
                        ),
                        array(
                            'title'       => __('Horaires flexibles', 'almetal'),
                            'description' => __('Des créneaux adaptés à vos disponibilités : stages en semaine, week-end ou vacances scolaires.', 'almetal'),
                            'icon'        => '<circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path>',
                        ),
                    );

                    foreach ($advantages as $advantage) :
                        ?>
                        <div class="advantage-card">
                            <div class="advantage-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <?php echo $advantage['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </svg>
                            </div>
                            <h3><?php echo esc_html($advantage['title']); ?></h3>
                            <p><?php echo esc_html($advantage['description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="formations-cta-section">
                <div class="formations-cta-content">
                    <h2><?php esc_html_e('Une question sur nos formations ?', 'almetal'); ?></h2>
                    <p><?php esc_html_e('Contactez-nous pour obtenir plus d\'informations ou pour réserver votre place.', 'almetal'); ?></p>
                    <div class="formations-cta-buttons">
                        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <?php esc_html_e('Nous contacter', 'almetal'); ?>
                        </a>
                        <a href="tel:+33673333532" class="btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            06 73 33 35 32
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
