<?php session_start();
   include 'connection.php';
   include 'function.php';
   $pdo = new PDO($dsn, $user, $pass, $opt);

   // Add User Starts Here
    if(isset($_REQUEST['update_admin'])){
      $table = "administrator_super_user";
      $result = $pdo->exec("UPDATE $table SET `name`='".$_REQUEST['name']."', `email`='".$_REQUEST['email']."', `tx_address`='".$_REQUEST['tx_address']."', `password`='".$_REQUEST['password']."'  WHERE id=1");

      add_notification("Admin Account Profile Updated", "admin");
      header('Location:dashboard.php?choice=success&value=Admin Profile Updated');
    }
?>