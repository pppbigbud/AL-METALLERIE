<?php
/**
 * Classe des templates d'articles
 */
class ACSP_Article_Templates {
    
    /**
     * Obtenir un template selon le type
     */
    public function get_template($type) {
        $templates = [
            'guide' => $this->get_guide_template(),
            'trend' => $this->get_trend_template(),
            'tutorial' => $this->get_tutorial_template(),
            'case_study' => $this->get_case_study_template(),
            'faq' => $this->get_faq_template(),
            'comparison' => $this->get_comparison_template(),
            'inspiration' => $this->get_inspiration_template()
        ];
        
        return $templates[$type] ?? $templates['guide'];
    }
    
    /**
     * Template guide pratique
     */
    private function get_guide_template() {
        return [
            'title' => 'Comment {KEYWORD} à {LOCATION} ? Le Guide Complet {YEAR}',
            'slug' => 'comment-{KEYWORD}-{LOCATION}',
            'meta_description' => 'Guide complet pour {KEYWORD} à {LOCATION}. Conseils d\'experts de {COMPANY}, choix des matériaux, normes et devis.',
            'excerpt' => 'Découvrez tout ce qu\'il faut savoir sur {KEYWORD} à {LOCATION}. Guide pratique par les artisans de {COMPANY}.',
            'content' => '<h1>Comment {KEYWORD} à {LOCATION} ? Le Guide Complet {YEAR}</h1>
            
            <p>Vous souhaitez {KEYWORD} à {LOCATION} ? Vous êtes au bon endroit ! En tant qu\'artisans métalliers de la région depuis plus de 10 ans, nous vous partageons notre expertise pour vous aider à faire les bons choix.</p>
            
            <h2>Pourquoi faire appel à un artisan local à {LOCATION} ?</h2>
            <p>Faire appel à {COMPANY} pour {KEYWORD_PLURAL} présente de nombreux avantages. Notre connaissance du terrain, notre réactivité et notre maîtrise des normes locales font toute la différence. De plus, nous intervenons dans un rayon de 50km autour de notre atelier de Peschadoires.</p>
            
            <h2>Les critères essentiels à considérer</h2>
            <p>Pour {KEYWORD} avec succès, plusieurs éléments doivent être pris en compte :</p>
            <ul>
                <li><strong>Le choix du matériau</strong> : {MATERIAL} s\'adapte parfaitement à notre climat auvergnat</li>
                <li><strong>Les dimensions</strong> : Une prise de mesure précise est indispensable</li>
                <li><strong>La sécurité</strong> : Respect des normes en vigueur et garanties associées</li>
                <li><strong>L\'esthétique</strong> : Harmonie avec l\'architecture existante</li>
                <li><strong>Le budget</strong> : Devis détaillé et transparent</li>
            </ul>
            
            <h2>Les étapes de réalisation</h2>
            <p>Chez {COMPANY}, nous suivons un processus rigoureux pour garantir votre satisfaction :</p>
            <ol>
                <li><strong>Premier contact</strong> : Écoute de votre projet par téléphone au {PHONE}</li>
                <li><strong>Devis sur mesure</strong> : Visite technique et proposition détaillée</li>
                <li><strong>Fabrication</li> : Réalisation dans nos ateliers de Thiers</li>
                <li><strong>Pose</li> : Installation par notre équipe qualifiée</li>
                <li><strong>Finalisation</li> : Réception et garantie décennale</li>
            </ol>
            
            <h2>Nos réalisations similaires</h2>
            <p>Récemment, nous avons réalisé {REALISATION}. Ce projet témoigne de notre savoir-faire et de notre capacité à nous adapter aux demandes les plus spécifiques.</p>
            
            <h2>Le budget à prévoir</h2>
            <p>Le coût pour {KEYWORD} varie selon plusieurs facteurs : dimensions, matériaux, complexité, motorisation... Comptez entre X€ et Y€ pour un projet standard. Contactez-nous pour un devis personnalisé.</p>
            
            <h2>Pourquoi nous choisir ?</h2>
            <p>{COMPANY}, c\'est l\'assurance de :
            <ul>
                <li>✅ Un devis gratuit sous 48h</li>
                <li>✅ Une fabrication 100% française</li>
                <li>✅ Une garantie décennale</li>
                <li>✅ Plus de 10 ans d\'expérience</li>
                <li>✅ Une note de 5.0/5 sur Google</li>
            </ul></p>
            
            <h2>FAQ</h2>
            <p><strong>Quelle est la durée de validité d\'un devis ?</strong><br>
            Nos devis sont valables 3 mois. Vous disposez de ce délai pour prendre votre décision.</p>
            
            <p><strong>Proposez-vous des finitions personnalisées ?</strong><br>
            Oui, nous pouvons réaliser des finitions sur mesure selon vos envies.</p>
            
            <h2>Prêt à démarrer votre projet ?</h2>
            <p>Contactez-nous dès aujourd\'hui pour étudier votre projet. Nous vous répondrons dans les plus brefs délais.</p>
            
            <p><strong>{COMPANY}</strong><br>
            📍 Peschadoires, Thiers (63)<br>
            📞 {PHONE}<br>
            📧 {EMAIL}</p>',
            'type' => 'guide',
            'keywords' => ['{KEYWORD}', '{KEYWORD} {LOCATION}', 'métallerie {LOCATION}', 'artisan {LOCATION}']
        ];
    }
    
