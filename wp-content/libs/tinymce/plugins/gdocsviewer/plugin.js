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
 * gdoc plugin that adds a toolbar button and menu item.
 */

var $id_generator = 0;
/*var imported = document.createElement('script');
 imported.src = 'js/tinymce/plugins/gdocsviewer/jquery.gdocsviewer.v1.0/jquery.gdocsviewer.js';
 document.head.appendChild(imported);
 */
tinymce.PluginManager.add('gdocsviewer', function (editor, url) {
  // Add a button that opens a window
  editor.addButton('gdoc', {
    icon: 'newdocument',
    tooltip: 'Insert Document',
    text: '',
    onclick: function () {
      gDocViewerFunction(editor, url);
    }
  });

  // Adds a menu item to the tools menu
  editor.addMenuItem('gdoc', {
    icon: 'newdocument',
    text: 'Insert Document',
    context: 'insert',
    prependToContext: true,
    onclick: function () {
      gDocViewerFunction(editor, url);
    }
  });
});

function gDocViewerFunction(editor, url) {
  editor.windowManager.open({
    title: 'Insert Document',
    width: 420,
    height: 120,
    body: [
      {type: 'filepicker', filetype: 'file', name: 'url', label: 'Source'},
    ],
    onsubmit: function (e) {
      // Insert content when the window form is submitted
      $link = '<a href="' + uploadedfile.url + '" title="' + uploadedfile.url + '" >' + uploadedfile.filename + '</a>';

      var file = uploadedfile.url;
      var ext = file.substring(file.lastIndexOf('.') + 1).toUpperCase();
      if (/^(JPEG|PNG|GIF|BMP|TXT|CSS|HTML|PHP|C|CPP|H|HPP|JS|DOC|DOCX|XLS|XLSX|PPT|PPTX|PDF)$/.test(ext)) {
        $link = $link + '<p style="text-align:center"><div class="gdocsviewer"><iframe src="https://docs.google.com/viewer?embedded=true&url=' + encodeURIComponent(file) + '" width="98%" height="600" style="border: none;"></iframe></div></p>';
      } else if (file.toLowerCase().indexOf("docs.google.com") >= 0) {
        $link = $link + '<p style="text-align:center"><div class="gdocsviewer"><iframe src="' + file + '" width="98%" height="600" style="border: none;"></iframe></div></p>';
      }
      editor.insertContent($link);
    }
  });
}
