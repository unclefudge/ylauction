@extends('layouts/main')

@section('content')
    {{--}}
    <div class="jumbotron">
        <div class="container">
            <h1>Auction Items</h1>
        </div>
    </div>
    --}}

    <style>
        .auction-item {
            cursor: pointer;
        }

        .auction-item:hover {
            background: #efefef;
        }

        .item-info {
            margin: 20px;
        }
        @media (max-width: 500px) {
            .item-info {
                margin: 10px;
            }
        }

    </style>
    <div class="container" id="vue-app">
        <br>
        <h3 class="mb-3">Auction Items</h3>

        <div v-if="xx.items" class="row">
            <div v-for="item in xx.items" class="col-lg-6">
                <div v-on:click="viewItem(item)" class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative auction-item">
                    <div class="col-4 d-block">
                        <img v-bind:src="item.image1" class="img-fluid">
                    </div>
                    <div class="col d-flex flex-column position-static item-info">
                        <h3 class="mb-0 d-none d-sm-block">@{{ item.name }}</h3> {{-- regular --}}
                        <h5 class="mb-0 d-block d-sm-none">@{{ item.name }}</h5> {{-- mobile --}}
                        <div class="mb-1 text-muted">Current bid: $@{{ item.price }} &nbsp; <span v-if="item.winner == 1" class="badge badge-success">You are highest bidder</span></div>
                        <p class="mb-auto d-none d-sm-block">@{{ item.brief_description }}</p>

                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            No auction items listed
        </div>

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
        var xx = {items: []};

        new Vue({
            el: '#vue-app',
            data: function () {
                return {xx: xx};
            },
            methods: {
                loadData: function () {
                    $.getJSON('/data/auctions/item/all', function (data) {
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
