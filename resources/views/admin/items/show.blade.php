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
                <br>
                <button v-if="xx.item.bids == 0" class="btn btn-dark float-right ml-3" data-toggle="modal" data-target="#confirmModal"><i class="fa fa-trash-alt"></i></button>
                <a href="/admin/item/{{ $item->id }}/edit" class="btn btn-primary float-right ml-3"> Edit Item</a>
                <a href="/admin" class="btn btn-outline-primary float-right"><i class="fa fa-arrow-left"></i> Return to Auction Management</a>
            </div>
        </div>
        <div class="row featurette">
            <div class="col-md-8">
                <div class="card mb-4 shadow-sm mt-3">
                    <div class="card-header">
                        <h1 class="my-0 font-weight-normal">{{ $item->name }}</h1>
                        <h5 class="card-subtitle text-muted pt-1">Donated by {{ $item->donated_by }}</h5>
                    </div>
                    <div class="card-body">
                        @{{ xx.item.brief }}
                        <hr>
                        <div>
                            <h4 class="card-title pricing-card-title">
                                <small class="text-muted">Current bid:</small>
                                $@{{ xx.item.price }}
                                <div class="float-right">
                                    <small class="text-muted">Reserve:</small>
                                    $@{{ xx.item.reserve }}
                                </div>
                            </h4>
                            <p>Lead bidder: #@{{ xx.item.highest_bid_id }} @{{ xx.item.highest_bid_name }} <span v-if="xx.item.reserve && xx.item.price < xx.item.reserve" class="text-danger"> &nbsp; RESERVE NOT MET</span></p>
                        </div>
                        <br>

                        <h5>Bid History
                            <small class="float-right">No. of bids: @{{ xx.item.bids }}</small>
                        </h5>
                        <hr class="pt-1 mt-1">
                        <div v-for="bid in xx.item.bid_history">
                            @{{ bid }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Images --}}
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

        <!-- Confirm Delete Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <form method="POST" action="/admin/{{ $item->id }}">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <h2>Are you sure?</h2>
                            <p>You will not be able to recover this item</p>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, delete it!</button>
                        </div>
                    </div>
                </div>
            </form>
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
