@extends('layouts/main')

@section('content')
    <div class="container-fluid" id="vue-app">
        <br>
        <h1 class="mb-3 text-center">Auction Live <small v-if="xx.auction_status == 0" class="badge badge-pill badge-warning ml-3">PAUSED</small></h1>

        <table class="table table-hover table-sm">
            <thead>
            <tr>
                <th scope="col" width="10%">Price</th>
                <th scope="col">Item</th>
                <th scope="col" width="10%">Bids</th>
                <th scope="col" width="10%">Winner</th>
                <th scope="col">Status</th>
            </tr>
            </thead>
            <tbody>
            <div v-if="xx.items">
                <tr v-for="item in xx.items">
                    <td><h5>$@{{ item.price }}</h5></td>
                    <td><h5>@{{ item.name }} <small v-if="item.reserve && item.price < item.reserve" class="text-danger"> &nbsp; RESERVE NOT MET</small></h5></td>
                    <td><h5>@{{ item.bids }}</h5></td>
                    <td><h5 v-if="item.highest_bid_id">#@{{ item.highest_bid_id }}</h5></td>
                    <td>
                        <h5>
                            <span v-if="item.bids == 1" class="badge badge-pill badge-secondary">NO COMPETITION</span>
                            <span v-if="item.bids > 1 && item.bids < 4" class="badge badge-pill" style="background: #FFFFD2; color:#000">SOME COMPETITION</span>
                            <span v-if="item.bids > 3 && item.bids < 6" class="badge badge-pill" style="background: #FFF0AA; color:#000">SOME ACTION</span>
                            <span v-if="item.bids > 5 && item.bids < 8" class="badge badge-pill" style="background: #FEDE81; color:#000">WARMING UP</span>
                            <span v-if="item.bids > 7 && item.bids < 10" class="badge badge-pill" style="background: #FEBB56; color:#fff">GETTING HOT</span>
                            <span v-if="item.bids > 9 && item.bids < 11" class="badge badge-pill" style="background: #FC592F; color:#fff">SMOKING HOT</span>
                            <span v-if="item.bids > 11" class="badge badge-pill" style="background: #FF0000; color:#fff">ITS A BID WAR</span>
                        </h5>
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
                }.bind(this), 1000);
            }
        });
    </script>
@endsection
