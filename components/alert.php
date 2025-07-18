<?php 

    if(isset($success_msg)) {
        $message = is_array($success_msg) ? $success_msg : [$success_msg];
        foreach ($message as $message) {
            echo '<script>swal("'.$message.'", "", "success");</script>';
        }
    }

    if(isset($warning_msg)) {
        $message = is_array($warning_msg) ? $warning_msg : [$warning_msg];
        foreach ($message as $message) {
            echo '<script>swal("'.$message.'", "", "warning");</script>';
        }
    }

    if(isset($info_msg)) {
        $message = is_array($info_msg) ? $info_msg : [$info_msg];
        foreach ($message as $message) {
            echo '<script>swal("'.$message.'", "", "info");</script>';
        }
    }

    if(isset($error_msg)) {
         $message = is_array($error_msg) ? $error_msg : [$error_msg];
        foreach ($message as $message) {
            echo '<script>swal("'.$message.'", "", "error");</script>';
        }
    }
?>