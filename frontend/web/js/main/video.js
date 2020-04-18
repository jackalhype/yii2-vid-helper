Video = {
    init: function() {
        $(document).on('keydown', function(ev) {
            console.log(ev.originalEvent);
            let keyCode = ev.originalEvent.keyCode;
            if (keyCode == 39 || keyCode == 40 || keyCode == 32) { // right, down, space
                ev.preventDefault();
                $('.video-wrap').find('li.hide').slice(0, 1).removeClass('hide');
            } else if (keyCode == 38 || keyCode == 37) { // up, left
                ev.preventDefault();
                let li = $('.video-wrap').find('li').not('.hide');
                let len = li.length;
                if (len <= 1) {
                    return;
                }
                li.slice(len-1, len).addClass('hide');
            }
        });
    }
};

$(document).ready(function() {
    Video.init();
});
