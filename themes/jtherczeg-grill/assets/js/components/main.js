$(document).ready(function() {
    $("#btn-locations").click(function() {
        $('html, body').animate({
            scrollTop: $("#locations").offset().top
        }, 2000);
    });
});
$(document).ready(function() {
    $("#btn-matchmaking").click(function() {
        $('html, body').animate({
            scrollTop: $("#matchmaking").offset().top
        }, 2000);
    });
});
$(document).ready(function() {
    $("#btn-lessons").click(function() {
        $('html, body').animate({
            scrollTop: $("#lessons").offset().top
        }, 2000);
    });
});
$(window).scroll(function() {
    $('#index-slideup-obj').each(function() {
        var imagePos = $(this).offset().top;
        var topOfWindow = $(window).scrollTop();
        if (imagePos < topOfWindow + 400) {
            $(this).addClass("slideRight");
        }
    });
});
$(window).scroll(function() {
    $('#index-slideup-obj-2').each(function() {
        var imagePos = $(this).offset().top;
        var topOfWindow = $(window).scrollTop();
        if (imagePos < topOfWindow + 400) {
            $(this).delay(200).queue(function(next) {
                $(this).addClass("slideUp");
                next();
            });
        };
    });
});
$(window).scroll(function() {
    $('#index-slideup-obj-3').each(function() {
        var imagePos = $(this).offset().top;
        var topOfWindow = $(window).scrollTop();
        if (imagePos < topOfWindow + 400) {
            $(this).delay(400).queue(function(next) {
                $(this).addClass("slideLeft");
                next();
            });
        };
    });
});
$(window).scroll(function() {
    $('#index-mm-fadeIn').each(function() {
        var imagePos = $(this).offset().top;
        var topOfWindow = $(window).scrollTop();
        if (imagePos < topOfWindow + 400) {
            $(this).addClass("fadeIn");
        }
    });
});
$(window).scroll(function() {
    $('#index-loc-slideUp').each(function() {
        var imagePos = $(this).offset().top;
        var topOfWindow = $(window).scrollTop();
        if (imagePos < topOfWindow + 400) {
            $(this).addClass("slideUp");
        }
    });
});
$(document).ready(function() {
    $("#btn-trainer").click(function() {
        $('html, body').animate({
            scrollTop: $("#trainerinfo").offset().top
        }, 2000);
    });
});
$(document).ready(function() {
    $("#btn-index-more").click(function() {
        $('html, body').animate({
            scrollTop: $("#sliderone").offset().top
        }, 1800);
    });
});
$(document).ready(function() {
    $("#btn-whatis-more").click(function() {
        $('html, body').animate({
            scrollTop: $("#slidertwo").offset().top
        }, 1800);
    });
});
$(document).ready(function() {
    $("#btn-locations-more").click(function() {
        $('html, body').animate({
            scrollTop: $("#sliderthree").offset().top
        }, 1800);
    });
});
$(document).ready(function() {
    $("#btn-message-more").click(function() {
        $('html, body').animate({
            scrollTop: $("#sliderfour").offset().top
        }, 1800);
    });
});
$(document).ready(function() {
    $("#btn-sportclub").click(function() {
        $('html, body').animate({
            scrollTop: $("#sportclubinfo").offset().top
        }, 2000);
    });
});
$(document).ready(function() {
    $("#link-locations").click(function() {
        $('html, body').animate({
            scrollTop: $("#locations").offset().top
        }, 2000);
    });
});
$(document).ready(function() {
    $("#link-matchmaking").click(function() {
        $('html, body').animate({
            scrollTop: $("#matchmaking").offset().top
        }, 2000);
    });
});
$(document).ready(function() {
    $("#link-lessons").click(function() {
        $('html, body').animate({
            scrollTop: $("#lessons").offset().top
        }, 2000);
    });
});
$('#signup').on('shown.bs.modal', function() {
    $('#signupInput').focus()
});

function onEnterPress() {
    var key = window.event.keyCode;
    if (key === 13) {
        document.getElementById("txtArea").value = document.getElementById("txtArea").value + "\n*";
        return false;
    } else {
        return true;
    }
}