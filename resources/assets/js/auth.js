import http from './http';
import { inputCheckCountSymbols, inputIsNotEmpty, registerInputWatcher } from './helpers/form';

export default {
  install () {
    const authModal = $('#authModal');
    const authButton = $('#authButton');
    const badAuthAlert = $('#badAuth');
    const successAuthAlert = $('#successAuth');
    
    const inputLogin = $('#userLogin');
    const formGroupLogin = inputLogin.parents('.form-group');
    
    const inputPassword = $('#userPassword');
    const formGroupPassword = inputPassword.parents('.form-group');
    
    let loginValid = true;
    let passwordValid = true;
    
    authButton.click(e => {
      loginValid = inputIsNotEmpty(inputLogin, formGroupLogin,);
      
      passwordValid = inputCheckCountSymbols(
        inputPassword,
        formGroupPassword
      );
      
      if (!loginValid || !passwordValid) {
        return false;
      }
      
      const data = {
        login: inputLogin.val(),
        password: inputPassword.val(),
      };
      
      http.post('/?module=auth&action=login', data)
        .then(res => {
          successAuthAlert.css('display', 'block');
          setTimeout(() => {
            window.location.href = '/';
          }, 700);
        })
        .catch(e => {
          inputPassword.val('');
          inputLogin.val('');
          badAuthAlert.css('display', 'block');
          
        });
      
    });
    
    const hideBadAuth = () => {
      badAuthAlert.css('display', 'none');
    };
    
    registerInputWatcher(formGroupLogin, inputLogin, hideBadAuth);
    registerInputWatcher(formGroupPassword, inputPassword, hideBadAuth);
  }
};