    /**
     * Template tendances
     */
    private function get_trend_template() {
        return [
            'title' => 'Tendances {YEAR} : {KEYWORD} qui font la différence',
            'slug' => 'tendances-{YEAR}-{KEYWORD}',
            'meta_description' => 'Découvrez les dernières tendances {YEAR} pour {KEYWORD}. Designs innovants, matériaux écologiques et technologies modernes par {COMPANY}.',
            'excerpt' => 'Les tendances {YEAR} pour {KEYWORD} : design, innovation et durabilité. Inspiration par les experts de {COMPANY}.',
            'content' => '<h1>Tendances {YEAR} : {KEYWORD} qui font la différence</h1>
            
            <p>L\'année {YEAR} apporte son lot de nouveautés dans le monde de la métallerie. Découvrez les tendances qui marquent {KEYWORD_PLURAL} et comment les intégrer dans vos projets.</p>
            
            <h2>Le design minimaliste s\'impose</h2>
            <p>En {YEAR}, la tendance est au minimalisme épuré. Les lignes simples, les formes géométriques et les finitions discrètes sont très recherchées. Le {MATERIAL} s\'adapte parfaitement à cette esthétique contemporaine.</p>
            
            <h2>L\'ère du connecté</h2>
            <p>La domotique s\'invite dans {KEYWORD_PLURAL}. Motorisation connectée, capteurs de sécurité, gestion via smartphone... Les innovations technologiques transforment notre façon de vivre.</p>
            
            <h2>Matériaux durables</h2>
            <p>La conscience écologique influence nos choix. Les matériaux recyclables, les traitements respectueux de l\'environnement et la durabilité sont devenus des critères essentiels.</p>
            
            <h2>Couleurs et finitions</h2>
            <p>Pour {YEAR}, les couleurs tendance sont :
            <ul>
                <li>Noir mat pour un look moderne</li>
                <li>Gis anthracite pour l\'élégance</li>
                <li>Vert forêt pour l\'originalité</li>
                <li>Blanc cassé pour le classicisme</li>
            </ul></p>
            
            <h2>Inspirations de nos réalisations</h2>
            <p>Nos dernières créations illustrent parfaitement ces tendances. {REALISATION} en est un bel exemple avec ses lignes épurées et sa finition moderne.</p>
            
            <h2>Comment adopter ces tendances ?</h2>
            <p>Pas besoin de tout changer ! Quelques éléments suffisent à moderniser votre espace. Nos artisans vous conseillent pour les meilleures adaptations.</p>
            
            <p><strong>Contactez {COMPANY}</strong> pour un projet tendance {YEAR} !<br>
            📞 {PHONE} | 📍 {LOCATION}</p>',
            'type' => 'trend',
            'keywords' => ['tendances {YEAR}', '{KEYWORD} moderne', 'design {KEYWORD}', 'innovation métallerie']
        ];
    }
    
