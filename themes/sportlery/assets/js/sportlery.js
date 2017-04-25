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
    $("#btn-sportclub").click(function() {
        $('html, body').animate({
            scrollTop: $("#sportclubscr").offset().top
        }, 2000);
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
