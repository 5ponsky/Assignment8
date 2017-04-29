<?php session_start(); ?>

<!DOCTYPE html>

<html>
  <body>

    <!-- File Upload Interface -->
    <form action="upload.php" method="post" enctype="multipart/form-data">
      Select an image to upload:
      <input type="file" name="fileToUpload" id="fileToUpload">
      <br>
      <input type="submit" value="Upload Image" name="submit">
    </form>

    <?php
      // Error reporting
      //ini_set("display_errors", 1);
      //error_reporting(~0);

      $target_dir = "uploads/";
      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
      $uploadOk = 1;
      $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

      // Prevent execution if no file has been selected
      if(!isset($_FILES["fileToUpload"]["name"])) {
        echo("no file chosen" . "<br>");
      }

      // If the uploads folder doesn't exist, make it, otherwise die
      if(!file_exists($target_dir)) {
        if(!mkdir($target_dir, 0755))
          die("failed to create folder." . "<br>");
      }

      // Initialize the session token
      if(isset($_SESSION["file_count"])) {
        $_SESSION["file_count"] = 0;
      }

      // Check if the image file is a real image file
      if(isset($_POST["submit"])) {
        // Prevent execution if no file has been selected
        if(!$_FILES["fileToUpload"]["tmp_name"]) {
          echo("No file chosen!" . "<br>");
        }

        // This hack abuses an image size function
        //if the function does not error out, then the file is an image
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

        if($check !== false) {
          echo "File is an image - " . $check["mime"] . "<br>";
          $uploadOk = 1;
        } else {
          echo "File is not an image"  . "<br>";
          $uploadOk = 0;
        }
      }

      // Check if the file already exists
      if(file_exists($target_file)) {
        echo "File already exists"  . "<br>";
        $uploadOk = 0;
      }

      // Check file Size
      $fileSize = 500000000;
      if($_FILES["fileToUpload"]["size"] > $fileSize) {
        echo "File size exceeds maximum size"  . "<br>";
        $uploadOk = 0;
      }

      // Check if the $uploadOk flag has been dropped by an error ($uploadOk == 0)?
      if($uploadOk == 0) {
        echo "The file was not uploaded"  . "<br>";
      } else {
        if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
          // Set readable properties on the file
          chmod($target_file, 0777);
          $_SESSION["file_count"]++;
          echo("Total files uploaded: " . $_SESSION["file_count"] . "<br>");


          echo "The file: " . basename($_FILES["fileToUpload"]["name"]). " has been uploaded" . "<br>";
        } else {
          echo "Sorry, there was an error uploading your file"  . "<br>";
        }
      }
     ?>

    <br>
    <br>

    <form action="upload.php" method="post" enctype="multipart/form-data" id="userform">
      <table>
        <tr style="border: 1px solid black; align: center;">
          <td style="border: 1px solid black; align: center;">Flag</td>
          <td style="border: 1px solid black; align: center;">Files:</td>
        </tr>

        <?php

        // Delete selected files
        if (isset($_POST['checkboxvar'])) {
          foreach($_POST['checkboxvar'] as $it) {
            unlink($target_dir . $it);
          }
        }

        // Cycle through the array of files (in the upload directory)
        //and echo them out with some HTML table formatting
        $files = array_slice(scandir($target_dir), 2);
        $size = count($files);
        for($i = 0; $i < $size; $i++) {
          echo("<tr><td><a href='" . $target_dir . $files[$i] . "' target='_blank'>" . $files[$i] . "</a></td><td><input type='checkbox' name='checkboxvar[]' value='" . $files[$i] . "'></td></tr>\n");
        }
        ?>
      </table>

      <input type="submit" value="Delete Flagged" name="delete">
    </form>

  </body>
</html>
