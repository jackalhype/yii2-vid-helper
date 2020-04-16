Video = {
    tree_editor: null,
    list_type: "ol", // "ul"
    nbsp: "&nbsp;",
    tab_spaces: 4,

    init: function() {
        const _this = this;
        console.log('Video.init()');

        _this.tree_editor = CKEDITOR.replace( 'tree-editor', { tabSpaces: _this.tab_spaces  } );
        _this.tree_editor.on( 'change', function( ev ) {
            // getData() returns CKEditor's HTML content.
            // console.log( 'Total bytes: ' + ev.editor.getData().length );
        });

        // let contents = $(document).find('.cke_wysiwyg_frame').contents();
        // contents.find('html').on('click', function(ev) {
        //     console.log('click')
        // });
        // contents.find('html').on('keyup', function(ev) {
        //     console.log('keyup')
        // });
        _this.tree_editor.on('key', function(ev){
            // console.log(ev);
            let keyCode = ev.data.keyCode;
            // if (keyCode == 9) { // tab
            //     ev.cancel();
            //     CKEDITOR.instances['tree-editor'].insertText('    ');
            // }
            // let selection = ev.editor.getSelection();
            // let range = selection.getRanges()[0];
            // console.log(range);
            // let cursor_position = range.startOffset;
            // if (keyCode == 2228233) {  // shift + tab
            //     ev.cancel();
            // }
        });

        _this.addFormatLiBtn();

    },

    addFormatLiBtn: function() {
        const _this = this;
        CKEDITOR.on('instanceReady', function(ev) {
            let btn = '<span id="cke_101" class="cke_toolbar" role="toolbar" aria-labelledby="cke_86_label">' +
                '<span class="cke_toolgroup" role="presentation">' +
                '<button type="button" class="cke-btn format-li-btn" id="format-li-btn">fmt li</button>' +
                '</span>' +
            '</span>'
            $('#cke_1_toolbox').append(btn);
            $('.format-li-btn').on('click', function(ev){
                let html = CKEDITOR.instances['tree-editor'].getData();
                $html = $(html);
                // console.log($html);
                if (!$html[0].nodeName) {
                    return false;
                }
                _this.list_type = $html[0].nodeName.toLowerCase();
                let prev_level = level = 0;
                let prev_nbsp_n = -1
                    , nbsp_n = 0;
                let new_li_html, new_li;
                let opened_lists_n = 0;
                let new_html = '';
                $html.find('> *').each(function(k, v) {
                    // console.log(k, v.nodeName, $(v).html());
                    let li_html = $(v).html();
                    let r = new RegExp(_this.nbsp, 'g');
                    nbsp_n = (li_html.match(r) || []).length;
                    new_li_html = li_html.replace(r, '').trim();
                    if (new_li_html === '') {
                        return true;
                    }
                    // console.log('nbsp_n: ', nbsp_n);
                    if (prev_nbsp_n > -1 && nbsp_n > prev_nbsp_n) {
                        level = prev_level + 1;
                        new_li = '<'+_this.list_type+'>\n<li>' + new_li_html + '</li>\n';
                        opened_lists_n +=1;
                    } else if (prev_nbsp_n > -1 && nbsp_n < prev_nbsp_n) {
                        let level_reduce = Math.floor((prev_nbsp_n - nbsp_n) / _this.tab_spaces);
                        level = prev_level - level_reduce;
                        let s = '</'+_this.list_type+'>\n';
                        new_li = s.repeat(level_reduce)  +'<li>' + new_li_html + '</li>\n';
                        opened_lists_n -= level_reduce;
                    } else {
                        new_li = '<li>' + new_li_html + '</li>\n';
                    }
                    new_html += new_li;
                    prev_nbsp_n = nbsp_n;
                    prev_level = level;
                });
                new_html = '<'+_this.list_type+'>\n' +  new_html + '</'+_this.list_type+'>\n'

                CKEDITOR.instances['tree-editor'].setData(new_html);
            });
        });

    }
}

$(document).ready(function() {
    Video.init();
})
