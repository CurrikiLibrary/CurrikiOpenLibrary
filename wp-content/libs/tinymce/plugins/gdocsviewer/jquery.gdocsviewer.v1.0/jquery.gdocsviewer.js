/*
 * jQuery.gdocViewer - Embed linked documents using Google Docs Viewer
 * Licensed under MIT license.
 * Date: 2011/01/16
 *
 * @author Jawish Hameed
 * @version 1.0
 */
(function($){
	$.fn.gdocsViewer = function(options) {
	
		var settings = {
			width  : '95%',
			height : '600'
		};
		
		if (options) { 
			$.extend(settings, options);
		}
		
		return this.each(function() {
			var file = $(this).attr('href');
			var ext = file.substring(file.lastIndexOf('.') + 1).toUpperCase();
			if (/^(JPEG|PNG|GIF|BMP|TXT|CSS|HTML|PHP|C|CPP|H|HPP|JS|DOC|DOCX|XLS|XLSX|PPT|PPTX|PDF)$/.test(ext)) {
				$(this).after(function () {
					var id = $(this).attr('id');
					var gdvId = (typeof id !== 'undefined' && id !== false) ? id + '-gdocsviewer' : '';
					return '<div id="' + gdvId + '" class="gdocsviewer"><iframe src="https://docs.google.com/viewer?embedded=true&url=' + encodeURIComponent(file) + '" width="' + settings.width + '" height="' + settings.height + '" style="border: none;"></iframe></div>';
				})
			}else if (file.toLowerCase().indexOf("docs.google.com") >= 0){
				$(this).after(function () {
					var id = $(this).attr('id');
					var gdvId = (typeof id !== 'undefined' && id !== false) ? id + '-gdocsviewer' : '';
					return '<div id="' + gdvId + '" class="gdocsviewer"><iframe src="' + file + '" width="' + settings.width + '" height="' + settings.height + '" style="border: none;"></iframe></div>';
				})
			}
		});
	};
})( jQuery );
