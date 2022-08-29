<script>
    $(document).ready(function(){
        $.get({
            type: 'GET',
            url: '/finance-dashboard/api/',
            data: {
                'module': 'profile',
                'symbol': $('#stock-detail-container').attr('data-symbol'),
                'html': true
            },
            success: function (response) {
                $('#stock-detail-container').append(response);
            }
        });
    });
</script>
