jQuery(document).ready(function () {

    jQuery("#imports_csv").submit(function (e) {
        e.preventDefault();
        jQuery(".spinner_box").removeClass('hide');
        
        var formData = new FormData();        
        var fileInputElement = document.getElementById("imports_csv_file");
        var csv_file = fileInputElement.files[0];        
        formData.append('csv_file' , csv_file);
        formData.append('action' , 'process_imports_csv_upload');                
        jQuery.ajax({
            method: "POST",
            url: ajaxurl,
            data: formData,
            contentType: false,
            processData: false,
        }).done(function (response) {
            jQuery(".spinner_box").addClass('hide');
            var res = JSON.parse(response);
            if(res.status === 400){
                alert(res.message);
            }else{
                alert("Import Completed!");
            }            
        });
    });
    
    jQuery("#ye_api_imports").submit(function (e) {
        e.preventDefault();
        jQuery(".spinner_box_ye").removeClass('hide');        
        
        var formData = new FormData();                                        
        formData.append('action' , 'process_ye_api_import');
        formData.append( 'ye_api_url', jQuery('#ye_api_url').val() );
        
        jQuery.ajax({
            method: "POST",
            url: ajaxurl,
            data: formData,
            contentType: false,
            processData: false,
        }).done(function (response) {
            jQuery(".spinner_box_ye").addClass('hide');
            var res = JSON.parse(response);
            if(res.status === 400){
                alert(res.message);
            }else{
                alert("Import Completed!");
            }            
        });
    });
    
});