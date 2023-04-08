@extends('markets-dashboard::main')

@section('content')
    <div class="btn-toolbar mb-2">
        <div class="btn-group">
            <a href="{{ route(config("markets-dashboard.routing.as") .'stocks.create') }}"
               class="btn btn-sm btn-outline-secondary">{{ trans('Hinzufügen') }}</a>
            <a href="{{ route(config("markets-dashboard.routing.as") .'stocks.import.show') }}"
               class="btn btn-sm btn-outline-secondary">{{ trans('Importieren') }}</a>
        </div>
    </div>

    @if($stocks->count() <= 0)
        <div class="alert alert-warning alert-dismissible fade show my-3" role="alert">
            {{ trans("Keine Aktien verfügbar.")}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @else

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th scope="col">{{ trans('Symbol') }}</th>
                    <th scope="col">{{ trans('Name') }}</th>
                    <th scope="col">{{ trans('WKN') }}</th>
                    <th scope="col">{{ trans('ISIN') }}</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($stocks as $stock)
                    <tr>
                        <td scope="row">{{ $stock->symbol }}</td>
                        <td>{{ $stock->name }}</td>
                        <td>{{ $stock->wkn }}</td>
                        <td>{{ $stock->isin }}</td>
                        <td>
                            <a href="{{ route(config("markets-dashboard.routing.as") .'stocks.show', $stock) }}"
                               class="btn btn-outline-secondary">{{ trans('Anzeigen') }}</a>
                            <a href="{{ route(config("markets-dashboard.routing.as") .'stocks.edit', $stock) }}"
                               class="btn btn-secondary">{{ trans('Bearbeiten') }}</a>
                            <button onclick="storeStockInformation('{{ $stock->symbol }}')"
                                    class="btn btn-outline-success">{{ trans("Informationen aktualisieren") }}</button>
                            <form class="d-inline" method="POST"
                                  action="{{ route(config("markets-dashboard.routing.as") .'stocks.destroy', $stock) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{ trans('Löschen') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{ $stocks->links() }}

    @endempty
@endsection
