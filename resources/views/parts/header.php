<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo mix('/css/app.css') ?>">
  <title>Задачник для Unlimint</title>
</head>
<body>
<div id="app">
  <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Задачник</a>
      <button
         class="navbar-toggler"
         type="button"
         data-toggle="collapse"
         data-target="#navbarSupportedContent"
         aria-controls="navbarSupportedContent"
         aria-expanded="false"
         aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

        <?php $auth = auth(); ?>

        <?php if (!$auth->userIsLogged()): ?>
          <button data-toggle="modal" data-target="#authModal" class="btn btn-primary">
            Войти
          </button>
        <?php endif; ?>

        <?php if($auth->userIsLogged()): ?>
          <div style="display:flex;">
            <div>
            Вы авторизованы, <?php echo $auth->user()['name'] ?>
            </div>
            <div class="ml-4">
            <a href="/?module=auth&action=logout">Выйти</a>
            </div>
          </div>
        <?php endif; ?>
    </div>
  </nav>
