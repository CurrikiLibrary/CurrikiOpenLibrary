/**
 * plugin.js
 *
 * Copyright, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/*jshint unused:false */
/*global tinymce:true */

/**
 * oembed plugin that adds a toolbar button and menu item. 
 */
var $id_generator = 0;
tinymce.PluginManager.add('oembed', function (editor, url) {
  var imported = document.createElement('script');
  imported.src = url + '/jquery-oembed-all/jquery.oembed.js';
  document.head.appendChild(imported);
  // Add a button that opens a window
  editor.addButton('oembed', {
    icon: 'link',
    tooltip: 'Place a link to a website or a YouTube video url to embed video.',
    stateSelector: 'a[href]',
    onclick: function () {
      oEmbedFunction(editor, url);
    }
  });
  // Adds a menu item to the tools menu
  editor.addMenuItem('oembed', {
    icon: 'link',
    text: 'Insert link',
    stateSelector: 'a[href]',
    context: 'insert',
    prependToContext: true,
    onclick: function () {
      oEmbedFunction(editor, url);
    }
  });
});
function oEmbedFunction(editor, url) {
  editor.windowManager.open({
    title: 'Insert Link',
    width: 420,
    height: 120,
    body: [
      {type: 'textbox', name: 'url', label: 'Source'},
      /*{type: 'textbox', name: 'title', label: 'Title'},
       {type: 'textbox', name: 'alt_text', label: 'Alt Display Text'}*/
    ],
    onsubmit: function (e) {
      // Insert content when the window form is submitted
      var $id = 'id_' + $id_generator;
      $id_generator++;
      $link = '<p style="text-align:center"><a href="' + e.data.url + '" id="' + $id + '">' + e.data.url + '</a></p><br/>';
      editor.insertContent($link);
      if (jQuery('#frmmediatype').val() == 'text')
        jQuery('#frmmediatype').val('external');
      else
        jQuery('#frmmediatype').val('mixed');
      jQuery(editor.$('#' + $id)[0]).oembed(null,
              {
                maxWidth: 854,
                maxHeight: 400,
                greedy: false,
                onProviderNotFound: function (container, resourceURL) {

                  $link = '<a href="' + container + '" target="_blank" class="url_thumb">';
                  $link += '<img src="' + url + '/url-loader.gif" title="' + container + '" id="' + $id + '" style="margin:0 auto;" />';
                  $link += '</a>';
                  jQuery(this).replaceWith($link);

                  //jQuery.ajax({url: url + '/generate_thumbnail.php?url=' + container, dataType: "json", success: function (result) {
                  jQuery.ajax({url: 'http://cg.curriki.org/curriki/wp-content/libs/tinymce/plugins/oembed/generate_thumbnail.php?url=' + container, dataType: "json", success: function (result) {
                      jQuery(editor.$('#' + $id)[0]).parent().parent().replaceWith(result.html);
                      if (result.ext != 'downloadablefile')
                        jQuery('<input>').attr({
                          type: 'hidden',
                          value: JSON.stringify(result),
                          name: 'resourcefiles[]'
                        }).prependTo('form#create_resource_form');
                    }});

                }
                , apikeys: {
                  //etsy : 'd0jq4lmfi5bjbrxq2etulmjr',
                  amazon: 'caterwall-20',
                  //eventbrite: 'SKOFRBQRGNQW5VAS6P',
                }
              });
    }
  });
}
