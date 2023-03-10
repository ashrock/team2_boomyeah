
  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.modal');
    var show_modal = M.Modal.init(elems);

    var invite = document.querySelectorAll('.invite_people');
    var show_invite = M.Modal.init(invite);

    var drop = document.querySelectorAll('.role');
    var show_dropdown = M.Dropdown.init(drop);

    var users = document.querySelectorAll('.users');
    var checkbox =  M.Dropdown.init(users, {closeOnClick:false});

    var emails = document.querySelectorAll('.chips');
    var add_chips = M.Chips.init(emails);
  });