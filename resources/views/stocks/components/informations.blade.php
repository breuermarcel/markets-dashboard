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
                            {{ $information["asset_profile"]["address"] }}<br/>
                            {{ $information["asset_profile"]["zip"] }}
                            {{ $information["asset_profile"]["city"] }}<br/>
                            {{ $information["asset_profile"]["country"] }}<br/>
                            <a href="{{ $information["asset_profile"]["website"] }}" target="_blank"
                               title="{{ $stock->name }}">{{ $information["asset_profile"]["website"] }}</a>
                        </p>
                    </div>

                    <div class="col-xl-6">
                        <p>
                            {{ trans("Sektor") }}: {{ $information["asset_profile"]["sector"] }}<br/>
                            {{ trans("Industrie") }}: {{ $information["asset_profile"]["industry"] }}<br/>
                            {{ trans("Mitarbeiter in Vollzeit") }}: @fmt_number($information["asset_profile"]["employees"])<br/>
                        </p>
                    </div>

                    <div class="col-12">
                        <p>{{ $information["asset_profile"]["business_summary"] }}</p>
                    </div>
                </div>
        </div>
    </div>
</div>


<div class="col-sm-12 col-lg-6">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder">
            {{ trans("Geschäftsführung") }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th scope="col">{{ trans("Name") }}</th>
                        <th scope="col">{{ trans("Alter") }}</th>
                        <th scope="col">{{ trans("Jobtitel") }}</th>
                        <th scope="col">{{ trans("Gehalt") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($information["asset_profile"]["company_officiers"] as $officer)
                            <tr>
                                <th scope="row">
                                    @if (array_key_exists("name", $officer))
                                        {{ $officer["name"] }}
                                    @endif
                                </th>
                                <td>
                                    @if (array_key_exists("age", $officer))
                                        @fmt_number($officer["age"])
                                    @endif
                                </td>
                                <td>
                                    @if (array_key_exists("job_title", $officer))
                                        {{ $officer["job_title"] }}
                                    @endif
                                </td>
                                <td>
                                    @if (array_key_exists("total_pay", $officer))
                                        @fmt_money($officer["total_pay"])
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
