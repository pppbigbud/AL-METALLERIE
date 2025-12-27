/**
 * Gutenberg Integration for Smart Backlink Manager
 */

( function( wp ) {
    'use strict';

    const { registerPlugin } = wp.plugins;
    const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
    const { PanelBody, Button, TextControl, Spinner } = wp.components;
    const { withSelect, withDispatch } = wp.data;
    const { compose } = wp.compose;
    const { apiFetch } = wp;
    const { __ } = wp.i18n;

    // Panel de suggestions
    const SBM_SuggestionsPanel = function( props ) {
        const [ suggestions, setSuggestions ] = wp.element.useState( [] );
        const [ loading, setLoading ] = wp.element.useState( false );
        const [ searchTerm, setSearchTerm ] = wp.element.useState( '' );

        // Charger les suggestions au montage
        wp.element.useEffect( () => {
            loadSuggestions();
        }, [ props.postId ] );

        function loadSuggestions() {
            setLoading( true );
            
            apiFetch( {
                path: '/smart-backlink-manager/v1/suggestions/' + props.postId,
                method: 'GET'
            } ).then( ( response ) => {
                setSuggestions( response.suggestions || [] );
                setLoading( false );
            } ).catch( ( error ) => {
                console.error( 'SBM Error:', error );
                setLoading( false );
            } );
        }

        function addLink( suggestion ) {
            // Insérer le lien dans l'éditeur
            const postId = props.postId;
            const selectedText = window.getSelection().toString() || suggestion.title;
            
            // Créer le lien HTML
            const linkHtml = '<a href="' + suggestion.url + '" data-sbm-suggested="true">' + selectedText + '</a>';
            
            // Utiliser l'API de l'éditeur pour insérer le lien
            wp.data.dispatch( 'core/block-editor' ).insertBlocks(
                wp.blocks.createBlock( 'core/paragraph', {
                    content: linkHtml
                } )
            );

            // Notifier le plugin qu'un lien a été ajouté
            apiFetch( {
                path: '/smart-backlink-manager/v1/internal-links',
                method: 'POST',
                data: {
                    from_post_id: postId,
                    to_post_id: suggestion.id,
                    anchor_text: selectedText
                }
            } ).then( () => {
                // Recharger les suggestions
                loadSuggestions();
            } ).catch( ( error ) => {
                console.error( 'SBM Error:', error );
            } );
        }

        // Filtrer les suggestions
        const filteredSuggestions = suggestions.filter( suggestion => {
            if ( ! searchTerm ) return true;
            return suggestion.title.toLowerCase().includes( searchTerm.toLowerCase() ) ||
                   suggestion.excerpt.toLowerCase().includes( searchTerm.toLowerCase() );
        } );

        return (
            <PanelBody
                title={ __( 'Suggestions de liens', 'smart-backlink-manager' ) }
                initialOpen={ true }
            >
                <TextControl
                    label={ __( 'Rechercher', 'smart-backlink-manager' ) }
                    value={ searchTerm }
                    onChange={ setSearchTerm }
                    placeholder={ __( 'Rechercher un contenu...', 'smart-backlink-manager' ) }
                />

                { loading ? (
                    <div style={ { textAlign: 'center', padding: '20px' } }>
                        <Spinner />
                        <p>{ __( 'Chargement des suggestions...', 'smart-backlink-manager' ) }</p>
                    </div>
                ) : filteredSuggestions.length > 0 ? (
                    <div className="sbm-suggestions-list">
                        { filteredSuggestions.map( ( suggestion ) => (
                            <div key={ suggestion.id } className="sbm-suggestion-item">
                                <h4>
                                    <a href={ suggestion.url } target="_blank" rel="noopener noreferrer">
                                        { suggestion.title }
                                    </a>
                                </h4>
                                <p>{ suggestion.excerpt }</p>
                                <div className="sbm-suggestion-meta">
                                    <span className="sbm-suggestion-type">
                                        { suggestion.post_type_label }
                                    </span>
                                    <span className="sbm-suggestion-score">
                                        { __( 'Score:', 'smart-backlink-manager' ) } { suggestion.score }
                                    </span>
                                </div>
                                <Button
                                    isSecondary
                                    isSmall
                                    onClick={ () => addLink( suggestion ) }
                                >
                                    { __( 'Ajouter ce lien', 'smart-backlink-manager' ) }
                                </Button>
                            </div>
                        ) ) }
                    </div>
                ) : (
                    <p>
                        { searchTerm 
                            ? __( 'Aucune suggestion trouvée pour cette recherche.', 'smart-backlink-manager' )
                            : __( 'Aucune suggestion disponible pour ce contenu.', 'smart-backlink-manager' )
                        }
                    </p>
                ) }

                <Button
                    isSecondary
                    onClick={ loadSuggestions }
                    style={ { marginTop: '10px' } }
                >
                    { __( 'Actualiser', 'smart-backlink-manager' ) }
                </Button>
            </PanelBody>
        );
    };

    // Composant principal du plugin
    const SBM_Plugin = compose( [
        withSelect( ( select ) => {
            return {
                postId: select( 'core/editor' ).getCurrentPostId()
            };
        } )
    ] )( SBM_SuggestionsPanel );

    // Enregistrer le plugin
    registerPlugin( 'smart-backlink-manager', {
        icon: 'admin-links',
        render: function() {
            return (
                <Fragment>
                    <PluginSidebarMoreMenuItem
                        target="sbm-suggestions-sidebar"
                        icon="admin-links"
                    >
                        { __( 'Smart Backlink Manager', 'smart-backlink-manager' ) }
                    </PluginSidebarMoreMenuItem>
                    <PluginSidebar
                        name="sbm-suggestions-sidebar"
                        title={ __( 'Smart Backlink Manager', 'smart-backlink-manager' ) }
                    >
                        <SBM_Plugin />
                    </PluginSidebar>
                </Fragment>
            );
        }
    } );

    // Styles CSS pour le panneau
    const style = document.createElement( 'style' );
    style.textContent = `
        .sbm-suggestions-list {
            margin-top: 10px;
        }
        
        .sbm-suggestion-item {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
            background: #f9f9f9;
        }
        
        .sbm-suggestion-item h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
        }
        
        .sbm-suggestion-item h4 a {
            color: #0073aa;
            text-decoration: none;
        }
        
        .sbm-suggestion-item h4 a:hover {
            text-decoration: underline;
        }
        
        .sbm-suggestion-item p {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #666;
        }
        
        .sbm-suggestion-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 12px;
            color: #999;
        }
        
        .sbm-suggestion-type {
            background: #e7e7e7;
            padding: 2px 6px;
            border-radius: 3px;
        }
        
        .sbm-suggestion-score {
            font-weight: 600;
        }
        
        [data-sbm-suggested="true"] {
            background-color: #e6f3ff;
            border-radius: 2px;
            padding: 0 2px;
        }
    `;
    document.head.appendChild( style );

} )( window.wp );
