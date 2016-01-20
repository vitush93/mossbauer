var $ = require('jquery');

module.exports = function () {
    $('.js-page-menus a').each(function () {
        var href = $(this).attr('href');

        if (href == window.location.pathname) {
            $(this).addClass('active');
        }
    });
};
