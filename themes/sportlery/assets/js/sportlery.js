require('bootstrap-sass/assets/javascripts/bootstrap/collapse');
require('bootstrap-sass/assets/javascripts/bootstrap/dropdown');
require('bootstrap-sass/assets/javascripts/bootstrap/tab');
require('eonasdan-bootstrap-datetimepicker');
require('bootstrap-sass/assets/javascripts/bootstrap/modal');
require('bootstrap-responsive-tabs');

import LocationMap from './components/locationMap';
import './components/locationAutocomplete';
import './components/locationPicker';
import './components/chat';

$(function() {
    if ($('[data-component="locationMap"]').length) {
        new LocationMap($('[data-component="locationMap"]'));
    }

    $('[data-component="locationAutocomplete"]').locationAutocomplete();

    $('[data-component="locationPicker"]').each(function() {
        $(this).locationPicker();
    });

    $('[data-component="dateTimePicker"]').each(function() {
        var format = $(this).data('format') || 'YYYY-MM-DD HH:mm';
        var locale = $(this).data('locale');

        $(this).datetimepicker({
            locale,
            minDate: Date.now(),
            format: 'YYYY-MM-DD HH:mm',
        });
    });
    $('[data-component="dateTimePickerDay"]').each(function() {
        var format = $(this).data('format') || 'YYYY-MM-DD';
        var locale = $(this).data('locale');

        $(this).datetimepicker({
            locale,
            format: 'YYYY-MM-DD',
        });
    });
    $('#menu-toggle').click(function(e) {
        e.preventDefault();
        $('#wrapper').toggleClass('toggled');
        $('#sidebar-toggle').toggleClass('glyphicon-chevron-left glyphicon-chevron-right');
    });

    $('#logout').on('click', function(e) {
        e.preventDefault();
        $(this).next('form').submit();
    });
    $("#btn-index-more").click(function() {
        $('html, body').animate({
            scrollTop: $("#page-one").offset().top
        }, 2000);
    });
    $("#btn-trainer").click(function() {
        $('html, body').animate({
            scrollTop: $("#trainerscr").offset().top
        }, 2000);
    });
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> refs/remotes/origin/tarik
    $("#btn-sportclub").click(function() {
        $('html, body').animate({
            scrollTop: $("#sportclubscr").offset().top
        }, 2000);
<<<<<<< HEAD
=======
=======
    });
    $("#btn-modal-close").click(function() {
        $('body').css('padding-right', '0px');
    });
});
    $('#datetime-start').datetimepicker({
        locale: 'nl',
        format: 'YYYY-MM-DD HH:mm:ss',
    });
    $('#datetime-end').datetimepicker({
        locale: 'nl',
        format: 'YYYY-MM-DD HH:mm:ss',
    });
<<<<<<< HEAD
<<<<<<< HEAD
>>>>>>> refs/remotes/origin/tarik
});
=======

>>>>>>> refs/remotes/origin/tarik
=======
>>>>>>> origin/develop
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
/*

$(function($) {

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
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD

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

>>>>>>> refs/remotes/origin/tarik
    });
});
=======
>>>>>>> refs/remotes/origin/tarik
=======
*/

/**
 * cbpFWTabs.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2014, Codrops
 * http://www.codrops.com
 */
;( function( window ) {

    'use strict';

    function extend( a, b ) {
        for( var key in b ) {
            if( b.hasOwnProperty( key ) ) {
                a[key] = b[key];
            }
        }
        return a;
    }

    function CBPFWTabs( el, options ) {
        this.el = el;
        this.options = extend( {}, this.options );
        extend( this.options, options );
        this._init();
    }

    CBPFWTabs.prototype.options = {
        start : 0
    };

    CBPFWTabs.prototype._init = function() {
        // tabs elemes
        this.tabs = [].slice.call( this.el.querySelectorAll( 'nav > ul > li' ) );
        // content items
        this.items = [].slice.call( this.el.querySelectorAll( '.content > section' ) );
        // current index
        this.current = -1;
        // show current content item
        this._show();
        // init events
        this._initEvents();
    };

    CBPFWTabs.prototype._initEvents = function() {
        var self = this;
        this.tabs.forEach( function( tab, idx ) {
            tab.addEventListener( 'click', function( ev ) {
                ev.preventDefault();
                self._show( idx );
            } );
        } );
    };

    CBPFWTabs.prototype._show = function( idx ) {
        if( this.current >= 0 ) {
            this.tabs[ this.current ].className = '';
            this.items[ this.current ].className = '';
        }
        // change current
        this.current = idx != undefined ? idx : this.options.start >= 0 && this.options.start < this.items.length ? this.options.start : 0;
        this.tabs[ this.current ].className = 'tab-current';
        this.items[ this.current ].className = 'content-current';
    };

    // add to global namespace
    window.CBPFWTabs = CBPFWTabs;

})( window );
>>>>>>> refs/remotes/origin/tarik
=======
    };
});
>>>>>>> origin/develop
