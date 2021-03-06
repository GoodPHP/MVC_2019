(function ($) {

  $.fn.scrollTo = function (elem) {

    const $this = $(this);
    $this.scrollTop($this.scrollTop() - $this.offset().top + $(elem).offset().top);
    return this;
  };

  $.fn.dropdown = function (option) {

    this.each(function () {

      const origin = $(this);
      const options = $.extend({}, $.fn.dropdown.defaults, option);
      let isFocused = false;

      // Dropdown menu
      const activates = $(`#${origin.attr('data-activates')}`);

      function updateOptions() {

        if (origin.data('induration') !== undefined) {
          options.inDuration = origin.data('inDuration');
        }
        if (origin.data('outduration') !== undefined) {
          options.outDuration = origin.data('outDuration');
        }
        if (origin.data('constrainwidth') !== undefined) {
          options.constrain_width = origin.data('constrainwidth');
        }
        if (origin.data('hover') !== undefined) {
          options.hover = origin.data('hover');
        }
        if (origin.data('gutter') !== undefined) {
          options.gutter = origin.data('gutter');
        }
        if (origin.data('beloworigin') !== undefined) {
          options.belowOrigin = origin.data('beloworigin');
        }
        if (origin.data('alignment') !== undefined) {
          options.alignment = origin.data('alignment');
        }
      }

      updateOptions();

      // Attach dropdown to its activator
      origin.after(activates);

      /*
        Helper function to position and resize dropdown.
        Used in hover and click handler.
      */
      function placeDropdown(eventType) {

        // Check for simultaneous focus and click events.
        if (eventType === 'focus') {
          isFocused = true;
        }

        // Check html data attributes
        updateOptions();

        // Set Dropdown state
        activates.addClass('active');
        origin.addClass('active');

        // Constrain width
        if (options.constrain_width === true) {

          activates.css('width', origin.outerWidth());
        } else {

          activates.css('white-space', 'nowrap');
        }

        // Offscreen detection
        const windowHeight = window.innerHeight;
        const originHeight = origin.innerHeight();
        const offsetLeft = origin.offset().left;
        const offsetTop = origin.offset().top - $(window).scrollTop();
        let currAlignment = options.alignment;
        let gutterSpacing = 0;
        let leftPosition = 0;

        // Below Origin
        let verticalOffset = 0;
        if (options.belowOrigin === true) {
          verticalOffset = originHeight;
        }

        // Check for scrolling positioned container.
        let scrollOffset = 0;
        const wrapper = origin.parent();
        if (!wrapper.is('body') && wrapper[0].scrollHeight > wrapper[0].clientHeight) {

          scrollOffset = wrapper[0].scrollTop;
        }


        if (offsetLeft + activates.innerWidth() > $(window).width()) {

        // Dropdown goes past screen on right, force right alignment
          currAlignment = 'right';
        } else if (offsetLeft - activates.innerWidth() + origin.innerWidth() < 0) {

        // Dropdown goes past screen on left, force left alignment
          currAlignment = 'left';
        }
        // Vertical bottom offscreen detection
        if (offsetTop + activates.innerHeight() > windowHeight) {

          // If going upwards still goes offscreen, just crop height of dropdown.
          if (offsetTop + originHeight - activates.innerHeight() < 0) {

            const adjustedHeight = windowHeight - offsetTop - verticalOffset;
            activates.css('max-height', adjustedHeight);
          } else {

            // Flow upwards.
            if (!verticalOffset) {
              verticalOffset += originHeight;
            }
            verticalOffset -= activates.innerHeight();
          }
        }

        // Handle edge alignment
        if (currAlignment === 'left') {

          gutterSpacing = options.gutter;
          leftPosition = origin.position().left + gutterSpacing;
        } else if (currAlignment === 'right') {

          const offsetRight = origin.position().left + origin.outerWidth() - activates.outerWidth();
          gutterSpacing = -options.gutter;
          leftPosition =  offsetRight + gutterSpacing;
        }

        // Position dropdown
        activates.css({
          position: 'absolute',
          top: origin.position().top + verticalOffset + scrollOffset,
          left: leftPosition
        });


        // Show dropdown
        activates.stop(true, true).css('opacity', 0)
          .slideDown({
            queue: false,
            duration: options.inDuration,
            easing: 'easeOutCubic',
            complete() {
              $(this).css('height', '');
            }
          })
          .animate({
            opacity: 1,
            scrollTop: 0
          }, {
            queue: false,
            duration: options.inDuration,
            easing: 'easeOutSine'
          });
      }

      function hideDropdown() {

        // Check for simultaneous focus and click events.
        isFocused = false;
        activates.fadeOut(options.outDuration);
        activates.removeClass('active');
        origin.removeClass('active');
        setTimeout(() => {
          activates.css('max-height', '');
        }, options.outDuration);
      }

      // Hover
      if (options.hover) {

        let open = false;
        origin.unbind(`click.${origin.attr('id')}`);
        // Hover handler to show dropdown
        origin.on('mouseenter', () => { // Mouse over

          if (open === false) {

            placeDropdown();
            open = true;
          }
        });
        origin.on('mouseleave', (e) => {

          // If hover on origin then to something other than dropdown content, then close
          const toEl = e.toElement || e.relatedTarget; // added browser compatibility for target element
          if (!$(toEl).closest('.dropdown-content').is(activates)) {

            activates.stop(true, true);
            hideDropdown();
            open = false;
          }
        });

        activates.on('mouseleave', (e) => { // Mouse out

          const toEl = e.toElement || e.relatedTarget;
          if (!$(toEl).closest('.dropdown-button').is(origin)) {

            activates.stop(true, true);
            hideDropdown();
            open = false;
          }
        });

        // Click
      } else {

        // Click handler to show dropdown
        origin.unbind(`click.${origin.attr('id')}`);
        origin.bind(`click.${origin.attr('id')}`, (e) => {

          if (!isFocused) {

            if (origin[0] === e.currentTarget && !origin.hasClass('active') && $(e.target).closest('.dropdown-content').length === 0) {

              e.preventDefault(); // Prevents button click from moving window
              placeDropdown('click');
            } else if (origin.hasClass('active')) { // If origin is clicked and menu is open, close menu

              hideDropdown();
              $(document).unbind(`click.${activates.attr('id')} touchstart.${activates.attr('id')}`);
            }
            // If menu open, add click close handler to document
            if (activates.hasClass('active')) {

              $(document).bind(`click.${activates.attr('id')} touchstart.${activates.attr('id')}`, (e) => {

                if (!activates.is(e.target) && !origin.is(e.target) && !origin.find(e.target).length) {

                  hideDropdown();
                  $(document).unbind(`click.${activates.attr('id')} touchstart.${activates.attr('id')}`);
                }
              });
            }
          }
        });

      }

      origin.on('open', (e, eventType) => {

        placeDropdown(eventType);
      });

      origin.on('close', hideDropdown);
    });
  };

  $.fn.dropdown.defaults = {
    inDuration: 300,
    outDuration: 225,
    constrain_width: true,
    hover: false,
    gutter: 0,
    belowOrigin: false,
    alignment: 'left'
  };

  $('.dropdown-button').dropdown();

}(jQuery));


