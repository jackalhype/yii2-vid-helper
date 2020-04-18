Menu = {
    init: function() {
        $('.menu-ul > li').hover(function() {
            $(this).find('.nav-second-level').show();
        }, function(){
            $(this).find('.nav-second-level').hide();
        })
    }
};

$(document).ready(function() {
    Menu.init();
});