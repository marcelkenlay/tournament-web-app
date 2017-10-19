<?php
    function readFileText($filePath){
      $file = fopen($filePath, "r") or die("Unable to open file!");
      $fileText = fread($file,filesize($filePath));
      fclose($file);
      return $fileText;
    }
 ?>
