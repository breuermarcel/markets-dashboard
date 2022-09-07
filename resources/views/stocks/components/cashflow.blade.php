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
                            <td scope="row">{{ trans("Cashflow aus operativer Geschchäftstätigkeit") }}</td>
                            <td>@fmt_money($cashflow["total_cash_from_operating_activities"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Cashflow aus Investitionstätigkeit") }}</td>
                            <td>@fmt_money($cashflow["total_cashflows_from_investing_activities"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Cashflow aus Finanzierungstätigkeit") }}</td>
                            <td>@fmt_money($cashflow["total_cash_from_financing_activities"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Netto-Änderung bei Cash") }}</td>
                            <td>@fmt_money($cashflow["change_to_net_income"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Barbestand zu Beginn des Zeitraums") }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Barbestand zum Ende des Zeitraums") }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Freier Cashflow") }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

