<section class="section tasks mb-5" id="main-page">
  <div class="container-fluid">
    <div class="section__content">
      <div class="row">
        <div class="col-md-7">
          <h4>Список задач</h4>
          <div id="accordion" class="tasks-list mt-4">
              <?php foreach ($tasks as $task): ?>
                <div
                   id="task-card-<?php echo $task['id']; ?>"
                   data-task-id="<?php echo $task['id']; ?>"
                   class="card task-card <?php echo $task['is_complete'] ? 'task-card--completed' : ''; ?> mb-2"
                >
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
                            <?php if (!$task['is_complete']): ?>
                            <button
                               type="button"
                               data-toggle="tooltip"
                               data-placement="top"
                               title="Завершить задачу"
                               class="btn btn-icon btn-icon--success mr-2 taskToggle"
                            >
                              <span class="material-icons">assignment_turned_in</span>
                            </button>
                            <?php endif; ?>

                            <?php if ($task['is_complete']): ?>
                            <button
                               type="button"
                               data-toggle="tooltip"
                               data-placement="top"
                               title="Перевести задачу в незавершенные"
                               class="btn btn-icon btn-icon--danger mr-2 taskToggle"
                            >
                              <span class="material-icons">unpublished</span>
                            </button>
                            <?php endif; ?>
                          <button
                             type="button"
                             class="btn btn-icon btn-icon--primary "
                             data-toggle="tooltip"
                             data-placement="top"
                             title="Редактировать"
                          >
                            <span class="material-icons">create</span>
                          </button>
                        <?php else: ?>
                          <div
                             style="line-height: normal; color: #12b038"
                             data-toggle="tooltip"
                             title="Задача завершена"
                             data-placement="top"
                          >
                            <span class="material-icons">check</span>
                          </div>
                        <?php endif; ?>
                    </div>
                  </div>

                  <div
                     id="<?php echo 'task-'.$task['id'] ?>"
                     class="collapse" aria-labelledby="headingOne"
                     data-parent="#accordion"
                  >
                    <div class="card-body">

                      <div class="task-picture">
                        <img src="<?php echo $task['picture_url']; ?>" alt="">
                      </div>

                      <ul class="list-unstyled">
                        <li>Дата создания: <?php echo $task['created_at']; ?></li>
                          <?php if ($task['creator_id'] > 0): ?>
                            <li>Создатель: <?php echo $task['creator']['name']; ?></li>
                          <?php endif; ?>
                      </ul>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
          </div>
        </div>
        <div class="col-md-5">
          <h4>Добавить задачу</h4>

          <div class="card mt-4">
            <div class="card-body">
              <form id="createTask">
                <input type="hidden" id="formTaskId">
                <div class="form-group">
                  <label for="formTaskTitle">Название</label>
                  <input
                     class="form-control"
                     id="formTaskTitle"
                     placeholder="Например: доработать модуль авторизации"
                  >
                </div>

                <div class="form-group">
                  <label for="formTaskPicture">Обложка</label>
                  <input
                     type="file"
                     accept="image/jpeg, image/gif, image/png"
                     class="form-control"
                     id="formTaskPicture"
                  >
                </div>

                <div class="form-group">
                  <label for="formTaskContent">Описание задачи</label>
                  <div id="formTaskContent"></div>
                  <button
                     type="button"
                     class="btn btn-primary mt-2"
                     id="buttonTaskPreview"
                  >
                    Предпросмотр
                  </button>
                </div>
                <hr>
                <div class="actions">
                  <button type="button" id="taskSaveButton" class="btn btn-success">Сохранить</button>
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
