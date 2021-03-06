<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . '/../configs/auto_config.php';
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        if (isset($_GET['filename'])) {
            $target_file = getenv("BASE_DIR") . "/uploads/" . $_GET['filename'];
            echo "The file " . $_GET['originalname'] . " has been loaded.";

            $inputFileType = PHPExcel_IOFactory::identify($target_file);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $sheets = $objReader->listWorksheetInfo($target_file);

            echo "<br>" . count($sheets) . " sheet(s) founds. ";
            echo "<br>Based on detected sheet names possible actions are listed below:";
            $arr = [];
            foreach ($sheets as $index => $sheet) {
                switch ($sheet['worksheetName']) {
                    case "FACULTY-DETAILS":
                        $arr["FACULTY-DETAILS"] = TRUE;
                        echo '<br><a href="faculty_list/sheet_to_db.php?filename=' . basename($target_file) . '">FACULTY-DETAILS</a>';
                        break;
                    case "STUDENT-DETAILS":
                        $arr["STUDENT-DETAILS"] = TRUE;
                        echo '<br><a href="student_list/sheet_to_db.php?filename=' . basename($target_file) . '">STUDENT-DETAILS</a>';
                        break;
                    case "COURSE-INSTRUCTOR DETAILS":
                        $arr["COURSE-INSTRUCTOR DETAILS"] = TRUE;
                        echo '<br><a href="faculty_course_list/sheet_to_db.php?filename=' . basename($target_file) . '">COURSE-INSTRUCTOR DETAILS</a>';
                        break;
                    case "COURSE-STUDENT DETAILS":
                        $arr["COURSE-STUDENT DETAILS"] = TRUE;
                        echo '<br><a href="student_course_list/sheet_to_db.php?filename=' . basename($target_file) . '">COURSE-STUDENT DETAILS</a>';
                        break;
                }
            }
            if (count($arr) == 4) {
                echo '<br><a href="sheet_to_db.php?filename=' . basename($target_file) . '">ALL</a>';
            };
            if (count($arr) == 0) {
                echo 'NO EXPECTED SHEETS DETECTED';
            };
        }
        ?>
        <br>
        <br>
        <br>
        <form action="upload_handle.php" method="POST" enctype="multipart/form-data">
            LOAD NEW FILE: &nbsp;<input type="file" name="xlsx_file" />
            <input type="submit" name="upload" value="Upload" />
        </form>
        <?php
        $target_dir = getenv("BASE_DIR") . "/uploads/";
        $fileStore = array();
        foreach (scandir($target_dir) as $filename) {
            if ($filename !== '.' && $filename !== '..' && $filename !== '.gitignore') {
                array_push($fileStore, $filename);
            }
            /*
             * For file types:
              if('xlsx' == pathinfo($filename, PATHINFO_EXTENSION)){
              echo $filename;
              $cnt++;
              }

             */
        }
        echo count($fileStore) . ' files in the temporary upload folder. <a href="index.php?clearStore=asnfcjsdvnhwikasnqpoqf">Click here to empty </a>';
        if (isset($_GET['clearStore'])) {
            if ($_GET['clearStore'] === 'asnfcjsdvnhwikasnqpoqf') {
                foreach ($fileStore as $file) {
                    unlink($target_dir . $file);
                }
                echo '<script>location.href="index.php"</script>';
            }
        }
        ?>
    </body>
</html>
