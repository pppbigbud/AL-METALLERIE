<?php
/**
 * Template Name: Politique de Confidentialité
 * Description: Page de politique de confidentialité conforme RGPD
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();

// Détection mobile
if (almetal_is_mobile()) {
    get_template_part('template-parts/header', 'mobile');
    echo '<main class="mobile-legal-page mobile-politique-confidentialite">';
} else {
    echo '<main class="legal-page politique-confidentialite">';
}
?>
    <div class="legal-hero scroll-fade">
        <div class="container">
            <h1 class="legal-title scroll-fade scroll-delay-1">
                <svg class="legal-icon" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Politique de Confidentialité
            </h1>
            <p class="legal-subtitle scroll-fade scroll-delay-2">Protection de vos données personnelles - Conforme RGPD</p>
            <p class="legal-date scroll-fade scroll-delay-3">Dernière mise à jour : <?php echo date('d/m/Y'); ?></p>
        </div>
    </div>

    <div class="legal-content">
        <div class="container">
            
            <!-- Introduction -->
            <section class="legal-section intro-section scroll-fade">
                <div class="section-content">
                    <p class="intro-text">
                        AL Métallerie s'engage à protéger la confidentialité et la sécurité de vos données personnelles. 
                        Cette politique vous informe sur la collecte, l'utilisation et la protection de vos informations 
                        conformément au RGPD et à la loi Informatique et Libertés.
                    </p>
                </div>
            </section>

            <!-- Section 1 : Responsable -->
            <section class="legal-section scroll-fade">
                <h2 class="section-title">
                    <span class="section-number">01</span>
                    Responsable du traitement
                </h2>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Raison sociale :</strong>
                            <span>AL Métallerie</span>
                        </div>
                        <div class="info-item">
                            <strong>Responsable :</strong>
                            <span>LASTEYRAS Aurélien</span>
                        </div>
                        <div class="info-item">
                            <strong>Adresse :</strong>
                            <span>14 route de Maringues, 63920 Peschadoires</span>
                        </div>
                        <div class="info-item">
                            <strong>Email :</strong>
                            <span><a href="mailto:contact@al-metallerie.fr">contact@al-metallerie.fr</a></span>
                        </div>
                        <div class="info-item">
                            <strong>SIRET :</strong>
                            <span>891 729 477 00044</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 2 : Données collectées -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">02</span>
                    Données collectées
                </h2>
                <div class="section-content">
                    <h3>Formulaire de contact et devis</h3>
                    <ul class="legal-list">
                        <li>Nom, prénom</li>
                        <li>Email, téléphone</li>
                        <li>Type de projet, description</li>
                        <li>Adresse IP, date/heure</li>
                    </ul>
                    
                    <h3>Newsletter</h3>
                    <ul class="legal-list">
                        <li>Email</li>
                        <li>Préférences et historique</li>
                    </ul>
                    
                    <h3>Cookies et navigation</h3>
                    <ul class="legal-list">
                        <li>Adresse IP, navigateur</li>
                        <li>Pages visitées, durée</li>
                        <li>Source de trafic</li>
                    </ul>
                </div>
            </section>

            <!-- Section 3 : Finalités -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">03</span>
                    Utilisation des données
                </h2>
                <div class="section-content">
                    <ul class="legal-list">
                        <li><strong>Gestion des demandes :</strong> Traiter vos demandes de contact et devis</li>
                        <li><strong>Communication :</strong> Envoi de newsletters (avec consentement)</li>
                        <li><strong>Amélioration :</strong> Analyse du site via Google Analytics</li>
                        <li><strong>Obligations légales :</strong> Comptabilité, fiscalité</li>
                        <li><strong>Sécurité :</strong> Protection contre la fraude</li>
                    </ul>
                </div>
            </section>

            <!-- Section 4 : Destinataires -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">04</span>
                    Destinataires
                </h2>
                <div class="section-content">
                    <h3>Personnel autorisé</h3>
                    <p>Seules les personnes habilitées ont accès à vos données.</p>
                    
                    <h3>Prestataires</h3>
                    <ul class="legal-list">
                        <li><strong>O2switch :</strong> Hébergement (France)</li>
                        <li><strong>MailChimp :</strong> Newsletter (USA - Clauses contractuelles types)</li>
                        <li><strong>Google Analytics :</strong> Statistiques (USA - Data Privacy Framework)</li>
                        <li><strong>Google Maps :</strong> Cartographie (USA - Data Privacy Framework)</li>
                    </ul>
                </div>
            </section>

            <!-- Section 5 : Conservation -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">05</span>
                    Durée de conservation
                </h2>
                <div class="section-content">
                    <ul class="legal-list">
                        <li><strong>Demandes de devis :</strong> Conservation illimitée (gestion client)</li>
                        <li><strong>Contrats/factures :</strong> 10 ans (obligation légale)</li>
                        <li><strong>Newsletter :</strong> Jusqu'à désinscription ou 3 ans d'inactivité</li>
                        <li><strong>Cookies :</strong> 13 mois maximum</li>
                        <li><strong>Logs :</strong> 12 mois</li>
                    </ul>
                </div>
            </section>

            <!-- Section 6 : Sécurité -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">06</span>
                    Sécurité
                </h2>
                <div class="section-content">
                    <ul class="legal-list">
                        <li>Chiffrement SSL/TLS (HTTPS)</li>
                        <li>Hébergement sécurisé en France</li>
                        <li>Accès restreint et authentification forte</li>
                        <li>Sauvegardes régulières</li>
                        <li>Mises à jour de sécurité</li>
                    </ul>
                </div>
            </section>

            <!-- Section 7 : Vos droits -->
            <section class="legal-section highlight-section">
                <h2 class="section-title">
                    <span class="section-number">07</span>
                    Vos droits
                </h2>
                <div class="section-content">
                    <p>Vous disposez des droits suivants :</p>
                    <ul class="legal-list">
                        <li><strong>Accès :</strong> Obtenir une copie de vos données</li>
                        <li><strong>Rectification :</strong> Corriger vos données</li>
                        <li><strong>Effacement :</strong> Supprimer vos données (droit à l'oubli)</li>
                        <li><strong>Portabilité :</strong> Recevoir vos données dans un format structuré</li>
                        <li><strong>Opposition :</strong> Vous opposer au traitement</li>
                        <li><strong>Limitation :</strong> Limiter le traitement</li>
                    </ul>
                    
                    <h3>Exercer vos droits</h3>
                    <p>Contactez-nous :</p>
                    <ul class="legal-list">
                        <li><strong>Email :</strong> <a href="mailto:contact@al-metallerie.fr">contact@al-metallerie.fr</a></li>
                        <li><strong>Courrier :</strong> AL Métallerie, 14 route de Maringues, 63920 Peschadoires</li>
                    </ul>
                    <p>Délai de réponse : <strong>1 mois maximum</strong></p>
                </div>
            </section>

            <!-- Section 8 : Cookies -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">08</span>
                    Cookies
                </h2>
                <div class="section-content">
                    <h3>Types de cookies</h3>
                    <ul class="legal-list">
                        <li><strong>Essentiels :</strong> Fonctionnement du site (obligatoires)</li>
                        <li><strong>Analytiques :</strong> Google Analytics (mesure d'audience)</li>
                        <li><strong>Marketing :</strong> MailChimp (newsletter)</li>
                    </ul>
                    
                    <h3>Gestion des cookies</h3>
                    <p>Vous pouvez désactiver les cookies depuis votre navigateur :</p>
                    <ul class="legal-list">
                        <li><strong>Chrome :</strong> Paramètres > Confidentialité > Cookies</li>
                        <li><strong>Firefox :</strong> Options > Vie privée > Cookies</li>
                        <li><strong>Safari :</strong> Préférences > Confidentialité</li>
                        <li><strong>Edge :</strong> Paramètres > Confidentialité > Cookies</li>
                    </ul>
                </div>
            </section>

            <!-- Section 9 : Réclamation -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">09</span>
                    Réclamation
                </h2>
                <div class="section-content">
                    <p>Si vous estimez que vos droits ne sont pas respectés, vous pouvez introduire une réclamation auprès de la CNIL :</p>
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>CNIL :</strong>
                            <span>Commission Nationale de l'Informatique et des Libertés</span>
                        </div>
                        <div class="info-item">
                            <strong>Adresse :</strong>
                            <span>3 Place de Fontenoy, TSA 80715, 75334 Paris Cedex 07</span>
                        </div>
                        <div class="info-item">
                            <strong>Site web :</strong>
                            <span><a href="https://www.cnil.fr" target="_blank" rel="noopener">www.cnil.fr</a></span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 10 : Modifications -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">10</span>
                    Modifications
                </h2>
                <div class="section-content">
                    <p>Cette politique peut être modifiée à tout moment. La version en vigueur est celle publiée sur ce site.</p>
                    <p>Date de dernière mise à jour : <strong><?php echo date('d/m/Y'); ?></strong></p>
                </div>
            </section>

            <!-- Boutons -->
            <div class="legal-footer">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-back">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Retour à l'accueil
                </a>
                <a href="<?php echo esc_url(home_url('/mentions-legales')); ?>" class="btn-link">
                    Mentions légales
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <polyline points="12 5 19 12 12 19"/>
                    </svg>
                </a>
            </div>

        </div>
    </div>
</main>

<?php get_footer(); ?>
