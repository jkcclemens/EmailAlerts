$.ajax(
    '/notifications',
    {
        success: function (data) {
            $.each(data, function (number, datum) {
                datum['created_at'] = moment(datum['created_at']).fromNow();
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
            });
        }
    }
);
