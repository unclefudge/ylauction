@extends('layouts/main')

@section('content')
    <div class="jumbotron jumbotron-fluid" style="color: #FFD700; background: #000">
        <div class="container text-center">
            <h1>
                <small>Welcome to Barossa Young Life </small>
                <br>Trivia Night.
            </h1>
        </div>
    </div>

    <div class="container" id="vue-app">
        <h3 class="mb-3">Hows Does it Work?</h3>
        <p>We have a online system that allows you to bid on our fabulous items up for auction. It's simple to use and lots of fun.</p>
        <p>If you've ever used eBay then you'll be familiar with how this auction works. Each item is listed with a current bid, along with the bidders #number. Your bidder #number can be found in the top right of screen. Once you find an auction item you like, you can then place a bid using our automatic bidding system then sit back and have fun.</p>
        <h5 style="text-decoration: underline;">Automatic Bidding</h5>
        <p>Automatic bidding is easy to use. Simply enter the highest price you're willing to pay for an item or feel comfortable with. We'll then bid on your behalf – just enough to keep you in the lead, but only up to your maximum limit.</p>
        <p>You'll be able to follow along on the current auction prices and if someone outbids you, then you can decide if you want to increase your maximum limit.</p>
        <h5 style="text-decoration: underline;">Minimum Bid Increase</h5>
        <p>New bids must be higher then the 'Minimum Increase' which are as follows:</p>
        <ul>
            <li>Items under $100 - Minimum $5 increase</li>
            <li>Items over $100 - Minimum $10 increase</li>
            <li>Items over $200 - Minimum $20 increase</li>
        </ul>
        <h5 style="text-decoration: underline;">Reserve Price</h5>
        <p>Some items have a specified reserve price, which means that until the reserve has been met then the item hasn't been won.</p>

        <a class="btn btn-lg btn-primary" href="/auctions" role="button">View Auction Items</a>

        <br><br><br>
    </div>
@endsection

@section('page-styles')
@stop

@section('page-scripts')
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script type="text/javascript">

    </script>
@endsection
