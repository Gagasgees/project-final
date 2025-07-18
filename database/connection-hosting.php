<?php 
    $db_name = 'if0_39490925_project_gesge';
    $db_user = 'if0_39490925';
    $db_password = 'LuioWbsFh4R';
    $db_host = 'sql107.infinityfree.com';

    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

    try {
        $conn = new PDO($dsn, $db_user, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die ("connection failed: ". $e->getMessage());
    }   


    function unique_id() {
        $chars = '01234567891011abcdefghijklmnopqwrstuzANHBKMLPOIUYBDFAWYZXCVBAGFTHJW';
        $charLength = strlen($chars);
        $randomString = '';
        for ($i=0; $i < 20 ; $i++) {
            $randomString.=$chars[mt_rand(0, $charLength - 1)];
        }
        return $randomString;
    }
?>


