<div class="col col-sm-12 col-lg-6" id="bm__balance_sheet-container">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder">
            {{ trans("Bilanz") }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th scope="col">{{ trans("Aufschlüsselung") }}</th>
                        <th scope="col">@fmt_date($balance_sheet["date"])</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr class="fw-bold">
                            <td scope="row">{{ trans("Aktuelle Anlagen") }}</td>
                            <td>@fmt_money($balance_sheet["total_current_assets"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Cash (gesamt)") }}</td>
                            <td>@fmt_money($balance_sheet["cash_total"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Forderungen") }}</td>
                            <td>@fmt_money($balance_sheet["net_receivables"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Inventar") }}</td>
                            <td>@fmt_money($balance_sheet["inventory"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Sonstige aktuelle Anlagen") }}</td>
                            <td>@fmt_money($balance_sheet["other_current_assets"])</td>
                        </tr>

                        <tr>
                            <td scope="row">{{ trans("Sachanlagen (brutto)") }}</td>
                            <td>@fmt_money($balance_sheet["long_term_debt"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Aufgelaufene Abschreibungen") }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Sachanlagen (netto)") }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Eigenkapital und sonstige Investitionen") }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Sonstige langfristige Aktiva") }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Eigenkapital (gesamt)") }}</td>
                            <td>@fmt_money($balance_sheet["net_tangible_assets"])</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>