    /**
     * Template tutoriel
     */
    private function get_tutorial_template() {
        return [
            'title' => '{KEYWORD} : Notre Tutoriel Complet pour Réussir',
            'slug' => 'tutoriel-{KEYWORD}',
            'meta_description' => 'Tutoriel détaillé pour {KEYWORD}. Étapes, conseils, erreurs à éviter. Guide pratique par {COMPANY}, artisans experts.',
            'excerpt' => 'Apprenez à {KEYWORD} avec notre tutoriel complet. Conseils d\'experts et astuces pratiques.',
            'content' => '<h1>{KEYWORD} : Notre Tutoriel Complet pour Réussir</h1>
            
            <p>Aujourd\'hui, nous vous partageons notre expertise pour {KEYWORD} comme un professionnel. Suivez ces étapes et nos conseils pour un résultat parfait.</p>
            
            <h2>La préparation : 90% du succès</h2>
            <p>Avant de commencer :
            <ul>
                <li>Rassemblez tous les outils nécessaires</li>
                <li>Préparez votre espace de travail</li>
                <li>Lisez bien toutes les étapes</li>
                <li>N\'hésitez pas à nous appeler pour conseil</li>
            </ul></p>
            
            <h2>Étape par étape</h2>
            <h3>Étape 1 : La prise de mesures</h3>
            <p>Precision is key. Utilisez un mètre ruban et notez tout. Une erreur de 1cm peut avoir des conséquences importantes.</p>
            
            <h3>Étape 2 : Le choix du matériel</h3>
            <p>Le {MATERIAL} est idéal pour ce projet. Assurez-vous de la qualité des fournitures.</p>
            
            <h3>Étape 3 : La préparation</h3>
            <p>Une bonne préparation garantit la durabilité de votre réalisation.</p>
            
            <h3>Étape 4 : La réalisation</h3>
            <p>Suivez notre méthode éprouvée. Prenez votre temps, la précision paie.</p>
            
            <h3>Étape 5 : Les finitions</h3>
            <p>Les détails font la différence. Soignez chaque finition.</p>
            
            <h2>Erreurs à éviter</h2>
            <p>Voici les erreurs les plus courantes :
            <ul>
                <li>❌ Précipiter les mesures</li>
                <li>❌ Négliger la sécurité</li>
                <li>❌ Utiliser des matériaux inadaptés</li>
                <li>❌ Ne pas demander conseil</li>
            </ul></p>
            
            <h2>Nos astuces de pro</h2>
            <p>Après plus de 10 ans d\'expérience, voici nos meilleures astuces :
            <ul>
                <li>Travaillez toujours avec de bons outils</li>
                <li>Prenez des photos à chaque étape</li>
                <li>Testez avant la finalisation</li>
                <li>N\'hésitez pas à défaire et refaire</li>
            </ul></p>
            
            <h2>Besoin d\'aide ?</h2>
            <p>Si vous avez le moindre doute, notre équipe est là pour vous. Nous proposons même des formations personnalisées dans nos ateliers de Thiers.</p>
            
            <p><strong>{COMPANY}</strong> - Votre partenaire métallier de confiance<br>
            📞 {PHONE} | 📧 {EMAIL}</p>',
            'type' => 'tutorial',
            'keywords' => ['tutoriel {KEYWORD}', 'comment {KEYWORD}', 'guide pratique', 'astuces pro']
        ];
    }
    
