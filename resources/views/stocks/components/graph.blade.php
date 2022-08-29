
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.0/chart.min.js"></script>

<script>
    $(document).ready(function(){
        $.get({
            type: 'GET',
            url: '/finance-dashboard/api/',
            data: {
                'module': 'chart',
                'symbol': $('#stock-detail-container').attr('data-symbol'),
                'html': true
            },
            success: function (response) {
                $('#stock-detail-container').append(response);
            }
        });
    });
</script>
