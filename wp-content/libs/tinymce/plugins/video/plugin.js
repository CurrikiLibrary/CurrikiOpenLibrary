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
var jwurl = '';
tinymce.PluginManager.add('video', function (editor, url) {
  var imported = document.createElement('script');
  jwurl = url + '/../../../jwplayer/jwplayer.js'
  imported.src = jwurl;
  document.head.appendChild(imported);

  // Add a button that opens a window
  editor.addButton('video', {
    icon: 'media',
    tooltip: 'Insert video',
    onclick: function () {
      videoDFunction(editor, url);
    }
  });

  // Adds a menu item to the tools menu
  editor.addMenuItem('video', {
    text: 'Insert Video',
    icon: 'media',
    context: 'insert',
    prependToContext: true,
    onclick: function () {
      videoDFunction(editor, url);
    }
  });
});

function videoDFunction(editor, url) {
  editor.windowManager.open({
    title: 'Insert Video',
    width: 420,
    height: 120,
    body: [
      {type: 'filepicker', filetype: 'video', name: 'url', label: 'Source'}
    ],
    onsubmit: function (e) {
      vid_id = 'jw_video_' + Math.floor((Math.random() * 1000000) + 1);

      $link = (
              '<center><p style="text-align:center"><div id="' + vid_id + '" class="jwplayer_video" style="text-align:center">' +
              '<video width="640" ' + (uploadedfile.poster ? ' poster="' + uploadedfile.poster + '"' : '') + ' controls="controls">\n' +
              '<source src="' + uploadedfile.url_alt1 + '" type="video/webm" />\n' +
              //'<source src="' + uploadedfile.url_alt2 + '" type="application/x-mpegURL" />\n' +
              //'<source src="' + uploadedfile.url_alt3 + '" type="application/x-mpegURL" />\n' +
              '<source src="' + uploadedfile.url + '" type="video/mp4" />\n' +
              '</video></div> </p></center>' +
              '<script src="' + jwurl + '"></script>' +
              '<script type="text/javascript">jwplayer.key="XlnEnswS1k0cpvBFXqJYwsnzSWECBaplnchsHRncTA4=";</script>' +
              '<script type="text/javascript">' +
              'jwplayer("' + vid_id + '").setup({' +
              'sources: [{file: "' + uploadedfile.url_alt1 + '"}/*,{file: "' + uploadedfile.url_alt2 + '"},{file: "' + uploadedfile.url_alt3 + '"}*/,{file: "' + uploadedfile.url + '"}]' +
              (uploadedfile.poster ? ',image: "' + uploadedfile.poster + '"' : '') +
              ',width: "100%", aspectratio: "16:9",backcolor: "transparent",wmode: "transparent",primary: "flash"}); </script>'
              );

      editor.insertContent($link);

    }
  });
}
