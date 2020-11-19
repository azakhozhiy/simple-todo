import Axios from 'axios';

Axios.defaults.timeout = 10000;

const http = Axios.create({
  headers: {
    common: {
      'Content-Type': 'multipart/form-data',
      'Cache-Control': 'no-cache, no-store, must-revalidate',
      Pragma: 'no-cache',
      Expires: '0'
    },
    get: {
      'Content-Type': 'application/x-www-form-urlencoded'
    }
  }
});

http.interceptors.response.use(
  response => {
    return response;
  },
  error => {
    return Promise.reject({
      error: error,
    });
  }
);

export default http;