    /**
     * Template étude de cas
     */
    private function get_case_study_template() {
        return [
            'title' => 'Réalisation : {KEYWORD} sur mesure à {LOCATION}',
            'slug' => 'realisation-{KEYWORD}-{LOCATION}',
            'meta_description' => 'Découvrez notre réalisation de {KEYWORD} à {LOCATION}. Projet complet de {COMPANY} : conception, fabrication et pose.',
            'excerpt' => 'Étude de cas : notre projet de {KEYWORD} à {LOCATION}. Une réalisation signature {COMPANY}.',
            'content' => '<h1>Réalisation : {KEYWORD} sur mesure à {LOCATION}</h1>
            
            <p>Aujourd\'hui, nous vous présentons l\'une de nos réalisations phares : un projet de {KEYWORD} entièrement sur mesure pour un client à {LOCATION}.</p>
            
            <h2>Le contexte du projet</h2>
            <p>Le client souhaitait {KEYWORD} avec des contraintes spécifiques : dimensions particulières, esthétique moderne, et respect des normes de sécurité.</p>
            
            <h2>Le cahier des charges</h2>
            <ul>
                <li>Type : {KEYWORD}</li>
                <li>Lieu : {LOCATION}</li>
                <li>Matériau principal : {MATERIAL}</li>
                <li>Contraintes techniques : Spécifiques au site</li>
                <li>Délai : 4 semaines</li>
            </ul>
            
            <h2>Notre approche</h2>
            <p>Nous avons suivi notre méthodologie habituelle :
            <ol>
                <li><strong>Analyse</strong> : Étude détaillée du site</li>
                <li><strong>Conception</strong> : Plans 3D et validation client</li>
                <li><strong>Fabrication</strong> : Dans nos ateliers de Thiers</li>
                <li><strong>Pose</strong> : Installation par notre équipe</li>
            </ol></p>
            
            <h2>Les défis techniques</h2>
            <p>Ce projet présentait plusieurs défis :
            <ul>
                <li>Accès difficile sur le chantier</li>
                <li>Dimensions non standards</li>
                <li>Intégration avec l\'existant</li>
            </ul></p>
            
            <h2>Notre solution</h2>
            <p>Nous avons développé une solution sur mesure répondant à toutes les exigences. Le {MATERIAL} a été travaillé avec précision pour s\'adapter parfaitement.</p>
            
            <h2>Le résultat</h2>
            <p>Le client est ravi ! Le {KEYWORD} s\'intègre parfaitement et répond à toutes ses attentes. Un projet réussi qui démontre notre savoir-faire.</p>
            
            <h2>Témoignage client</h2>
            <p>"Professionnalisme, réactivité et qualité du travail. Je recommande vivement {COMPANY} pour tout projet métallier." - Client satisfait</p>
            
            <h2>Photos du projet</h2>
            <p>[Galerie photos du projet avant/après]</p>
            
            <h2>Votre projet similaire ?</h2>
            <p>Vous avez un projet de {KEYWORD} ? Contactez-nous pour une étude personnalisée.</p>
            
            <p><strong>{COMPANY}</strong><br>
            Experts en {KEYWORD_PLURAL} sur mesure<br>
            📞 {PHONE} | 📍 {LOCATION}</p>',
            'type' => 'case_study',
            'keywords' => ['réalisation {KEYWORD}', 'projet {LOCATION}', 'étude de cas', 'client satisfait']
        ];
    }
    
