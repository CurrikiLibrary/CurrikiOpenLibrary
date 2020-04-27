<?php
error_reporting(E_ALL); 
ini_set("display_startup_errors", 1); 
ini_set("display_errors", 1);


class Sync {

    public static $connection;
    public static $env;
    public static $skip;
    public static $skip_original;
    public static $standards_table;
    public static $standards_insert_table;
    public static $standards_educationleveles_table;
    public static $standards_educationleveles_insert_table;
    public static $statements_table;
    public static $statements_insert_table;
    public static $statements_educationleveles_table;
    public static $statements_educationleveles_insert_table;
    public static $parentids;

    public function __construct($env, $skip = 0) {
        Sync::$env = $env;
        Sync::$skip = $skip * Conn::$limit;
        Sync::$skip_original = $skip;
        Sync::$standards_table = 'standards';
        Sync::$standards_insert_table = 'tmp_standards';
        Sync::$standards_educationleveles_table = 'standard_educationlevels';
        Sync::$standards_educationleveles_insert_table = 'tmp_standard_educationlevels';
        Sync::$statements_educationleveles_table = 'statement_educationlevels';
        Sync::$statements_educationleveles_insert_table = 'tmp_statement_educationlevels';
        Sync::$statements_table = 'statements';
        Sync::$statements_insert_table = 'tmp_statements';
        Sync::$parentids = array();
        
        if(Sync::$env == 'local'){
            Sync::$connection = mysqli_connect(Conn::HOST_LOCAL, Conn::USERNAME_LOCAL, Conn::PASSWORD_LOCAL, Conn::DATABASE_LOCAL);
        } elseif($env == 'dev'){
            Sync::$connection = mysqli_connect(Conn::HOST_DEV, Conn::USERNAME_DEV, Conn::PASSWORD_DEV, Conn::DATABASE_DEV);
        } elseif($env == 'prod'){
            Sync::$connection = mysqli_connect(Conn::HOST_PROD, Conn::USERNAME_PROD, Conn::PASSWORD_PROD, Conn::DATABASE_PROD);
        }

        if (!Sync::$connection) {
            print ("\nError description: No Connection");
            return false;
        }
        
        mysqli_set_charset(Sync::$connection, "utf8");

    }

    public function uploadResources($resource_num = 5){
        die();
        require_once 'PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $inputFileName =  __DIR__.'/../docs/upload_res.xlsx';
        
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();

        
        
        
        
        
        //initialization
        
        $licenseid = 2;
        $lasteditorid = $contributorid = 10000;
        $language = 'eng';
        $partner = 'T';
        $type = 'resource';
        $access = 'public';
        $pageurl = null;
        $public = 'T';
        $source = 'Excel-15-05-18';
        $currikilicense = 'T';
        
        //  Loop through each row of the worksheet in turn
//        $i=5;
        $i = $resource_num;
        foreach ($sheet->getRowIterator() as $row) {
            
//            if($i < 207){ //skip row 25
//                $i++;
//                continue;
//            }

            $collection_title = $objPHPExcel->getActiveSheet()->getCell("A$i")->getValue();//column A
            $collection_title = trim(preg_replace('/\s+/', ' ', $collection_title));
            
            $collection_description = trim($objPHPExcel->getActiveSheet()->getCell("B$i")->getValue());//column A
            $resource_type = trim($objPHPExcel->getActiveSheet()->getCell("C$i")->getValue());//column A
            $collection_sequence = trim($objPHPExcel->getActiveSheet()->getCell("D$i")->getValue());//column A
            
            $title = $objPHPExcel->getActiveSheet()->getCell("E$i")->getValue();//column A
            $title = trim(preg_replace('/\s+/', ' ', $title));
            $description = trim($objPHPExcel->getActiveSheet()->getCell("F$i")->getValue());//column B
            $pre_video_text = trim($objPHPExcel->getActiveSheet()->getCell("G$i")->getValue());//column C
            $embedding_link = trim($objPHPExcel->getActiveSheet()->getCell("H$i")->getValue());//column E
            $externalurl = trim($objPHPExcel->getActiveSheet()->getCell("I$i")->getValue());//column D
            $post_video_text = trim($objPHPExcel->getActiveSheet()->getCell("J$i")->getValue());//column F
            $subtopics = trim($objPHPExcel->getActiveSheet()->getCell("K$i")->getValue());//column G
            $grade_level = trim($objPHPExcel->getActiveSheet()->getCell("L$i")->getValue());//column H
            $instructionType = trim($objPHPExcel->getActiveSheet()->getCell("M$i")->getValue());//column I
            $keywords = trim($objPHPExcel->getActiveSheet()->getCell("N$i")->getValue());//column J
            $standards_aligned = trim($objPHPExcel->getActiveSheet()->getCell("O$i")->getValue());//column K
            $studentfacing = trim($objPHPExcel->getActiveSheet()->getCell("P$i")->getValue());//column L
            $question = trim($objPHPExcel->getActiveSheet()->getCell("R$i")->getValue());//column L
            
            $option_1 = trim($objPHPExcel->getActiveSheet()->getCell("S$i")->getValue());//column L
            $option_1_correct = trim($objPHPExcel->getActiveSheet()->getCell("T$i")->getValue());//column L
            $response_1 = trim($objPHPExcel->getActiveSheet()->getCell("V$i")->getValue());//column L
            
            $option_2 = trim($objPHPExcel->getActiveSheet()->getCell("W$i")->getValue());//column L
            $option_2_correct = trim($objPHPExcel->getActiveSheet()->getCell("X$i")->getValue());//column L
            $response_2 = trim($objPHPExcel->getActiveSheet()->getCell("Y$i")->getValue());//column L
            
            $option_3 = trim($objPHPExcel->getActiveSheet()->getCell("Z$i")->getValue());//column L
            $option_3_correct = trim($objPHPExcel->getActiveSheet()->getCell("AA$i")->getValue());//column L
            $response_3 = trim($objPHPExcel->getActiveSheet()->getCell("AB$i")->getValue());//column L
            
            $option_4 = trim($objPHPExcel->getActiveSheet()->getCell("AC$i")->getValue());//column L
            $option_4_correct = trim($objPHPExcel->getActiveSheet()->getCell("AD$i")->getValue());//column L
            $response_4 = trim($objPHPExcel->getActiveSheet()->getCell("AE$i")->getValue());//column L
            
            $i++;
            
            if($studentfacing == 'Y'){
                $studentfacing = 'T';
            } else {
                $studentfacing = 'F';
            }
            
            
            
            //inset $column_A_Value value in DB query here
            if($title == null){
                echo "\nRow\t ".($i - 1). "Title is empty";
                continue;
            }

//            $sql = 'SELECT * FROM resources WHERE title = "'.mysqli_real_escape_string(Sync::$connection, $title).'" and source="Learn Liberty"';
            
            
//            if(!($result = mysqli_query(Sync::$connection, $sql))){
//                print ("\nError description 0: " . mysqli_error(Sync::$connection));
//                die();
//            }
//            if(mysqli_num_rows($result) > 0){
//                $resourceid = mysqli_fetch_array($result, MYSQLI_ASSOC)['resourceid'];
//                $update = true;
//            } else {
//                $update = false;
//            }
            if (strpos($embedding_link, 'vimeo') !== false) {
                $embedding_link = @end(explode("/", $embedding_link));
                $embedding_link = "<iframe width='500px' height='294px' src='https://player.vimeo.com/video/$embedding_link?'></iframe>";
            }
            if($resource_type == 'C'){
                $type = 'collection';
                $mediatype = 'collection';
                
            } else{
                $type='resource';
            }
            if($embedding_link){
                $content = '<div>'.$pre_video_text.'</div><br /><br /><div>'.$embedding_link.'</div><br /><br /><div>'.$post_video_text.'</div>';
            } else {
                $content = '<div>'.$pre_video_text.'</div>';
            }
//            if($update == true){
//                $query = "UPDATE resources"
//                    . " SET licenseid = $licenseid,"
//                    . " contributorid = $contributorid,"
//                    . " contributiondate = NOW(), "
//                    . " description = '".  mysqli_real_escape_string(Sync::$connection, $description)."',"
//                    . " title = '".  mysqli_real_escape_string(Sync::$connection, $title)."',"
//                    . " keywords = '".  mysqli_real_escape_string(Sync::$connection, $keywords)."',"
//                    . " language = '".  mysqli_real_escape_string(Sync::$connection, $language)."',"
//                    . " lasteditorid = '".  mysqli_real_escape_string(Sync::$connection, $lasteditorid)."',"
//                    . " lasteditdate = NOW(), "
//                    . " currikilicense = '".  mysqli_real_escape_string(Sync::$connection, $currikilicense)."',"
//                    . " externalurl = '".  mysqli_real_escape_string(Sync::$connection, $externalurl)."',"
//                    . " content = '".  mysqli_real_escape_string(Sync::$connection, $content)."',"
//                    . " studentfacing = '".  mysqli_real_escape_string(Sync::$connection, $studentfacing)."',"
//                    . " source = '".  mysqli_real_escape_string(Sync::$connection, $source)."',"
//                    . " partner = '".  mysqli_real_escape_string(Sync::$connection, $partner)."',"
//                    . " createdate = NOW(), "
//                    . " type = '".  mysqli_real_escape_string(Sync::$connection, $type)."',"
//                    . " public = '".  mysqli_real_escape_string(Sync::$connection, $public)."',"
//                    . " mediatype = '".  mysqli_real_escape_string(Sync::$connection, $mediatype)."',"
//                    . " access = '".  mysqli_real_escape_string(Sync::$connection, $access)."'"
//                    . " WHERE resourceid = $resourceid";
//
//                
//
//                
//            }
//            else {
            
                

                $sql = '(
                    "' . mysqli_real_escape_string(Sync::$connection, $licenseid) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $contributorid) . '",
                    NOW(),
                    "' . mysqli_real_escape_string(Sync::$connection, $description) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $title) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $keywords) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $language) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $lasteditorid) . '",
                    NOW(),
                    "' . mysqli_real_escape_string(Sync::$connection, $currikilicense) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $externalurl) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $content) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $studentfacing) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $source) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $partner) . '",
                    NOW(),
                    "' . mysqli_real_escape_string(Sync::$connection, $type) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $public) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $access) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $pageurl) . '")';

                $query = "INSERT INTO resources (licenseid,
                                contributorid,
                                contributiondate,
                                description,
                                title,
                                keywords,
                                language,
                                lasteditorid,
                                lasteditdate,
                                currikilicense,
                                externalurl,
                                content,
                                studentfacing,
                                source,
                                partner,
                                createdate,
                                type,
                                public,
                                access,
                                pageurl)
                                VALUES $sql";
                
                
//            }
            if(mysqli_query(Sync::$connection, $query)){
                $resourceid = Sync::$connection->insert_id;
                $dup_sql = "SELECT count(*) CNT FROM resources where pageurl = '" . $pageurl . "' and  resourceid != '" . $resourceid . "'";
                
                if (!($dup_title = mysqli_query(Sync::$connection, $dup_sql))) {
                    print ("\nError description: " . mysqli_error(Sync::$connection));
                }

                if (mysqli_num_rows($dup_title) > 0) {
                    $pageurl = $title ? $title : substr($description, 1, 30);
                    $pageurl = substr($pageurl = str_replace(array(' ', ',', ':', '.', '(', ')', '\'', '?', '/', '+', '\\', '--', '&', '#', '"'), array('-', '-', '-', '', '-', '-', '-', '-', '-', '-', '-', '-', 'and', '-', '-'), $pageurl), 0, 499);
                    $pageurl = $pageurl . '-' . $resourceid;
                    
                    $ql = "UPDATE resources SET pageurl = '$pageurl' WHERE resourceid = $resourceid";
                    if (!mysqli_query(Sync::$connection, $ql)) {
                        print ("\nError description 1: " . mysqli_error(Sync::$connection));
                    }
                }
                echo "\n".$resourceid. "\t$title"; 
            }
            
            if($question != ''){
                $sql = "DELETE FROM user_resourcequestions WHERE answerid IN (SELECT answerid FROM resourcequestion_answers WHERE questionid IN (SELECT questionid FROM resourcequestions WHERE resourceid = $resourceid))";
                if(!mysqli_query(Sync::$connection, $sql)){
                    print ("\nError description delete 0: " . mysqli_error(Sync::$connection));
                }
                $sql = "DELETE FROM resourcequestion_answers WHERE questionid IN (SELECT questionid FROM resourcequestions WHERE resourceid = $resourceid)";
                if(!mysqli_query(Sync::$connection, $sql)){
                    print ("\nError description delete 1: " . mysqli_error(Sync::$connection));
                }
                $sql = "DELETE FROM resourcequestions WHERE resourceid = $resourceid";
                if(!mysqli_query(Sync::$connection, $sql)){
                    print ("\nError description delete 2: " . mysqli_error(Sync::$connection));
                }
                
                if(!mysqli_query(Sync::$connection, 'INSERT INTO resourcequestions (resourceid, type, question) VALUES('.$resourceid.', "mcq","'.  mysqli_real_escape_string(Sync::$connection, $question).'")')){
                    die(mysqli_error(Sync::$connection));
                }
                $question_last_id = Sync::$connection->insert_id;
                if(!mysqli_query(Sync::$connection, 'INSERT INTO resourcequestion_answers (questionid, sequence, answer, correct, response) VALUES('.$question_last_id.', 1,"'.  mysqli_real_escape_string(Sync::$connection, $option_1).'", "'.$option_1_correct.'", "'.mysqli_real_escape_string(Sync::$connection, $response_1).'")')){
                    die(mysqli_error(Sync::$connection));
                }
                if(!mysqli_query(Sync::$connection, 'INSERT INTO resourcequestion_answers (questionid, sequence, answer, correct, response) VALUES('.$question_last_id.', 2,"'.  mysqli_real_escape_string(Sync::$connection, $option_2).'", "'.$option_2_correct.'", "'.mysqli_real_escape_string(Sync::$connection, $response_2).'")')){
                    die(mysqli_error(Sync::$connection));
                }
                if(!mysqli_query(Sync::$connection, 'INSERT INTO resourcequestion_answers (questionid, sequence, answer, correct, response) VALUES('.$question_last_id.', 3,"'.  mysqli_real_escape_string(Sync::$connection, $option_3).'", "'.$option_3_correct.'", "'.mysqli_real_escape_string(Sync::$connection, $response_3).'")')){
                    die(mysqli_error(Sync::$connection));
                }
                if(!mysqli_query(Sync::$connection, 'INSERT INTO resourcequestion_answers (questionid, sequence, answer, correct, response) VALUES('.$question_last_id.', 4,"'.  mysqli_real_escape_string(Sync::$connection, $option_4).'", "'.$option_4_correct.'", "'.mysqli_real_escape_string(Sync::$connection, $response_4).'")')){
                    die(mysqli_error(Sync::$connection));
                }
                if($option_1_correct == 'T'){
                    $correct = 1;
                } elseif($option_2_correct == 'T'){
                    $correct = 2;
                } elseif($option_3_correct == 'T'){
                    $correct = 3;
                } elseif($option_4_correct == 'T'){
                    $correct = 4;
                }
                $content .= '<div class="question_wrapper">
                            <form class="question_front_form">
                                <input name="question_num" type="hidden" value="1">
                                <div class="question_frontend_statement">
                                    <h4>
                                        <span class="question">'.$question.'</span>
                                        <span class="display_none edit_question_link" style="text-decoration: underline; cursor: pointer;">Edit</span>
                                    </h4>
                                </div>
                                <div class="question_frontend_options">
                                    <div class="frontend_option">
                                        <label>
                                            <input id="option1" name="option" type="radio" value="1">
                                            <span class="option_val">'.$option_1.'</span>
                                        </label>
                                    </div>
                                    <div id="response_option1" class="frontend_response" style="display: none;">'.$response_1.'</div>
                                    <div class="frontend_option">
                                        <label>
                                            <input id="option2" name="option" type="radio" value="2">
                                            <span class="option_val">'.$option_2.'</span>
                                        </label>
                                    </div>
                                    <div id="response_option2" class="frontend_response" style="display: none;">'.$response_2.'</div>
                                    <div class="frontend_option">
                                        <label>
                                            <input id="option3" name="option" type="radio" value="3">
                                            <span class="option_val">'.$option_3.'</span>
                                        </label>
                                    </div>
                                    <div id="response_option3" class="frontend_response" style="display: none;">'.$response_3.'</div>
                                    <div class="frontend_option">
                                        <label>
                                            <input id="option4" name="option" type="radio" value="4">
                                            <span class="option_val">'.$option_4.'</span>
                                        </label>
                                    </div>
                                    <div id="response_option4" class="frontend_response" style="display: none;">'.$response_4.'</div>
                                </div>
                                <div class="correct_option" style="display: none;">'.$correct.'</div>
                                <div class="question_type" style="display: none;">mcq</div>
                                <input class="questionid" name="questionid" type="hidden" value="'.$question_last_id.'">
                                <div class="question_frontend_submit_wrapper">
                                    <input name="question_frontend_submit" type="submit" value="Check Answer">
                                </div>
                            </form>
                            <div>&nbsp;</div>
                        </div>';
                $ql = "UPDATE resources SET content = '".  mysqli_real_escape_string(Sync::$connection, $content)."' WHERE resourceid = $resourceid";
                if (!mysqli_query(Sync::$connection, $ql)) {
                    print ("\nError description 2: " . mysqli_error(Sync::$connection));
                }
            }

