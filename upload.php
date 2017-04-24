<?php
  // target_dir := specifices the directory where the file is going to be placed
  // target_file := specifices the path of the file to be uploaded
  // uploadOk := whether or not the file is a real imageFileType
  // imageFileType := the file extension of the file

  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

  // Check if the image file is a real image file
  if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
      echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
    } else {
      echo "File is not an image";
      $uploadOk = 0;
    }
  }

  // Check if the file already exists
  if(file_exists($target_file)) {
    echo "File already exists";
    $uploadOk = 0;
  }

  // Check file Size
  $fileSize = 500000;
  if($_FILES["fileToUpload"]["size"] > $fileSize) {
    echo "File size exceeds maximum size";
    $uploadOk = 0;
  }

  // Only allow certain file types
  if($imageFileType != "jpg" && $imageFIleType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif") {
      echo "Sorry, invalid file type";
      $uploadOk = 0;
    }

  // Check if the $uploadOk flag has been dropped by an error ($uploadOk == 0)?
  if($uploadOk == 0) {
    echo "The file was not uploaded";
  } else {
    if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      echo "The file: " . basename($_FILES["fileToUpload"]["name"]). " has been uploaded";
    } else {
      echo "Sorry, there was an error uploading your file";
    }
  }
 ?>
