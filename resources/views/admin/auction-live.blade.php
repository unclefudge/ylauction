@extends('layouts/main')

@section('content')
    <div class="container" id="vue-app">
        <br>
        <h3 class="mb-3">Auction Items</h3>

        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col" width="10%">Price</th>
                <th scope="col">Item</th>
                <th scope="col" width="10%">Bidder</th>
                <th scope="col">Status</th>
            </tr>
            </thead>
            <tbody>
            <div v-if="xx.items">
                <tr v-for="item in xx.items">
                    <td><h4>$@{{ item.price }}</h4></td>
                    <td><h4>@{{ item.name }} <small v-if="item.reserve && item.price < item.reserve" class="text-danger"> &nbsp; RESERVE NOT MET</small></h4></td>
                    <td><h4>#@{{ item.highest_bid_id }}</h4></td>
                    <td>
                        <h4>
                            <span v-if="item.bids > 1 && item.bids < 4" class="badge badge-pill" style="background: #FFFFD2; color:#000">LITTLE COMPETITION</span>
                            <span v-if="item.bids > 3 && item.bids < 6" class="badge badge-pill" style="background: #FFF0AA; color:#000">SOME ACTION</span>
                            <span v-if="item.bids > 5 && item.bids < 8" class="badge badge-pill" style="background: #FEDE81; color:#000">WARMING UP</span>
                            <span v-if="item.bids > 7 && item.bids < 10" class="badge badge-pill" style="background: #FEBB56; color:#fff">GETTING HOT</span>
                            <span v-if="item.bids > 9 && item.bids < 11" class="badge badge-pill" style="background: #FC592F; color:#fff">SMOKING HOT</span>
                            <span v-if="item.bids > 11" class="badge badge-pill" style="background: #FF0000; color:#fff">ITS A BID WAR</span>
                        </h4>
                    </td>
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
            params: {date: '', supervisor_id: '', site_id: '', site_start: '', trade_id: '', _token: $('meta[name=token]').attr('value')},
            items: [],
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
                        //this.xx.searching = false;
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
