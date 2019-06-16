@extends('layouts/main')

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h1>Auction Report <a href="/admin" class="btn btn-outline-primary float-right"><i class="fa fa-arrow-left"></i> Return to Auction Management</a></h1>

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
                            {{ $item->brief }}<br><br>
                            <b>Bid History</b>
                            <table class="table">
                                <thead>
                                <tr style="background: #eee">
                                    <th>Bid</th>
                                    <th>Max</th>
                                    <th>Price</th>
                                    <th>Winner</th>
                                </tr>
                                </thead>
                                <?php $price = 0; $max = 0; ?>
                                @foreach ($item->bids as $bid)
                                    <?php
                                    $table = ($bid->user->table) ? ' (table ' . $bid->user->table . ')' : '';
                                    if ($price == 0) {
                                        $price = 5;
                                        $max = $bid->bid;
                                        $winner = $bid->user->bidder_id;
                                    } else {
                                        if ($bid->bid > $max) {   // New bid is higher then previous)
                                            if ($winner != $bid->user->bidder_id)
                                                $price = ($item->nextBid($max) > $bid->bid) ? $bid->bid : $item->nextBid($max); //
                                            $max = $bid->bid;
                                            $winner = $bid->user->bidder_id;
                                        } elseif ($winner != $bid->user->bidder_id)
                                            $price = $bid->bid;     // New bid is less then or same as previous offer but also not by current Top Bidder
                                    }
                                    ?>
                                    <tr>
                                        <td>{{ $bid->created_at->format('h:ia') }} - #{{ $bid->user->bidder_id }} {{ $bid->user->name }} {{ $table }}</td>
                                        <td>{{ $bid->bid }}</td>
                                        <td>{{ $price }}</td>
                                        <td>#{{ $winner }}</td>
                                    </tr>
                                @endforeach
                            </table>

                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="/admin/item/{{ $item->id }}" role="button">View</a> &nbsp;
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
