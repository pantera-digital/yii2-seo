$(document).on('click', '.seo-url-group-delete', function () {
    var selections = $('.grid-view').yiiGridView('getSelectedRows');
    if (selections.length > 0 && confirm('Вы уверены?')) {
        var url = $(this).attr('href');
        $.post(url, {ids: selections}, function (result) {
            window.location.reload();
        })
    }
    return false;
});