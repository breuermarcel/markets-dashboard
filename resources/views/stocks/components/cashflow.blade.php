<div class="col col-sm-12 col-lg-6" id="bm__cashflow-container">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder">
            {{ trans("Cashflow") }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th scope="col">{{ trans("Aufschlüsselung") }}</th>
                        <th scope="col">@fmt_date($cashflow["date"])</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">{{ trans("Operativen Cashflow") }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Investierenden Cashflow") }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Finanzierenden Cashflow") }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Kassenbestand am Ende") }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("TEST") }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("TEST") }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("TEST") }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("TEST") }}</td>
                            <td></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

