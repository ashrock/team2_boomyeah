
  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems);

    var select = document.querySelectorAll('select');
    var dropdown = M.FormSelect.init(select);
  });
