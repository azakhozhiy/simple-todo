<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="<?php echo mix('/css/app.css') ?>">
  <title>Todo list</title>
</head>
<body>
<?php include_view('parts/header.php') ?>

<section class="section tasks">
  <div class="container">
    <h3>Список задач</h3>

    <div class="section__content">
      <?php
      while($row = $userQuery->fetch()){
            echo $row['password'];
      }


      ?>
    </div>
  </div>
</section>

<script src="<?php echo mix('/js/app.js') ?>">
</html>
