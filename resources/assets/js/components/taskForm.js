import { inputIsNotEmpty, registerInputWatcher } from '../helpers/form';
import http from '../http';

const taskToggle = () => {
  const tasks = $('.tasks-list');
  
  tasks.on('click', '.taskToggle', function (e) {
    e.preventDefault();
    const button = $(this);
    const taskCard = button.parents('.task-card');
    const taskId = taskCard.data('task-id');
    
    if (!taskId) {
      throw 'Task card must have data attribute task-id.';
    }
    
    http.post(`?module=tasks&action=toggle&task_id=${ taskId }`)
      .then(res => {
        const completed = res.data.is_completed;
  
        $.notifier.callSystem({
          type: completed ? 'done' : 'info',
          icon: 'check',
          text: completed ? 'Задача завершена!' : 'Задача перенесена в незавершенные!'
        });
        
        button.removeClass('btn-icon--danger btn-icon--success');
        button.addClass(completed ? 'btn-icon--danger' : 'btn-icon--success');
        button.attr('data-original-title', completed ? 'Перевести в незавершенные' : 'Завершить');
        
        const buttonIcon = button.find('span.material-icons');
        buttonIcon.html(completed ? 'unpublished' : 'assignment_turned_in');
      });
  });
};

const editTask = () => {

}

export default {
  install () {
    // Preview
    const previewModal = $('#taskPreviewModal');
    const previewModalContent = previewModal.find('.modal-body');
    const buttonClosePreview = $('#closePreview');
    
    taskToggle();
    
    // Fields
    const inputTaskId = $('#formTaskId');
    const inputTaskTitle = $('#formTaskTitle');
    const inputTaskPicture = $('#formTaskPicture');
    const taskEditor = $('#formTaskContent');
    
    const titleFormGroup = inputTaskTitle.parents('.form-group');
    
    // Activators
    const taskSaveButton = $('#taskSaveButton');
    const buttonTaskPreview = $('#buttonTaskPreview');
    
    // Init summernote
    taskEditor.summernote({
      placeholder: 'Опишите подробно суть задачи',
      tabsize: 2,
      height: 250,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['codeview', 'help']]
      ]
    });
    
    // Show preview
    buttonTaskPreview.click(e => {
      e.preventDefault();
      
      previewModal.modal('show');
      previewModalContent.html(taskEditor.summernote('code'));
    });
    
    // Close preview
    buttonClosePreview.click(e => {
      e.preventDefault();
      
      previewModal.modal('hide');
    });
    
    let titleValid = true;
    
    // Save task
    taskSaveButton.click(e => {
      e.preventDefault();
      
      titleValid = inputIsNotEmpty(inputTaskTitle, titleFormGroup);
      
      if (!titleValid) {
        return false;
      }
      
      const formData = new FormData();
      
      if (inputTaskPicture[0].files[0]) {
        formData.append('picture', inputTaskPicture[0].files[0]);
      }
      
      const taskId = inputTaskId.val() ? inputTaskId.val() : null;
      
      formData.append('content', taskEditor.summernote('code'));
      formData.append('id', taskId);
      formData.append('title', inputTaskTitle.val());
      
      http.post('?module=tasks&action=touch', formData)
        .then(res => {
          $.notifier.callSystem({
            type: 'done',
            icon: 'check',
            text: 'Задача успешно добавлена!'
          });
        })
        .catch(e => {
          $.notifier.callSystem({
            type: 'error',
            icon: 'close',
            text: 'Произошла ошибка!'
          });
        })
        .finally(() => {
          setTimeout(() => {
            document.location.reload();
          }, 800)
        })
    });
    
    registerInputWatcher(titleFormGroup, inputTaskTitle);
  }
};
