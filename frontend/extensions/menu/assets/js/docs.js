// jscs:disable requireCamelCaseOrUpperCaseIdentifiers

'use strict';

$(function() {
  
  $('#scroll_top').on('click', function() {
    this.disabled = true;

    // 'html' for Mozilla Firefox, 'body' for other browsers
    $('body, html').animate({
      scrollTop: 0
    }, 800, $.proxy(function() {
      this.disabled = false;
    }, this));

    this.blur();
  });

  // Dropdown fix
  $('.dropdown > a[tabindex]').on('keydown', function(event) {
    // 13: Return

    if (event.keyCode == 13) {
      $(this).dropdown('toggle');
    }
  });

  // Предотвращаем закрытие при клике на неактивный элемент списка
  $('.dropdown-menu > .disabled, .dropdown-header').on('click.bs.dropdown.data-api', function(event) {
    event.stopPropagation();
  });

  $('[data-submenu]').submenupicker();
  
});
