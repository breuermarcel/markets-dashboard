<div class="col col-sm-12 col-lg-6" id="bm__esg-container">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder">
            {{ trans("Environment, Social and Governance (ESG)") }} ({{ $esg["year"] }})
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <strong>{{ trans("Score") }}</strong>: @fmt_decimal($esg["total"])<br/>
                    <strong>{{ trans("Umwelt") }}</strong>: @fmt_decimal($esg["environment"])<br/>
                    <strong>{{ trans("Sozial") }}</strong>: @fmt_decimal($esg["social"])<br/>
                    <strong>{{ trans("Governance") }}</strong>: @fmt_number($esg["governance"])
                </div>
            </div>
        </div>
    </div>
</div>
