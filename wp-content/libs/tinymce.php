<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

<script type="text/javascript" src="tinymce/tinymce.js"></script>

<!--script type="text/javascript" src="jwplayer/jwplayer.js"></script>
<script type="text/javascript">jwplayer.key = "XlnEnswS1k0cpvBFXqJYwsnzSWECBaplnchsHRncTA4=";</script-->

<!-- place in header of your html document -->
<script>
  tinymce.init({
    selector: "textarea#elm1",
    theme: "modern",
    width: '99.5%',
    height: '300',
    subfolder: "",
    plugins: [
      /*gdocsviewer video*/
      "fileuploader oembed",
      "advlist autolink lists charmap print hr anchor pagebreak spellchecker",
      "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking",
      "save table contextmenu directionality emoticons template paste textcolor"
    ],
    image_advtab: true,
    /*content_css: "css/content.css",*/
    toolbar: "oembed gdoc image video | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | print preview fullpage | forecolor backcolor emoticons",
    style_formats: [
      {title: 'Bold text', inline: 'b'},
      {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
      {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
      {title: 'Example', inline: 'span', classes: 'example1'},
      /*{title: 'Example 2', inline: 'span', classes: 'example2'},*/
      {title: 'Table styles'},
      {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ]
  });

</script>
<form action='preview.php' method='post' target='_blank' >
  <!-- place in body of your html document -->
  <textarea id="elm1" name="area"></textarea><br/><br/><br/>
  <input type='submit' name='submit' title='Preview' value='Preview'/>
</form>

<script>

</script>


