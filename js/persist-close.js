/**
 * @file persist-close.js
 *
 * Close and keep an alert closed
 */

"use strict";

(function ($, Drupal, drupalSettings) {

  var cookieName = 'emergency-alert';

  Drupal.behaviors.emergencyAlerts = {
    attach: function (context) {
      $('.emergency-alert .close-button').on('click', function() {
        setCookie(cookieName, 'closed', 1);
        $(this).closest('[data-closeable]').hide();
      });

      if (getCookie(cookieName) == 'closed') {
        $('.emergency-alert.closeable').hide();
      }
    }
  };

  function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = 'expires='+ d.toUTCString();
    document.cookie = cname + '=' + cvalue + '; ' + expires;
  }

  function getCookie(cname) {
    var name = cname + '=';
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return '';
}

})(jQuery, Drupal, drupalSettings);