            $sql = "DELETE FROM resource_educationlevels WHERE resourceid = $resourceid";

            mysqli_query(Sync::$connection, $sql);
            $grade_level = explode(",", $grade_level);

            foreach ($grade_level as $grade) {
                $educationlevelid = null;
                $grade = trim($grade);
                if(is_numeric($grade)){
                    $educationlevelid = $grade + 6;
                } elseif($grade == 'HigherEducation'){
                    $educationlevelid = 22;
                }
                if($educationlevelid == null){
                    continue;
                }
                $que = "INSERT INTO resource_educationlevels values($resourceid, $educationlevelid)";

                if (!mysqli_query(Sync::$connection, $que)) {
                    print ("\nError description 3: " . mysqli_error(Sync::$connection));
                }
            }

            //subtopics
            $sql = "DELETE FROM resource_subjectareas WHERE resourceid = $resourceid";
            mysqli_query(Sync::$connection, $sql);
            $subtopics = explode(",", $subtopics);
            foreach($subtopics as $subtopic){
                $subtopic = explode(":", $subtopic);
                if(isset($subtopic[0]) && isset($subtopic[1])){
                    $subject = trim($subtopic[0]);
                    $subjectarea = trim($subtopic[1]);
                } else {
                    continue;
                }


//                    $subject = 'SocialStudies';
                $sql = "SELECT subjectid FROM subjects WHERE subject LIKE '$subject'";
                $cursor_subjects = mysqli_query(Sync::$connection, $sql);
                if (!($cursor_subjects)) {
                    print ("\nError description 4: " . mysqli_error(Sync::$connection));
                }


                while ($subjects = mysqli_fetch_array($cursor_subjects, MYSQLI_ASSOC)) {
                    $subjectid = $subjects['subjectid'];
//                        $subjectarea = 'Civics';

                    $sql = "SELECT * FROM subjectareas WHERE subjectid = $subjectid AND subjectarea LIKE '$subjectarea'";

                    $cursor_subjectareas = mysqli_query(Sync::$connection, $sql);
                    if (!($cursor_subjectareas)) {
                        print ("\nError description 5: " . mysqli_error(Sync::$connection));
                    }
                    while ($subjectareas = mysqli_fetch_array($cursor_subjectareas, MYSQLI_ASSOC)) {
                        $subjectareaid = $subjectareas['subjectareaid'];
                        $sql = "INSERT INTO resource_subjectareas VALUES($resourceid, $subjectareaid)";

                        if (!mysqli_query(Sync::$connection, $sql)) {
                            print ("\nError description 6: " . mysqli_error(Sync::$connection));
                        }
                    }

                }


            }

            $sql = "DELETE FROM collectionelements WHERE resourceid = $resourceid";
            mysqli_query(Sync::$connection, $sql);
            if($collection_title != ''){
                if(!$rid = mysqli_fetch_array(mysqli_query(Sync::$connection, "SELECT resourceid from resources WHERE title = '".mysqli_real_escape_string(Sync::$connection, $collection_title)."' and type='collection'"), MYSQLI_ASSOC)['resourceid']){
                    print ("\nError description 7: " . mysqli_error(Sync::$connection));
                    die();
                }
                if(!mysqli_query(Sync::$connection, "INSERT INTO collectionelements VALUES ($rid, $resourceid, $collection_sequence)")){
                    print ("\nError description 8: " . mysqli_error(Sync::$connection));
                    die();
                }
            }
            
