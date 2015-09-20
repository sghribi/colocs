/**
 * Website utils
 */

$(document).ready(function() {
  // NProgress bar
  NProgress.start();
  var interval = setInterval(function() { NProgress.inc(); }, 1000);
  $(window).load(function () {
    clearInterval(interval);
    NProgress.done();
  });
  $(window).unload(function () {
    NProgress.start();
  });

  // Enable bootstrap tooltips and popover
  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();
  });

  // Add spinner on submit
  $('form.spinner').each(function() {
    var $submitButton = $(this).find('[type=submit]');
    $(this).on('submit',function(e) {
      $submitButton.addClass('disabled');
      var $faIcon = $submitButton.find('i');
      $faIcon.removeClass().addClass('fa fa-spinner fa-fw fa-spin');
    });
  });

  // Menu
  $('.toggle-menu').jPushMenu();
});

// Disable 300ms delay on mobile devices
$(function() {
  FastClick.attach(document.body);
});
