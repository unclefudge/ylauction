<?php

namespace App\Models;

use App\Http\Utilities\Slim;
//use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;

class AuctionBid extends Model {

    protected $table = 'auction_bids';
    protected $fillable = ['aid', 'uid', 'bid'];

    /**
     * A AuctionBid belongs to a Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo('App\Models\AuctionItem', 'aid');
    }

    /**
     * A AuctionBid belongs to a User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'uid');
    }
}