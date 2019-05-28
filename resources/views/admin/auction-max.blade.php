@extends('layouts/main')

@section('content')
    <div class="container" id="vue-app">
        <br>
        <h3 class="mb-3">Auction Items <small v-if="xx.auction_status == 0" class="badge badge-pill badge-warning ml-3">PAUSED</small></h3>

        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Item</th>
                <th scope="col" width="10%">Bids</th>
                <th scope="col" width="10%">Winner</th>
                <th scope="col" width="10%">Price</th>
                <th scope="col">Max</th>
            </tr>
            </thead>
            <tbody>
            <div v-if="xx.items">
                <tr v-for="item in xx.items">
                    <td>@{{ item.name }} <small v-if="item.reserve && item.price < item.reserve" class="text-danger"> &nbsp; RESERVE NOT MET</small></td>
                    <td>@{{ item.bidders }}/@{{ item.bids }}</td>
                    <td>#@{{ item.highest_bid_id }}</td>
                    <td>$@{{ item.price }}</td>
                    <td>$@{{ item.highest_bid_max }}</td>
                </tr>
            </div>
            <div v-else>Currently no items</div>
            </tbody>
        </table>

        <!--<pre>@{{ $data }}</pre>
        -->
    </div>
@endsection

@section('page-styles')
@stop

@section('page-scripts')
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="/js/vue.min.js"></script>
    <script type="text/javascript">
        var xx = {
            auction_status: 1, items: [],
        };

        new Vue({
            el: '#vue-app',
            data: function () {
                return {xx: xx};
            },
            methods: {
                loadData: function () {
                    $.getJSON('/data/auction-items', function (data) {
                        this.xx.items = data;
                        this.xx.auction_status = data[0].auction_status;
                    }.bind(this));
                },
                viewItem: function (item) {
                    window.location.href = "/auctions/" + item.id;
                }
            },
            created: function () {
                this.loadData();

                setInterval(function () {
                    this.loadData();
                    console.log('refreshed')
                }.bind(this), 3000);
            }
        });
    </script>
@endsection