            $instructionTypeArr = explode(",",$instructionType);
            foreach($instructionTypeArr as $instruction){
                $sql = "SELECT * FROM instructiontypes WHERE displayname = '" . mysqli_real_escape_string(Sync::$connection, trim($instruction)). "'";
               
                $cursor_instruction_types = mysqli_query(Sync::$connection, $sql);
                if (!($cursor_instruction_types)) {
                    print ("\nError description5 : " . mysqli_error(Sync::$connection));
                }


                while ($instruction = mysqli_fetch_array($cursor_instruction_types, MYSQLI_ASSOC)) {
//                        $subjectarea = 'Civics';

                    $sql = "INSERT INTO resource_instructiontypes VALUES($resourceid, {$instruction['instructiontypeid']})";

                    mysqli_query(Sync::$connection, $sql);

                }
            }
            
        
            break;
            
        }
        echo "<script>window.location = 'https://www.curriki.org/curriki_scripts/upload_resources.php?env=prod&resource_num=".($resource_num+1)."'</script>";
    }

    public function uploadResourcesUSEconomic($resource_num){
        $collectionid = 304003;
        require_once 'PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $inputFileName =  __DIR__.'/../docs/upload_res.xlsx';
        
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();

        
        echo "<pre>";
        
        
        
        //initialization
        
        $licenseid = 2;
        $lasteditorid = $contributorid = 534974;
        $language = 'eng';
        $partner = 'T';
        $type = 'resource';
        $access = 'public';
        $pageurl = null;
        $public = 'T';
        $source = 'Learn Liberty';
        $currikilicense = 'F';
        
        //  Loop through each row of the worksheet in turn
        $i=4;
        $i = $resource_num;
        foreach ($sheet->getRowIterator() as $row) {
            
            if($i < 207){ //skip till row 207
                $i++;
                continue;
            }

            $collection_title = $objPHPExcel->getActiveSheet()->getCell("A$i")->getValue();//column A
            $collection_title = trim(preg_replace('/\s+/', ' ', $collection_title));
            $collection_description = trim($objPHPExcel->getActiveSheet()->getCell("B$i")->getValue());//column A
            $resource_type = trim($objPHPExcel->getActiveSheet()->getCell("C$i")->getValue());//column A
            $collection_sequence = trim($objPHPExcel->getActiveSheet()->getCell("D$i")->getValue());//column A
            
            $title = $objPHPExcel->getActiveSheet()->getCell("E$i")->getValue();//column A
            $title = trim(preg_replace('/\s+/', ' ', $title));
            $description = trim($objPHPExcel->getActiveSheet()->getCell("F$i")->getValue());//column B
            $pre_video_text = trim($objPHPExcel->getActiveSheet()->getCell("G$i")->getValue());//column C
            $externalurl = trim($objPHPExcel->getActiveSheet()->getCell("H$i")->getValue());//column D
            $embedding_link = trim($objPHPExcel->getActiveSheet()->getCell("I$i")->getValue());//column E
            $post_video_text = trim($objPHPExcel->getActiveSheet()->getCell("J$i")->getValue());//column F
            $subtopics = trim($objPHPExcel->getActiveSheet()->getCell("K$i")->getValue());//column G
            $grade_level = trim($objPHPExcel->getActiveSheet()->getCell("L$i")->getValue());//column H
            $mediatype = trim($objPHPExcel->getActiveSheet()->getCell("M$i")->getValue());//column I
            $keywords = trim($objPHPExcel->getActiveSheet()->getCell("N$i")->getValue());//column J
            $standards_aligned = trim($objPHPExcel->getActiveSheet()->getCell("O$i")->getValue());//column K
            $studentfacing = trim($objPHPExcel->getActiveSheet()->getCell("P$i")->getValue());//column L
            $question = trim($objPHPExcel->getActiveSheet()->getCell("R$i")->getValue());//column L
            
            $option_1 = trim($objPHPExcel->getActiveSheet()->getCell("S$i")->getValue());//column L
            $option_1_correct = trim($objPHPExcel->getActiveSheet()->getCell("T$i")->getValue());//column L
            $response_1 = trim($objPHPExcel->getActiveSheet()->getCell("V$i")->getValue());//column L
            
            $option_2 = trim($objPHPExcel->getActiveSheet()->getCell("W$i")->getValue());//column L
            $option_2_correct = trim($objPHPExcel->getActiveSheet()->getCell("X$i")->getValue());//column L
            $response_2 = trim($objPHPExcel->getActiveSheet()->getCell("Y$i")->getValue());//column L
            
            $option_3 = trim($objPHPExcel->getActiveSheet()->getCell("Z$i")->getValue());//column L
            $option_3_correct = trim($objPHPExcel->getActiveSheet()->getCell("AA$i")->getValue());//column L
            $response_3 = trim($objPHPExcel->getActiveSheet()->getCell("AB$i")->getValue());//column L
            
            $option_4 = trim($objPHPExcel->getActiveSheet()->getCell("AC$i")->getValue());//column L
            $option_4_correct = trim($objPHPExcel->getActiveSheet()->getCell("AD$i")->getValue());//column L
            $response_4 = trim($objPHPExcel->getActiveSheet()->getCell("AE$i")->getValue());//column L
            
            $i++;
            
            if($studentfacing == 'Y'){
                $studentfacing = 'T';
            } else {
                $studentfacing = 'F';
            }
            
            
            
            //inset $column_A_Value value in DB query here
            if($title == null){
                echo "\nRow\t ".($i - 1). "Title is empty";
                continue;
            }
            
            $type='resource';
            $content = '<div>'.$pre_video_text.'</div><br /><br /><div>'.$embedding_link.'</div><br /><br /><div>'.$post_video_text.'</div>';
            
            $sql = '(
                    "' . mysqli_real_escape_string(Sync::$connection, $licenseid) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $contributorid) . '",
                    NOW(),
                    "' . mysqli_real_escape_string(Sync::$connection, $description) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $title) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $keywords) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $language) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $lasteditorid) . '",
                    NOW(),
                    "' . mysqli_real_escape_string(Sync::$connection, $currikilicense) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $externalurl) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $content) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $studentfacing) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $source) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $partner) . '",
                    NOW(),
                    "' . mysqli_real_escape_string(Sync::$connection, $type) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $public) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, strtolower($mediatype)) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $access) . '",
                    "' . mysqli_real_escape_string(Sync::$connection, $pageurl) . '")';

                $query = "INSERT INTO resources (licenseid,
                                contributorid,
                                contributiondate,
                                description,
                                title,
                                keywords,
                                language,
                                lasteditorid,
                                lasteditdate,
                                currikilicense,
                                externalurl,
                                content,
                                studentfacing,
                                source,
                                partner,
                                createdate,
                                type,
                                public,
                                mediatype,
                                access,
                                pageurl)
                                VALUES $sql";
                if(mysqli_query(Sync::$connection, $query)){
                $resourceid = Sync::$connection->insert_id;
                $dup_sql = "SELECT count(*) CNT FROM resources where pageurl = '" . $pageurl . "' and  resourceid != '" . $resourceid . "'";
                
                if (!($dup_title = mysqli_query(Sync::$connection, $dup_sql))) {
                    print ("\nError description: " . mysqli_error(Sync::$connection));
                }

                if (mysqli_num_rows($dup_title) > 0) {
                    $pageurl = $title ? $title : substr($description, 1, 30);
                    $pageurl = substr($pageurl = str_replace(array(' ', ',', ':', '.', '(', ')', '\'', '?', '/', '+', '\\', '--', '&', '#', '"'), array('-', '-', '-', '', '-', '-', '-', '-', '-', '-', '-', '-', 'and', '-', '-'), $pageurl), 0, 499);
                    $pageurl = $pageurl . '-' . $resourceid;
                    
                    $ql = "UPDATE resources SET pageurl = '$pageurl' WHERE resourceid = $resourceid";
                    if (!mysqli_query(Sync::$connection, $ql)) {
                        print ("\nError description 1: " . mysqli_error(Sync::$connection));
                    }
                }
                if(!mysqli_query(Sync::$connection, "INSERT INTO collectionelements VALUES ($collectionid, $resourceid, $collection_sequence)")){
                    print ("\nError description 8: " . mysqli_error(Sync::$connection));
                    die();
                }
                echo "\n".$resourceid. "\t$title"; 
            }
        }
    }
    public function getLinks(){
        die();
        $sql = "SELECT * FROM resources WHERE resourceid >=302510 AND resourceid <= 302709 ORDER BY resourceid DESC";
//        $sql = "SELECT * FROM resources WHERE source LIKE '%Learn Liberty%'";

        $cursor = mysqli_query(Sync::$connection, $sql);
        if (!($cursor)) {
            print ("Error description: " . mysqli_error(Sync::$connection));
            return false;
        }

        echo "<table border='1'>";
        while ($resource = mysqli_fetch_array($cursor, MYSQLI_ASSOC)) {
            echo "<tr>";
                echo "<td>";
                    echo "{$resource['resourceid']}";
                echo "</td>";
                echo "<td>";
                    echo "<a href='http://www.curriki.org/oer/{$resource['pageurl']}?testx=true'>{$resource['title']}</a>";
                echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    public function uploadThinkfinity(){
//        die();
        if(Sync::$env == 'local'){
            $thinkfinity_dir = Constants::THINKFINITY_DIR_LOCAL;
        } elseif (Sync::$env == 'dev') {
            $thinkfinity_dir = Constants::THINKFINITY_DIR_DEV;
        }
        
        Sync::getContentAndUploadDataThinkfinity($thinkfinity_dir);
        
        
    }
    
    private static function getContentAndUploadDataThinkfinity($dir){
        if(is_dir($dir)){
            $sub_dirs = scandir($dir);
            for($i = 0; $i < count($sub_dirs); $i++){
                if($sub_dirs[$i] == '.' || $sub_dirs[$i] == '..')
                    continue;
                $dir1 = $dir. '/'.$sub_dirs[$i];
                Sync::getContentAndUploadDataThinkfinity($dir1);
            }
        } elseif(is_file($dir)) {
            
            $folder_arr = explode('/', $dir);
            $filename = @end($folder_arr);
            $foldername = $folder_arr[count($folder_arr)-2];
            if($foldername == str_replace(".csv", "", $filename)){
                echo "\n**************************************************************************************************************\n";
                echo $dir . "\n";
                $data = Sync::getDataMainFile($dir);
                
                $dir_arr = explode("/", $dir);
                $directory = $dir_arr[count($dir_arr) - 4];
                
                $allow_dir_arr = [
                    '21CSF', 'AAASS', 'AK', 'AL', 'ALCCS', 'AR', 'AZ', 'AZCCS',
                    'CA', 'CACCS', 'CCK12', 'CO', 'CT', 'CTCCS', 'DC', 'DCCCS',
                    'DE', 'DECCS', 'FL', 'FLCCS', 'GA', 'GACCS', 'HI', 'HICCS',
                    'IA', 'IACCS', 'ID', 'IDCCS', 'IL', 'ILCCS', 'IN', 'INCCS',
                    'KS', 'KSCCS', 'KY', 'LA', 'LACCS', '__Logo', 'MA', 'MACCS',
                    'MD', 'ME', 'MECCS', 'MI', 'MICCS', 'MN', 'MNCCS', 'MO',
                    'MOCCS', 'MS', 'MSCCS', 'MT', 'NC', 'NCCCS', 'ND', 'NDCCS',
                    'NE', 'NH', 'NHCCS', 'NJ', 'NM', 'NMPS', 'NRCDSC', 'NV',
                    'NVCCS', 'NY', 'NYCCS', 'OH', 'OH201213', 'OHCCS', 'OK', 'OKCCS',
                    'OR', 'ORCCS', 'PA', 'RI', 'RICCS', 'SC', 'SCCCS', 'SD',
                    'SDCCS', 'TN', 'TNCCS', 'TX', 'UT', 'UTCCS', 'VA', 'VICCS',
                    'VT', 'WA', 'WI', 'WICCS', 'WV', 'WVCCS', 'WY', 'WYCCS',
                    ];
//                $allow_dir_arr = [
//                    'MD', 'ME', 'MECCS', 'MI', 'MICCS', 'MN', 'MNCCS', 'MO',
//                    'MOCCS', 'MS', 'MSCCS', 'MT', 'NC', 'NCCCS', 'ND', 'NDCCS',
//                    ];
                if(!in_array($directory, $allow_dir_arr)){
                    return false;
                }
                $directory = preg_replace('/CCS$/', '', $directory);
                
                $grade = $dir_arr[count($dir_arr) - 3];
                $subject = $dir_arr[count($dir_arr) - 2];
                
                
                foreach ($data as $key => $value){
                    
                  
                    $que = "INSERT INTO thinkfinity_standards (label,description, link, counter, directory, grade, subject)"
                        . " VALUES (
                            '".mysqli_real_escape_string(Sync::$connection, $value['label'])."',
                            '".mysqli_real_escape_string(Sync::$connection, $value['description'])."',
                            '".mysqli_real_escape_string(Sync::$connection, $value['link'])."',
                            '".mysqli_real_escape_string(Sync::$connection, $value['counter'])."',
                            '".mysqli_real_escape_string(Sync::$connection, $directory)."',
                            '".mysqli_real_escape_string(Sync::$connection, $grade)."',
                            '".mysqli_real_escape_string(Sync::$connection, $subject)."'
                            )";
                    
                    if (!mysqli_query(Sync::$connection, $que)) {
                        print ("Error description 1: " . mysqli_error(Sync::$connection));
                    }
                    $last_id = Sync::$connection->insert_id;
                    
                    $folderpath = str_replace($filename, "", $dir);
                    $subfilepath = $folderpath.$value['label'].'csv';
                    if(file_exists($subfilepath)){
                        echo $subfilepath . "\n";
                        $sub_data = Sync::getDataSubFile($subfilepath);
                        $sql = array();
                        foreach ($sub_data as $k => $v){
                            
//                            $que = "INSERT INTO thinkfinity_standards_meta (thinkfinity_standardid, title, meta, description, logo, link)"
//                                . " VALUES (
//                                    ".mysqli_real_escape_string(Sync::$connection, $last_id).",
//                                    '".mysqli_real_escape_string(Sync::$connection, $v['title'])."',
//                                    '".mysqli_real_escape_string(Sync::$connection, $v['meta'])."',
//                                    '".mysqli_real_escape_string(Sync::$connection, $v['description'])."',
//                                    '".mysqli_real_escape_string(Sync::$connection, $v['logo'])."',
//                                    '".mysqli_real_escape_string(Sync::$connection, $v['link'])."'
//                                    )";
//                            if (!mysqli_query(Sync::$connection, $que)) {
//                                print ("Error description 1: " . mysqli_error(Sync::$connection));
//                            }
                        
                            $sql[] = "(
                                        ".mysqli_real_escape_string(Sync::$connection, $last_id).",
                                        '".mysqli_real_escape_string(Sync::$connection, $v['title'])."',
                                        '".mysqli_real_escape_string(Sync::$connection, $v['meta'])."',
                                        '".mysqli_real_escape_string(Sync::$connection, $v['description'])."',
                                        '".mysqli_real_escape_string(Sync::$connection, $v['logo'])."',
                                        '".mysqli_real_escape_string(Sync::$connection, $v['link'])."'
                                        )";
                            
                        }
                        $imploded = implode(',', $sql);
                        $que = "INSERT INTO thinkfinity_standards_meta (thinkfinity_standardid, title, meta, description, logo, link)"
                            . " VALUES $imploded";
                        if (!mysqli_query(Sync::$connection, $que)) {
                            print ("Error description 2: " . mysqli_error(Sync::$connection));
                        }
                    }
                    
                }
            }
            

            
//            die();
//            echo $dir . "\n";
        }
    }
    private static function getDataMainFile($file){
        require_once 'PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $inputFileName =  $file;
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();

        $i = 2;
        $count = 0;
        $data = array();
        foreach ($sheet->getRowIterator() as $row) {
            $data[$count]['label'] = $objPHPExcel->getActiveSheet()->getCell("A$i")->getValue();//column A
            $data[$count]['description'] = $objPHPExcel->getActiveSheet()->getCell("B$i")->getValue();//column B
            $data[$count]['link'] = $objPHPExcel->getActiveSheet()->getCell("C$i")->getValue();//column C
            $data[$count++]['counter'] = $objPHPExcel->getActiveSheet()->getCell("D$i")->getValue();//column D
            
            $i++;
        }
        return $data;
    }
    private static function getDataSubFile($file){
        require_once 'PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $inputFileName =  $file;
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();

        $i = 2;
        $count = 0;
        $data = array();
        foreach ($sheet->getRowIterator() as $row) {
            $data[$count]['title'] = $objPHPExcel->getActiveSheet()->getCell("A$i")->getValue();//column A
            $data[$count]['meta'] = $objPHPExcel->getActiveSheet()->getCell("B$i")->getValue();//column B
            $data[$count]['description'] = $objPHPExcel->getActiveSheet()->getCell("C$i")->getValue();//column C
            $data[$count]['logo'] = $objPHPExcel->getActiveSheet()->getCell("D$i")->getValue();//column D
            $data[$count++]['link'] = $objPHPExcel->getActiveSheet()->getCell("E$i")->getValue();//column E
            
            $i++;
        }
        return $data;
    }

    public function checkThinkfinity(){
        $limit = 50;
        for($i = 0; $i < 10000; $i++){
            $offset = $i * $limit;
//            $time = time();
            $sql = "SELECT * FROM thinkfinity_standards WHERE counter > 0 LIMIT $limit OFFSET $offset";
            
            
        
            $cursor = mysqli_query(Sync::$connection, $sql);
            if (!($cursor)) {
                print ("Error description 1: " . mysqli_error(Sync::$connection));
                return false;
            }
            while ($thinkfinity_standard = mysqli_fetch_array($cursor, MYSQLI_ASSOC)) {
    //            if($thinkfinity_standard['directory'] != 'AL'){
    //                continue;
    //            }
//                $time = time() - $time;
//                echo "\nTime 1: ".$time;
//                $time = time();
                if($thinkfinity_standard['directory'] == 'AAASS'){
                    $sql = "select * from statements"
                            . " WHERE (standardid = 1 OR standardid = 2)"
                            . " and description LIKE '".mysqli_real_escape_string(Sync::$connection, rtrim($thinkfinity_standard['description'],"."))."%'";
                } elseif( $thinkfinity_standard['directory'] == '21CSF') {
                    $sql = "select * from statements"
                            . " WHERE standardid = 544"
                            . " and description LIKE '".mysqli_real_escape_string(Sync::$connection, rtrim($thinkfinity_standard['description'],"."))."%'";
                }
                else{
                    $sql = "select st.*
                        from statements st
                        inner join standards s on st.standardid = s.standardid
                        inner join states sta on sta.state_name = s.jurisdictioncode
                        where sta.state_abbr = '".mysqli_real_escape_string(Sync::$connection, $thinkfinity_standard['directory'])."'
                        and s.active = 'T'
                        and st.description like '".mysqli_real_escape_string(Sync::$connection, rtrim($thinkfinity_standard['description'],"."))."%';";
                }


    //            echo "\n**************************************************************************************************************\n";
//                 echo $sql."\n\n";
//                 die();
                $cur = mysqli_query(Sync::$connection, $sql);
                if (!($cur)) {
                    print ("Error description 2: " . mysqli_error(Sync::$connection));
                    return false;
                }
                if(mysqli_num_rows($cur) == 0){
//                    $sq = "INSERT INTO thinkfinity_missing (thinkfinity_standardid, query, status, thinkfinity_standard_metaid) VALUES ({$thinkfinity_standard['thinkfinity_standardid']}, '".mysqli_real_escape_string(Sync::$connection, $sql)."', 'missing', {$thinkfinity_standard['thinkfinity_standard_metaid']})";
//                    
//                    if(!mysqli_query(Sync::$connection, $sq)){
//                        print ("Error description missing 2: " . mysqli_error(Sync::$connection));
//                        die();
//                    }
                    echo "Missing\t".$thinkfinity_standard['thinkfinity_standardid']."\n";
                    continue;
                }
                if(mysqli_num_rows($cur) > 1){
                    $grade = null;
                    switch ($thinkfinity_standard['grade']){
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                        case 5:
                        case 6:
                        case 7:
                        case 8:
                        case 9:
                        case 10:
                        case 11:
                        case 12:
                            $grade = 'Grade ' . $thinkfinity_standard['grade'];
                            break;
                        case 'K':
                            $grade =  $thinkfinity_standard['grade'];
                            break;
                        case 'Pre K':
                            $grade =  'Pre-K';
                        default :
                            break;
                    }
                    
                    
                    $sql = "select st.statementid, el.displayname
                        from statement_educationlevels sel
                        inner join statements st on sel.statementid = st.statementid
                        inner join educationlevels el on el.levelid = sel.educationlevelid
                        inner join standards s on st.standardid = s.standardid
                        inner join states sta on sta.state_name = s.jurisdictioncode
                        where sta.state_abbr = '".mysqli_real_escape_string(Sync::$connection, $thinkfinity_standard['directory'])."'
                        and s.active = 'T'
                        and st.description like '".mysqli_real_escape_string(Sync::$connection, rtrim($thinkfinity_standard['description'],"."))."%'
                        and el.displayname = '".mysqli_real_escape_string(Sync::$connection, $grade)."' LIMIT 1";
//                    echo $sql."\n\n";
                    
                    $cur = mysqli_query(Sync::$connection, $sql);
                    if (!($cur)) {
                        print ("Error description 2B: " . mysqli_error(Sync::$connection));
                        return false;
                    }
//                    if(mysqli_num_rows($cur) > 1){
////                        $sq = "INSERT INTO thinkfinity_missing (thinkfinity_standardid, query, status, thinkfinity_standard_metaid) VALUES ({$thinkfinity_standard['thinkfinity_standardid']}, '".mysqli_real_escape_string(Sync::$connection, $sql)."', 'multiple', {$thinkfinity_standard['thinkfinity_standard_metaid']})";
////                        if(!mysqli_query(Sync::$connection, $sq)){
////                            print ("Error description missing 1: " . mysqli_error(Sync::$connection));
////                            die();
////                        }
//                        echo $sql."\n\n";
//                        echo "Multiple\t".$thinkfinity_standard['thinkfinity_standardid']."\n";
//                        continue;
//                    }
                }
                while ($statements = mysqli_fetch_array($cur, MYSQLI_ASSOC)) {
//                    $time = time() - $time;
//                    echo "\nTime 2: ".$time;
//                    $time = time();
                    $sql = "SELECT * FROM thinkfinity_standards_meta WHERE thinkfinity_standardid = {$thinkfinity_standard['thinkfinity_standardid']}";
                    
//                     echo $sql."\n\n";
//                     die();
                    $cur2 = mysqli_query(Sync::$connection, $sql);
                    if (!($cur2)) {
                        print ("Error description 3: " . mysqli_error(Sync::$connection));
                        return false;
                    }
                    while ($thinkfinity_standard_meta = mysqli_fetch_array($cur2, MYSQLI_ASSOC)) {
                        $link = $thinkfinity_standard_meta['link'];
                        
//                        $time = time() - $time;
//                        echo "\nTime 3: ".$time;
//                        $time = time();
                        $sql = "SELECT * FROM resources WHERE externalurl = '".mysqli_real_escape_string(Sync::$connection, $link)."'";
                        // echo $sql."\n\n";
                        $cur3 = mysqli_query(Sync::$connection, $sql);
                        if (!($cur3)) {
                            print ("Error description 4: " . mysqli_error(Sync::$connection));
                            return false;
                        }
                        while ($resource = mysqli_fetch_array($cur3, MYSQLI_ASSOC)) {
                            $statementid = $statements['statementid'];
    //                        echo $thinkfinity_standardid = $thinkfinity_standard['thinkfinity_standardid'];
                            $resourceid = $resource['resourceid'];
//                            die();
//                            $time = time() - $time;
//                            echo "\nTime 4: ". $time;
//                            $time = time();
                            $sql = "select * from resource_statements where resourceid = {$resource['resourceid']} and statementid = {$statementid};";
                            
                            $cur4 = mysqli_query(Sync::$connection, $sql);
                            if (!($cur4)) {
                                print ("Error description 5: " . mysqli_error(Sync::$connection));
                                return false;
                            }
                            if (!mysqli_num_rows($cur4)) {
//                                echo $thinkfinity_standard['thinkfinity_standardid']. "\t\t";
//                                echo $thinkfinity_standard['directory']. "\t\t";
//                                echo "Statement ID\t" . $statementid. "\t\t";
//                                echo "Resourceid\t". $resourceid . "\n";
    //                            die();
//                                $time = time() - $time;
//                                echo "\nTime 5: ".$time;
//                                $time = time();
                                $sql = "INSERT IGNORE INTO thinkfinity_resource_statements VALUES ($resourceid, $statementid, {$thinkfinity_standard['thinkfinity_standardid']}, {$thinkfinity_standard_meta['id']}, 10000)";
                                if(!mysqli_query(Sync::$connection, $sql)){
                                    print ("Error description 5: " . mysqli_error(Sync::$connection));
                                    die();
                                }
                                if(mysqli_affected_rows(Sync::$connection) > 0){
                                    echo $thinkfinity_standard['thinkfinity_standardid']. "\t\t";
                                    echo $thinkfinity_standard['directory']. "\t\t";
                                    echo "Statement ID\t" . $statementid. "\t\t";
                                    echo "Resourceid\t". $resourceid . "\n";
                                }
                            }

                        }
                    }
                }
            }
        }
        
    }
    
    public function checkCloudSearchResources(){
        die();
        $sql = "select *
                from resources
                WHERE resourceid >= 67657 AND resourceid <= 68021";
        
        
        $cursor = mysqli_query(Sync::$connection, $sql);
        if (!($cursor)) {
            print ("Error description: " . mysqli_error(Sync::$connection));
            return false;
        }
        $count = 1;
//        echo "<table border='1'>";
        while ($resource = mysqli_fetch_array($cursor, MYSQLI_ASSOC)) {
            $exurl = AWS_CLOUDSEARCH_END_POINT . "/2013-01-01/search?q.parser=lucene&q.options={fields:%20[%27title^4%27,%20%27description^2.5%27,%27keywords^3%27,%20%27filecontent^1%27,%27content^1%27],defaultOperator:%20%27or%27}&q=(%20id:%22{$resource['resourceid']}%22%20AND%20active:%22T%22%20AND%20type:%22Resource%22%20)&size=10&sort=createdate%20desc&return=title,id";
            
            $handle = curl_init($exurl);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($handle, CURLOPT_USERAGENT, 'Mozilla/' . rand(1, 5) . '.0 (X11; CrOS x86_64 ' . rand(1000, 8000) . '.0.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.' .
                    rand(300, 900) . '.3 Safari/' . rand(100, 800) . '.' . rand(25, 76));
            curl_setopt($handle, CURLOPT_TIMEOUT, 120);

            /* Get the HTML or whatever is linked in $url. */
            $response = curl_exec($handle);

            /* Check for 404 (file not found). */

            if (curl_errno($handle) > 0) {
                print "\nCurl Error\t " . $resource['resourceid'] . "\n";
                continue;
            }
            
            $response_arr = json_decode($response, true);
            
            if(count($response_arr['hits']['hit']) > 0){
//                echo "<tr>";
//                    echo "<td>";
                echo "\n";
                        echo $count++;
                        echo "\t";
//                    echo "</td>";
//                    echo "<td>";
                        echo $resource['resourceid'];
                        echo "\t";
//                    echo "</td>";
//                    echo "<td>";
                    $link = 'http://curriki.org/oer/'.$resource['pageurl'];
                        echo "<a href='$link'>{$resource['title']}</a>";
                        
//                    echo "</td>";
//                echo "</tr>";
            }
        }
//        echo "</table>";
    }
    
    public function getCloudSearchResources($cursor){
        
//        echo "
//            <head>
//              <meta http-equiv='refresh' content='0.1'>
//            </head>";
        
        $exurl = AWS_CLOUDSEARCH_END_POINT . "/2013-01-01/search?q.parser=lucene&q.options={fields:%20[%27title^4%27,%20%27description^2.5%27,%27keywords^3%27,%20%27filecontent^1%27,%27content^1%27],defaultOperator:%20%27or%27}&q=((active:%22T%22)+AND+(((resourcetype:%22collection%22)+AND+NOT+title:%22Favorites%22)+OR+resourcetype:%22resource%22)+AND+NOT+access:%22private%22+AND%20(%22%22))&sort=id%20asc&cursor={$cursor}&size=10000&return=title,id";
        echo "\n**************************************************************************************************************\n";
        echo $exurl."\n\n";
        
        $initial_output = file_get_contents($exurl, false, stream_context_create(array('http' => array('ignore_errors' => true))));
        $initial_output_arr = json_decode($initial_output, true);

        if(count($initial_output_arr['hits']['hit']) > 0){
            if(isset($initial_output_arr['hits']['cursor'])){
                
                $resourceids = array();
                foreach ($initial_output_arr['hits']['hit'] as $hit){
                    $resourceids[] = "(".$hit['fields']['id'].")";
                }
                $sql = "INSERT INTO aws_resources (resourceid) VALUES ".implode(",",$resourceids);
                $cursor = mysqli_query(Sync::$connection, $sql);
                if (!($cursor)) {
                    print ("Error description: " . mysqli_error(Sync::$connection));
                    return false;
                }
                echo "\n**************************************************************************************************************\n";
                echo "\nResourceids = ".implode(",",$resourceids)."\n";
                $cursor = $initial_output_arr['hits']['cursor'];
                echo "<script>window.location = 'http://curriki.org/curriki_scripts/cloudsearch_resources.php?cursor={$cursor}&env=prod'</script>";
//                Sync::getCloudSearchNextResources($cursor);
            }
        }
    }
    
    private static function getCloudSearchNextResources($cursor){
        die();
        $exurl = AWS_CLOUDSEARCH_END_POINT . "/2013-01-01/search?q.parser=lucene&q.options={fields:%20[%27title^4%27,%20%27description^2.5%27,%27keywords^3%27,%20%27filecontent^1%27,%27content^1%27],defaultOperator:%20%27or%27}&q=((active:%22T%22)+AND+(((resourcetype:%22collection%22)+AND+NOT+title:%22Favorites%22)+OR+resourcetype:%22resource%22)+AND+NOT+access:%22private%22+AND%20(%22%22))&sort=createdate%20desc&cursor={$cursor}&size=10000&return=title,id";
        
        echo "\n**************************************************************************************************************\n";
        echo $exurl."\n\n";
                
        $next_output = file_get_contents($exurl, false, stream_context_create(array('http' => array('ignore_errors' => true))));
        $next_output_arr = json_decode($next_output, true);
        
        
        
        if(count($next_output_arr['hits']['hit']) > 0){
            if(isset($next_output_arr['hits']['cursor'])){
                
                foreach ($next_output_arr['hits']['hit'] as $hit){
                    $sql = "INSERT INTO aws_resources (resourceid) VALUES ({$hit['fields']['id']})";
                    $cursor = mysqli_query(Sync::$connection, $sql);
                    if (!($cursor)) {
                        print ("Error description: " . mysqli_error(Sync::$connection));
                        return false;
                    }
                }
                
                echo "\n**************************************************************************************************************\n";
                $cursor = $next_output_arr['hits']['cursor'];
                Sync::getCloudSearchNextResources($cursor);
            }
        }
        
    }
    
    public function getCloudSearchResourcesComparison($limit, $offset){
        $sql = "select *
                from aws_to_be_resources
                LIMIT $limit OFFSET $offset;";
//        $sql = "SELECT * FROM resources LIMIT $limit OFFSET $offset";
        
        $aws_resources = mysqli_query(Sync::$connection, $sql);
        if (!($aws_resources)) {
            print ("Error description 1: " . mysqli_error(Sync::$connection));
            die();
        }
        $resourceids = array();
        while ($aws_resource = mysqli_fetch_array($aws_resources, MYSQLI_ASSOC)) {
            $aws_resource_resourceid = $aws_resource['resourceid'];
            $sql = "SELECT * FROM aws_resources WHERE resourceid = $aws_resource_resourceid";
            $cr = mysqli_query(Sync::$connection, $sql);
            if (!mysqli_num_rows($cr)) {
                $resourceids[] = "(".$aws_resource_resourceid.")";
//                die();
            }
            
        }
        if(count($resourceids) > 0){
            $sql = "INSERT INTO aws_not_uploaded_resources (resourceid) VALUES ".implode(",",$resourceids);
            $cursor = mysqli_query(Sync::$connection, $sql);
            if (!($cursor)) {
                print ("Error description 2: " . mysqli_error(Sync::$connection));
                die();
            }
            echo implode(",",$resourceids);
        }
        $offset = $limit + $offset;
        echo "<script>window.location = 'http://curriki.org/curriki_scripts/cloudsearch_resources_comparison.php?env=prod&limit=$limit&offset=$offset'</script>";
    }
    
    public function checkAvatars($limit, $offset){
        die();
        $sql = "select * from s3_updated_avatars 
            WHERE (uniqueavatarfile  LIKE '%.jpg%'
            OR uniqueavatarfile  LIKE '%.jpeg%'
            OR uniqueavatarfile  LIKE '%.png%'
            OR uniqueavatarfile  LIKE '%.gif%')
            AND reprocess = 'T' LIMIT $limit OFFSET $offset";
        $users = mysqli_query(Sync::$connection, $sql);
        if (!($users)) {
            print ("Error description 1: " . mysqli_error(Sync::$connection));
            die();
        }
        $uqavatar = array();
        while ($user = mysqli_fetch_array($users, MYSQLI_ASSOC)) {
            
            $uniqueavatarfile = $user['uniqueavatarfile'];
//            die();
            $url = 'https://' . DBI_AWS_S3_CDN_URL . "/avatars/$uniqueavatarfile";
            
            $size = Sync::getFileSize($url);
            if($size > 100000){
                $file = __DIR__."/../tmp/".$uniqueavatarfile;
                
                file_put_contents($file, fopen($url, 'r'));
//echo mime_content_type($file);
//                die();
                $orientation = 0;
                $image_type = IMAGETYPE_JPEG;
                if(mime_content_type($file) == 'image/jpeg'){
                    $exif = exif_read_data($file);
//                    echo "<pre>";
//                    print_r($exif);
//                    die();
                    $image_type = IMAGETYPE_JPEG;
                    if(isset($exif['Orientation'])){
                        $orientation = $exif['Orientation'];
                    } else {
                        $orientation = 0;
                    }
                    
                } elseif(mime_content_type($file) == 'image/png'){
                    $image_type = IMAGETYPE_PNG;
                } elseif(mime_content_type($file) == 'image/gif'){
                    $image_type = IMAGETYPE_GIF;
                } else{
                    $query = "UPDATE s3_updated_avatars SET processed = 'T', reprocess = 'F' WHERE id = {$user['id']}";
                    if(!mysqli_query(Sync::$connection, $query)){
                        print ("Error description 2: " . mysqli_error(Sync::$connection));
                        die();
                    } else{
                        
                    }

                    continue;
                }
                
                
                
                Sync::resizeImage($file, 200, $orientation, $image_type);
                $response = array();
                Sync::uploadFileS3($response, 'currikicdn', "avatars/$uniqueavatarfile", $file);
                @unlink($file);
                
                
                
                
            }
            $query = "UPDATE s3_updated_avatars SET processed = 'T', reprocess = 'F' WHERE id = {$user['id']}";
            if(!mysqli_query(Sync::$connection, $query)){
                print ("Error description 2: " . mysqli_error(Sync::$connection));
                die();
            } else{
                $uqavatar[] = $user['uniqueavatarfile'];
            }
            

            
        }

        $offset = $limit + $offset;
        if(count($uqavatar) > 0){
            echo implode(",", $uqavatar);
        } else {
            echo "Nothing updated";
        }
        echo "<script>window.location = 'http://curriki.org/curriki_scripts/check_avatars.php?env=prod&limit=$limit&testx=".  uniqid()."'</script>";
    }
    
    public static function getFileSize($url){
        die();
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);

        $data = curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

        curl_close($ch);
        return $size;
    }
    
    function resizeImage($target_file, $dimension, $orientation = 0, $image_type = IMAGETYPE_JPEG) {
        die();
        require_once __DIR__.'/../inc/SimpleImage.php';
        $image = new SimpleImage();
        $image->load($target_file);
        $image->resizeToWidth($dimension, $orientation);
        $image->save($target_file, $image_type);
        return $target_file; //return name of saved file in case you want to store it in you database or show confirmation message to user
    }
    
    public static function uploadFileS3(&$response, $bucket, $key, $targetFile) {
        global $vars;
//        die();
        if (!isset($vars['s3_client']))
            $vars['s3_client'] = $vars['aws']->get('S3');
        
        if (file_exists($targetFile)) {
            $upload = $vars['s3_client']->putObject(array(
                        'ACL' => 'public-read',
                        'Bucket' => $bucket,
                        'Key' => $key,
                        'CacheControl' => 'max-age=172800',
                        'Body' => fopen($targetFile, 'r.')
                    ))->toArray();

            if ($upload['ObjectURL']) {
                $response['error'] = '';
                $response['status'] = '1';
                $response['url'] = $upload['ObjectURL'];

                
            } else {
                $response['status'] = '0';
                $response['error'] = 'Error: File not uploaded correctly to bucket.';
            }
        } else {
            $response['status'] = '0';
            $response['error'] = 'Error: File does not exist.';
        }
    }
    
    public static function copyS3Image($sourceBucket, $sourceKeyname, $targetBucket, $targetKeyname){
        die();
        global $vars;
//        die();
        if (!isset($vars['s3_client']))
            $vars['s3_client'] = $vars['aws']->get('S3');

        try {
            
            $upload = $vars['s3_client']->copyObject(array(
                    'ACL' => 'public-read',
                    'Bucket' => $targetBucket,
                    'Key' => $targetKeyname,
                    'CopySource' => "{$sourceBucket}/{$sourceKeyname}"
                ))->toArray();
        } catch (Exception $ex) {
            echo $ex->getMessage();
            die();
        }
    }
    
    public function populateS3UpdatedAvatars($limit, $offset){
        die();
        $sql = "select * from s3_updated_avatars WHERE reprocess = 'T' LIMIT $limit OFFSET $offset";
        
        $users = mysqli_query(Sync::$connection, $sql);
        if (!($users)) {
            print ("Error description 1: " . mysqli_error(Sync::$connection));
            die();
        }
        $sql = array();
        while ($user = mysqli_fetch_array($users, MYSQLI_ASSOC)) {
            $sourceBucket = DBI_AWS_S3_BUCKET;
            $sourceKeyname = 'avatars/'.$user['uniqueavatarfile'];
            $targetBucket = DBI_AWS_S3_BUCKET;
            $file_arr = explode(".", $user['uniqueavatarfile']);
            if(count($file_arr) == 2){
                $filename = $file_arr[0];
                $extension = $file_arr[1];
                $targetKeyname = "avatars/$filename"."_original.".$extension;
            } else {
                continue;
            }
            Sync::copyS3Image($sourceBucket, $sourceKeyname, $targetBucket, $targetKeyname);
            $query = "UPDATE s3_updated_avatars SET processed = 'T', reprocess = 'F' WHERE id = {$user['id']}";
            if(!mysqli_query(Sync::$connection, $query)){
                print ("Error description 2: " . mysqli_error(Sync::$connection));
                die();
            } else{
                $targetKeyname_arr[] = $targetKeyname;
            }
        }
        $offset = $limit + $offset;
        
        echo implode(",", $targetKeyname_arr);
        echo "<script>window.location = 'http://curriki.org/curriki_scripts/populate_s3_updated_avatars.php?env=prod&limit=$limit&testx=".  uniqid()."'</script>";
        
        
//        if(!empty($sql)){
//            $imploded = implode(',', $sql);
//            $query = "INSERT INTO s3_updated_avatars (userid, uniqueavatarfile, processed, reprocess) VALUES $imploded";
//            
//            if(!mysqli_query(Sync::$connection, $query)){
//                print ("Error description 2: " . mysqli_error(Sync::$connection));
//                die();
//            } else {
//                echo $imploded;
//            }
//            $offset = $limit + $offset;
//            echo "<script>window.location = 'http://curriki.org/curriki_scripts/populate_s3_updated_avatars.php?env=prod&limit=$limit&offset=$offset'</script>";
//        } else {
//            echo "No data";
//            die();
//        }
    }
    public function getInsertResourceFilesQuery() {
        $sql = "SELECT * FROM resources WHERE source LIKE 'thinkfinity.org'";
        $cursor = mysqli_query(Sync::$connection2, $sql);
        while($resource = mysqli_fetch_array($cursor, MYSQLI_ASSOC)){
            

            $resourceid = $resource['resourceid'];
            $licenseid = $resource['licenseid'];
            $uploaddate = $resource['uploaddate'];
            $sequence = $resource['sequence'];
            $uniquename = $resource['uniquename'];
            $ext = $resource['ext'];
            $active = $resource['active'];
            $tempactive = $resource['tempactive'];
            $folder = $resource['folder'];
            $s3path = $resource['s3path'];
            $SDFstatus = $resource['SDFstatus'];
            $transcoded = $resource['transcoded'];
            $lodestar = $resource['lodestar'];


            $sql2 = '(
                    "' . mysqli_real_escape_string(Sync::$connection2, $live_rd) . '",
                    "' . mysqli_real_escape_string(Sync::$connection2, $licenseid) . '",
                    "' . mysqli_real_escape_string(Sync::$connection2, $uploaddate) . '",
                    "' . mysqli_real_escape_string(Sync::$connection2, $sequence) . '",
                    "' . mysqli_real_escape_string(Sync::$connection2, $uniquename) . '",
                    "' . mysqli_real_escape_string(Sync::$connection2, $ext) . '",
                    "' . mysqli_real_escape_string(Sync::$connection2, $active) . '",
                    "' . mysqli_real_escape_string(Sync::$connection2, $tempactive) . '",
                    "' . mysqli_real_escape_string(Sync::$connection2, $folder) . '",
                    "' . mysqli_real_escape_string(Sync::$connection2, $s3path) . '",
                    "' . mysqli_real_escape_string(Sync::$connection2, $SDFstatus) . '",
                    "' . mysqli_real_escape_string(Sync::$connection2, $transcoded) . '",
                    "' . mysqli_real_escape_string(Sync::$connection2, $lodestar) . '")';

            $future_sql = "\nINSERT INTO resourcefiles (
                resourceid,
                filename,
                uploaddate,
                sequence,
                uniquename,
                ext,
                active,
                tempactive,
                folder,
                s3path,
                SDFstatus,
                transcoded,
                lodestar
                )
                VALUES $sql2
                ";

            echo $future_sql;
            echo "\n";
            die();
        }
    }
    public function insertTsMatch(){
        
        $limit = 100000;
        $finish = false;
        for($i = 0; $i < 68; $i++){
            $offset = $i * $limit;
            $sql = 'select distinct concat(substring(ts.description, 1, length(ts.description) - 1), \'%\') as description, ts.jurisdictioncode, tsm.id as tsmid from  thinkfinity_standards ts inner join tsmeta tsm on ts.thinkfinity_standardid = tsm.thinkfinity_standardid where length(ts.description) < 20 limit '.$limit.' OFFSET '.$offset.'; ';
            $cursor = mysqli_query(Sync::$connection, $sql);
            if (!($cursor)) {
                print ("Error description 1: " . mysqli_error(Sync::$connection));
                return false;
            }
            if(mysqli_num_rows($cursor) == 0){
                $finish = true;
            }

            while ($resource = mysqli_fetch_array($cursor, MYSQLI_ASSOC)) {
                
                if($resource['description'] == '%'){
                    print ("\nSkipping \t ".$resource['tsmid']);
                    continue;
                }
                $sql = 'insert into tsmatch (statementid, standardid, description, subject, title, tsdescription, jurisdictioncode, tsmid)
                        select s.statementid, s.standardid, s.description, s.subject, st.title, "'.mysqli_real_escape_string(Sync::$connection, $resource['description']).'", "'.mysqli_real_escape_string(Sync::$connection, $resource['jurisdictioncode']).'", '.mysqli_real_escape_string(Sync::$connection, $resource['tsmid']).'
                        from statements s
                        inner join standards st on s.standardid = st.standardid
                        where st.jurisdictioncode = "'.mysqli_real_escape_string(Sync::$connection, $resource['jurisdictioncode']).'"
                        and s.description like "'.mysqli_real_escape_string(Sync::$connection, $resource['description']) .'"; ';
                
                if(!mysqli_query(Sync::$connection, $sql)){
                    print ("Error description 3: " . mysqli_error(Sync::$connection));
                } else {
                    print ("\nInserted ".$resource['tsmid']);
                }
            }
            if($finish){
                print ("\nFinished");
                break;
            }
        }
        
    }
    
    public function tsmatchResourceStatements (){
        $limit = 100000;
        $finish = false;
        for($i = 0; $i < 68; $i++){
            $offset = $i * $limit;
            $sql = 'SELECT statementid, tsmid, link, standardid FROM tsmatch INNER JOIN thinkfinity_standards_meta ON tsmatch.tsmid = thinkfinity_standards_meta.id  limit '.$limit.' OFFSET '.$offset.'; ';
            $cursor = mysqli_query(Sync::$connection, $sql);
            if (!($cursor)) {
                print ("Error description 1: " . mysqli_error(Sync::$connection));
                return false;
            }
            if(mysqli_num_rows($cursor) == 0){
                $finish = true;
            }

            while ($tsdata = mysqli_fetch_array($cursor, MYSQLI_ASSOC)) {
                $sql = "SELECT * FROM resources WHERE externalurl = '".mysqli_real_escape_string(Sync::$connection, $tsdata['link'])."' and source = 'thinkfinity.org'";
                $cursor1 = mysqli_query(Sync::$connection, $sql);
                if (!($cursor1)) {
                    print ("Error description 1: " . mysqli_error(Sync::$connection));
                    return false;
                }
                while ($resource = mysqli_fetch_array($cursor1, MYSQLI_ASSOC)) {
                    $sql = "INSERT IGNORE INTO tsmatch_resource_statements VALUES ({$resource['resourceid']}, {$tsdata['statementid']}, {$tsdata['standardid']}, {$tsdata['tsmid']}, 10000)";
                    if(!mysqli_query(Sync::$connection, $sql)){
                        print ("Error description 5: " . mysqli_error(Sync::$connection));
                        die();
                    }
                    if(mysqli_affected_rows(Sync::$connection) > 0){
                        echo "Statement ID\t" . $tsdata['statementid']. "\t\t";
                        echo "Resourceid\t". $resource['resourceid'] . "\n";
                    }
                }
            }
            if($finish){
                print ("\nFinished");
                break;
            }
        }
        
    }
    public function ASNJurisdictionStandards(){
        
        
        $create_table_sql = "CREATE TABLE IF NOT EXISTS `ASNJurisdictionStandard_links` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `jurisdiction_organization` varchar(255) NOT NULL,
            `description` varchar(255) NOT NULL,
            `jurisdiction_class` varchar(255) NOT NULL,
            `link` varchar(255) NOT NULL,
            `date_processed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
           ) ENGINE=InnoDB DEFAULT CHARSET=latin1
           ";
        
        if(!mysqli_query(Sync::$connection, $create_table_sql)){
            print ("\nError description create table: " . mysqli_error(Sync::$connection));
            die();
        }
        
        
        $urls = [
            'http://asn.jesandco.org/resources/ASNJurisdiction',
            'http://asn.jesandco.org/resources/ASNJurisdiction?page=1',
            'http://asn.jesandco.org/resources/ASNJurisdiction?page=2',
            'http://asn.jesandco.org/resources/ASNJurisdiction?page=3',
            'http://asn.jesandco.org/resources/ASNJurisdiction?page=4',
            'http://asn.jesandco.org/resources/ASNJurisdiction?page=5'
        ];
        foreach ($urls as $url){
            $sql = array();
            $standards = Sync::getASNStandardLinks($url);
            foreach($standards as $standard){
                $sql[] = "(
                            '{$standard['jurisdiction_organization']}',
                            '{$standard['description']}',
                            '{$standard['jurisdiction_class']}',
                            '{$standard['link']}'
                          )"; 
            }
            
            $imploded = implode(',', $sql);
            
            $query = "INSERT INTO ASNJurisdictionStandard_links (jurisdiction_organization, description, jurisdiction_class, link) VALUES $imploded ";
           
            if(!mysqli_query(Sync::$connection, $query)){
                print ("\nError description 0: " . mysqli_error(Sync::$connection));
                die();
            } else{
                echo "\nInserted\n";
            }
        }
    }
    public function upsertStandardsStatements(){
//        $sql = "SELECT * FROM ASNJurisdiction_statement_links where id = 3404 LIMIT " . Conn::$limit . " OFFSET " . Sync::$skip; // no identifier
//        $sql = "SELECT * FROM ASNJurisdiction_statement_links LIMIT " . Conn::$limit . " OFFSET " . Sync::$skip; 
        
//        $sql = "SELECT * FROM ASNJurisdiction_statement_links 
//                INNER JOIN ASNJurisdictionStandard_links
//                ON ASNJurisdiction_statement_links.ASNJurisdiction_standardid = ASNJurisdictionStandard_links.id LIMIT " . Conn::$limit . " OFFSET " . Sync::$skip;
        
        $create_table_sql = "CREATE TABLE IF NOT EXISTS `statements_duplicate_tmp` (
                            `statementid` int(11) NOT NULL,
                            `standardid` int(11) NOT NULL,
                            `parentid` int(11) DEFAULT NULL,
                            `resourceidentifier` varchar(45) DEFAULT NULL COMMENT 'asn:Statement',
                            `subject` varchar(45) DEFAULT NULL COMMENT 'dcterms:subject',
                            `description` text COMMENT 'dcterms:description',
                            `language` varchar(45) DEFAULT NULL COMMENT 'dcterms:language',
                            `comment` text COMMENT 'asn:comment',
                            `notation` varchar(45) DEFAULT NULL COMMENT 'asn:statementNotation',
                            `guid` varchar(100) DEFAULT NULL COMMENT 'skos:exactMatch',
                            `label` varchar(45) DEFAULT NULL COMMENT 'asn:statementLabel (Domain, Cluster, Standard, etc.)',
                            `active` char(1) NOT NULL DEFAULT 'T',
                            `alternatenotation` varchar(45) DEFAULT NULL,
                            `displayseqno` int(11) DEFAULT NULL,
                            PRIMARY KEY (`statementid`)
                           ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        
        if(!mysqli_query(Sync::$connection, $create_table_sql)){
            print ("\nError description create table: " . mysqli_error(Sync::$connection));
            die();
        }
        
        
        
        
        
        $create_table_sql = "CREATE TABLE IF NOT EXISTS `".Sync::$statements_educationleveles_insert_table."` (
                            `statementid` int(11) NOT NULL,
                            `educationlevelid` int(11) NOT NULL,
                            PRIMARY KEY (`statementid`,`educationlevelid`)
                           ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        
        if(!mysqli_query(Sync::$connection, $create_table_sql)){
            print ("\nError description create table: " . mysqli_error(Sync::$connection));
            die();
        }
        
        
        
        $create_table_sql = "CREATE TABLE IF NOT EXISTS `".Sync::$standards_educationleveles_insert_table."` (
                                `standardid` int(11) NOT NULL,
                                `educationlevelid` int(11) NOT NULL,
                                PRIMARY KEY (`standardid`,`educationlevelid`)
                               ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        
        if(!mysqli_query(Sync::$connection, $create_table_sql)){
            print ("\nError description create table: " . mysqli_error(Sync::$connection));
            die();
        }
        
        $create_table_sql = "CREATE TABLE IF NOT EXISTS `".Sync::$statements_insert_table."` (
                            `statementid` int(11) NOT NULL AUTO_INCREMENT,
                            `standardid` int(11) NOT NULL,
                            `parentid` int(11) DEFAULT NULL,
                            `resourceidentifier` varchar(45) DEFAULT NULL COMMENT 'asn:Statement',
                            `subject` varchar(45) DEFAULT NULL COMMENT 'dcterms:subject',
                            `description` text COMMENT 'dcterms:description',
                            `language` varchar(45) DEFAULT NULL COMMENT 'dcterms:language',
                            `comment` text COMMENT 'asn:comment',
                            `notation` varchar(45) DEFAULT NULL COMMENT 'asn:statementNotation',
                            `guid` varchar(100) DEFAULT NULL COMMENT 'skos:exactMatch',
                            `label` varchar(45) DEFAULT NULL COMMENT 'asn:statementLabel (Domain, Cluster, Standard, etc.)',
                            `active` char(1) NOT NULL DEFAULT 'T',
                            `alternatenotation` varchar(45) DEFAULT NULL,
                            `displayseqno` int(11) DEFAULT NULL,
                            PRIMARY KEY (`statementid`)
                           ) ENGINE=InnoDB AUTO_INCREMENT=22510504 DEFAULT CHARSET=latin1";
        
        if(!mysqli_query(Sync::$connection, $create_table_sql)){
            print ("\nError description create table: " . mysqli_error(Sync::$connection));
            die();
        }
        
        
        
        
        $create_table_sql = "CREATE TABLE IF NOT EXISTS `".Sync::$standards_insert_table."` (
                            `standardid` int(11) NOT NULL AUTO_INCREMENT,
                            `title` varchar(100) DEFAULT NULL COMMENT 'dc:title',
                            `description` text COMMENT 'dcterms:description',
                            `source` varchar(255) DEFAULT NULL COMMENT 'dcterms:source',
                            `publicationstatus` varchar(45) DEFAULT NULL COMMENT 'asn:publicationstatus - ''Published''',
                            `validdate` int(11) DEFAULT NULL COMMENT 'dcterms:valid - 2010',
                            `language` varchar(45) DEFAULT NULL COMMENT 'dcterms:language - eng',
                            `subject` varchar(100) DEFAULT NULL COMMENT 'dcterms:subject',
                            `resourceidentifier` varchar(50) DEFAULT NULL COMMENT 'asn:StandardDocument',
                            `active` char(1) DEFAULT 'T',
                            `url` varchar(255) DEFAULT NULL COMMENT 'Url of where xml file is located',
                            `lastprocessdate` datetime DEFAULT NULL,
                            `filename` varchar(500) DEFAULT NULL,
                            `jurisdictioncode` varchar(100) DEFAULT NULL,
                            `publisher` varchar(20) DEFAULT NULL,
                            PRIMARY KEY (`standardid`)
                           ) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
        
        if(!mysqli_query(Sync::$connection, $create_table_sql)){
            print ("\nError description create table: " . mysqli_error(Sync::$connection));
            die();
        }
        
        
        
        $sql = "DELETE FROM ".Sync::$statements_educationleveles_insert_table.";";
        
        
        if(!mysqli_query(Sync::$connection, $sql)){
            print ("\nError description: " . mysqli_error(Sync::$connection));
            die();
        }
        
        
        
        $sql = "DELETE FROM ".Sync::$standards_educationleveles_insert_table.";";
        
        
        if(!mysqli_query(Sync::$connection, $sql)){
            print ("\nError description: " . mysqli_error(Sync::$connection));
            die();
        }
        
        
        $sql = "DELETE FROM ".Sync::$standards_insert_table.";";
        
        
        if(!mysqli_query(Sync::$connection, $sql)){
            print ("\nError description: " . mysqli_error(Sync::$connection));
            die();
        }
        
        
        $sql = "DELETE FROM ".Sync::$statements_insert_table.";";
        
        
        if(!mysqli_query(Sync::$connection, $sql)){
            print ("\nError description: " . mysqli_error(Sync::$connection));
            die();
        }
        
        
//        $sql = "INSERT INTO `".Sync::$standards_insert_table."` SELECT * FROM standards;";
//        
//        
//        if(!mysqli_query(Sync::$connection, $sql)){
//            print ("\nError description: " . mysqli_error(Sync::$connection));
//            die();
//        }
//        
//        $sql = "INSERT INTO `".Sync::$statements_insert_table."` SELECT * FROM statements;";
//        
//        
//        if(!mysqli_query(Sync::$connection, $sql)){
//            print ("\nError description: " . mysqli_error(Sync::$connection));
//            die();
//        }
//        
//        
//        $sql = "INSERT INTO `".Sync::$standards_educationleveles_insert_table."` SELECT * FROM standard_educationlevels;";
//        
//        
//        if(!mysqli_query(Sync::$connection, $sql)){
//            print ("\nError description: " . mysqli_error(Sync::$connection));
//            die();
//        }
//        $sql = "INSERT INTO `".Sync::$statements_educationleveles_insert_table."` SELECT * FROM statement_educationlevels;";
//        
//        
//        if(!mysqli_query(Sync::$connection, $sql)){
//            print ("\nError description: " . mysqli_error(Sync::$connection));
//            die();
//        }
        
        
        
        
        
        
        
        
//        $sql = "SELECT * FROM ASNJurisdiction_statement_links 
//        INNER JOIN ASNJurisdictionStandard_links
//        ON ASNJurisdiction_statement_links.ASNJurisdiction_standardid = ASNJurisdictionStandard_links.id LIMIT " . Conn::$limit . " OFFSET " . Sync::$skip;
        
        $sql = "SELECT * FROM ASNJurisdiction_statement_links 
        INNER JOIN ASNJurisdictionStandard_links
        ON ASNJurisdiction_statement_links.ASNJurisdiction_standardid = ASNJurisdictionStandard_links.id WHERE ASNJurisdiction_statement_links.id = 1547 LIMIT ". Conn::$limit . " OFFSET " . Sync::$skip;
        

        
//        $sql = "SELECT * FROM ASNJurisdiction_statement_links 
//                INNER JOIN ASNJurisdictionStandard_links
//                ON ASNJurisdiction_statement_links.ASNJurisdiction_standardid = ASNJurisdictionStandard_links.id";
        if(!($result = mysqli_query(Sync::$connection, $sql))){
            print ("\nError description 0: " . mysqli_error(Sync::$connection));
            die();
        }
        $c = 0;
        while ($asn_statements = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
//            echo "\n******************************************************************************************\n";
//            echo $asn_statements['xml_link'];
//            die();
//            echo "\n";
            
//            if($c >= 718){
                Sync::scrapeASNJurisdiction($asn_statements, $c);
//            }
            $c++;
            
            
//            echo "\n";
//            echo "\n******************************************************************************************\n";
        }
//        Sync::scrapeASNJurisdiction();
    }
    
    
    private static function scrapeASNJurisdiction($asn_statements, $c){
        $url = $asn_statements['xml_link'];
        
        
        echo "\n******************************************************************************************\n";
        echo "\n". Sync::$skip_original." - ".$c."\t".$url. "\n";
        $standard_statements_data = array();
        
        if($asn_statements['publicationStatus'] != 'Published'){
            
            $standard_res_identifier = end(@explode("/", rtrim($url, "_full.xml")));
            
            $sql = "SELECT publicationstatus FROM ".Sync::$standards_table." WHERE resourceidentifier = '". mysqli_real_escape_string(Sync::$connection, $standard_res_identifier)."'";
            if(!$cursor = mysqli_query(Sync::$connection, $sql)){
                print "Error description select publicationstatus: " . mysqli_error(Sync::$connection);
                die();
            }
            if(mysqli_num_rows($cursor) > 0){
                $result = mysqli_fetch_array($cursor, MYSQLI_ASSOC);
                
                
                
                
                $sql = "UPDATE ".Sync::$standards_table." SET publicationstatus = '".mysqli_real_escape_string(Sync::$connection, $asn_statements['publicationStatus'])."' WHERE resourceidentifier = '".mysqli_real_escape_string(Sync::$connection, $standard_res_identifier)."'";
                if(!mysqli_query(Sync::$connection, $sql)){
                    print "Error description Update publicationstatus: " . mysqli_error(Sync::$connection);
                    die();
                }
                echo "\nUpdated publication status $standard_res_identifier\n";
            }
            
            return false;
            
        }
        
        // to be put when fetching online
        $standard_statement_xml = Sync::fetchXMLContent($url);
          
         
        /*
        // to be removed when fetching live
        $standard_statement_xml = $asn_statements['xml_content'];
        if (!$standard_statement_xml) {
            echo "\nno xml found\n";
            return false;
        }
         * 
         */
        
        

        
        echo "\nDone Fetching\n";
        $standard_statements_data = Sync::getStandardStatementsData($standard_statement_xml);
        
//        unset($standard_statements_data['statements']);
//        print_r($standard_statements_data);
//        die();
        $standard_statements_data['url'] = $url;
        
        $standard_statements_data['jurisdictioncode'] = $asn_statements['jurisdiction_organization'];
        $standard_statements_data['publisher'] = 'ASN';
//        unset($standard_statements_data['statements']);
//        print_r($standard_statements_data);
//        die();
        if(!isset($standard_statements_data['resource_identifier'])){
            echo "\nResource Identifier not present.. Skipping\n";
            return false;
        }
        if($standard_statements_data['resource_identifier'] == ''){
            echo "\nResource Identifier not present.. Skipping\n";
            return false;
        }
        
        $standard_data = Sync::getStandardData($standard_statements_data['resource_identifier']);
        
        if(count($standard_data) == 0){ //insert standard
            
            
            
            $last_standard_id = Sync::insertStandard($standard_statements_data);
            
            if($last_statementid = Sync::insertParentStatement($last_standard_id)){
                foreach ($standard_statements_data['children'] as $child){
                    Sync::$parentids['parentid'][$child] = $last_statementid;
                }
                
            }
            
            
            
            
            $sql = "SELECT * FROM ".Sync::$standards_table." WHERE resourceidentifier='".mysqli_real_escape_string(Sync::$connection, $standard_statements_data['resource_identifier'])."'";
            echo "\nNot present\t{$standard_statements_data['title']}\t".$sql."\n";
            print "\nInserted standard Id\t".$last_standard_id."\n";
        }
        elseif($standard_data['validdate'] == $standard_statements_data['valid']){
            $last_standard_id = $standard_data['standardid'];
            echo "\nvaliddate is same.. No update required\nChecking standard_educationlevels\n";
        } else{
            $sql = "SELECT * FROM ".Sync::$standards_table." WHERE resourceidentifier='".mysqli_real_escape_string(Sync::$connection, $standard_statements_data['resource_identifier'])."'";
            echo "\nOne only\t".$sql."\n";
            
            $last_standard_id = Sync::updateStandard($standard_statements_data);
            echo "Updated standard \t" .$last_standard_id;
        }
        $standardeducationlevelids = array();
        if(isset($standard_statements_data['educationlevels'])){
            foreach($standard_statements_data['educationlevels'] as $educationlevels){
                $standardeducationlevelids[] = "(".$last_standard_id."," . Sync::getEducationLevelid($educationlevels).")";
            }
        }
        if(count($standardeducationlevelids) > 0){
            if(Sync::insertStandardEducationLevels($standardeducationlevelids)){
                echo "\nInserted StandardEducationLevelids\n";
            }
        }
        
        
        $statementeducationlevelids = array();
        if(isset($standard_statements_data['statements'])){
            $statement_exists = Sync::checkStatementsExists($standard_statements_data['statement_resource_identifiers']);
            if(!$statement_exists){
                foreach($standard_statements_data['statements'] as $statement){
                    $parentid = null;
                    if(isset(Sync::$parentids['parentid'][$statement['resource_identifier']])){
                        $parentid = Sync::$parentids['parentid'][$statement['resource_identifier']];
                    }
                    if($st_inserted = Sync::insertStatement($last_standard_id, $statement, $parentid)){
                        echo "\nInserted statement {$st_inserted}\n";
                        echo "\n";
                    }
                    if(isset($statement['children'])){
                        foreach ($statement['children'] as $child){
                            Sync::$parentids['parentid'][$child] = $st_inserted;
                        } 
                    }
                    if(isset($statement['educationlevels'])){
                        foreach($statement['educationlevels'] as $educationlevels){
                            $statementeducationlevelids[] = "(".$st_inserted."," . Sync::getEducationLevelid($educationlevels).")";
                        }
                    }
                    if(count($statementeducationlevelids) > 0) {
                        if(Sync::insertStatementEducationLevels($statementeducationlevelids)){
                            echo "\nInserted StatementEducationLevelid\n";
                        }
                    }
                    
                }
                
                
//                if($st_inserted = Sync::insertStatements($last_standard_id, $standard_statements_data['statements'])){
//                    echo "\nInserted statements\n";
//                    echo "\n";
//                    
//                    
//                    $first = $st_inserted['first'];
//                    foreach($standard_statements_data['statements'] as $statement){
//                        if(isset($statement['educationlevels'])){
//                            
//                            $first++;
//                            foreach($statement['educationlevels'] as $educationlevels){
//                                $statementeducationlevelids[] = "(".$first."," . Sync::getEducationLevelid($educationlevels).")";
//                            }
//                        }
//
//
//                    }
//                   
//
//                    if(count($statementeducationlevelids) > 0) {
//                        if(Sync::insertStatementEducationLevels($statementeducationlevelids)){
//                            echo "\nInserted StatementEducationLevelid\n";
//                        }
//                    }
//                }
            } else{
                //statements exist
                $sql = "DELETE FROM statements_duplicate_tmp";
                mysqli_query(Sync::$connection, $sql);
                if(!mysqli_query(Sync::$connection, $sql)){
                    echo mysqli_error(Sync::$connection);
                    die();
                }
            
                $r_identifier = array();
                
                foreach ($standard_statements_data['statements'] as $statement){
                    
                    $r_identifier[] = "'".mysqli_real_escape_string(Sync::$connection, $statement['resource_identifier'])."'";
                }
                $imploded = implode(", ", $r_identifier);
                $sql = "INSERT INTO statements_duplicate_tmp SELECT * FROM ".Sync::$statements_table." WHERE resourceidentifier IN ( $imploded )";
                
                if(!mysqli_query(Sync::$connection, $sql)){
                    print "Error description: " . mysqli_error(Sync::$connection);
                    die();
                }
                foreach($standard_statements_data['statements'] as $statement){
                    $statement_exists = Sync::checkStatementExists($statement['resource_identifier'], 'statements_duplicate_tmp');
                    if($statement_exists){
                        $inserted_statement = $statement_exists['statementid'];
                        echo "\nStatement Exists\t{$statement['resource_identifier']}\nChecking Educationlevels\t";
                    } else {
                        $inserted_statement = Sync::insertStatement($last_standard_id, $statement);
                    }
                    if(isset($statement['educationlevels'])){
                        foreach($statement['educationlevels'] as $educationlevels){
                            $statementeducationlevelids[] = "(".$inserted_statement."," . Sync::getEducationLevelid($educationlevels).")";
                        }
                        if(count($standardeducationlevelids) > 0){
                            if(Sync::insertStatementEducationLevels($statementeducationlevelids)){
                                echo "\nInserted StatementEducationLevelids \n";
                            }
                        }
                    }
                }
                
                
                /*
                foreach($standard_statements_data['statements'] as $statement){
                    $statement_exists = Sync::checkStatementExists($statement['resource_identifier'], Sync::$statements_table);
                    if($statement_exists){
                        //update
                        // use this query
                        //INSERT INTO `item`(`item_name`, `items_in_stock`, `new_items_count`) VALUES ('abc','50','100'), ('cde','50','100')
                        //ON DUPLICATE KEY UPDATE item_name = VALUES(item_name);
                        echo "\nAlready Present...Skipping {$statement['resource_identifier']}\n";
                        continue;
                        
                        $last_inserted_statement = Sync::updateStatement($last_standard_id, $statement);
                        echo "Updated\t";
                        echo "$last_inserted_statement\t{$statement['resource_identifier']}";
                        echo "\n";
                    } else{
                        //insert
                        
                        $last_inserted_statement = Sync::insertStatement($last_standard_id, $statement);
                        
                        echo "\nInserted Satement\tid\t$last_inserted_statement\tidentifier\t{$statement['resource_identifier']}\n";
                        
                    }
                }
                */
            }
            
            /*
            $insert_statements = array();
            
            foreach($standard_statements_data['statements'] as $statement){
                $statement_exists = Sync::checkStatementExists($statement['resource_identifier']);
                if($statement_exists){
                    //update

                    echo "\nStatement\t";
                    $last_inserted_statement = Sync::updateStatement($last_standard_id, $statement);
                    echo "Updated\t";
                    echo "$last_inserted_statement\t{$statement['resource_identifier']}";
                    echo "\n";
                } else{
                    //insert
                    echo "\nStatement\t";
                    $insert_statements[] = $statement;
                }
            }
             * 
             */
        }
        return 1;
    }
    private static function insertParentStatement($standardid){
        $sql = "INSERT INTO ".Sync::$statements_insert_table." (standardid, description) VALUES($standardid, 'Parent')";
        
        if(!mysqli_query(Sync::$connection, $sql)){
            print "Error description: " . mysqli_error(Sync::$connection);
            die();
        }
        return Sync::$connection->insert_id;
    }
    private static function insertStandardEducationLevels($educationlevelids){
        $inserted = false;
        $sql = "INSERT IGNORE INTO ".Sync::$standards_educationleveles_insert_table." VALUES 
            ".implode(",", $educationlevelids)."
            ";
        
        if(!mysqli_query(Sync::$connection, $sql)){
            print "Error description: " . mysqli_error(Sync::$connection);
            die();
        }
        if(mysqli_affected_rows(Sync::$connection)){
            $inserted = true;
        }
        return $inserted;
    }
    private static function getEducationLevelid($identifier){
        $levelid = 0;
        $sql = "SELECT * FROM educationlevels WHERE identifier = '".mysqli_real_escape_string(Sync::$connection, $identifier)."'";
        if(!$cursor = mysqli_query(Sync::$connection, $sql)){
            print "Error description" . mysqli_error(Sync::$connection);
            die();
        }
        if($result = mysqli_fetch_array($cursor, MYSQLI_ASSOC)){
            $levelid = $result['levelid'];
        }
        return $levelid;
    }
    private static function insertStatementEducationLevels($educationlevelids){
        $inserted = false;
        
        $sql = "INSERT IGNORE INTO ".Sync::$statements_educationleveles_insert_table." VALUES 
            ".implode(",", $educationlevelids)."
            ";
        
        if(!mysqli_query(Sync::$connection, $sql)){
            print "Error description: " . mysqli_error(Sync::$connection);
            die();
        }
        if(mysqli_affected_rows(Sync::$connection)){
            $inserted = true;
        }
        return $inserted;
    }
    private static function checkStandardExists($resource_identifier){
        $standard_exists = false;
        $sql = "SELECT * FROM ".Sync::$standards_table." WHERE resourceidentifier='".mysqli_real_escape_string(Sync::$connection, $resource_identifier)."'";
        
        if(!$cursor = mysqli_query(Sync::$connection, $sql)){
            print ("\nError description 0: " . mysqli_error(Sync::$connection));
            die();
        }
        
        if(mysqli_num_rows($cursor) > 1){
            echo "\n******************************************************************************************\n";
            echo "\n". Sync::$skip_original."\n";
            echo "\nMore than one\t".$sql."\n";
            while ($standards = mysqli_fetch_array($cursor, MYSQLI_ASSOC)) {
                var_dump($standards);

            }
            die();
            
        }elseif (mysqli_num_rows($cursor) == 0) {   //insert standard
            $standard_exists = false;
        } else {                                    //update standard
            $standard_exists = true;
        }
        return $standard_exists;
    }
    
    private static function getStandardData($resource_identifier){
        $standard_data = array();
        $sql = "SELECT * FROM ".Sync::$standards_table." WHERE resourceidentifier='".mysqli_real_escape_string(Sync::$connection, $resource_identifier)."'";
        
        if(!$cursor = mysqli_query(Sync::$connection, $sql)){
            print ("\nError description 0: " . mysqli_error(Sync::$connection));
            die();
        }
        
        if (mysqli_num_rows($cursor) > 0) {   //standard present
            $standard_data = mysqli_fetch_array($cursor, MYSQLI_ASSOC);
        } 
        return $standard_data;
    }
    
    private static function checkStatementsExists($resource_identifiers){
        $satement_exists = false;
        $sql = "SELECT * FROM ".Sync::$statements_table." WHERE resourceidentifier IN ('".implode("','",$resource_identifiers)."')";
        
        if(!$cursor = mysqli_query(Sync::$connection, $sql)){
            print ("\nError description 0: " . mysqli_error(Sync::$connection));
            die();
        }
        
//        if(mysqli_num_rows($cursor) > 1){
//            echo "\n******************************************************************************************\n";
//            echo "\n". Sync::$skip_original."\n";
//            echo "\nMore than one statement\t".$sql."\n";
//            while ($statement = mysqli_fetch_array($cursor, MYSQLI_ASSOC)) {
//                var_dump($statement);
//
//            }
//            die();
//            
//        }else
        if (mysqli_num_rows($cursor) == 0) {   //insert standard
            $satement_exists = false;
        } else {                                    //update standard
            $satement_exists = true;
        }
        return $satement_exists;
    }
    
    private static function checkStatementExists($resource_identifier, $table){
        $satement = false;
        $sql = "SELECT * FROM ".$table." WHERE resourceidentifier = '$resource_identifier'";
        
        if(!$cursor = mysqli_query(Sync::$connection, $sql)){
            print ("\nError description 0: " . mysqli_error(Sync::$connection));
            die();
        }
        
//        if(mysqli_num_rows($cursor) > 1){
//            echo "\n******************************************************************************************\n";
//            echo "\n". Sync::$skip_original."\n";
//            echo "\nMore than one statement\t".$sql."\n";
//            while ($statement = mysqli_fetch_array($cursor, MYSQLI_ASSOC)) {
//                var_dump($statement);
//
//            }
//            die();
//            
//        }else
        if (mysqli_num_rows($cursor) == 0) {   //insert standard
            $statement = false;
        } else {                                    //update standard
            $statement = mysqli_fetch_array($cursor, MYSQLI_ASSOC);
        }
        return $statement;
    }
    
    private static function getStandardStatementsData($standard_statement_xml){
        
        $standard_statements_data = array();
        
        $xmlReader = new XMLReader();
                
        $xmlReader->xml($standard_statement_xml);
        $index = 1;
        $counter = 0;
        while ($xmlReader->read()){
            if($xmlReader->nodeType === XMLReader::ELEMENT && $xmlReader->name == 'asn:StandardDocument'){
                $main_doc = new DOMDocument();
                libxml_use_internal_errors(TRUE); //disable libxml errors
                $main_doc->loadXML($xmlReader->readOuterXML());
                libxml_clear_errors();
                $main_xpath = new DOMXPath($main_doc);
                
                $xpath_title = $main_xpath->query("//dc:title");
        
                if($xpath_title != false && $xpath_title->length > 0){
                    $standard_statements_data['title'] = $xpath_title->item(0)->nodeValue;
                }
        
                $xpath_description = $main_xpath->query("//dcterms:description");
                if($xpath_description != false && $xpath_description->length > 0){
                    $standard_statements_data['description'] = $xpath_description->item(0)->nodeValue;
                }
        
                $xpath_source = $main_xpath->query("//dcterms:source");
                if($xpath_source != false && $xpath_source->length > 0){
                    $standard_statements_data['source'] = $xpath_source->item(0)->getAttribute('rdf:resource');
                }
        
                $xpath_publicationStatus = $main_xpath->query("//asn:publicationStatus");
                if($xpath_publicationStatus != false && $xpath_publicationStatus->length > 0){
                    $standard_statements_data['publicationStatus'] = end(@explode("/",$xpath_publicationStatus->item(0)->getAttribute('rdf:resource')));
                }
        
                $xpath_repositoryDate = $main_xpath->query("//asn:repositoryDate");
                if($xpath_repositoryDate != false && $xpath_repositoryDate->length > 0){
                    $standard_statements_data['repositoryDate'] = $xpath_repositoryDate->item(0)->nodeValue;
                }
        
                $xpath_valid = $main_xpath->query("//dcterms:valid");
                if($xpath_valid != false && $xpath_valid->length > 0){
                    $standard_statements_data['valid'] = $xpath_valid->item(0)->nodeValue;
                }
        
                $xpath_tableOfContents = $main_xpath->query("//dcterms:tableOfContents");
                if($xpath_tableOfContents != false && $xpath_tableOfContents->length > 0){
                    $standard_statements_data['tableOfContents'] = $xpath_tableOfContents->item(0)->getAttribute('rdf:resource');
                }
        
                $xpath_subject = $main_xpath->query("//dcterms:subject");
                if($xpath_subject != false && $xpath_subject->length > 0){
                    $standard_statements_data['subject'] = end(@explode("/",$xpath_subject->item(0)->getAttribute('rdf:resource')));
                }
        
                $xpath_educationLevel = $main_xpath->query("//dcterms:educationLevel");
        //        echo "\n******************************************************************************************\n";
                if($xpath_educationLevel != false && $xpath_educationLevel->length > 0){
                    foreach($xpath_educationLevel as $educationLevel){
                        $standard_statements_data['educationlevels'][] = end(@explode("/",$educationLevel->getAttribute('rdf:resource')));
                    }
                }
        //        echo "\n******************************************************************************************\n";
                $xpath_language = $main_xpath->query("//dcterms:language");
                if($xpath_language != false && $xpath_language->length > 0){
                    $standard_statements_data['language'] = end(@explode("/",$xpath_language->item(0)->getAttribute('rdf:resource')));
                }
                
//                $xpath_jurisdiction = $main_xpath->query("//asn:jurisdiction");
//                
//                if($xpath_jurisdiction != false && $xpath_jurisdiction->length > 0){
//                    $standard_statements_data['jurisdictioncode'] = end(@explode("/",$xpath_jurisdiction->item(0)->getAttribute('rdf:resource')));
//                }
                
                $xpath_standard_children = $main_xpath->query("//gemq:hasChild");
                if($xpath_standard_children != false && $xpath_standard_children->length > 0){
                    $index2 = 1;
                    foreach ($xpath_standard_children as $xpath_standard_child){
                        $xpath_standard_child = $main_xpath->query("//gemq:hasChild[{$index2}]")->item(0)->getAttribute('rdf:resource');
                        $standard_statements_data['children'][] = end(@explode("/",$xpath_standard_child));
                        $index2++;
                    }
                }
                
            }
            
            if($xmlReader->nodeType === XMLReader::ELEMENT && $xmlReader->name == 'rdf:Description'){
                
                $main_doc = new DOMDocument();
                libxml_use_internal_errors(TRUE); //disable libxml errors
                $main_doc->loadXML($xmlReader->readOuterXML());
                libxml_clear_errors();
                $main_xpath = new DOMXPath($main_doc);
                
                $resource_identifier = $main_xpath->query("//rdf:Description");
                
                if($resource_identifier == false || $resource_identifier->length == 0){
                    echo "\n******************************************************************************************\n";
                    echo "\n". Sync::$skip_original." - ".$c."\t".$url. "\n";
                    echo "No resourceidentifier";
                    return false;
                }
                $resource_identifier = $resource_identifier->item(0)->getAttribute('rdf:about');
                $standard_statements_data['resource_identifier'] = Sync::getResourceIdentifier($resource_identifier);
                
            }
            
            if($xmlReader->nodeType === XMLReader::ELEMENT && $xmlReader->name == 'asn:Statement'){
                $main_doc = new DOMDocument();
                libxml_use_internal_errors(TRUE); //disable libxml errors
                $main_doc->loadXML($xmlReader->readOuterXML());
                libxml_clear_errors();
                $main_xpath = new DOMXPath($main_doc);
                
                $xpath_statement = $main_xpath->query("//asn:Statement");
                if($xpath_statement != false && $xpath_statement->length > 0){
                    $standard_statements_data['statements'][$counter]['resource_identifier'] = end(@explode("/", $xpath_statement->item(0)->getAttribute('rdf:about')));
                    $statement_resource_identifiers[] = $standard_statements_data['statements'][$counter]['resource_identifier'];
                }
                $standard_statements_data['statement_resource_identifiers'] = $statement_resource_identifiers;
                
                $xpath_statement_parents = $main_xpath->query("//gemq:isChildOf");
                if($xpath_statement_parents != false && $xpath_statement_parents->length > 0){
                    $standard_statements_data['statements'][$counter]['parent'] = end(@explode("/", $xpath_statement_parents->item(0)->getAttribute('rdf:resource')));
                }
                
                $xpath_statement_children = $main_xpath->query("//gemq:hasChild");
                if($xpath_statement_children != false && $xpath_statement_children->length > 0){
                    $index2 = 1;
                    foreach ($xpath_statement_children as $xpath_statement_child){
                        $xpath_statement_child = $main_xpath->query("//gemq:hasChild[{$index2}]")->item(0)->getAttribute('rdf:resource');
                        $standard_statements_data['statements'][$counter]['children'][] = end(@explode("/",$xpath_statement_child));
                        $index2++;
                    }
                }
                
                $xpath_statement_educationLevels = $main_xpath->query("//dcterms:educationLevel");
                
                if($xpath_statement_educationLevels != false && $xpath_statement_educationLevels->length > 0){
                    $index2 = 1;
                    foreach ($xpath_statement_educationLevels as $xpath_statement_educationLevel){
                        $xpath_statement_educationLevel = $main_xpath->query("//dcterms:educationLevel[{$index2}]")->item(0)->getAttribute('rdf:resource');
                        $standard_statements_data['statements'][$counter]['educationlevels'][] = end(@explode("/",$xpath_statement_educationLevel));
                        $index2++;
                    }
                }
                
                $xpath_statement_subject = $main_xpath->query("//dcterms:subject");
                
                if($xpath_statement_subject != false && $xpath_statement_subject->length > 0){
                    $standard_statements_data['statements'][$counter]['subject'] = end(@explode("/",$xpath_statement_subject->item(0)->getAttribute('rdf:resource')));
                }
                
                $xpath_statement_description = $main_xpath->query("//dcterms:description");
                
                if($xpath_statement_description != false && $xpath_statement_description->length > 0){
                    $standard_statements_data['statements'][$counter]['description'] = $xpath_statement_description->item(0)->nodeValue;
                }
                $xpath_statement_language = $main_xpath->query("//dcterms:language");
                
                if($xpath_statement_language != false && $xpath_statement_language->length > 0){
                    $standard_statements_data['statements'][$counter]['language'] = end(@explode("/",$xpath_statement_language->item(0)->getAttribute('rdf:resource')));
                }
                
//                $xpath_statement_parent = $main_xpath->query("//gemq:isChildOf");
//                
//                if($xpath_statement_parent != false && $xpath_statement_parent->length > 0){
//                    $standard_statements_data['statements'][$counter]['parent'] = end(@explode("/",$xpath_statement_parent->item(0)->getAttribute('rdf:resource')));
//                }
                
                $xpath_statement_comment = $main_xpath->query("//asn:comment");
                
                if($xpath_statement_comment != false && $xpath_statement_comment->length > 0){
                    $standard_statements_data['statements'][$counter]['comment'] = $xpath_statement_comment->item(0)->nodeValue;
                }
                
                $xpath_statement_notation = $main_xpath->query("//asn:statementNotation");
                
                if($xpath_statement_notation != false && $xpath_statement_notation->length > 0){
                    $standard_statements_data['statements'][$counter]['notation'] = $xpath_statement_notation->item(0)->nodeValue;
                }
                $xpath_statement_guid = $main_xpath->query("//skos:exactMatch");
                
                if($xpath_statement_guid != false && $xpath_statement_guid->length > 0){
                    $standard_statements_data['statements'][$counter]['guid'] = $xpath_statement_guid->item(0)->getAttribute('rdf:resource');
                }
                
                $xpath_statement_label = $main_xpath->query("//asn:statementLabel");
                
                if($xpath_statement_label != false && $xpath_statement_label->length > 0){
                    $standard_statements_data['statements'][$counter]['label'] = $xpath_statement_label->item(0)->nodeValue;
                }
                
                $xpath_statement_alternatenotation = $main_xpath->query("//asn:altStatementNotation");
                
                if($xpath_statement_alternatenotation != false && $xpath_statement_alternatenotation->length > 0){
                    $standard_statements_data['statements'][$counter]['alternatenotation'] = $xpath_statement_alternatenotation->item(0)->nodeValue;
                }
                
                
                $index++;
                $counter++;
            }
        }
        
        
        $xmlReader->close();
        
        
        return $standard_statements_data;
    }
    
    private static function updateStandard($standard_statements_data){
        
        $sql = "UPDATE " . Sync::$standards_table ." 
                SET title = '". mysqli_real_escape_string(Sync::$connection, $standard_statements_data['title'])."',
                description = '". mysqli_real_escape_string(Sync::$connection, $standard_statements_data['description'])."',
                source = '". mysqli_real_escape_string(Sync::$connection, $standard_statements_data['source'])."',
                publicationstatus = '". mysqli_real_escape_string(Sync::$connection, $standard_statements_data['publicationStatus'])."',
                validdate = '". mysqli_real_escape_string(Sync::$connection, $standard_statements_data['valid'])."',
                language = '". mysqli_real_escape_string(Sync::$connection, $standard_statements_data['language'])."',
                subject = '". mysqli_real_escape_string(Sync::$connection, $standard_statements_data['subject'])."',
                resourceidentifier = '". mysqli_real_escape_string(Sync::$connection, $standard_statements_data['resource_identifier'])."',
                active = 'T',
                url = '". mysqli_real_escape_string(Sync::$connection, $standard_statements_data['url'])."',
                lastprocessdate = NOW(),
                filename = NULL,
                jurisdictioncode = '". mysqli_real_escape_string(Sync::$connection, $standard_statements_data['jurisdictioncode'])."',
                publisher = '". mysqli_real_escape_string(Sync::$connection, $standard_statements_data['publisher'])."'
                WHERE resourceidentifier = '".mysqli_real_escape_string(Sync::$connection, $standard_statements_data['resource_identifier'])."'
                ";
        
        if(!mysqli_query(Sync::$connection, $sql)){
            print ("\nError description updateStandard 1: " . mysqli_error(Sync::$connection));
            die();
        }
        
        $sql = "SELECT standardid FROM " .Sync::$standards_table. " WHERE resourceidentifier = '". mysqli_real_escape_string(Sync::$connection, $standard_statements_data['resource_identifier'])."'";
        
        if(!$result = mysqli_query(Sync::$connection, $sql)){
            print ("\nError description updateStandard 2: " . mysqli_error(Sync::$connection));
            die();
        }
        $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $last_standard_id = $result['standardid'];
    }
    /*
     * @return Last inserted id
     */
    private static function insertStandard($data){
        $sql = "INSERT INTO ".Sync::$standards_insert_table." 
                (title,
                description,
                source,
                publicationstatus,
                validdate,
                language,
                subject,
                resourceidentifier,
                active,
                url,
                lastprocessdate,
                filename,
                jurisdictioncode,
                publisher)
                VALUES (
                '". mysqli_real_escape_string(Sync::$connection, $data['title'])."',
                '". mysqli_real_escape_string(Sync::$connection, $data['description'])."',
                '". mysqli_real_escape_string(Sync::$connection, $data['source'])."',
                '". mysqli_real_escape_string(Sync::$connection, $data['publicationStatus'])."',
                '". mysqli_real_escape_string(Sync::$connection, $data['valid'])."',
                '". mysqli_real_escape_string(Sync::$connection, $data['language'])."',
                '". mysqli_real_escape_string(Sync::$connection, $data['subject'])."',
                '". mysqli_real_escape_string(Sync::$connection, $data['resource_identifier'])."',
                '". mysqli_real_escape_string(Sync::$connection, "T")."',
                '". mysqli_real_escape_string(Sync::$connection, $data['url'])."',
                NOW(),
                NULL,
                '". mysqli_real_escape_string(Sync::$connection, $data['jurisdictioncode'])."',
                '". mysqli_real_escape_string(Sync::$connection, $data['publisher'])."'
                )";
        
        if(!mysqli_query(Sync::$connection, $sql)){
            print ("\nError description insertStandard: " . mysqli_error(Sync::$connection));
            die();
        }
        return Sync::$connection->insert_id;
    }
    
//    private static function updateStatement($last_standard_id, $statement){
//       
//        $sql = "UPDATE " . Sync::$statements_table ." 
//                SET standardid = '". mysqli_real_escape_string(Sync::$connection, $last_standard_id)."',
//                parentid = '". mysqli_real_escape_string(Sync::$connection, 1)."',
//                resourceidentifier = '". mysqli_real_escape_string(Sync::$connection, $statement['resource_identifier'])."',
//                subject = '". mysqli_real_escape_string(Sync::$connection, $statement['subject'])."',
//                description = '". mysqli_real_escape_string(Sync::$connection, $statement['description'])."',
//                language = '". mysqli_real_escape_string(Sync::$connection, $statement['language'])."',
//                notation = '". mysqli_real_escape_string(Sync::$connection, $statement['notation'])."',
//                active = 'T'
//                WHERE resourceidentifier = '".mysqli_real_escape_string(Sync::$connection, $statement['resource_identifier'])."'
//                ";
//        
//       
//        if(!mysqli_query(Sync::$connection, $sql)){
//            print ("\nError description updateStatement 1: " . mysqli_error(Sync::$connection));
//            die();
//        }
//        $sql = "SELECT statementid FROM " .Sync::$statements_table. " WHERE resourceidentifier = '". mysqli_real_escape_string(Sync::$connection, $statement['resource_identifier'])."'";
//        if(!$result = mysqli_query(Sync::$connection, $sql)){
//            print ("\nError description updateStatement 2: " . mysqli_error(Sync::$connection));
//            die();
//        }
//        $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
//        return $question_last_id = $result['statementid'];
//    }
    
    private static function insertStatements($last_standard_id, $insert_statements){
        $values = array();
        foreach($insert_statements as $statement){
            $value = array();
            if(isset($last_standard_id)){
                $standardid = "'".mysqli_real_escape_string(Sync::$connection, $last_standard_id)."'";
            }
            
            if(isset($statement['parent'])){
                if(!$parentid = Sync::getStatementParentId($statement)){
                    $value['parentid'] = 'NULL';
                } else {
                    $value['parentid'] = "'".mysqli_real_escape_string(Sync::$connection, $parentid)."'";
                }
            }
            
            
            if(isset($statement['resource_identifier'])){
                $value['resourceidentifier'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['resource_identifier'])."'";
            } else {
                $value['resourceidentifier'] = 'NULL';
            }
            
            if(isset($statement['subject'])){
                $value['subject'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['subject'])."'";
            } else {
                $value['subject'] = 'NULL';
            }
            
            if(isset($statement['description'])){
                $value['description'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['description'])."'";
            } else {
                $value['description'] = 'NULL';
            }
            
            if(isset($statement['language'])){
                $value['language'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['language'])."'";
            } else {
                $value['language'] = 'NULL';
            }
            
            if(isset($statement['comment'])){
                $value['comment'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['comment'])."'";
            } else {
                $value['comment'] = 'NULL';
            }
            
            if(isset($statement['notation'])){
                $value['notation'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['notation'])."'";
            } else {
                $value['notation'] = 'NULL';
            }
            
            if(isset($statement['guid'])){
                $value['guid'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['guid'])."'";
            } else {
                $value['guid'] = 'NULL';
            }
            if(isset($statement['label'])){
                $value['label'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['label'])."'";
            } else {
                $value['label'] = 'NULL';
            }
            
            if(isset($statement['alternatenotation'])){
                $value['alternatenotation'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['alternatenotation'])."'";
            } else {
                $value['alternatenotation'] = 'NULL';
            }
            
            
            $value['active'] = "'T'";
            
            $values[] = "(".$standardid
                    .",".$value['parentid']
                    .", ".$value['resourceidentifier']
                    .", ".$value['subject']
                    .", ".$value['description']
                    .", ".$value['language']
                    .", ".$value['comment']
                    .", ".$value['notation']
                    .", ".$value['guid']
                    .", ".$value['label']
                    .", ".$value['active']
                    .", ".$value['alternatenotation']
                    . ")";
        }
        
        
        
        
        $columns = "(standardid
            ,parentid
            ,resourceidentifier
            ,subject
            ,description
            ,language
            ,comment
            ,notation
            ,guid
            ,label
            ,active
            ,alternatenotation)";
        $imploded_values = implode(", ", $values);
        
        $sql = "INSERT INTO ".Sync::$statements_table." 
                $columns
                VALUES $imploded_values";
        
        if(!mysqli_query(Sync::$connection, $sql)){
            print ("\nError description insertStatement: " . mysqli_error(Sync::$connection));
            die();
        }
        $first_id = Sync::$connection->insert_id;
        echo "\nFirst id\t$first_id\n";
        $count = count($insert_statements);
        echo "\nCount\t$count\n";
        echo "\nLast id\t".($first_id+$count -1)."\n";
        
        $st_inserted['first'] = $first_id;
        $st_inserted['last'] = $first_id+$count -1;
        
        return $st_inserted;
    }
    private static function insertStatement($last_standard_id, $statement, $parentid = null){
        $values = null;
        
        if(isset($last_standard_id)){
            $standardid = "'".mysqli_real_escape_string(Sync::$connection, $last_standard_id)."'";
        }

        
        if($parentid == null){
            $value['parentid'] = 'NULL';
        } else {
            $value['parentid'] = "'".mysqli_real_escape_string(Sync::$connection, $parentid)."'";
        }


        if(isset($statement['resource_identifier'])){
            $value['resourceidentifier'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['resource_identifier'])."'";
        } else {
            $value['resourceidentifier'] = 'NULL';
        }

        if(isset($statement['subject'])){
            $value['subject'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['subject'])."'";
        } else {
            $value['subject'] = 'NULL';
        }

        if(isset($statement['description'])){
            $value['description'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['description'])."'";
        } else {
            $value['description'] = 'NULL';
        }

        if(isset($statement['language'])){
            $value['language'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['language'])."'";
        } else {
            $value['language'] = 'NULL';
        }

        if(isset($statement['comment'])){
            $value['comment'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['comment'])."'";
        } else {
            $value['comment'] = 'NULL';
        }

        if(isset($statement['notation'])){
            $value['notation'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['notation'])."'";
        } else {
            $value['notation'] = 'NULL';
        }

        if(isset($statement['guid'])){
            $value['guid'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['guid'])."'";
        } else {
            $value['guid'] = 'NULL';
        }
        if(isset($statement['label'])){
            $value['label'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['label'])."'";
        } else {
            $value['label'] = 'NULL';
        }

        if(isset($statement['alternatenotation'])){
            $value['alternatenotation'] = "'".mysqli_real_escape_string(Sync::$connection, $statement['alternatenotation'])."'";
        } else {
            $value['alternatenotation'] = 'NULL';
        }


        $value['active'] = "'T'";

        $imploded_values = "(".$standardid
                .",".$value['parentid']
                .", ".$value['resourceidentifier']
                .", ".$value['subject']
                .", ".$value['description']
                .", ".$value['language']
                .", ".$value['comment']
                .", ".$value['notation']
                .", ".$value['guid']
                .", ".$value['label']
                .", ".$value['active']
                .", ".$value['alternatenotation']
                . ")";
        
        
        
        
        
        $columns = "(standardid
            ,parentid
            ,resourceidentifier
            ,subject
            ,description
            ,language
            ,comment
            ,notation
            ,guid
            ,label
            ,active
            ,alternatenotation)";
        
        $sql = "INSERT INTO ".Sync::$statements_insert_table." 
                $columns
                VALUES $imploded_values";
        
        if(!mysqli_query(Sync::$connection, $sql)){
            print ("\nError description insertStatement: " . mysqli_error(Sync::$connection));
            die();
        }
        return Sync::$connection->insert_id;
        
    }
    private static function getStatementParentId($statement){
        $statementid = 0;
        $sql = "SELECT * FROM ".Sync::$statements_table." WHERE resourceidentifier = '".$statement['parent']."'";
        if(!$cursor = mysqli_query(Sync::$connection, $sql)){
            print "Error Description: " . mysqli_error(Sync::$connection);
            die();
        }
        if(mysqli_num_rows($cursor) == 0){
            $statementid = 0;
        } else {
            $result = mysqli_fetch_array($cursor, MYSQLI_ASSOC);
            $statementid = $result['statementid'];
        }
        return $statementid;
        
    }
    private static function getResourceIdentifier($xml_url){
        return end(@explode("/", rtrim(trim($xml_url),".xml")));
    }
    private static function getStandards($url){
        
    }
    private static function getASNStandardLinks($url){
        sleep(10);
        $main_xpath = Sync::fetchXML($url);
        if (!$main_xpath) {
            echo "\nCouldnt find main_xpath on $url\nRetrying ...";
            sleep(20);
            $main_xpath = Sync::fetchXML($url);
            if (!$main_xpath) {
                echo "\nCouldnt find main_xpath on $url\nRetrying ...";
                die();
            }
        }
        $standards_row = $main_xpath->query('//div[contains(@id,"col_wrapper")]//div[contains(@id, "content")]//div[contains(@class, "view")]//div[contains(@class, "view-content")]//table[contains(@class, "views-table")]//tbody//tr');
        $data = array();
        $index = 0;
        
        foreach ($standards_row as $row){
            $count = 1;
            foreach ($row->getElementsByTagName('td') as $td){
                switch ($count){
                    case 1:
                        $data[$index]['jurisdiction_organization'] = mysqli_real_escape_string(Sync::$connection, trim(strip_tags($td->nodeValue)));
                        $data[$index]['link'] = mysqli_real_escape_string(Sync::$connection,"http://asn.jesandco.org".trim(strip_tags($td->getElementsByTagName('a')[0]->getAttribute('href'))));
                        break;
                    case 2:
                        $data[$index]['description'] = mysqli_real_escape_string(Sync::$connection,trim(strip_tags($td->nodeValue)));
                        break;
                    case 3:
                        $data[$index]['jurisdiction_class'] = mysqli_real_escape_string(Sync::$connection,trim(strip_tags($td->nodeValue)));
                        break;
                    default;
                        break;
                }
                $count++;
            }
            $index++;
        }
        return $data;
    }
    public function ASNJurisdictionStatements(){
        
        $create_table_sql = "CREATE TABLE IF NOT EXISTS `ASNJurisdiction_statement_links` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `ASNJurisdiction_standardid` int(11) NOT NULL,
                            `xml_link` varchar(255) NOT NULL,
                            `xml_content` longtext NOT NULL,
                            `subject` varchar(255) NOT NULL,
                            `validdate` varchar(255) NOT NULL,
                            `publicationStatus` varchar(255) NOT NULL,
                            `processed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                            PRIMARY KEY (`id`),
                            KEY `ASNJurisdiction_standardid` (`ASNJurisdiction_standardid`),
                            CONSTRAINT `asnjurisdiction_standarid` FOREIGN KEY (`ASNJurisdiction_standardid`) REFERENCES `ASNJurisdictionStandard_links` (`id`)
                           ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        
        if(!mysqli_query(Sync::$connection, $create_table_sql)){
            print ("\nError description create table: " . mysqli_error(Sync::$connection));
            die();
        }
        
        
        
        $sql = "SELECT * FROM ASNJurisdictionStandard_links LIMIT ". Conn::$limit . " OFFSET " . Sync::$skip;
        
        if(!($result = mysqli_query(Sync::$connection, $sql))){
            print ("\nError description 0: " . mysqli_error(Sync::$connection));
            die();
        }
        while ($jurisdiction = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $url = $jurisdiction['link'];
            $url = $url. "?publication_status=All";
            $sub_standards_data = Sync::getSubStandards($url);
            foreach($sub_standards_data as $sub_standard){
//                if($sub_standard['standard_publicaton_status'] == 'Published')
//                    $standard_statement_xml = Sync::fetchXMLContent($sub_standard['standard_link']);
                
                $sql = "INSERT INTO ASNJurisdiction_statement_links
                    (ASNJurisdiction_standardid, xml_link, subject,validdate, publicationStatus)
                    VALUES ({$jurisdiction['id']},
                        '". mysqli_real_escape_string(Sync::$connection, $sub_standard['standard_link'])."',
                        '". mysqli_real_escape_string(Sync::$connection, $sub_standard['standard_subject'])."',
                        '". mysqli_real_escape_string(Sync::$connection, $sub_standard['standard_validdate'])."',
                        '". mysqli_real_escape_string(Sync::$connection, $sub_standard['standard_publicaton_status'])."'
                            ) ";
                
                if(!mysqli_query(Sync::$connection, $sql)){
                    print ("\nError description 1: " . mysqli_error(Sync::$connection));
                    die();
                } else {
                    echo "\tInserted {$sub_standard['standard_link']}\n";
                }
            }
        }
    }

    private static function getSubStandards($url){
        
//            echo $main_xpath->document->saveHTML();
        echo "\n******************************************************************************\n";
            while(true){
                $data = array();
                $main_xpath = Sync::fetchXML($url);
                if (!$main_xpath) {
                    echo "\nCouldnt find main_xpath on $url\n";
                    continue;
                }
                
                $data = array();
                
//                var_dump($main_xpath);
//                var_dump($standards_row);
                if (strpos($main_xpath->document->saveHTML(), '400 Bad Request') !== false) {
                    echo "Retrying\n";
                    sleep(10);
                    continue;
                } else{
                    echo "\n******************************************************************************\n";    
                    
                    $standard_links = $main_xpath->query('//div[contains(@id,"col_wrapper")]//div[contains(@id, "content")]//div[contains(@class, "view")]//div[contains(@class, "view-content")]//table[contains(@class, "views-table")]//tbody//tr//td[contains(@class, "views-field-phpcode-1")]//a[1]');
                    $standard_subjects = @$main_xpath->query('//div[contains(@id,"col_wrapper")]//div[contains(@id, "content")]//div[contains(@class, "view")]//div[contains(@class, "view-content")]//table[contains(@class, "views-table")]//tbody//tr//td[contains(@class, "views-field-markup")]');
                    $standard_validdates = @$main_xpath->query('//div[contains(@id,"col_wrapper")]//div[contains(@id, "content")]//div[contains(@class, "view")]//div[contains(@class, "view-content")]//table[contains(@class, "views-table")]//tbody//tr//td[contains(@class, "views-field-field-dcterms-valid-value")]//span');
                    $standard_publicaton_statuses = @$main_xpath->query('//div[contains(@id,"col_wrapper")]//div[contains(@id, "content")]//div[contains(@class, "view")]//div[contains(@class, "view-content")]//table[contains(@class, "views-table")]//tbody//tr//td[contains(@class, "views-field-name")]');

                    $count = 0;
                    foreach($standard_links as $link){
                        $data[$count]['standard_link'] = trim($link->getAttribute('href'));
                        $data[$count]['standard_subject'] = trim($standard_subjects[$count]->nodeValue);
                        $data[$count]['standard_validdate'] = trim($standard_validdates[$count]->nodeValue);
                        $data[$count]['standard_publicaton_status'] = trim($standard_publicaton_statuses[$count]->nodeValue);
                        $count++;
                    }
                    break;
                }
            }
            
        
        
        
        return $data;
    }
    
    private static function fetchPage($page) {
        if (!isset($page) || $page == '') {
            echo "\nfetch page error 1\n";
            return false;
        }

        date_default_timezone_set('UTC');

        $url = $page;

        $request = $url;
        
        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_URL, $request);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/' . rand(1, 5) . '.0 (X11; CrOS x86_64 ' . rand(1000, 8000) . '.0.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.' .
//                rand(300, 900) . '.3 Safari/' . rand(100, 800) . '.' . rand(25, 76));

///Send the complete request to the API
        if (!$result = curl_exec($ch)) {
            echo "\nfetch page error 2\t".curl_error($ch)."\n";
            return false;
        } else {
        }

        if ($result === false) {
            echo "\nfetch page error 3\t".curl_error($ch)."\n";
            return false;
        }
        //echo $result;
        return $result;
    }
    
    private static function fetchXML($url){
        try{
            $html = self::fetchPage($url);
            if(!$html):
                print "\n\n*** Couldn't fetch... ***\n\n";
                return false;
            endif;
            $main_doc = new DOMDocument();
            libxml_use_internal_errors(TRUE); //disable libxml errors
            
            if(empty($html)):
                print "\n\n*** Empty HTML ... ***\n\n";
                return false;
            endif;
            $main_doc->loadHTML($html);
            libxml_clear_errors();
            $main_xpath = new DOMXPath($main_doc);
            return $main_xpath;
        }
        catch (Exception $e) {
            print '\n\n*** Caught exception: '.  $e->getMessage(). "***\n\n";
            false;
        }
    }
    private static function fetchOriginalXML($url){
        try{
            while(true){
                $html = self::fetchPage($url);
                if(!$html):
                    echo "\n******************************************************************************\n";    
                    print "\n\n***$url Couldn't fetch... Retrying...***\n\n";
                    echo "\n******************************************************************************\n";                   
                    sleep(10);
                    continue;
                endif;
                $main_doc = new DOMDocument();
                libxml_use_internal_errors(TRUE); //disable libxml errors

                if(empty($html)):
                    print "\n\n*** Empty HTML ... ***\n\n";
                    return false;
                endif;
                $main_doc->loadXML($html);
                libxml_clear_errors();
                $main_xpath = new DOMXPath($main_doc);
                
                break;
            }
            return $main_xpath;
            
        }
        catch (Exception $e) {
            print '\n\n*** Caught exception: '.  $e->getMessage(). "***\n\n";
            false;
        }
    }
    private static function fetchXMLContent($url){
        try{
            while(true){
                $html = self::fetchPage($url);
                if(!$html):
                    echo "\n******************************************************************************\n";    
                    print "\n\n***$url Couldn't fetch... Retrying...***\n\n";
                    echo "\n******************************************************************************\n";                   
                    sleep(10);
                    continue;
                endif;
                break;
            }
            return $html;
            
        }
        catch (Exception $e) {
            print '\n\n*** Caught exception: '.  $e->getMessage(). "***\n\n";
            false;
        }
    }
    public function getDeleteResources() {
        $arr = [
            [
                'folder' => 'resourceimgs',
                'filename' => '598b57f6b3600.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598b57ed81bc4.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598b57e63b052.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598b57c72e906.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598b57bb02306.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598b57ad8fcfb.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598b5798567b5.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598b578881d0c.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598c8dca5d259.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598c8dbf17b9d.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598c8db1daed1.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598c8d9bbd20c.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598c8d2203b84.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598c8d18523b3.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598c8d0b94c11.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598c8d0256516.JPG'
            ],
            [
                'folder' => 'resourceimgs',
                'filename' => '598c8cf3ec15e.JPG'
            ],
        ];

        foreach ($arr as $ar) {
            foreach ($ar as $k => $v) {
                if ($k == 'folder') {
                    $data['folder'] = $v;
                }
                if ($k == 'filename') {
                    $data['uniquename'] = $v;
                }
            }
            self::deleteFileS3($data);
            $q = "DELETE FROM resourcefiles WHERE uniquename = '{$data['uniquename']}'";
            mysqli_query(Sync::$connection, $q);
        }
    }
    public static function deleteFileS3($file) {
        global $vars;
        global $s3_client;
//        echo "<pre>";
//        print_r($s3_client);
        $vars['s3_client'] = $s3_client;
//        die();
//        if (!isset($vars['s3_client']))
//            $vars['s3_client'] = $vars['aws']->get('S3');
        $dir = pathinfo($file['uniquename'], PATHINFO_FILENAME);
//        if (file_exists($vars['upload_path'] . $file['folder'] . $file['uniquename'])) {
//            unlink($vars['upload_path'] . $file['folder'] . $file['uniquename']);
//        }
//        if (file_exists($vars['upload_path'] . $file['folder'] . $dir)) {
//            delete_directory($vars['upload_path'] . $file['folder'] . $dir);
//        }
//        
//        print_r($file['folder']);
//        die();
//        var_dump($s3_client->doesObjectExist('currikicdn', '/' . '57f956b0ecc15.jpg'));
//        die();
        $file['folder'] = $file['folder'] . '/';
        if ($s3_client->doesObjectExist($vars['awsBucket'], $file['folder'] . $file['uniquename'])) {

            try {
                $delete = $vars['s3_client']->deleteObject(array(
                    'Bucket' => $vars['awsBucket'],
                    'Key' => $file['folder'] . $file['uniquename']
                ));
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
    }
    public function changeStructureQuestions(){
        die();
        $resourceid = 303908;
        $content=<<<EOD
                <h3 style="text-align: center;">Title<p>description</p><p>instructions</p>



<div>&nbsp;</div><div class="oembedall-container" style="display: block; margin-left: auto; margin-right: auto; text-align: center; width: 100%;"><span class="oembedall-closehide">&darr;</span><a href="https://DBI_AWS_S3_CDN_URL/resourcedocs/5968ee01ec464.pdf" target="_blank">BillofRights</a><br> <iframe style="border: none;" src="https://docs.google.com/viewer?embedded=true&amp;url=https://DBI_AWS_S3_CDN_URL/resourcedocs%2F5968ee01ec464.pdf" width="98%" height="600"></iframe></div><p>&nbsp;</p><p>&nbsp;</p></h3>

                
EOD;

        $content = sprintf($content, DBI_AWS_S3_CDN_URL);
        
        $sql = "UPDATE resources SET content = '" . mysqli_real_escape_string(Sync::$connection, $content) ."' WHERE resourceid = {$resourceid}";
        mysqli_query(Sync::$connection, $sql);
        echo "Updated";
    }
    
    public function uploadImage(){
        $file_name="5582d294d77b8.jpg";
//        $url = "https://archivecurrikicdn.s3-us-west-2.amazonaws.com/uploads/CODIE_2017_winner_white.png";

       
        $file = __DIR__."/../tmp/".$file_name;
        
        
        $response = array();
        $bucket = 'uploads';
        $remote_file_path = "avatars/$file_name";
        Sync::uploadFileS3($response, DBI_AWS_S3_BUCKET, $remote_file_path, $file);
        echo "<pre>";
        print_r($response);
    }
}
