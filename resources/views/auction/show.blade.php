@extends('layouts/main')

@section('content')
    <style>
        .auction-item {
            cursor: pointer;
        }

        .auction-item:hover {
            background: #efefef;
        }

    </style>
    <div class="container" id="vue-app">
        <div class="row">
            <div class="col">
                <br><a href="/auctions" class="btn btn-outline-primary float-right"><i class="fa fa-arrow-left"></i> Return to Auction List</a><br><br>
            </div>
        </div>
        <div class="row featurette">
            <div class="col-md-4 text-center">
                <div style="padding: 5px; margin-top: 10px">
                    <img src="{{ $item->img1 }}" class="img-fluid" id="big_image">
                </div>

                @if ($item->image_count > 1)
                    <?php $col_width = "col-" . (12 / $item->image_count); $col_width = 'col-3'?>
                    <div class="row d-flex justify-content-center border p-1">
                        <div class="{{ $col_width }} auction-item"><img src="{{ $item->img1 }}" class="img-fluid" id="img1"></div>
                        <div class="{{ $col_width }} auction-item"><img src="{{ $item->img2 }}" class="img-fluid" id="img2"></div>
                        @if ($item->img3)
                            <div class="{{ $col_width }} auction-item"><img src="{{ $item->img3 }}" class="img-fluid" id="img3"></div>
                        @endif
                        @if ($item->img4)
                            <div class="{{ $col_width }} auction-item"><img src="{{ $item->img4 }}" class="img-fluid" id="img4"></div>
                        @endif
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <div class="card mb-4 shadow-sm mt-3">
                    <div class="card-header">
                        <h1 class="my-0 font-weight-normal">{{ $item->name }}</h1>
                        <h5 class="card-subtitle text-muted pt-1">Donated by {{ $item->donated_by }}</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <h2 class="card-title pricing-card-title">
                                <small class="text-muted">Current bid:</small>
                                $@{{ xx.item.price }}
                                <small class="float-right">
                                    <span style="font-size:16px">@{{ xx.item.bids }} bids</span>
                                    <span v-if="xx.item.bids == 1" class="badge badge-pill badge-secondary">NO COMPETITION</span>
                                    <span v-if="xx.item.bids > 1 && xx.item.bids < 4" class="badge badge-pill" style="background: #FFFFD2; color:#000">SOME COMPETITION</span>
                                    <span v-if="xx.item.bids > 3 && xx.item.bids < 6" class="badge badge-pill" style="background: #FFF0AA; color:#000">SOME ACTION</span>
                                    <span v-if="xx.item.bids > 5 && xx.item.bids < 8" class="badge badge-pill" style="background: #FEDE81; color:#000">WARMING UP</span>
                                    <span v-if="xx.item.bids > 7 && xx.item.bids < 10" class="badge badge-pill" style="background: #FEBB56; color:#fff">GETTING HOT</span>
                                    <span v-if="xx.item.bids > 9 && xx.item.bids < 12" class="badge badge-pill" style="background: #FC592F; color:#fff">SMOKING HOT</span>
                                    <span v-if="xx.item.bids > 11" class="badge badge-pill" style="background: #FF0000; color:#fff">ITS A BID WAR</span>
                                </small>
                            </h2>
                            <p>Lead bidder: #@{{ xx.item.highest_bid_id }} <span v-if="xx.item.reserve && xx.item.price < xx.item.reserve" class="text-danger"> &nbsp; RESERVE NOT MET</span></p>
                            <h4 v-if="xx.item.winner == 1"><span class="badge badge-success">You are highest bidder</span> &nbsp; <span style="font-size: 14px">Up to Max: $@{{ xx.item.winner_max }}</span></h4>

                        </div>
                        <br>

                        {{-- Auction Live --}}
                        <div v-if="xx.item.auction_status">
                            {{-- Admin bid on behalf --}}
                            @if (Auth::user()->admin)
                                <select v-model="xx.bidder" class="custom-select form-control-lg" aria-label="Large" id="bidder" onChange="bidderName(this)" required>
                                    <option value="">Place bid on behalf of</option>
                                    @foreach (\App\User::where('admin', 0)->get() as $user)
                                        <option value="{{ $user->id }}">#{{ $user->bidder_id }} &nbsp; {{ $user->name }}</option>
                                    @endforeach
                                </select>
                                </br><br>
                            @endif

                            {{-- Place Bid --}}
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="bid">$</label>
                                        </div>
                                        <input v-model="xx.bid" type="text" class="form-control form-control-lg allownumericwithoutdecimal" name="bid" id="bid" required="">
                                    </div>
                                    Enter $@{{ xx.item.bid_min  }} or more
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-block btn-lg btn-primary" data-toggle="modal" data-target="#confirmModal">Place bid</button>
                                </div>
                            </div>
                        </div>

                        {{-- Auction Paused --}}
                        <div v-else>
                            <h3>
                                <span class="badge badge-pill badge-warning">Auction currently paused</span>
                            </h3>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <br>
        <div class="row">
            <div class="col">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h3 class="my-0 font-weight-normal">Description</h3>
                    </div>
                    <div class="card-body">
                        {!! $item->description !!}
                    </div>
                </div>

            </div>
        </div>

        <!-- Confirm bid Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <form method="POST" action="/auctions/{{ $item->id }}">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-white" v-bind:class="modalClass()">
                            <h5 class="modal-title" id="exampleModalLabel">{{ $item->name }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <input v-model="xx.bid" name="mybid" type="hidden" value="">
                            <input v-model="xx.bidder" name="mybidder" type="hidden" value="">
                            <div v-if="xx.admin == 1 && !xx.bidder">
                                {{-- Admin - but no bidder given --}}
                                <h4 class="text-danger">Must select a bidder to place bid</h4>
                                <h4 class="text-primary">{{ $item->name }}</h4>
                                <br>
                            </div>
                            <div v-else>
                                {{-- Confirm bid --}}
                                <div v-if="xx.bid >= xx.item.bid_min">
                                    <h4>Please confirm your bid for</h4>
                                    <h4 class="text-primary">{{ $item->name }}</h4>
                                    @if (Auth::user()->admin)
                                        <h4><span class="badge badge-info">on behalf of @{{ xx.bidder_name }}</span></h4>
                                    @endif

                                    <br>
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-6 border p-3"><h3>$@{{ xx.bid }}</h3></div>
                                    </div>
                                </div>
                                {{-- Insufficient bid --}}
                                <div v-else>
                                    <h4 class="text-danger">Insufficient bid for</h4>
                                    <h4 class="text-primary">{{ $item->name }}</h4>
                                    <br>
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-9 border p-3"><h3>Minimum bid $@{{ xx.item.bid_min }}</h3></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <div v-if="xx.admin == 1 && !xx.bidder || xx.bid < xx.item.bid_min">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">I Understand</button>
                            </div>
                            <div v-else>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Confirm bid</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!--<pre>@{{ $data }}</pre>-->
    </div>
@endsection

@section('page-styles')
@stop

@section('page-scripts')
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="/js/vue.min.js"></script>
    <script type="text/javascript">
        var xx = {
            //bid: "{{ $item->nextBid($item->price) }}", item: '',
            bid: '', bidder: '', bidder_name: '', admin: "{{ (Auth::user()->admin) ? 1 : 0 }}", item: '',
        };

        $(document).ready(function () {
            // Gallery
            $("#img1").click(function () {
                $("#big_image").attr('src', "{{ $item->img1}}");
            });
            $("#img2").click(function () {
                $("#big_image").attr('src', "{{ $item->img2}}");
            });
            $("#img3").click(function () {
                $("#big_image").attr('src', "{{ $item->img3}}");
            });
            $("#img4").click(function () {
                $("#big_image").attr('src', "{{ $item->img4}}");
            });

            $(".allownumericwithoutdecimal").on("keypress keyup blur", function (event) {
                $(this).val($(this).val().replace(/[^\d].+/, ""));
                if ((event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });

        });

        function bidderName(sel) {
            //alert(sel.options[sel.selectedIndex].text);
            xx.bidder_name = sel.options[sel.selectedIndex].text;
        }

        new Vue({
            el: '#vue-app',
            data: function () {
                return {xx: xx};
            },
            methods: {
                loadData: function () {
                    $.getJSON('/data/auctions/item/' + {{ $item->id }}, function (data) {
                        this.xx.item = data;
                        //this.xx.searching = false;
                    }.bind(this));
                },
                viewItem: function (item) {
                    window.location.href = "/auction/item/" + item.id;
                },
                modalClass: function () {
                    if (this.xx.bid >= this.xx.item.bid_min)
                        return 'bg-info'
                    return 'bg-danger';
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
