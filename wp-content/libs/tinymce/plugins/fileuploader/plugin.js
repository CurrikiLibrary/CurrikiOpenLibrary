
tinymce.PluginManager.add('fileuploader', function (editor, url) {

  /*var editor = editor;
   var url = url;*/
  /****************Defining Global Variables ******************/
  var fbc_name, fbc_type, fbc_win;

  /****************Importing Scripts and styles ******************/
  var imported = document.createElement('script');
  imported.src = url + '/js/jquery.form.js';
  document.head.appendChild(imported);

  var imported = document.createElement('link');
  imported.href = url + '/css/progress_bar.css';
  imported.rel = 'stylesheet';
  document.head.appendChild(imported);

  var imported = document.createElement('script');
  editor.jwurl = url + '/../../../jwplayer/jwplayer.js'
  imported.src = editor.jwurl;
  document.head.appendChild(imported);


  /****************Adding form to submit file ******************/
  editor.settings.file_browse_form_id = 'file_browse_form_' + Math.floor((Math.random() * 1000000) + 1);
  $form = '<form id="' + editor.settings.file_browse_form_id + '" enctype="multipart/form-data" method="post"';
  $form += 'action="' + url + '/upload.php" style ="display:none;">';
  $form += '<input type="file" name="file" onchange="tinymce.activeEditor.settings.file_upload_callback()" />';
  $form += '<input type="hidden" name="type" value=""/>';
  $form += '<button id="clear">Clear</button>';
  $form += '</form>';
  jQuery("body").append($form);


  /****************Adding form to submit file ******************/
  editor.settings.file_upload_callback = function () {
    var options = {
      type: 'post',
      dataType: "json",
      beforeSubmit: beforeSubmit, // pre-submit callback 
      success: afterSuccess, // post-submit callback 
      uploadProgress: OnProgress, //upload progress callback 
      resetForm: true, // reset the form after successful submit 
      messageWindow: ''
    };
    jQuery('#' + editor.settings.file_browse_form_id).ajaxSubmit(options);
  }

  function beforeSubmit() {
    var $ret = false;
    var $html = '';

    if (window.File && window.FileReader && window.FileList && window.Blob)
    {
      $file = jQuery('#' + editor.settings.file_browse_form_id + ' input[type="file"]')[0].files[0];
      if ($file.size > 10000000000) {
        $html = '<div style="margin: 20px;color: red;" >Error : Too much large file</div>';
      }
      else {
        $html = '<div class="meter animate" style="height:100%" ><span class="prg_bar" style="width: 5%"><span></span></span></div>';
        $ret = true;
      }
    }
    else
    {
      $html = '<div style="margin: 20px;color: red;" >Error : Old Browser, Please Update</div>';
    }

    jQuery(this)[0].messageWindow = editor.windowManager.open({
      title: 'File Uploader',
      type: 'container',
      html: $html,
      classes: 'filemanager',
      width: 420,
      height: 60,
      inline: 1,
      buttons: [{
          text: 'Abort',
          onclick: 'close'
        }]
    }, {
      fbc_name: fbc_name,
      fbc_type: fbc_type,
      fbc_win: fbc_win
    });

    return $ret;
  }
  function OnProgress(event, position, total, percentComplete) {
    jQuery('.meter span.prg_bar').attr('style', 'width:' + percentComplete + '% !important');
  }
  function afterSuccess(data, c, xhr) {
    jQuery('#' + editor.settings.file_browse_form_id + ' clear').click();
    if (data.status == 1) {
      editor.uploadedfile = data;
      if (fbc_type == 'image' && editor.uploadedfile.type == 'image')
        fbc_win.document.getElementById(fbc_name).value = data.url;
      else
        fbc_win.document.getElementById(fbc_name).value = data.filename;

      jQuery(this)[0].messageWindow.close();
    } else {
      jQuery('.meter').replaceWith('<div style="margin: 20px;color: red;" >' + data.error + '</div>');
    }
  }

  editor.settings.file_browser_callback = function (fbc_name_l, fbc_url_l, fbc_type_l, fbc_win_l) {
    fbc_name = fbc_name_l;
    fbc_type = fbc_type_l;
    fbc_win = fbc_win_l;
    jQuery('#' + editor.settings.file_browse_form_id + ' input[type="hidden"]').val(fbc_type_l);
    jQuery('#' + editor.settings.file_browse_form_id + ' input').click();
  }


  /**************Embeding HTML in browser *****************/
  var embedHTML = function () {

    editor.insertContent(editor.uploadedfile.html.replace('JWURL', editor.jwurl));

    if (jQuery('#frmmediatype').val() == 'text') {
      switch (editor.uploadedfile.type) {
        case 'swf':
          jQuery('#frmmediatype').val('swf');
          break;
        case 'video':
          jQuery('#frmmediatype').val('video');
          break;
        case 'image':
          jQuery('#frmmediatype').val('image');
          break;
        case 'document':
          jQuery('#frmmediatype').val('document');
          break;
        default:
          jQuery('#frmmediatype').val('attachment');
          break;
      }
    } else
      jQuery('#frmmediatype').val('mixed');

    editor.uploadedfile.html = '';

    jQuery('<input>').attr({
      type: 'hidden',
      value: JSON.stringify(editor.uploadedfile),
      name: 'resourcefiles[]'
    }).prependTo('form#create_resource_form');
  }

  /****************Insert Video Dialog Generator ******************/
  function insertDialog(label) {
    editor.windowManager.open({
      title: 'Insert ' + label,
      width: 420,
      height: 120,
      body: [
        {type: 'filepicker', filetype: 'file', name: 'url', label: 'Browse'},
      ],
      onsubmit: embedHTML
    });
  }

  function embedDialog() {
    editor.windowManager.open({
      title: 'Place HTML Code',
      width: 450,
      height: 250,
      layout: 'flex',
      direction: 'column',
      align: 'stretch',
      padding: 0,
      spacing: 0,
      body: [
        {type: 'label', text: 'Paste your embed code below:', forId: 'embedHTML'},
        {id: 'embedHTML', type: 'textbox', flex: 1, name: 'embedHTML', value: '', multiline: true}
      ],
      onsubmit: function (e) {
        editor.insertContent(e.data.embedHTML);
      }

    });
  }


  // Adds doc menu icon
  editor.addButton('gdoc', {
    icon: 'newdocument',
    tooltip: 'Insert a document/zip or file.',
    text: '',
    onclick: function () {
      insertDialog('Document/Zip');
    }
  });

  // Adds doc menu item to the tools menu
  editor.addMenuItem('gdoc', {
    icon: 'newdocument',
    text: 'Insert Document/Zip',
    context: 'insert',
    prependToContext: true,
    onclick: function () {
      insertDialog('Document/Zip');
    }
  });

  // Adds video menu icon
  editor.addButton('video', {
    icon: 'media',
    tooltip: 'Insert a video/audio from your computer.',
    onclick: function () {
      insertDialog('Video/Audio');
    }
  });

  // Adds video menu item to the tools menu
  editor.addMenuItem('video', {
    text: 'Insert Video/Audio',
    icon: 'media',
    context: 'insert',
    prependToContext: true,
    onclick: function () {
      insertDialog('Video/Audio');
    }
  });

  editor.addButton('image', {
    icon: 'image',
    tooltip: 'Insert or edit an image.',
    onclick: function () {
      insertDialog('Image');
    }
  });

  editor.addMenuItem('image', {
    icon: 'image',
    text: 'Insert image',
    context: 'insert',
    prependToContext: true,
    onclick: function () {
      insertDialog('Image');
    }
  });

  editor.addButton('embed', {
    icon: 'code',
    tooltip: 'Insert/embed HTML Code.',
    onclick: function () {
      embedDialog();
    }
  });

  editor.addMenuItem('embed', {
    icon: 'code',
    text: 'Embed HTML Code',
    context: 'insert',
    prependToContext: true,
    onclick: function () {
      embedDialog();
    }
  });


});






