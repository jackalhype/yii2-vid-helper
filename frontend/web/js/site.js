Menu = {
    init: function() {
        $('.menu-ul > li').hover(function() {
            console.log('hover', $(this));
            $(this).find('.nav-second-level').show();
        }, function(){
            console.log('hover out', $(this));
            $(this).find('.nav-second-level').hide();
        })
    }
};

$(document).ready(function() {
    Menu.init();
});