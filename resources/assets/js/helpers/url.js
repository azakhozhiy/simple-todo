export function getQueryObject () {
  const uri = window.location.href.split('?');
  if (uri.length === 2) {
    let vars = uri[1].split('&');
    const data = {};
    let tmp = '';
    vars.forEach(function (v) {
      tmp = v.split('=');
      if (tmp.length === 2) {
        data[tmp[0]] = tmp[1];
      }
    });
    
    return data;
  }
}
