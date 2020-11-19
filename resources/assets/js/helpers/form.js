export function registerInputWatcher (formGroup, input, callback = null) {
  $(document).on('input', formGroup[0], e => {
    if (input.val()) {
      formGroup.removeClass('has-danger');
      input.removeClass('is-invalid');
      const invalidFeedback = formGroup.find('.invalid-feedback');
      if (invalidFeedback) {
        invalidFeedback.remove();
      }
      
      if(callback){
        callback()
      }
    }
  });
}

export function inputIsNotEmpty (input, formGroup, message = 'Обязательное поле') {
  if (!input.val()) {
    formGroup.addClass('has-danger');
    input.addClass('is-invalid');
    formGroup.append(`<div class="invalid-feedback">${ message }</div>`);
    return false;
  }
  
  return true;
}

export function inputCheckCountSymbols (input, formGroup, minSymbols, errorText = null, regExp = null) {
  const checkCount = inputIsNotEmpty(input, formGroup);
  
  if (!errorText) {
    errorText = `Введите минимум ${ minSymbols } символов.`;
  }
  
  const inputValue = regExp ? input.val().match(regExp).join('') : input.val();
  
  if (checkCount) {
    if (inputValue.length < minSymbols) {
      formGroup.addClass('has-danger');
      input.addClass('is-invalid');
      formGroup.append(`<div class="invalid-feedback">${ errorText }</div>`);
      return false;
    }
  }
  
  return checkCount;
};
