$(function () {
    function updateTimes() {
        $('.list.ui.cards .card .meta.time').each(function (number, meta) {
            var time = parseInt(meta.getElementsByClassName('created_at')[0].innerHTML);
            meta.getElementsByClassName('created_at_render')[0].innerHTML = moment(time).fromNow();
        });
    }

    $.ajax(
        '/notifications',
        {
            success: function (data) {
                $.each(data, function (number, datum) {
                    datum['created_at_render'] = moment(datum['created_at']).fromNow();
                });
                $('.list.ui.cards > .active.loader').removeClass('active');
                var list = new List(
                    'notifications',
                    {
                        item: 'template',
                        valueNames: ['subject'],
                        page: 5,
                        plugins: [ListPagination({})],
                        searchField: "prompt"
                    },
                    data
                );
                list.on('updated', function () {
                    $('.ui.sticky').sticky('refresh');
                    updateTimes();
                });
            }
        }
    );

    setInterval(updateTimes, 60000);
});
