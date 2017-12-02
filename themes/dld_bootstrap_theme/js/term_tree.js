/**
 * @file
 * Contains the definition of the behaviour jsTestBlackWeight.
 */

(function ($, Drupal, drupalSettings) {

    'use strict';

    /**
     * Attaches the JS test behavior to to weight div.
     */

    Drupal.behaviors.term_tree_block = {
        attach: function (context, settings) {
            $('body').once('term_tree_block').each(function () {

                var term_tree = drupalSettings.term_tree.js_var;
                //console.log(drupalSettings.term_tree);
                
                $('#zhishidian-termtree').treeview({enableLinks: true, data: term_tree, levels: 1});




            });
        }
    };


})(jQuery, Drupal, drupalSettings);
