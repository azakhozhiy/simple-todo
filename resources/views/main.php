<section class="section tasks" id="main-page">
  <div class="container-fluid">
    <div class="section__content">
      <div class="row">
        <div class="col-md-6">
          <h4>Список задач</h4>
          <div id="accordion" class="mt-4">
            <div class="card task-card">
                <?php foreach ($tasks as $task): ?>
                  <div class="card-header">
                    <h5 class="mb-0">
                      <button
                         class="btn btn-link"
                         data-toggle="collapse"
                         data-target="#<?php echo 'task-'.$task['id'] ?>"
                         aria-expanded="true"
                         aria-controls="collapseOne">
                          <?php echo $task['title']; ?>
                      </button>
                    </h5>
                    <div class="spacer"></div>
                    <div class="task-card__actions">
                        <?php if (auth()->userIsLogged()): ?>
                          <button type="button" class="btn btn-icon">
                            <span class="material-icons">create</span>
                          </button>
                        <?php endif; ?>
                    </div>

                  </div>

                  <div
                     id="<?php echo 'task-'.$task['id'] ?>"
                     class="collapse" aria-labelledby="headingOne"
                     data-parent="#accordion"
                  >
                    <div class="card-body">

                    </div>
                  </div>
                <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <h4>Добавить задачу</h4>

          <div class="card mt-4">
            <div class="card-body">
              <form id="createTask">
                <div class="form-group">
                  <label for="title">Название</label>
                  <input
                     class="form-control"
                     id="title"
                     aria-describedby="emailHelp"
                     placeholder="Например: доработать модуль авторизации"
                  >
                </div>

                <div class="form-group">
                  <label for="taskContent">Суть задачи</label>
                  <div id="taskContent"></div>
                  <button
                     type="button"
                     class="btn btn-primary mt-2"
                     id="buttonTaskPreview"
                  >
                    Предпросмотр
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<div class="modal modal-task-preview fade" id="taskPreviewModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Предпросмотр</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" id="closePreview" class="btn btn-primary">Закрыть</button>
      </div>
    </div>
  </div>
</div>
