<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Validator;
use App\User;
use App\Models\AuctionConfig;
use App\Models\AuctionItem;
use App\Models\AuctionBid;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuctionController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //dd('here');
        $items = AuctionItem::where('status', 1)->orderBy('name')->get();
        $config = AuctionConfig::find(1);

        return view('auction/index', compact('items', 'config'));
    }

    public function show($id)
    {
        $item = AuctionItem::findOrFail($id);
        $config = AuctionConfig::find(1);

        return view('auction/show', compact('item', 'config'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $item = AuctionItem::findOrFail($id);

        // Validate
        $rules = ['mybid' => 'required'];
        $mesgs = ['mybid.required' => 'A bid is required.',];
        request()->validate($rules, $mesgs);
        //dd(request()->all());

        // Create Bid
        $newBid = request('mybid');
        $uid = (Auth::user()->admin && request('mybidder')) ? request('mybidder') : Auth::user()->id;
        $now = Carbon::now()->toDateTimeString();
        $bid_id = DB::table('auction_bids')->insertGetId(['aid' => $item->id, 'uid' => $uid, 'bid' => $newBid, 'created_at' => $now, 'updated_at' => $now]);

        // Ensure New Bid is higher then current price
        if ($newBid > $item->price) {

            // price 100  max 200  new 210  = 210
            // price 100  max 200  new 300  = 220
            // price 100  max 200  new 200  = 200

            // Determine Highest Bidder
            $topBid = $item->topBid;
            if ($topBid) {
                if ($newBid > $topBid->bid) {   // New bid is higher then previous
                    if ($topBid->uid != $uid)  // New bidder is different from current winner so update price
                        $item->price = ($item->nextBid($topBid->bid) > $newBid) ? $newBid : $item->nextBid($topBid->bid);
                    $item->max = $newBid;
                    $item->highest_bid = $bid_id;
                } elseif ($topBid->uid != $uid)
                    $item->price = $newBid;     // New bid is less then or same as previous offer but also not by current Top Bidder
            } else {
                // First ever bid on item
                $item->price = 5;
                $item->max = $newBid;
                $item->highest_bid = $bid_id;
            }

            // Save item changes
            $item->save();
        }

        return redirect("/auctions/$item->id");
    }


    /**
     * Get Items (ajax)
     */
    public function getItem($id)
    {
        $config = AuctionConfig::find(1);
        $items = ($id == 'all') ? AuctionItem::where('status', 1)->orderBy('order')->orderBy('name')->get() : AuctionItem::where('id', $id)->get();
        $items_array = [];
        foreach ($items as $item) {
            $bids = $item->bids->count();
            $bid_history = [];
            foreach ($item->bids as $bid) {
                $table = ($bid->user->table) ? ' (table ' . $bid->user->table . ')' : '';
                $bid_history[] = $bid->created_at->format('h:ia') . ' - #' . $bid->user->bidder_id . ' ' . $bid->user->name . $table;
            }
            $bids_bg = 'badge-info';
            if ($bids > 3) $bids_bg = 'badge-warning';
            if ($bids > 6) $bids_bg = 'badge-danger';
            $it = [
                'id'                => $item->id,
                'name'              => $item->name,
                'price'             => $item->price,
                'max'               => $item->max,
                'reserve'           => $item->reserve,
                'highest_bid'       => $item->highest_bid,
                'highest_bid_id'    => ($item->topBidder()) ? $item->topBidder()->bidder_id : '',
                'highest_bid_name'  => ($item->topBidder()) ? $item->topBidder()->name : '',
                'winner'            => ($item->topBidder() && $item->topBidder()->id == Auth::user()->id) ? 1 : 0,
                'winner_max'        => ($item->topBidder() && $item->topBidder()->id == Auth::user()->id) ? $item->topBid->bid : 0,
                'bids'              => $bids,
                'bid_min'           => $item->nextBid($item->price),
                'bids_bg'           => $bids_bg,
                'bid_history'       => $bid_history,
                'order'             => $item->order,
                'brief'             => $item->brief,
                'brief_description' => $item->brief_description,
                'description'       => $item->description,
                'image1'            => $item->img1,
                'image2'            => $item->img2,
                'image3'            => $item->img3,
                'image4'            => $item->img4,
                'status'            => $item->status,
                'auction_status'    => $config->status,
            ];
            if ($id == 'all')
                $items_array[] = $it;
            else
                return $it;
        }

        return $items_array;
    }
}
