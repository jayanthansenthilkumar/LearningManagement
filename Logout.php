<?php
   session_start();
   
   if(session_destroy()) {
      echo "<script>
         sessionStorage.clear();
         window.location = 'index.php';
      </script>";
   }
?>