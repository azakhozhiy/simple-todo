export default {
  install () {
    const buttonTaskPreview = $('#buttonTaskPreview');
    const previewModal = $('#taskPreviewModal');
    const previewModalContent = previewModal.find('.modal-body');
    const buttonClosePreview = $('#closePreview');
    const taskEditor = $('#taskContent');
    
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
    
    buttonTaskPreview.click(e => {
      e.preventDefault();
      
      previewModal.modal('show');
      previewModalContent.html(taskEditor.summernote('code'));
    });
    
    buttonClosePreview.click(e => {
      e.preventDefault();
      
      previewModal.modal('hide');
    });
  }
};
