<?php

function get_string($string, $language){
    switch ($string){
        case 'no_constitution_selected':
            switch($language){
                case 'fr':
                    return "Aucune constitution n'est sélectionnée.";
                    break;
                case 'es':
                    return "Ninguna constitución seleccionada.";
                    break;
                case 'en':
                default:
                    return "No constitution selected yet.";
                    break;
            }
            break;
        case 'select_constitution':
            switch($language){
                case 'fr':
                    return "Selectionnez une constitution pour la tagger :";
                    break;
                case 'es':
                    return "Seleccione una constitución para taggearla :";
                    break;
                case 'en':
                default:
                    return "Select a constitution to tag :";
                    break;            
            }
            break;
        case 'select_tags':
            switch($language){
                case 'fr':
                    return "Choisissez les tags :";
                    break;
                case 'es':
                    return "Escoge los tags :";
                    break;
                case 'en':
                default:
                    return "Select tags :";
                    break;            
            }
            break;
        case 'instructions':
            switch($language){
                case 'fr':
                    return '1. Cliquez sur une constitution. 2. Sélectionnez un article. 3. Ajoutez les tags correspondants à l\'article. 4. Sauvez vos tags en clickant le bouton "' . get_string('save_tags', $language) . '".';
                    break;
                case 'es':
                    return '1. Haga clic en una constitución. 2. Seleccione un elemento. 3. Las etiquetas se pueden añadir al artículo correspondiente. 4. Guarde sus etiquetas haciendo clic en el botón "' . get_string('save_tags', $language) . '".';
                    break;
                case 'en':
                default:
                    return '1. Click on a constitution. 2. Select an article. 3. Check the tags you want for the article. 4. Save your changes with the "' . get_string('save_tags', $language) . '" button.'; 
                    break;
            }
            break;
        case 'save_tags':
            switch($language){
                case 'fr':
                    return "Enregister";
                    break;
                case 'es':
                    return "Guardar";
                    break;
                case 'en':
                default:
                    return "Save tags";
                    break;
            }
            break;
        case 'title':
            switch($language){
                case 'fr':
                    return "Tagger pour Constitution Explorer";
                    break;
                case 'es':
                    return "Tagger para Constitution Explorer";
                    break;
                case 'en':
                default:
                    return "Tagger for Constitution Explorer";
                    break;
            }
            break;
        case 'open_all':
            switch($language){
                case 'fr':
                    return "Tout ouvrir";
                    break;
                case 'es':
                    return "Abrir todo";
                    break;
                case 'en':
                default:
                    return "Show all";
                    break;
            }
            break;
        case 'close_all':
            switch($language){
                case 'fr':
                    return "Tout fermer";
                    break;
                case 'es':
                    return "Cerrar todo";
                    break;
                case 'en':
                default:
                    return "Close all";
                    break;
            }
            break;
        case 'search':
            switch($language){
                case 'fr':
                    return "Rechercher";
                    break;
                case 'es':
                    return "Buscar";
                    break;
                case 'en':
                default:
                    return "Search";
                    break;
            }
            break;
        }
}


?>