const dropdownSelectors = $('.dropdown, .dropup');

// Custom function to read dropdown data
function dropdownEffectData(target) {

  // TODO - page level global?
  let effectInDefault = 'fadeIn';
  let effectOutDefault = 'fadeOut';
  const dropdown = $(target);
  const dropdownMenu = $('.dropdown-menu', target);
  const parentUl = dropdown.parents('ul.nav');

  // If parent is ul.nav allow global effect settings
  if (parentUl.height > 0) {

    effectInDefault = parentUl.data('dropdown-in') || null;
    effectOutDefault = parentUl.data('dropdown-out') || null;
  }

  return {
    target,
    dropdown,
    dropdownMenu,
    effectIn: dropdownMenu.data('dropdown-in') || effectInDefault,
    effectOut: dropdownMenu.data('dropdown-out') || effectOutDefault
  };
}

// Custom function to start effect (in or out)
function dropdownEffectStart(data, effectToStart) {

  if (effectToStart) {

    data.dropdown.addClass('dropdown-animating');
    data.dropdownMenu.addClass(['animated', effectToStart].join(' '));
  }
}

// Custom function to read when animation is over
function dropdownEffectEnd(data, callbackFunc) {

  const animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
  data.dropdown.one(animationEnd, () => {

    data.dropdown.removeClass('dropdown-animating');
    data.dropdownMenu.removeClass(['animated', data.effectIn, data.effectOut].join(' '));

    // Custom callback option, used to remove open class in out effect
    if (typeof callbackFunc === 'function') {

      callbackFunc();
    }
  });
}

// Bootstrap API hooks
dropdownSelectors.on({
  'show.bs.dropdown'() {
    // On show, start in effect
    const dropdown = dropdownEffectData(this);
    dropdownEffectStart(dropdown, dropdown.effectIn);
  },
  'shown.bs.dropdown'() {
    // On shown, remove in effect once complete
    const dropdown = dropdownEffectData(this);
    if (dropdown.effectIn && dropdown.effectOut) {
      dropdownEffectEnd(dropdown);
    }
  },
  'hide.bs.dropdown'(e) {
    // On hide, start out effect
    const dropdown = dropdownEffectData(this);
    if (dropdown.effectOut) {

      e.preventDefault();
      dropdownEffectStart(dropdown, dropdown.effectOut);
      dropdownEffectEnd(dropdown, () => {

        dropdown.dropdown.removeClass('show');
        dropdown.dropdownMenu.removeClass('show');
      });
    }
  }
});
