Video = {
    init: function() {
        const _this = this;
        _this.refreshTip();
        $(document).on('keydown', function(ev) {
            let keyCode = ev.originalEvent.keyCode;
            if (keyCode == 39 || keyCode == 40 || keyCode == 32) { // right, down, space
                ev.preventDefault();
                let li = _this.getFirstHidden();
                if (li) {
                    li.removeClass('hide');
                    _this.refreshTip();
                }
            } else if (keyCode == 38 || keyCode == 37) { // up, left
                ev.preventDefault();
                let li = _this.getLastShown();
                if (li) {
                    li.addClass('hide');
                    _this.refreshTip();
                }
            }
        });
    },

    getLastShown: function() {
        let li = $('.video-wrap').find('li').not('.hide');
        let len = li.length;
        if (len <= 1) {
            return false;
        }
        return li.slice(len - 1, len);
    },

    getFirstHidden: function() {
        let li = $('.video-wrap').find('li.hide')
        if (li.length < 1) {
            return false;
        }
        return li.slice(0, 1);
    },

    refreshTip: function () {
        const _this = this;
        // TODO: count val
        let val, tip, cur_class, next_class;
        let cur = _this.getLastShown();
        let next = _this.getFirstHidden();
        if (cur === false) {
            val = 1;
        } else if (next === false) {
            val = 'end';
        } else {
            cur_class = cur.attr('class');
            next_class = next.attr('class');
            let regex = /lvl-(\d+)/,
                cur_v, next_v, cur_m, next_m;
            cur_m = cur_class.match(regex);
            next_m = next_class.match(regex);
            if (!cur_m || cur_m[1] === undefined || !next_m || next_m[1] === undefined) {
                val = 'xxx';
            } else {
                cur_v = +cur_m[1];
                next_v = +next_m[1];
                val = next_v - cur_v;
            }
        }

        if (val === 'end') {
            tip = 'end';
        } else if (val === 'xxx') {
            tip = "xxx";
        } else if (val > 0) {
            tip = '-->';
        } else if (val < 0) {
            tip = '<--';
        } else {
            tip = '|';
        }

        $('#tip').html(tip);
    }
};

$(document).ready(function() {
    Video.init();
});
