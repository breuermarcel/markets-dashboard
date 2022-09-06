<div class="col col-sm-12 col-lg-6" id="bm__chart-container">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header justify-content-between d-flex align-items-center">
            <span class="me-2 fw-bolder">{{ trans('Chart') }}</span>

            <div class="btn-group btn-group-sm" role="group" aria-label="{{ trans('Period') }}">
                <button class="btn btn-outline-danger" onclick="callAPI({
                        'symbol': '{{ $stock->symbol }}',
                        'module': 'chart',
                        'period': '30',
                        'container': '#bm__stock-detail-container #bm__chart-container'
                    });">
                    1 {{ trans('Monat') }}
                </button>

                <button class="btn btn-outline-danger" onclick="callAPI({
                        'symbol': '{{ $stock->symbol }}',
                        'module': 'chart',
                        'period': '90',
                        'container': '#bm__stock-detail-container #bm__chart-container'
                    });">
                    3 {{ trans('Monate') }}
                </button>

                <button class="btn btn-outline-danger" onclick="callAPI({
                        'symbol': '{{ $stock->symbol }}',
                        'module': 'chart',
                        'period': '180',
                        'container': '#bm__stock-detail-container #bm__chart-container'
                    });">
                    6 {{ trans('Monate') }}
                </button>

                <button class="btn btn-outline-danger" onclick="callAPI({
                        'symbol': '{{ $stock->symbol }}',
                        'module': 'chart',
                        'period': '365',
                        'container': '#bm__stock-detail-container #bm__chart-container'
                    });">
                    1 {{ trans('Jahr') }}
                </button>

                <button class="btn btn-outline-danger" onclick="callAPI({
                        'symbol': '{{ $stock->symbol }}',
                        'module': 'chart',
                        'period': '1095',
                        'container': '#bm__stock-detail-container #bm__chart-container'
                    });">
                    3 {{ trans('Jahre') }}
                </button>

                <button class="btn btn-outline-danger" onclick="callAPI({
                        'symbol': '{{ $stock->symbol }}',
                        'module': 'chart',
                        'period': '1825',
                        'container': '#bm__stock-detail-container #bm__chart-container'
                    });">
                    5 {{ trans('Jahre') }}
                </button>
            </div>
        </div>

        <div class="card-body">
            <canvas id="bm__chart"></canvas>
            <script>
                var ctx = document.getElementById('bm__chart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [
                            @foreach ($history as $data)
                                '{{ $data['date'] }}',
                            @endforeach
                        ],
                        datasets: [{
                            label: '{{ $stock->name }} ({{ $stock->symbol }})',
                            fill: false,
                            borderColor: 'rgb(255, 99, 132)',
                            pointRadius: 0,
                            lineTension: 0,
                            data: [
                                @foreach ($history as $data)
                                    '{{ $data['adj_close'] }}',
                                @endforeach
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: '{{ $stock->name }} ({{ $stock->symbol }})'
                        },
                        scales: {
                            x: {
                                display: true
                            },
                            y: [{
                                display: true,
                                type: 'time',
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Value in {{ $history[0]['currency'] }}'
                                }
                            }]
                        },
                    }
                });
            </script>
        </div>
    </div>
</div>
