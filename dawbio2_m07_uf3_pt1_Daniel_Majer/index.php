<?php
session_start(); //initialize session to access session variables.
//Configuration for debugging (only developing mode). Change for production.
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
ini_set("error_reporting", E_ALL);

//
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Store manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>
      <?php include "loadnavbar.php";
//navigation bar.
?>
      <?php
      //dynamic html content generated here by controller.
      require_once "controllers/MainController.php";
      use proven\store\controllers\MainController;
      (new MainController())->processRequest();
      ?>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>      
  </body>
</html>
