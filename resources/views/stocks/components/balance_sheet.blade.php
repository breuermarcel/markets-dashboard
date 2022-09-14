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
                            <td scope="row">{{ trans("Anlagevermögen (gesamt)") }}</td>
                            <td>@fmt_money($balance_sheet["total_assets"])</td>
                        </tr>
                        <tr class="fw-bolder">
                            <td scope="row">{{ trans("Aktuelle Anlagen") }}</td>
                            <td>@fmt_money($balance_sheet["total_current_assets"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Cash") }}</td>
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

                        <tr class="fw-bolder">
                            <td scope="row">{{ trans("Anlagevermögen") }}</td>
                            <td>@fmt_money($balance_sheet["total_non_current_assets"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Sachanlagen") }}</td>
                            <td>@fmt_money(0)</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Aufgelaufene Abschreibungen") }}</td>
                            <td>@fmt_money(0)</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Sachanlagen (netto)") }}</td>
                            <td>@fmt_money(0)</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Eigenkapital und sonstige Investitionen") }}</td>
                            <td>@fmt_money($balance_sheet["long_term_investments"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Sonstige langfristige Aktiva") }}</td>
                            <td>@fmt_money(0)</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Eigenkapital (gesamt)") }}</td>
                            <td>@fmt_money($balance_sheet["net_tangible_assets"])</td>
                        </tr>


                        <tr class="fw-bold">
                            <td scope="row">{{ trans("Verbindlichkeiten und Eigenkapitalpositionen") }}
                            <td>@fmt_money($balance_sheet["total_assets"])</td>
                        </tr>
                        <tr class="fw-bolder">
                            <td scope="row">{{ trans("Verbindlichkeiten (gesamt)") }}</td>
                            <td>@fmt_money($balance_sheet["total_liab"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Aktuelle Verbindlichkeiten") }}</td>
                            <td>@fmt_money($balance_sheet["total_current_liabilities"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Langfristige Verbindlichkeiten") }}</td>
                            <td>@fmt_money($balance_sheet["long_term_liab"])</td>
                        </tr>
                        <tr class="fw-bolder">
                            <td scope="row">{{ trans("Eigenkapital (gesamt)") }}</td>
                            <td>@fmt_money($balance_sheet["net_tangible_assets"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Stammaktie") }}</td>
                            <td>@fmt_money($balance_sheet["common_stock"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Gewinnrücklagen") }}</td>
                            <td>@fmt_money($balance_sheet["retained_earnings"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Aufgelaufener sonstiger Gesamterfolg") }}</td>
                            <td>@fmt_money($balance_sheet["treasury_stock"])</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>