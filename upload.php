<?php

if($_FILES['upload_file']['name'] != ''){
    $data = explode('.', $_FILES['upload_file']['name']);
    $extention = $data[1];
    $allowed_extention = array("jpg", "png", "gif", "txt", "doc", "pdf");
    if(in_array($extention, $allowed_extention)){
        $new_file_name = rand().'.'.$extention;
        $path = $_POST['hidden_folder_name'].'/'.$new_file_name;
        if(move_uploaded_file($_FILES['upload_file']['tmp_name'], $path)){
            echo "File Uploaded";
        }else{
            echo "There is some ERROR";
        }
    }else{
        echo "Invalid File";
    }
}else{
    echo "Please Select File";
}


?>