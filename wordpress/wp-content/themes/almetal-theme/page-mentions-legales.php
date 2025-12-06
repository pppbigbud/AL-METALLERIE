<?php
/**
 * Template Name: Mentions Légales
 * Description: Page des mentions légales conformes RGPD
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();

// Détection mobile
if (almetal_is_mobile()) {
    get_template_part('template-parts/header', 'mobile');
    echo '<main class="mobile-legal-page mobile-mentions-legales">';
} else {
    echo '<main class="legal-page mentions-legales">';
}
?>
    <div class="legal-hero scroll-fade">
        <div class="container">
            <h1 class="legal-title scroll-fade scroll-delay-1">
                <svg class="legal-icon" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
                Mentions Légales
            </h1>
            <p class="legal-subtitle scroll-fade scroll-delay-2">Informations légales et réglementaires</p>
            <p class="legal-date scroll-fade scroll-delay-3">Dernière mise à jour : <?php echo date('d/m/Y'); ?></p>
        </div>
    </div>

    <div class="legal-content">
        <div class="container">
            
            <!-- Section 1 : Éditeur du site -->
            <section class="legal-section scroll-fade">
                <h2 class="section-title">
                    <span class="section-number">01</span>
                    Éditeur du site
                </h2>
                <div class="section-content">
                    <p>Le site <strong><?php echo home_url(); ?></strong> est édité par :</p>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Raison sociale :</strong>
                            <span>AL Métallerie</span>
                        </div>
                        <div class="info-item">
                            <strong>Forme juridique :</strong>
                            <span>Entrepreneur individuel</span>
                        </div>
                        <div class="info-item">
                            <strong>Responsable légal :</strong>
                            <span>LASTEYRAS Aurélien</span>
                        </div>
                        <div class="info-item">
                            <strong>Siège social :</strong>
                            <span>14 route de Maringues<br>63920 Peschadoires<br>France</span>
                        </div>
                        <div class="info-item">
                            <strong>SIREN :</strong>
                            <span>891 729 477</span>
                        </div>
                        <div class="info-item">
                            <strong>SIRET :</strong>
                            <span>891 729 477 00044</span>
                        </div>
                        <div class="info-item">
                            <strong>Numéro de TVA intracommunautaire :</strong>
                            <span>FR29891729477</span>
                        </div>
                        <div class="info-item">
                            <strong>Code NAF/APE :</strong>
                            <span>43.32B - Travaux de menuiserie métallique et serrurerie</span>
                        </div>
                        <div class="info-item">
                            <strong>Activité principale :</strong>
                            <span>Métallerie - Travaux de construction spécialisés</span>
                        </div>
                        <div class="info-item">
                            <strong>Forme d'exercice :</strong>
                            <span>Artisanale réglementée</span>
                        </div>
                        <div class="info-item">
                            <strong>Convention collective :</strong>
                            <span>Métallurgie - IDCC 3248</span>
                        </div>
                        <div class="info-item">
                            <strong>Téléphone :</strong>
                            <span><a href="tel:+33673333532">06 73 33 35 32</a></span>
                        </div>
                        <div class="info-item">
                            <strong>Email :</strong>
                            <span><a href="mailto:contact@al-metallerie.fr">contact@al-metallerie.fr</a></span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 2 : Directeur de publication -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">02</span>
                    Directeur de publication
                </h2>
                <div class="section-content">
                    <p>Le directeur de la publication du site est <strong>Monsieur LASTEYRAS Aurélien</strong>, en sa qualité de responsable légal de l'entreprise.</p>
                    <p>Contact : <a href="mailto:contact@al-metallerie.fr">contact@al-metallerie.fr</a></p>
                </div>
            </section>

            <!-- Section 3 : Hébergement -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">03</span>
                    Hébergement du site
                </h2>
                <div class="section-content">
                    <p>Le site est hébergé par :</p>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Hébergeur :</strong>
                            <span>O2switch</span>
                        </div>
                        <div class="info-item">
                            <strong>Adresse :</strong>
                            <span>Chemin des Pardiaux<br>63000 Clermont-Ferrand<br>France</span>
                        </div>
                        <div class="info-item">
                            <strong>Téléphone :</strong>
                            <span>04 44 44 60 40</span>
                        </div>
                        <div class="info-item">
                            <strong>Site web :</strong>
                            <span><a href="https://www.o2switch.fr" target="_blank" rel="noopener">www.o2switch.fr</a></span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 4 : Assurance professionnelle -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">04</span>
                    Assurance professionnelle
                </h2>
                <div class="section-content">
                    <p>Conformément à la réglementation en vigueur, AL Métallerie dispose d'une assurance responsabilité civile professionnelle et décennale.</p>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Compagnie d'assurance :</strong>
                            <span>[À COMPLÉTER : Nom de la compagnie d'assurance]</span>
                        </div>
                        <div class="info-item">
                            <strong>Numéro de police :</strong>
                            <span>[À COMPLÉTER : Numéro de contrat]</span>
                        </div>
                        <div class="info-item">
                            <strong>Garanties :</strong>
                            <span>Responsabilité civile professionnelle<br>Assurance décennale</span>
                        </div>
                    </div>
                    
                    <p class="info-note">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        Les attestations d'assurance sont disponibles sur demande.
                    </p>
                </div>
            </section>

            <!-- Section 5 : Propriété intellectuelle -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">05</span>
                    Propriété intellectuelle
                </h2>
                <div class="section-content">
                    <p>L'ensemble du contenu de ce site (textes, images, vidéos, logos, icônes, sons, logiciels, etc.) est la propriété exclusive d'AL Métallerie ou de ses partenaires, et est protégé par les lois françaises et internationales relatives à la propriété intellectuelle.</p>
                    
                    <p>Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des éléments du site, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation écrite préalable d'AL Métallerie.</p>
                    
                    <p>Toute exploitation non autorisée du site ou de l'un quelconque des éléments qu'il contient sera considérée comme constitutive d'une contrefaçon et poursuivie conformément aux dispositions des articles L.335-2 et suivants du Code de Propriété Intellectuelle.</p>
                </div>
            </section>

            <!-- Section 6 : Données personnelles -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">06</span>
                    Protection des données personnelles
                </h2>
                <div class="section-content">
                    <p>Conformément au Règlement Général sur la Protection des Données (RGPD) et à la loi Informatique et Libertés, vous disposez d'un droit d'accès, de rectification, de suppression et d'opposition aux données personnelles vous concernant.</p>
                    
                    <p>Pour plus d'informations sur la collecte et le traitement de vos données personnelles, consultez notre <a href="<?php echo esc_url(home_url('/politique-confidentialite')); ?>">Politique de confidentialité</a>.</p>
                    
                    <p>Pour exercer vos droits, contactez-nous :</p>
                    <ul class="legal-list">
                        <li>Par email : <a href="mailto:contact@al-metallerie.fr">contact@al-metallerie.fr</a></li>
                        <li>Par courrier : AL Métallerie, 14 route de Maringues, 63920 Peschadoires</li>
                    </ul>
                </div>
            </section>

            <!-- Section 7 : Cookies -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">07</span>
                    Cookies et traceurs
                </h2>
                <div class="section-content">
                    <p>Ce site utilise des cookies pour améliorer votre expérience de navigation et analyser le trafic. Les cookies sont de petits fichiers texte stockés sur votre appareil.</p>
                    
                    <h3>Types de cookies utilisés :</h3>
                    <ul class="legal-list">
                        <li><strong>Cookies essentiels :</strong> Nécessaires au fonctionnement du site</li>
                        <li><strong>Cookies analytiques :</strong> Google Analytics pour mesurer l'audience</li>
                        <li><strong>Cookies marketing :</strong> MailChimp pour la gestion de la newsletter</li>
                    </ul>
                    
                    <p>Vous pouvez à tout moment désactiver les cookies depuis les paramètres de votre navigateur. Cependant, cela peut affecter certaines fonctionnalités du site.</p>
                    
                    <p>Pour plus d'informations, consultez notre <a href="<?php echo esc_url(home_url('/politique-confidentialite')); ?>">Politique de confidentialité</a>.</p>
                </div>
            </section>

            <!-- Section 8 : Responsabilité -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">08</span>
                    Limitation de responsabilité
                </h2>
                <div class="section-content">
                    <p>AL Métallerie s'efforce d'assurer l'exactitude et la mise à jour des informations diffusées sur ce site, dont elle se réserve le droit de corriger le contenu à tout moment et sans préavis.</p>
                    
                    <p>Toutefois, AL Métallerie ne peut garantir l'exactitude, la précision ou l'exhaustivité des informations mises à disposition sur ce site.</p>
                    
                    <p>En conséquence, AL Métallerie décline toute responsabilité :</p>
                    <ul class="legal-list">
                        <li>Pour toute imprécision, inexactitude ou omission portant sur des informations disponibles sur le site</li>
                        <li>Pour tous dommages résultant d'une intrusion frauduleuse d'un tiers ayant entraîné une modification des informations</li>
                        <li>Pour tous dommages directs ou indirects résultant de l'utilisation du site</li>
                        <li>Pour les interruptions temporaires du site pour maintenance ou mise à jour</li>
                    </ul>
                </div>
            </section>

            <!-- Section 9 : Liens hypertextes -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">09</span>
                    Liens hypertextes
                </h2>
                <div class="section-content">
                    <p>Le site peut contenir des liens hypertextes vers d'autres sites. AL Métallerie n'exerce aucun contrôle sur ces sites et décline toute responsabilité quant à leur contenu.</p>
                    
                    <p>La création de liens hypertextes vers le site www.al-metallerie.fr nécessite une autorisation préalable écrite d'AL Métallerie.</p>
                </div>
            </section>

            <!-- Section 10 : Droit applicable -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">10</span>
                    Droit applicable et juridiction
                </h2>
                <div class="section-content">
                    <p>Les présentes mentions légales sont régies par le droit français.</p>
                    
                    <p>En cas de litige et à défaut d'accord amiable, le litige sera porté devant les tribunaux français conformément aux règles de compétence en vigueur.</p>
                    
                    <p>Pour toute question concernant les présentes mentions légales, vous pouvez nous contacter à l'adresse : <a href="mailto:contact@al-metallerie.fr">contact@al-metallerie.fr</a></p>
                </div>
            </section>

            <!-- Section 11 : Crédits -->
            <section class="legal-section">
                <h2 class="section-title">
                    <span class="section-number">11</span>
                    Crédits
                </h2>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Conception et réalisation :</strong>
                            <span>AL Métallerie</span>
                        </div>
                        <div class="info-item">
                            <strong>Développement :</strong>
                            <span>[À COMPLÉTER : Nom du développeur ou agence]</span>
                        </div>
                        <div class="info-item">
                            <strong>Photographies :</strong>
                            <span>AL Métallerie - Tous droits réservés</span>
                        </div>
                        <div class="info-item">
                            <strong>Icônes :</strong>
                            <span>Lucide Icons (Licence MIT)</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Bouton retour -->
            <div class="legal-footer">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-back">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Retour à l'accueil
                </a>
                <a href="<?php echo esc_url(home_url('/politique-confidentialite')); ?>" class="btn-link">
                    Politique de confidentialité
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
