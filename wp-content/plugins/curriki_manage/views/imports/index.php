<div class="wrap">
    <div id="icon-users" class="icon32"><br/></div>
    <h2>Imports</h2>

    <div id="curriki-imports-ui">
        
        <div div="poly-up">
            <h2>Polyup CSV Import</h2>
            <form id="imports_csv" method="post" enctype="multipart/form-data">
                <label>Select CSV file </label>
                <input type="file" name="imports_csv_file" id="imports_csv_file" />
                <input type="submit" name="imports_csv_upload" id="imports_csv_upload" class="button button-primary" value="Upload" />                            
                <span class="spinner_box hide"><img width="27" height="27" src="<?php echo home_url() ?>/wp-content/themes/genesis-curriki/images/spinner.gif" /></span>
            </form>
            <div class="clear"></div>
            <p>
                <strong></strong>
            </p>
        </div>
        
        <br /><br />
        
        <div div="ye-api-imports">
            <h2>YE Academy API Imports</h2>
            <form id="ye_api_imports" method="post" enctype="multipart/form-data">
                <label>API Endpoint </label>
                <input type="text" name="ye_api_url" id="ye_api_url" value="https://yeacademy.org/wp-json/ex/v1/curriki" />
                <input type="submit" name="ye_api_submit" id="ye_api_submit" class="button button-primary" value="Import" />
                <span class="spinner_box_ye hide"><img width="20" height="20" src="<?php echo home_url() ?>/wp-content/themes/genesis-curriki/images/spinner.gif" /></span>
            </form>
            <div class="clear"></div>
            <p>
                <strong></strong>
            </p>
        </div>
        
        
    </div>    
</div>