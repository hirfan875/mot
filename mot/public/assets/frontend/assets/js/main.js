 jQuery(document).ready(function() {
                var offset = 220;
                var duration = 500;
                jQuery(window).scroll(function() {
                    if (jQuery(this).scrollTop() > offset) {
                        jQuery('.up-btn').fadeIn(duration);
                    } else {
                        jQuery('.up-btn').fadeOut(duration);
                    }
                });

                jQuery('.up-btn').click(function(event) {
                    event.preventDefault();
                    jQuery('html, body').animate({scrollTop: 0}, duration);
                    return false;
                })
            });

$(document).ready(function() {
              var owl = $('.trending-products');
              owl.owlCarousel({
                margin: 10,
                nav: true,
                loop: false,
                responsive: {
                  0: {
                    items: 2
                  },
                  600: {
                    items: 3
                  },
                  1000: {
                    items: 5
                  }
                }
              })
            });

            $(document).ready(function() {
              var owl = $('.flashdeals');
              owl.owlCarousel({
                margin: 10,
                nav: false,
                loop: false,
                responsive: {
                  0: {
                    items: 2
                  },
                  600: {
                    items: 2
                  },
                  1000: {
                    items: 4
                  }
                }
              })
            });

$(document).ready(function() {
              var owl1 = $('#daideals');
              owl1.owlCarousel({
                margin: 10,
                nav: true,
                loop: true,
                responsive: {
                  0: {
                    items: 2
                  },
                  600: {
                    items: 3
                  },
                  1000: {
                    items: 4
                  }
                }
                
              })
  })
  $(document).ready(function() {
    $(".filterin").click(function() {
      $(".products_filters, .products_sidebar_menu").toggle();
    });
    $(".search-fm").hide();
    $(".mini-search a").click(function() {
      $(".search-fm").toggle();
    });

  })
  $(document).ready(function() {
      jQuery('#showRight').click(function() {
        jQuery('.menu-right').toggleClass('right-open');
    });
    jQuery('.backBtn').click(function() {
        jQuery('.menu').removeClass('top-open bottom-open right-open left-open pushleft-open pushright-open');
        jQuery('body').removeClass('push-toleft push-toright');
    });
  })


    $(document).ready(function() {
      jQuery('.filterin2').click(function() {
        jQuery('.products_sidebar_menu').toggleClass('right-open');
    });
    jQuery('.side_bar_close').click(function() {
        jQuery('.products_sidebar_menu').removeClass('right-open ');
    });
  })



 $(document).ready(function() {
      jQuery('.open_product_attr').click(function() {
        jQuery('.products_atr_block').toggleClass('right-open');
    });
    jQuery('.close_product_attr').click(function() {
        jQuery('.products_atr_block').removeClass('right-open ');
    });
  })



//   $(document).ready(function() {
//     var owl2 = $('.flashdeals');
//     owl2.owlCarousel({
//       margin: 10,
//       nav: true,
//       loop: true,
//       responsive: {
//         0: {
//           items: 2
//         },
//         600: {
//           items: 3
//         },
//         1000: {
//           items: 4
//         }
//       }
//     })
// })
$(document).ready(function() {
              var owl = $('#owl-carousel2');
              owl.owlCarousel({
                margin: 10,
                nav: true,
                loop: true,
                responsive: {
                  0: {
                    items: 1
                  },
                  600: {
                    items: 3
                  },
                  1000: {
                    items: 5
                  }
                }
              })
            })




  $(".tbUser").on('click','.btnDelete',function(){
       $(this).closest('tr').remove();
     });


     /*$('#datepicker').datepicker({
            uiLibrary: 'bootstrap4'
        });*/

     $(function() {
  $('#form-tags-1').tagsInput();

  $('#form-tags-2').tagsInput({
    'onAddTag': function(input, value) {
      console.log('tag added', input, value);
    },
    'onRemoveTag': function(input, value) {
      console.log('tag removed', input, value);
    },
    'onChange': function(input, value) {
      console.log('change triggered', input, value);
    }
  });

  $('#form-tags-3').tagsInput({
    'unique': true,
    'minChars': 2,
    'maxChars': 10,
    'limit': 5,
    'validationPattern': new RegExp('^[a-zA-Z]+$')
  });

  $('#form-tags-4').tagsInput({
    'autocomplete': {
      source: [
        'apple',
        'banana',
        'orange',
        'pizza'
      ]
    }
  });

  $('#form-tags-5').tagsInput({
    'delimiter': ';'
  });

  $('#form-tags-6').tagsInput({
    'delimiter': [',', ';']
  });
});



/* jQuery Tags Input Revisited Plugin
 *
 * Copyright (c) Krzysztof Rusnarczyk
 * Licensed under the MIT license */

