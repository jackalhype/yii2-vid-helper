Loader = {
    gif: '/admin/img/ajax-loader.gif',
    getHtml() {
        return '<div class="ajax-loader" id="ajax-loader" style="background-image: url(' + this.gif + '); ' +
            'display:block; position: fixed; background-size: cover;' +
            'width: 120px; height: 120px; ' +
            'top:50%; left: 50%; ' +
            'margin-top: -60px; margin-left: -60px;"></div>'
    },

    show() {
        if ($('body').find('.ajax-loader').length == 0) {
            $('body').append(this.getHtml());
        } else {
            $('body').find('.ajax-loader').show();
        }
    },

    hide() {
        $('body').find('.ajax-loader').hide();
    }
};

function setDatepickerDefaults() {
    if (jQuery.datepicker === undefined) {
        return;
    }
    $.datepicker.regional['ru'] = {
        closeText: 'Закрыть',
        prevText: 'Пред',
        nextText: 'След',
        currentText: 'Сегодня',
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
            'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
        monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
            'Июл','Авг','Сен','Окт','Ноя','Дек'],
        dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
        dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
        weekHeader: 'Нед',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['ru']);
}

$.datetimepicker.setLocale('ru');
