<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\AuctionItem;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $items = AuctionItem::where('status', 1)->orderBy('name')->get();

        return view('home', compact('items'));
    }
}
