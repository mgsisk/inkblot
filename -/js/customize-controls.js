// Generated by CoffeeScript 1.9.2
(function() {
  (function($) {
    var inkblot_toggle_controls;
    inkblot_toggle_controls = function(settingId, object) {
      return wp.customize(settingId, function(setting) {
        return $.each(object.controls, function(index, controlId) {
          return wp.customize.control(controlId, function(control) {
            var visibility;
            visibility = function(to) {
              return control.container.toggle(object.callback(to));
            };
            visibility(setting.get());
            return setting.bind(visibility);
          });
        });
      });
    };
    $.each({
      content: {
        controls: ['sidebar1_width'],
        callback: function(to) {
          return 'one-column' !== to;
        }
      },
      page_background_image: {
        controls: ['page_background_repeat', 'page_background_position_x', 'page_background_attachment'],
        callback: function(to) {
          return !!to;
        }
      },
      trim_background_image: {
        controls: ['trim_background_repeat', 'trim_background_position_x', 'trim_background_attachment'],
        callback: function(to) {
          return !!to;
        }
      }
    }, function(settingId, object) {
      return inkblot_toggle_controls(settingId, object);
    });
    $.each({
      content: {
        controls: ['sidebar2_width'],
        callback: function(to) {
          return -1 !== to.indexOf('three') || -1 !== to.indexOf('four');
        }
      }
    }, function(settingId, object) {
      return inkblot_toggle_controls(settingId, object);
    });
    $.each({
      content: {
        controls: ['sidebar3_width'],
        callback: function(to) {
          return -1 !== to.indexOf('four');
        }
      }
    }, function(settingId, object) {
      return inkblot_toggle_controls(settingId, object);
    });
    $(document).on('change', '#customize-control-layout_theme select', function(event) {
      return $.each($('wbr.inkblot-theme-layout.' + $(this).val()).data(), function(index, value) {
        index = index.replace(/([A-Z])/g, function(string) {
          return '_' + string.toLowerCase();
        });
        return wp.customize(index).set(value);
      });
    });
    $(document).on('change', '#customize-control-font_theme select', function(event) {
      return $.each($('wbr.inkblot-theme-font.' + $(this).val()).data(), function(index, value) {
        index = index.replace(/([A-Z])/g, function(string) {
          return '_' + string.toLowerCase();
        });
        return wp.customize(index).set(value);
      });
    });
    $(document).on('change', '#customize-control-color_theme select', function(event) {
      return $.each($('wbr.inkblot-theme-color.' + $(this).val()).data(), function(index, value) {
        index = index.replace(/([A-Z])/g, function(string) {
          return '_' + string.toLowerCase();
        });
        wp.customize(index).set(value);
        return wp.customize.control(index).container.find('.color-picker-hex').data('data-default-color', value).wpColorPicker('defaultColor', value);
      });
    });
    return $(document).on('click', function(event) {
      return $.each(['primary-sidebar', 'secondary-sidebar', 'tertiary-sidebar', 'document-header', 'document-footer', 'site-header', 'site-footer', 'page-header', 'page-footer', 'content-header', 'content-footer', 'comment-header', 'comment-footer', 'webcomic-header', 'webcomic-footer', 'webcomic-navigation-header', 'webcomic-navigation-footer'], function(index, value) {
        return $('#customize-control-sidebar-' + value + '-columns').show();
      });
    });
  })(jQuery);

}).call(this);
