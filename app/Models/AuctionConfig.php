<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionConfig extends Model {

    protected $table = 'auction_config';
    protected $fillable = ['name', 'welcome', 'rules', 'start', 'end', 'status'];


}