(function($) {
  var delimiter = [];
  var inputSettings = [];
  var callbacks = [];

  $.fn.addTag = function(value, options) {
    options = jQuery.extend({
      focus: false,
      callback: true
    }, options);

    this.each(function() {
      var id = $(this).attr('id');

      var tagslist = $(this).val().split(_getDelimiter(delimiter[id]));
      if (tagslist[0] === '') tagslist = [];

      value = jQuery.trim(value);

      if ((inputSettings[id].unique && $(this).tagExist(value)) || !_validateTag(value, inputSettings[id], tagslist, delimiter[id])) {
        $('#' + id + '_tag').addClass('error');
        return false;
      }

      $('<span>', {class: 'tag'}).append(
        $('<span>', {class: 'tag-text'}).text(value),
        $('<button>', {class: 'tag-remove'}).click(function() {
          return $('#' + id).removeTag(encodeURI(value));
        })
      ).insertBefore('#' + id + '_addTag');

      tagslist.push(value);

      $('#' + id + '_tag').val('');
      if (options.focus) {
        $('#' + id + '_tag').focus();
      } else {
        $('#' + id + '_tag').blur();
      }

      $.fn.tagsInput.updateTagsField(this, tagslist);

      if (options.callback && callbacks[id] && callbacks[id]['onAddTag']) {
        var f = callbacks[id]['onAddTag'];
        f.call(this, this, value);
      }

      if (callbacks[id] && callbacks[id]['onChange']) {
        var i = tagslist.length;
        var f = callbacks[id]['onChange'];
        f.call(this, this, value);
      }
    });

    return false;
  };

  $.fn.removeTag = function(value) {
    value = decodeURI(value);

    this.each(function() {
      var id = $(this).attr('id');

      var old = $(this).val().split(_getDelimiter(delimiter[id]));

      $('#' + id + '_tagsinput .tag').remove();

      var str = '';
      for (i = 0; i < old.length; ++i) {
        if (old[i] != value) {
          str = str + _getDelimiter(delimiter[id]) + old[i];
        }
      }

      $.fn.tagsInput.importTags(this, str);

      if (callbacks[id] && callbacks[id]['onRemoveTag']) {
        var f = callbacks[id]['onRemoveTag'];
        f.call(this, this, value);
      }
    });

    return false;
  };

  $.fn.tagExist = function(val) {
    var id = $(this).attr('id');
    var tagslist = $(this).val().split(_getDelimiter(delimiter[id]));
    return (jQuery.inArray(val, tagslist) >= 0);
  };

  $.fn.importTags = function(str) {
    var id = $(this).attr('id');
    $('#' + id + '_tagsinput .tag').remove();
    $.fn.tagsInput.importTags(this, str);
  };

  $.fn.tagsInput = function(options) {
    var settings = jQuery.extend({
      interactive: true,
      placeholder: 'Add a tag',
      minChars: 0,
      maxChars: null,
      limit: null,
      validationPattern: null,
      width: 'auto',
      height: 'auto',
      autocomplete: null,
      hide: true,
      delimiter: ',',
      unique: true,
      removeWithBackspace: true
    }, options);

    var uniqueIdCounter = 0;

    this.each(function() {
      if (typeof $(this).data('tagsinput-init') !== 'undefined') return;

      $(this).data('tagsinput-init', true);

      if (settings.hide) $(this).hide();

      var id = $(this).attr('id');
      if (!id || _getDelimiter(delimiter[$(this).attr('id')])) {
        id = $(this).attr('id', 'tags' + new Date().getTime() + (++uniqueIdCounter)).attr('id');
      }

      var data = jQuery.extend({
        pid: id,
        real_input: '#' + id,
        holder: '#' + id + '_tagsinput',
        input_wrapper: '#' + id + '_addTag',
        fake_input: '#' + id + '_tag'
      }, settings);

      delimiter[id] = data.delimiter;
      inputSettings[id] = {
        minChars: settings.minChars,
        maxChars: settings.maxChars,
        limit: settings.limit,
        validationPattern: settings.validationPattern,
        unique: settings.unique
      };

      if (settings.onAddTag || settings.onRemoveTag || settings.onChange) {
        callbacks[id] = [];
        callbacks[id]['onAddTag'] = settings.onAddTag;
        callbacks[id]['onRemoveTag'] = settings.onRemoveTag;
        callbacks[id]['onChange'] = settings.onChange;
      }

      var markup = $('<div>', {id: id + '_tagsinput', class: 'tagsinput'}).append(
        $('<div>', {id: id + '_addTag'}).append(
          settings.interactive ? $('<input>', {id: id + '_tag', class: 'tag-input', value: '', placeholder: settings.placeholder}) : null
        )
      );

      $(markup).insertAfter(this);

      $(data.holder).css('width', settings.width);
      $(data.holder).css('min-height', settings.height);
      $(data.holder).css('height', settings.height);

      if ($(data.real_input).val() !== '') {
        $.fn.tagsInput.importTags($(data.real_input), $(data.real_input).val());
      }

      // Stop here if interactive option is not chosen
      if (!settings.interactive) return;

      $(data.fake_input).val('');
      $(data.fake_input).data('pasted', false);

      $(data.fake_input).on('focus', data, function(event) {
        $(data.holder).addClass('focus');

        if ($(this).val() === '') {
          $(this).removeClass('error');
        }
      });

      $(data.fake_input).on('blur', data, function(event) {
        $(data.holder).removeClass('focus');
      });

      if (settings.autocomplete !== null && jQuery.ui.autocomplete !== undefined) {
        $(data.fake_input).autocomplete(settings.autocomplete);
        $(data.fake_input).on('autocompleteselect', data, function(event, ui) {
          $(event.data.real_input).addTag(ui.item.value, {
            focus: true,
            unique: settings.unique
          });

          return false;
        });

        $(data.fake_input).on('keypress', data, function(event) {
          if (_checkDelimiter(event)) {
            $(this).autocomplete("close");
          }
        });
      } else {
        $(data.fake_input).on('blur', data, function(event) {
          $(event.data.real_input).addTag($(event.data.fake_input).val(), {
            focus: true,
            unique: settings.unique
          });

          return false;
        });
      }

      // If a user types a delimiter create a new tag
      $(data.fake_input).on('keypress', data, function(event) {
        if (_checkDelimiter(event)) {
          event.preventDefault();

          $(event.data.real_input).addTag($(event.data.fake_input).val(), {
            focus: true,
            unique: settings.unique
          });

          return false;
        }
      });

      $(data.fake_input).on('paste', function () {
        $(this).data('pasted', true);
      });

      // If a user pastes the text check if it shouldn't be splitted into tags
      $(data.fake_input).on('input', data, function(event) {
        if (!$(this).data('pasted')) return;

        $(this).data('pasted', false);

        var value = $(event.data.fake_input).val();

        value = value.replace(/\n/g, '');
        value = value.replace(/\s/g, '');

        var tags = _splitIntoTags(event.data.delimiter, value);

        if (tags.length > 1) {
          for (var i = 0; i < tags.length; ++i) {
            $(event.data.real_input).addTag(tags[i], {
              focus: true,
              unique: settings.unique
            });
          }

          return false;
        }
      });

      // Deletes last tag on backspace
      data.removeWithBackspace && $(data.fake_input).on('keydown', function(event) {
        if (event.keyCode == 8 && $(this).val() === '') {
           event.preventDefault();
           var lastTag = $(this).closest('.tagsinput').find('.tag:last > span').text();
           var id = $(this).attr('id').replace(/_tag$/, '');
           $('#' + id).removeTag(encodeURI(lastTag));
           $(this).trigger('focus');
        }
      });

      // Removes the error class when user changes the value of the fake input
      $(data.fake_input).keydown(function(event) {
        // enter, alt, shift, esc, ctrl and arrows keys are ignored
        if (jQuery.inArray(event.keyCode, [13, 37, 38, 39, 40, 27, 16, 17, 18, 225]) === -1) {
          $(this).removeClass('error');
        }
      });
    });

    return this;
  };

  $.fn.tagsInput.updateTagsField = function(obj, tagslist) {
    var id = $(obj).attr('id');
    $(obj).val(tagslist.join(_getDelimiter(delimiter[id])));
  };

  $.fn.tagsInput.importTags = function(obj, val) {
    $(obj).val('');

    var id = $(obj).attr('id');
    var tags = _splitIntoTags(delimiter[id], val);

    for (i = 0; i < tags.length; ++i) {
      $(obj).addTag(tags[i], {
        focus: false,
        callback: false
      });
    }

    if (callbacks[id] && callbacks[id]['onChange']) {
      var f = callbacks[id]['onChange'];
      f.call(obj, obj, tags);
    }
  };

  var _getDelimiter = function(delimiter) {
    if (typeof delimiter === 'undefined') {
      return delimiter;
    } else if (typeof delimiter === 'string') {
      return delimiter;
    } else {
      return delimiter[0];
    }
  };

  var _validateTag = function(value, inputSettings, tagslist, delimiter) {
    var result = true;

    if (value === '') result = false;
    if (value.length < inputSettings.minChars) result = false;
    if (inputSettings.maxChars !== null && value.length > inputSettings.maxChars) result = false;
    if (inputSettings.limit !== null && tagslist.length >= inputSettings.limit) result = false;
    if (inputSettings.validationPattern !== null && !inputSettings.validationPattern.test(value)) result = false;

    if (typeof delimiter === 'string') {
      if (value.indexOf(delimiter) > -1) result = false;
    } else {
      $.each(delimiter, function(index, _delimiter) {
        if (value.indexOf(_delimiter) > -1) result = false;
        return false;
      });
    }

    return result;
  };

  var _checkDelimiter = function(event) {
    var found = false;

    if (event.which === 13) {
      return true;
    }

    if (typeof event.data.delimiter === 'string') {
      if (event.which === event.data.delimiter.charCodeAt(0)) {
        found = true;
      }
    } else {
      $.each(event.data.delimiter, function(index, delimiter) {
        if (event.which === delimiter.charCodeAt(0)) {
          found = true;
        }
      });
    }

    return found;
   };

   var _splitIntoTags = function(delimiter, value) {
     if (value === '') return [];

     if (typeof delimiter === 'string') {
       return value.split(delimiter);
     } else {
       var tmpDelimiter = '∞';
       var text = value;

       $.each(delimiter, function(index, _delimiter) {
         text = text.split(_delimiter).join(tmpDelimiter);
       });

       return text.split(tmpDelimiter);
     }

     return [];
   };
})(jQuery);



