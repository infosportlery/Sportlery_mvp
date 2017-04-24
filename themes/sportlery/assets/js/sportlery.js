require('bootstrap-sass/assets/javascripts/bootstrap/collapse');
require('bootstrap-sass/assets/javascripts/bootstrap/dropdown');
require('bootstrap-sass/assets/javascripts/bootstrap/tab');
require('eonasdan-bootstrap-datetimepicker');
require('bootstrap-sass/assets/javascripts/bootstrap/modal');


import LocationMap from './components/locationMap';
import './components/locationAutocomplete';
import './components/chat';

window.$(function($) {
    if ($('[data-component="locationMap"]').length) {
        new LocationMap($('[data-component="locationMap"]'));
    }

    $('[data-component="locationAutocomplete"]').locationAutocomplete();

    $('#menu-toggle').click(function(e) {
        e.preventDefault();
        $('#wrapper').toggleClass('toggled');
        $('#sidebar-toggle').toggleClass('glyphicon-chevron-left glyphicon-chevron-right');
    });

    $('#logout').on('click', function(e) {
        e.preventDefault();
        $(this).next('form').submit();
    });
    $('#datetime-start').datetimepicker({
        locale: 'nl',
        format: 'YYYY-MM-DD HH:mm:ss',
    });
    $('#datetime-end').datetimepicker({
        locale: 'nl',
        format: 'YYYY-MM-DD HH:mm:ss',
    });
});
$("#btn-index-more").click(function() {
    $('html, body').animate({
        scrollTop: $("#page-one").offset().top
    }, 2000);
});
$("#btn-activities-more").click(function() {
    $('html, body').animate({
        scrollTop: $("#activities").offset().top
    }, 2000);
});
$("#btn-whatis-more").click(function() {
    $('html, body').animate({
        scrollTop: $("#matchmaking").offset().top
    }, 2000);
});
$("#btn-locations-more").click(function() {
    $('html, body').animate({
        scrollTop: $("#locations").offset().top
    }, 2000);
});
$("#btn-trainer").click(function() {
    $('html, body').animate({
        scrollTop: $("#trainerscr").offset().top
    }, 2000);
});
$("#btn-sportclub").click(function() {
    $('html, body').animate({
        scrollTop: $("#sportclubscr").offset().top
    }, 2000);
});

/////////////////////////
// RESPONSIVE TABS /////
////////////////////////

/*
 * jQuery Bootstrap Responsive Tabs v2.0.1 | Valeriu Timbuc - vtimbuc.com
 * github.com/vtimbuc/bootstrap-responsive-tabs
 * @license WTFPL http://www.wtfpl.net/about/
 */

;(function($) {

  "use strict";

    var defaults = {
        accordionOn: ['xs'] // xs, sm, md, lg
    };

    $.fn.responsiveTabs = function (options) {

        var config = $.extend({}, defaults, options),
        accordion = '';

        $.each(config.accordionOn, function (index, value) {
            accordion += ' accordion-' + value;
        });

    return this.each(function () {

      var $self = $(this),
          $navTabs = $self.find('> li > a'),
          $tabContent = $($navTabs.first().attr('href')).parent('.tab-content'),
          $tabs = $tabContent.children('.tab-pane');

      // Wrap the tabs
      $self.add($tabContent).wrapAll('<div class="responsive-tabs-container" />');

      var $container = $self.parent('.responsive-tabs-container');

      $container.addClass(accordion);

      // Duplicate links for accordion
      $navTabs.each(function (i) {
        var $this = $(this),
            id = $this.attr('href'),
            active = '',
            first = '',
            last = '';

        // Add active class
        if ($this.parent('li').hasClass('active')) {
          active = ' active';
        }

        // Add first class
        if (i === 0) {
          first = ' first';
        }

        // Add last class
        if (i === $navTabs.length - 1) {
          last = ' last';
        }

        $this.clone(false).addClass('accordion-link' + active + first + last).insertBefore(id);
      });

      var $accordionLinks = $tabContent.children('.accordion-link');

      // Tabs Click Event
      $navTabs.on('click', function (event) {
        event.preventDefault();

        var $this = $(this),
            $li = $this.parent('li'),
            $siblings = $li.siblings('li'),
            id = $this.attr('href'),
            $accordionLink = $tabContent.children('a[href="' + id + '"]');

        if (!$li.hasClass('active')) {
          $li.addClass('active');
          $siblings.removeClass('active');

          $tabs.removeClass('active');
          $(id).addClass('active');

          $accordionLinks.removeClass('active');
          $accordionLink.addClass('active');
        }
      });

      // Accordion Click Event
      $accordionLinks.on('click', function (event) {
        event.preventDefault();

        var $this = $(this),
            id = $this.attr('href'),
            $tabLink = $self.find('li > a[href="' + id + '"]').parent('li');

        if (!$this.hasClass('active')) {
          $accordionLinks.removeClass('active');
          $this.addClass('active');

          $tabs.removeClass('active');
          $(id).addClass('active');

          $navTabs.parent('li').removeClass('active');
          $tabLink.addClass('active');
        }
      });

    });

    };

}(jQuery));