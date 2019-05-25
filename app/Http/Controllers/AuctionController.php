<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Validator;
use App\User;
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

        return view('auction/index', compact('items'));
    }

    public function show($id)
    {
        $item = AuctionItem::findOrFail($id);

        return view('auction/show', compact('item'));
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

            // Determine Highest Bidder
            $topBid = $item->topBid;
            if ($topBid) {
                if ($newBid > $topBid->bid) {   // New bid is higher then previous
                    $item->price = ($item->nextBid($topBid->bid) < $newBid) ? $item->nextBid($topBid->bid) : $newBid; //;
                    $item->highest_bid = $bid_id;
                } elseif ($topBid->uid != $uid)
                    $item->price = $newBid;     // New bid is less then or same as previous offer but also not by current Top Bidder
            } else {
                // First ever bid on item
                $item->price = 5;
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
        $items = ($id == 'all') ? AuctionItem::where('status', 1)->orderBy('name')->get() : AuctionItem::where('id', $id)->get();
        $items_array = [];
        foreach ($items as $item) {
            $bids = $item->bids->count();
            $bids_bg = 'badge-info';
            if ($bids > 3) $bids_bg = 'badge-warning';
            if ($bids > 6) $bids_bg = 'badge-danger';
            $it = [
                'id'                => $item->id,
                'name'              => $item->name,
                'price'             => $item->price,
                'reserve'           => $item->reserve,
                'bids'              => $bids,
                'bid_min'           => $item->nextBid($item->price),
                'bids_bg'           => $bids_bg,
                'highest_bid'       => $item->highest_bid,
                'highest_bid_id'    => ($item->topBidder()) ? $item->topBidder()->bidder_id : '',
                'highest_bid_name'  => ($item->topBidder()) ? $item->topBidder()->name : '',
                'winner'            => ($item->topBidder() && $item->topBidder()->id == Auth::user()->id) ? 1 : 0,
                'winner_max'        => ($item->topBidder() && $item->topBidder()->id == Auth::user()->id) ? $item->topBid->bid : 0,
                'order'             => $item->order,
                'description'       => $item->description,
                'brief_description' => $item->brief_description,
                'image1'            => $item->img1,
                'image2'            => $item->img2,
                'image3'            => $item->img3,
                'image4'            => $item->img4,
                'status'            => $item->status,
            ];
            if ($id == 'all')
                $items_array[] = $it;
            else
                return $it;
        }

        return $items_array;
    }
}
