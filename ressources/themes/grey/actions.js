$(document).ready(function () {
    windows_size_elements();
    resize_colone_article();
    resize_lot_informations();

    var win = $(window); //this = window
    console.log(win.width());
});

$(window).on('resize', function () {
    windows_size_elements();
});

$('main div#colone_article div#lot').on('resize', function () {
    resize_colone_article();
});
$('main div#colone_infos').on('resize', function () {
    resize_lot_informations();
});

function windows_size_elements() {
    var win = $(window); //this = window

    var selectorColone_infos = $('div#colone_infos');
    var coloneInfosVisible = selectorColone_infos.is(':visible');
    var coloneInfosHidden = selectorColone_infos.is(':hidden');

    var boutton_ajustable_text = $(
        'div#colone_menu > ' +
        'div.boutton_ajustable > ' +
        'div.boutton_ajustable_block > ' +
        'div.boutton_ajustable_text');
    var boutton_ajustable_texte_visible = boutton_ajustable_text.is(':visible');
    var boutton_ajustable_text_hidden = boutton_ajustable_text.is(':hidden');
    var colone_infos_width = selectorColone_infos.width();
    var header = $('header');

    switch (true) {
        case win.width() <= 450 :
            resize_colone_article();
            resize_lot_informations();
            header.css('-moz-box-flex', 0);
            header.css('flex-grow', 0);
            boutton_ajustable_text.hide();
            selectorColone_infos.hide();
            break;
        case boutton_ajustable_texte_visible && win.width() <= 1280 :
            boutton_ajustable_text.hide();
            break;
        case boutton_ajustable_text_hidden && win.width() > 1280 :
            boutton_ajustable_text.show();
            break;
        case colone_infos_width === 350 && win.width() <= 1095:
            selectorColone_infos.width(290);
            selectorColone_infos.css('max-width', 290);
            break;
        case colone_infos_width === 290 && win.width() > 1095:
            selectorColone_infos.width(350);
            selectorColone_infos.css('max-width', 350);
            break;
        case colone_infos_width === 400 && win.width() <= 1145:
            selectorColone_infos.width(350);
            selectorColone_infos.css('max-width', 350);
            break;
        case colone_infos_width === 350 && win.width() > 1145:
            selectorColone_infos.width(400);
            selectorColone_infos.css('max-width', 400);
            break;
        case coloneInfosVisible && win.width() <= 1005:
            selectorColone_infos.hide();
            header.css('-moz-box-flex', 0);
            header.css('flex-grow', 0);
            break;
        case coloneInfosHidden && win.width() > 1005:
            selectorColone_infos.show();
            header.css('-moz-box-flex', 1);
            header.css('flex-grow', 1);
            break;
    }

}

function resize_lot_informations() {
    var bar_secondaire = $('div#bar_secondaire');
    var selectorColone_infos = $('div#colone_infos');
    var lot_informations = $('div#lot_informations');

    console.log(selectorColone_infos.height() + '>' + lot_informations.height());

    if (selectorColone_infos.height() !== lot_informations.height()) {
        lot_informations.height(selectorColone_infos.height() + bar_secondaire.height());
    }
}

function resize_colone_article() {
    var bar_principal = $('div#bar_principal');
    var colone_article = $('div#colone_article');
    var lot = $('div#lot');

    console.log(lot.height() + '>' + colone_article.height());

    if (lot.height() > colone_article.height()) {
        colone_article.height(lot.height() + bar_principal.height());
    }
}