    /**
     * Template FAQ
     */
    private function get_faq_template() {
        return [
            'title' => '{KEYWORD} : 5 Questions que Vous Nous Posez Souvent',
            'slug' => 'faq-{KEYWORD}',
            'meta_description' => 'FAQ {KEYWORD} : réponses à 5 questions fréquentes. Conseils d\'experts de {COMPANY} pour vos projets de métallerie.',
            'excerpt' => 'Toutes les réponses à vos questions sur {KEYWORD}. FAQ complète par les artisans de {COMPANY}.',
            'content' => '<h1>{KEYWORD} : 5 Questions que Vous Nous Posez Souvent</h1>
            
            <p>Après plus de 10 ans d\'expérience, nous avons compilé les questions les plus fréquentes sur {KEYWORD}. Voici nos réponses d\'experts.</p>
            
            <h2>1. Quel budget prévoir pour {KEYWORD} ?</h2>
            <p>Le budget varie selon plusieurs critères : dimensions, matériaux, complexité... Comptez entre X€ et Y€ pour un projet standard. Le plus simple est de nous contacter pour un devis personnalisé et gratuit.</p>
            
            <h2>2. Quelle durée pour la réalisation ?</h2>
            <p>En général, comptez 2 à 4 semaines entre la validation du devis et la pose finale. Ce délai peut varier selon la complexité et notre charge de travail.</p>
            
            <h2>3. Quelles garanties proposez-vous ?</h2>
            <p>Nous offrons systématiquement :
            <ul>
                <li>Garantie décennale sur la structure</li>
                <li>Garantie biennale sur les équipements</li>
                <li>Garantie de bonne exécution</li>
            </ul></p>
            
            <h2>4. Proposez-vous des finitions personnalisées ?</h2>
            <p>Absolument ! Nous pouvons réaliser des finitions sur mesure : couleurs RAL, motifs spécifiques, gravures... Discutez de vos envies avec notre équipe.</p>
            
            <h2>5. Intervenez-vous dans ma région ?</h2>
            <p>Nous intervenons dans un rayon de 50km autour de Thiers. Cela inclut : Clermont-Ferrand, Riom, Issoire, Vichy, et de nombreuses autres villes du Puy-de-Dôme.</p>
            
            <h2>Questions bonus</h2>
            
            <h3>Comment entretenir {KEYWORD} ?</h3>
            <p>L\'entretien dépend du matériau. Pour l\'acier thermolaqué, un simple lavage à l\'eau savonneuse suffit. Pour l\'inox, utilisez des produits adaptés.</p>
            
            <h3>Puis-je voir vos réalisations ?</h3>
            <p>Oui ! Nous vous invitons à visiter notre site web et à nous contacter pour visiter nos ateliers ou voir nos réalisations en cours.</p>
            
            <h2>Vous avez d\'autres questions ?</h2>
            <p>N\'hésitez pas à nous contacter ! Notre équipe est à votre disposition pour répondre à toutes vos interrogations.</p>
            
            <p><strong>Contactez {COMPANY}</strong><br>
            📞 {PHONE} - Disponible 6j/7<br>
    📧 {EMAIL}<br>
    📍 Peschadoires, Thiers (63)</p>',
            'type' => 'faq',
            'keywords' => ['FAQ {KEYWORD}', 'questions {KEYWORD}', 'conseils experts', 'métallerie']
        ];
    }
    
    /**
     * Template comparatif
     */
    private function get_comparison_template() {
        return [
            'title' => '{KEYWORD} vs {MATERIAL} : Lequel Choisir ?',
            'slug' => 'comparaison-{KEYWORD}-{MATERIAL}',
            'meta_description' => 'Comparatif complet : {KEYWORD} vs {MATERIAL}. Avantages, inconvénients, prix. Aide au choix par {COMPANY}.',
            'excerpt' => 'Ne savez-vous pas choisir entre {KEYWORD} et {MATERIAL} ? Notre comparatif vous aide à décider.',
            'content' => '<h1>{KEYWORD} vs {MATERIAL} : Lequel Choisir ?</h1>
            
            <p>Face à {KEYWORD}, le choix du matériau est crucial. Comparons les options pour vous aider à prendre la bonne décision.</p>
            
            <h2>Présentation des matériaux</h2>
            <p>Pour {KEYWORD}, plusieurs options s\'offrent à vous. Aujourd\'hui, nous comparons {MATERIAL} avec d\'autres alternatives.</p>
            
            <h2>Tableau comparatif</h2>
            <table>
                <tr>
                    <th>Critère</th>
                    <th>{MATERIAL}</th>
                    <th>Alternative</th>
                </tr>
                <tr>
                    <td>Prix</td>
                    <td>€€</td>
                    <td>€€€</td>
                </tr>
                <tr>
                    <td>Durabilité</td>
                    <td>20+ ans</td>
                    <td>15+ ans</td>
                </tr>
                <tr>
                    <td>Entretien</td>
                    <td>Facile</td>
                    <td>Moyen</td>
                </tr>
                <tr>
                    <td>Esthétique</td>
                    <td>★★★★★</td>
                    <td>★★★★</td>
                </tr>
            </table>
            
            <h2>Avantages du {MATERIAL}</h2>
            <ul>
                <li>✅ Durabilité exceptionnelle</li>
                <li>✅ Rapport qualité/prix</li>
                <li>✅ Facilité d\'entretien</li>
                <li>✅ Large choix de finitions</li>
            </ul>
            
            <h2>Inconvénients potentiels</h2>
            <ul>
                <li>❌ Poids important</li>
                <li>❌ Nécessite un traitement anti-corrosion</li>
            </ul>
            
            <h2>Quand choisir {MATERIAL} ?</h2>
            <p>Optez pour {MATERIAL} si :
            <ul>
                <li>Vous recherchez la durabilité</li>
                <li>Votre budget est limité</li>
                <li>Vous voulez un matériau facile à entretenir</li>
            </ul></p>
            
            <h2>Notre recommandation</h2>
            <p>Pour {KEYWORD}, nous recommandons {MATERIAL} dans 80% des cas. C\'est le meilleur compromis entre prix, durabilité et esthétique.</p>
            
            <h2>Besoin de conseil personnalisé ?</h2>
            <p>Chaque projet est unique. Contactez-nous pour une recommandation adaptée à votre situation.</p>
            
            <p><strong>{COMPANY}</strong><br>
            Experts en conseil et réalisation<br>
    📞 {PHONE}</p>',
            'type' => 'comparison',
            'keywords' => ['comparaison {KEYWORD}', '{MATERIAL} vs', 'choix matériau', 'conseil métallerie']
        ];
    }
    
