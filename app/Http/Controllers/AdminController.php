<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Validator;
use App\User;
use App\Models\AuctionItem;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check authorisation
        if (!Auth::user()->admin)
            return abort(404);

        //dd('jj');
        $items = AuctionItem::where('status', 1)->orderBy('name')->get();

        return view('admin/index', compact('items'));
    }

    public function show($id)
    {
        //
    }

    public function auctionLive()
    {
        return view('admin/auction-live');
    }

    public function auctionMax()
    {
        return view('admin/auction-max');
    }

    /**
     * Get Items (ajax)
     */
    public function getItems()
    {
        $items = AuctionItem::where('status', 1)->orderBy('updated_at', 'desc')->get();
        $items_array = [];
        foreach ($items as $item) {
            $bids = $item->bids->count();
            $it = [
                'id'                => $item->id,
                'name'              => $item->name,
                'price'             => $item->price,
                'reserve'           => $item->reserve,
                'bids'              => $bids,
                'bidders'           => $item->bids->groupBy('uid')->count(),
                'highest_bid'       => $item->highest_bid,
                'highest_bid_id'    => ($item->topBidder()) ? $item->topBidder()->bidder_id : '',
                'highest_bid_name'  => ($item->topBidder()) ? $item->topBidder()->name : '',
                'highest_bid_max'   => ($item->topBidder()) ? $item->topBid->bid : '',
                'description'       => $item->description,
                'brief_description' => $item->brief_description,
                'image1'            => $item->img1,
                'updated_at'        => $item->updated_at->format('G:i:s'),
                'status'            => $item->status,
            ];
            $items_array[] = $it;
        }

        return $items_array;
    }


}
