<div class="col-sm-12 col-lg-6">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder">
            {{ trans("Asset Profile") }}
        </div>

        <div class="card-body">
            <p><strong>{{ $stock->name }}</strong></p>

               <div class="row">
                    <div class="col-xl-6">
                        <p>
                            {{ $profile["address"] }}<br/>
                            {{ $profile["zip"] }}
                            {{ $profile["city"] }}<br/>
                            {{ $profile["country"] }}<br/>
                            <a href="{{ $profile["website"] }}" target="_blank"
                               title="{{ $stock->name }}">{{ $profile["website"] }}</a>
                        </p>
                    </div>

                    <div class="col-xl-6">
                        <p>
                            {{ trans("Sektor") }}: {{ $profile["sector"] }}<br/>
                            {{ trans("Industrie") }}: {{ $profile["industry"] }}<br/>
                            {{ trans("Mitarbeiter in Vollzeit") }}: @fmt_number($profile["employees"])<br/>
                        </p>
                    </div>

                    <div class="col-12">
                        <p>{{ $profile["business_summary"] }}</p>
                    </div>
                </div>
        </div>
    </div>
</div>
