<?php
   session_start();
   unset($_SESSION['fname']);
   unset($_SESSION['user_email']);
   unset($_SESSION['is_admin']);
   unset($_SESSION['cart']);
   session_destroy();
   
   echo 'You have logged out!';
   header('Refresh: 2; URL = home.php');
?>