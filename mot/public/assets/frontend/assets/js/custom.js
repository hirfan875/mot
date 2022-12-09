function deselect(e) {
    $('.pop').slideFadeToggle(function () {
        e.removeClass('selected1');
    });
}

$(function () {
    $('#contact').on('click', function () {
        if ($(this).hasClass('selected1')) {
            deselect($(this));
        } else {
            $(this).addClass('selected1');
            $('.pop').slideFadeToggle();
        }
        return false;
    });

    $('.close1').on('click', function () {
        deselect($('#contact'));
        return false;
    });
});

$.fn.slideFadeToggle = function (easing, callback) {
    return this.animate({opacity: 'toggle', height: 'toggle'}, 'fast', easing, callback);
};

/* for mobile categories menus menu*/
$(document).ready(function () {
    $("#accordian a").click(function () {
        var link = $(this);
        var closest_ul = link.closest("ul");
        var parallel_active_links = closest_ul.find(".active")
        var closest_li = link.closest("li");
        var link_status = closest_li.hasClass("active");
        var count = 0;

        closest_ul.find("ul").slideUp(function () {
            if (++count == closest_ul.find("ul").length)
                parallel_active_links.removeClass("active");
        });

        if (!link_status) {
            closest_li.children("ul").slideDown();
            closest_li.addClass("active");
        }
    });
});
