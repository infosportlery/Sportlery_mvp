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