$(document).ready(function () {
    var itemsMainDiv = ('.MultiCarousel');
    var itemsDiv = ('.MultiCarousel-inner');
    var itemWidth = "";

    $('.leftLst, .rightLst').click(function () {
        var condition = $(this).hasClass("leftLst");
        if (condition)
            click(0, this);
        else
            click(1, this)
    });

    ResCarouselSize();




    $(window).resize(function () {
        ResCarouselSize();
    });

    //this function define the size of the items
    function ResCarouselSize() {
        var incno = 0;
        var dataItems = ("data-items");
        var itemClass = ('.item');
        var id = 0;
        var btnParentSb = '';
        var itemsSplit = '';
        var sampwidth = $(itemsMainDiv).width();
        var bodyWidth = $('body').width();
        $(itemsDiv).each(function () {
            id = id + 1;
            var itemNumbers = $(this).find(itemClass).length;
            btnParentSb = $(this).parent().attr(dataItems);
            itemsSplit = btnParentSb.split(',');
            $(this).parent().attr("id", "MultiCarousel" + id);


            if (bodyWidth >= 1200) {
                incno = itemsSplit[3];
                itemWidth = sampwidth / incno;
            }
            else if (bodyWidth >= 992) {
                incno = itemsSplit[2];
                itemWidth = sampwidth / incno;
            }
            else if (bodyWidth >= 768) {
                incno = itemsSplit[1];
                itemWidth = sampwidth / incno;
            }
            else {
                incno = itemsSplit[0];
                itemWidth = sampwidth / incno;
            }
            $(this).css({ 'transform': 'translateX(0px)', 'width': itemWidth * itemNumbers });
            $(this).find(itemClass).each(function () {
                $(this).outerWidth(itemWidth);
            });

            $(".leftLst").addClass("over");
            $(".rightLst").removeClass("over");

        });
    }


    //this function used to move the items
    function ResCarousel(e, el, s) {
        var leftBtn = ('.leftLst');
        var rightBtn = ('.rightLst');
        var translateXval = '';
        var divStyle = $(el + ' ' + itemsDiv).css('transform');
        var values = divStyle.match(/-?[\d\.]+/g);
        var xds = Math.abs(values[4]);
        if (e == 0) {
            translateXval = parseInt(xds) - parseInt(itemWidth * s);
            $(el + ' ' + rightBtn).removeClass("over");

            if (translateXval <= itemWidth / 2) {
                translateXval = 0;
                $(el + ' ' + leftBtn).addClass("over");
            }
        }
        else if (e == 1) {
            var itemsCondition = $(el).find(itemsDiv).width() - $(el).width();
            translateXval = parseInt(xds) + parseInt(itemWidth * s);
            $(el + ' ' + leftBtn).removeClass("over");

            if (translateXval >= itemsCondition - itemWidth / 2) {
                translateXval = itemsCondition;
                $(el + ' ' + rightBtn).addClass("over");
            }
        }
        $(el + ' ' + itemsDiv).css('transform', 'translateX(' + -translateXval + 'px)');
    }

    //It is used to get some elements from btn
    function click(ell, ee) {
        var Parent = "#" + $(ee).parent().attr("id");
        var slide = $(Parent).attr("data-slide");
        ResCarousel(ell, Parent, slide);
    }



});