/*
 function (e) {
 // Insert content when the window form is submitted
 $link = '<a href="' + editor.uploadedfile.url + '" title="' + editor.uploadedfile.url + '" >' + editor.uploadedfile.filename + '</a>';
 
 var file = editor.uploadedfile.url;
 var ext = file.substring(file.lastIndexOf('.') + 1).toUpperCase();
 if (/^(JPEG|PNG|GIF|BMP|TXT|CSS|HTML|PHP|C|CPP|H|HPP|JS|DOC|DOCX|XLS|XLSX|PPT|PPTX|PDF)$/.test(ext)) {
 $link = $link + '<p style="text-align:center"><div class="gdocsviewer"><iframe src="https://docs.google.com/viewer?embedded=true&url=' + encodeURIComponent(file) + '" width="98%" height="600" style="border: none;"></iframe></div></p>';
 } else if (file.toLowerCase().indexOf("docs.google.com") >= 0) {
 $link = $link + '<p style="text-align:center"><div class="gdocsviewer"><iframe src="' + file + '" width="98%" height="600" style="border: none;"></iframe></div></p>';
 }
 editor.insertContent($link);
 }
 function (e) {
 vid_id = 'jw_video_' + Math.floor((Math.random() * 1000000) + 1);
 
 $link = (
 '<center><p style="text-align:center"><div id="' + vid_id + '" class="jwplayer_video" style="text-align:center">' +
 '<video width="640" ' + (editor.uploadedfile.poster ? ' poster="' + editor.uploadedfile.poster + '"' : '') + ' controls="controls">\n' +
 '<source src="' + editor.uploadedfile.url_alt1 + '" type="video/webm" />\n' +
 //'<source src="' + editor.uploadedfile.url_alt2 + '" type="application/x-mpegURL" />\n' +
 //'<source src="' + editor.uploadedfile.url_alt3 + '" type="application/x-mpegURL" />\n' +
 '<source src="' + editor.uploadedfile.url + '" type="video/mp4" />\n' +
 '</video></div> </p></center>' +
 '<script src="' + editor.jwurl + '"></script>' +
 '<script type="text/javascript">jwplayer.key="XlnEnswS1k0cpvBFXqJYwsnzSWECBaplnchsHRncTA4=";</script>' +
 '<script type="text/javascript">' +
 'jwplayer("' + vid_id + '").setup({' +
 'sources: [{file: "' + editor.uploadedfile.url_alt1 + '"}/*,{file: "' + editor.uploadedfile.url_alt2 + '"},{file: "' + editor.uploadedfile.url_alt3 + '"}* /,{file: "' + editor.uploadedfile.url + '"}]' +
 (editor.uploadedfile.poster ? ',image: "' + editor.uploadedfile.poster + '"' : '') +
 ',width: "100%", aspectratio: "16:9",backcolor: "transparent",wmode: "transparent",primary: "flash"}); </script>'
 );
 
 editor.insertContent($link);
 
 }
 */