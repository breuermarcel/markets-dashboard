@extends('finance-dashboard::main')

@section('content')

    <div class="btn-toolbar mb-2">
        <div class="btn-group">
            <a href="{{ route('stocks.create') }}"
                class="btn btn-sm btn-outline-secondary">{{ trans('Hinzufügen') }}</a>
        </div>
    </div>

    @empty($stocks)
        <div class="">
            {{ trans("Keine Aktien verfügbar.")}}
        </div>
    @else

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>{{ trans('Symbol') }}</th>
                <th>{{ trans('Name') }}</th>
                <th>{{ trans('WKN') }}</th>
                <th>{{ trans('ISIN') }}</th>
                <th class="visually-hidden">{{ trans("Toolbar")}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($stocks as $stock)
                <tr>
                    <td>{{ $stock->symbol }}</td>
                    <td>{{ $stock->name }}</td>
                    <td>{{ $stock->wkn }}</td>
                    <td>{{ $stock->isin }}</td>

                    <td class="text-end">
                        <a href="{{ route('stocks.show', $stock) }}"
                           class="btn btn-outline-secondary">{{ trans('Anzeigen') }}</a>
                        <a href="{{ route('stocks.edit', $stock) }}"
                               class="btn btn-secondary">{{ trans('Bearbeiten') }}</a>
                        <form class="d-inline" method="POST" action="{{ route('stocks.destroy', $stock) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ trans('Löschen') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $stocks->links() }}

    @endempty
@endsection
