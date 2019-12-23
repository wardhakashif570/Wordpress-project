(function ($) {
  'use strict';

  if ($.fn.wpColorPicker) {
    $('input.rtwpvs-color-picker').wpColorPicker();
  }

  jQuery(document).ajaxComplete(function (event, request, options) {
    if (request && 4 === request.readyState && 200 === request.status && options.data && 0 <= options.data.indexOf('action=add-tag')) {
      var res = wpAjax.parseAjaxResponse(request.responseXML, 'ajax-response');

      if (!res || res.errors) {
        return;
      }

      if ($.fn.wpColorPicker) {
        $('input.rtwpvs-color-picker').wpColorPicker();
      }

      $('button.rtwpvs-remove-image').trigger('click');
      return;
    }
  });
  $(document).on('click', 'button.rtwpvs-upload-image', function () {
    event.stopPropagation();
    var file_frame;
    var self = $(this);

    if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
      if (file_frame) {
        file_frame.open();
        return;
      }

      file_frame = wp.media.frames.select_image = wp.media({
        title: rtwpvs.media_title,
        button: {
          text: rtwpvs.button_title
        },
        multiple: false
      });
      file_frame.on('select', function () {
        var attachment = file_frame.state().get('selection').first().toJSON();

        if ($.trim(attachment.id) !== '') {
          var url = typeof attachment.sizes.thumbnail === 'undefined' ? attachment.sizes.full.url : attachment.sizes.thumbnail.url;
          self.prev().val(attachment.id);
          self.closest('.rtwpvs-image-wrapper').find('img').attr('src', url);
          self.next().show();
        }
      });
      file_frame.on('open', function () {
        var selection = file_frame.state().get('selection');
        var current = self.prev().val();
        var attachment = wp.media.attachment(current);
        attachment.fetch();
        selection.add(attachment ? [attachment] : []);
      });
      file_frame.open();
    }
  });
  $(document).on('click', 'button.rtwpvs-remove-image', function () {
    event.preventDefault();
    event.stopPropagation();
    var self = $(this),
        placeholder = self.closest('.rtwpvs-image-wrapper').find('img').data('placeholder');
    self.closest('.rtwpvs-image-wrapper').find('img').attr('src', placeholder);
    self.prev().prev().val('');
    self.hide();
    return false;
  });
  $('#rtwpvs-settings-wrapper').on('click', '.nav-tab', function (event) {
    event.preventDefault();
    var target = $(this).data('target');
    $(this).addClass('nav-tab-active').siblings().removeClass('nav-tab-active');
    $('#' + target).show().siblings().hide();
    $('#_last_active_tab').val(target);

    if (history.pushState) {
      var newurl = setGetParameter('section', target);
      window.history.pushState({
        path: newurl
      }, '', newurl);
    }
  });

  function setGetParameter(paramName, paramValue) {
    var url = window.location.href;
    var hash = location.hash;
    url = url.replace(hash, '');

    if (url.indexOf("?") >= 0) {
      var params = url.substring(url.indexOf("?") + 1).split("&");
      var paramFound = false;
      params.forEach(function (param, index) {
        var p = param.split("=");

        if (p[0] == paramName) {
          params[index] = paramName + "=" + paramValue;
          paramFound = true;
        }
      });
      if (!paramFound) params.push(paramName + "=" + paramValue);
      url = url.substring(0, url.indexOf("?") + 1) + params.join("&");
    } else url += "?" + paramName + "=" + paramValue;

    return url + hash;
  }
})(jQuery);
