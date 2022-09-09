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
                        <tr>
                            <td scope="row">{{ trans("Anlagevermögen") }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>