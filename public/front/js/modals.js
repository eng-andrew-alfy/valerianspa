


$(document).ready(function () {
    $('.ms-modal-close').each(function () {
        $(this).click(function () {
            $(this).parents('.ms-modal').removeClass('active');
        });
    });


});


