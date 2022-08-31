<div class="col col-sm-12 col-lg-6" id="bm__income-container">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder">
            {{ trans("GuV") }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th scope="col">{{ trans("Aufschlüsselung") }}</th>
                        <th scope="col">@fmt_date($income["date"])</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">{{ trans("Gesamtumsatz") }}</td>
                            <td>@fmt_money($income["total_revenue"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Herstellungskosten") }}</td>
                            <td>@fmt_money($income["cost_of_revenue"])</td>
                        </tr>
                        <tr class="fw-bolder">
                            <td scope="row">{{ trans("Bruttoergebnis vom Umsatz") }}</td>
                            <td>@fmt_money($income["gross_profit"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Forschung und Entwicklung") }}</td>
                            <td>@fmt_money($income["research_and_development"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Verkauf allgemein und administrativ") }}</td>
                            <td>@fmt_money($income["selling_general_administrative"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Betriebliche Aufwendungen gesamt") }}</td>
                            <td>@fmt_money($income["total_operating_expanses"])</td>
                        </tr>
                        <tr class="fw-bolder">
                            <td scope="row">{{ trans("Operativer Gewinn/Verlust") }}</td>
                            <td>@fmt_money($income["ebit"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Zinsaufwand") }}</td>
                            <td>@fmt_money($income["interest_expense"])</td>
                        </tr>
                        {{--
                        <tr>
                            <td scope="row">{{ trans("Sonstiger Nettogewinn-/aufwendung") }}</td>
                            <td>@todo</td>
                        </tr>
                        --}}
                        <tr class="fw-bolder">
                            <td scope="row">{{ trans("Gewinn vor Steuer") }}</td>
                            <td>@fmt_money($income["income_before_tax"])</td>
                        </tr>
                        <tr>
                            <td scope="row">{{ trans("Steueraufwand") }}</td>
                            <td>@fmt_money($income["income_tax_expense"])</td>
                        </tr>
                        <tr class="fw-bolder">
                            <td scope="row">{{ trans("Nettoüberschuss") }}</td>
                            <td>@fmt_money($income["net_income"])</td>
                        </tr>
                    </tbody>
                </table>
            </div>



        </div>

    </div>
</div>
