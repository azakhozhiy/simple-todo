<div class="modal fade" id="authModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Вход</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" id="badAuth" style="display:none" role="alert">
          Неправильный логин или пароль!
        </div>

        <div class="alert alert-success" id="successAuth" style="display:none" role="alert">
          Успешно!
        </div>

        <form id="authForm">
          <div class="form-group">
            <label for="userLogin">Логин</label>
            <input
               class="form-control"
               id="userLogin"
               aria-describedby="emailHelp"
            >
          </div>
          <div class="form-group">
            <label for="userPassword">Пароль</label>
            <input
               type="password"
               class="form-control"
               id="userPassword"
               aria-describedby="emailHelp"
            >
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
        <button type="button" id="authButton" class="btn btn-primary">Войти</button>
      </div>
    </div>
  </div>
</div>
</div> <!-- close #app -->

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="<?php echo mix('/js/app.js') ?>"></script>
</html>
