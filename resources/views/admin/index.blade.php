@extends('layouts/main')

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h1>Auction Management</h1>
            <a class="btn btn-secondary" href="/admin/item/create" role="button">Add Item</a>
            <a class="btn btn-secondary" href="/admin/auction-live" role="button">Auction Live</a>
            @if (Auth::user()->email == 'fudge@younglife.org.au' || true)
                <a class="btn btn-secondary" href="/admin/auction-max" role="button">Max</a>
            @endif
        </div>
    </div>
    <div class="container">

        <h3 class="mb-3">Auction Items</h3>

        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col" width="100">#</th>
                <th scope="col">Item</th>
                <th scope="col" width="10%"></th>
            </tr>
            </thead>
            <tbody>
            @if ($items->count() > 0)
                @foreach ($items as $item)
                    <tr>
                        <th scope="row"><img src="/auction/images/{{ $item->image1 }}" width="90"></th>
                        <td>
                            <h5>{{ $item->name }}</h5>
                            {{ $item->description }}
                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="/admin/item/{{ $item->id }}/edit" role="button">Edit</a> &nbsp;
                            <a class="btn btn-dark btn-sm" href="/admin/item/{{ $item->id }}/del" role="button"><i class="fa fa-trash-alt"></i></a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">Currently no items</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
@endsection
