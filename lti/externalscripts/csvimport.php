<?php
ini_set("memory_limit", "-1");
set_time_limit(0);

$servername = "localhost";
$username = "curriki";
$password = "C9H21hGUaV2WbBY9";
$dbname = "wp_curriki";

$row = 1;
if (($handle = fopen("geoip.CSV", "r")) !== FALSE) {    
    while (($data = fgetcsv($handle)) !== FALSE) {
        $num = count($data);        
        $row++;
        
        $record = get_record($servername, $username, $password, $dbname , $data);
        
        if(!$record){
            //echo "\n do insert ....";
            insert_record($servername, $username, $password, $dbname , $data);
        }else{
            //echo "\n no insert .... me exisit";die;
        }
        
        /*if($row === 5)
            die;
         * 
         */
    }
    
    fclose($handle);
}


function get_record($servername, $username, $password, $dbname , $data){
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $country_name_str = str_replace("'", "\'", $data[3]);
    $region_name_str = str_replace("'", "\'", $data[4]);
    $city_name_str = str_replace("'", "\'", $data[5]);
    
    $sql = "
            select * from geodb where 
            ip_from='{$data[0]}'
            and ip_to = '{$data[1]}'
            and country_code='$data[2]' 
            and country_name='$country_name_str'
            and region_name='$region_name_str'
            and city_name='$city_name_str'
            and latitude='$data[6]'
            and longitude='$data[7]'
            and zip_code='$data[8]'
            and time_zone='$data[9]'
           ";

    $row = null;
    if ( $rs = mysqli_query($conn, $sql) ) {
        //echo "record fetched\n";
        $row = $rs->fetch_object();        
    } else {
        echo "record not exist so add ....: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
    return $row;
};

function insert_record($servername, $username, $password, $dbname , $data){
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    
    $country_name_str = str_replace("'", "\'", $data[3]);
    $region_name_str = str_replace("'", "\'", $data[4]);
    $city_name_str = str_replace("'", "\'", $data[5]);
    
    $sql = "INSERT INTO geodb (
            ip_from, 
            ip_to,
            country_code ,
            country_name,
            region_name,
            city_name,
            latitude,
            longitude,
            zip_code,
            time_zone
            ) VALUES ({$data[0]}, '{$data[1]}', '{$data[2]}', '{$country_name_str}','{$region_name_str}', '{$city_name_str}', {$data[6]}, {$data[7]}, '{$data[8]}', '{$data[9]}')";

    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully *** \n";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);die;
    }

    mysqli_close($conn);
}