    /**
     * Template inspiration
     */
    private function get_inspiration_template() {
        return [
            'title' => '10 Idées {KEYWORD} qui Vont Vous Inspirer',
            'slug' => 'inspiration-{KEYWORD}',
            'meta_description' => 'Découvrez 10 idées créatives pour {KEYWORD}. Projets originaux et tendances par {COMPANY}. Inspiration garantie !',
            'excerpt' => 'Manquez d\'inspiration pour {KEYWORD} ? Découvrez nos 10 idées créatives qui vont vous surprendre.',
            'content' => '<h1>10 Idées {KEYWORD} qui Vont Vous Inspirer</h1>
            
            <p>En quête d\'inspiration pour {KEYWORD} ? Voici 10 idées créatives qui vont stimuler votre imagination.</p>
            
            <h2>1. Le design minimaliste</h2>
            <p>Des lignes pures, des formes simples. Le minimalisme met en valeur la beauté du {MATERIAL}.</p>
            
            <h2>2. L\'industriel revisité</h2>
            <p>Mélangez le brut et le raffiné pour un look unique qui fait sensation.</p>
            
            <h2>3. L\'intégration végétale</h2>
            <p>Intégrez des plantes dans votre {KEYWORD} pour un effet naturel apaisant.</p>
            
            <h2>4. Le jeu des transparences</h2>
            <p>Verre et métal s\'associent pour créer des jeux de lumière fascinants.</p>
            
            <h2>5. La touche de couleur</h2>
            <p>Osez la couleur ! Un rouge vif ou un bleu profond peut transformer {KEYWORD}.</p>
            
            <h2>6. L\'éclairage intégré</h2>
            <p>Intégrez des LED directement dans la structure pour une ambiance magique.</p>
            
            <h2>7. Le motif géométrique</h2>
            <p>Créez des motifs répétitifs pour un effet visuel saisissant.</p>
            
            <h2>8. Le mix des matériaux</h2>
            <p>Associez {MATERIAL} avec le bois, le verre ou la pierre pour plus de caractère.</p>
            
            <h2>9. La forme organique</h2>
            <p>Oubliez les lignes droites et optez pour des formes douces et naturelles.</p>
            
            <h2>10. L\'high-tech</h2>
            <p>Intégrez la domotique, les capteurs et la motorisation pour {KEYWORD} 2.0.</p>
            
            <h2>Comment adapter ces idées ?</h2>
            <p>Toutes ces idées peuvent être adaptées à votre budget et votre espace. Notre équipe vous aide à concrétiser votre vision.</p>
            
            <h2>Nos inspirations du moment</h2>
            <p>Nos créations récentes s\'inspirent de ces tendances. {REALISATION} illustre parfaitement plusieurs de ces idées.</p>
            
            <h2>Prêt à créer votre projet unique ?</h2>
            <p>Contactez-nous pour transformer vos idées en réalité. Nous aimons les projets originaux !</p>
            
            <p><strong>{COMPANY}</strong><br>
    Créateurs d\'{KEYWORD_PLURAL} uniques<br>
    📞 {PHONE} | 📍 {LOCATION}</p>',
            'type' => 'inspiration',
            'keywords' => ['idées {KEYWORD}', 'création originale', 'design {KEYWORD}', 'inspiration métallerie']
        ];
    }
}
