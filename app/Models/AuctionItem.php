<?php

namespace App\Models;

use App\User;
use App\Models\AuctionBid;
use App\Http\Utilities\Slim;
//use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;

class AuctionItem extends Model {

    protected $table = 'auction_items';
    protected $fillable = [
        'name', 'description', 'price', 'reserve', 'highest_bid',
        'image1', 'image2', 'image3', 'image4',
        'start', 'end', 'notes', 'status', 'cid'];

    /**
     * A AuctionItem has many Bids
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bids()
    {
        return $this->hasMany('App\Models\AuctionBid', 'aid');
    }

    /**
     * A AuctionItem has a Top Bid
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topBid()
    {
        return $this->hasOne('App\Models\AuctionBid', 'id', 'highest_bid');
    }

    /**
     * A AuctionItem has a Top Bidder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topBidder()
    {
        return ($this->topBid) ? User::findOrFail($this->topBid->uid) : null;
    }


    public function nextBid($bid)
    {
        if ($bid < 100) return $bid + 5;
        if ($bid < 200) return $bid + 10;
        if ($bid < 500) return $bid + 20;
        if ($bid < 100) return $bid + 25;

        return $this->price + 50;
    }

    /**
     * Attach image to AuctionItem
     */
    public function attachImage($image_no)
    {
        // Pass Slim's getImages the name of your file input, and since we only care about one image, use Laravel's head() helper to get the first element
        $image = head(Slim::getImages("image$image_no"));

        // Grab the ouput data (data modified after Slim has done its thing)
        if (isset($image['output']['data'])) {
            $name = "$this->id-$image_no" . '.' . pathinfo($image['output']['name'], PATHINFO_EXTENSION);   // Original file name = $image['output']['name'];
            $data = $image['output']['data'];  // Base64 of the image
            $path = public_path("auction/images/");   // Server path
            $filepath = $path . $name;

            // Save the file to the server + update record
            $file = Slim::saveFile($data, $name, $path, false);
            if ($image_no == 1) $this->image1 = $name;
            if ($image_no == 2) $this->image2 = $name;
            if ($image_no == 3) $this->image3 = $name;
            if ($image_no == 4) $this->image4 = $name;
            $this->save();

            // Save the image as a thumbnail of 90x90
            /*
            if (exif_imagetype($filepath)) {
                Image::make($filepath)->resize(90, 90)->save($path . 's' . $name);
            } else
                Toastr::error("Bad image");
            */

        }
    }

    /**
     * Get brief description (getter)
     *
     * @return string;
     */
    public function getBriefDescriptionAttribute()
    {
        return substr($this->description, 0, 70) . "....";
    }


    public function getNextBidAttribute()
    {
        if ($this->price < 100) return $this->price + 5;
        if ($this->price < 200) return $this->price + 10;
        if ($this->price < 500) return $this->price + 20;
        if ($this->price < 100) return $this->price + 25;

        return $this->price + 50;
    }

    /**
     * Get bid options (getter)
     *
     * @return string;
     */
    public function getBidOptionsAttribute()
    {
        $now = $this->highest_bid;
        $end = 10;

        $options = '';
        while ($now < $end) {
            if ($now < 100) $now = $now + 5;
            elseif ($now < 200) $now = $now + 10;
            elseif ($now < 500) $now = $now + 20;
            elseif ($now < 100) $now = $now + 25;
            else $now = $now + 50;

            $options .= "<option value='$now'>$now</option>";
        }

        return $options;
    }


    /**
     * Get the first image url (getter)
     *
     * @return string;
     */
    public function getImageCountAttribute()
    {
        $path = public_path("/auction/images/");   // Server path
        $count = 0;

        if ($this->image1 && file_exists($path . $this->image1)) $count ++;
        if ($this->image2 && file_exists($path . $this->image2)) $count ++;
        if ($this->image3 && file_exists($path . $this->image3)) $count ++;
        if ($this->image4 && file_exists($path . $this->image4)) $count ++;

        return $count;
    }

    public function getImg1Attribute()
    {
        $path = public_path("/auction/images/");   // Server path

        if ($this->image1 && file_exists($path . $this->image1)) return "/auction/images/$this->image1";
        if ($this->image2 && file_exists($path . $this->image2)) return "/auction/images/$this->image2";
        if ($this->image3 && file_exists($path . $this->image3)) return "/auction/images/$this->image3";
        if ($this->image4 && file_exists($path . $this->image4)) return "/auction/images/$this->image4";

        return '';
    }

    public function getImg2Attribute()
    {
        $path = public_path("/auction/images/");   // Server path

        if ($this->image2 && file_exists($path . $this->image2)) return "/auction/images/$this->image2";
        if ($this->image3 && file_exists($path . $this->image3)) return "/auction/images/$this->image3";
        if ($this->image4 && file_exists($path . $this->image4)) return "/auction/images/$this->image4";

        return '';
    }

    public function getImg3Attribute()
    {
        $path = public_path("/auction/images/");   // Server path

        if ($this->image3 && file_exists($path . $this->image3)) return "/auction/images/$this->image3";
        if ($this->image4 && file_exists($path . $this->image4)) return "/auction/images/$this->image4";

        return '';
    }

    public function getImg4Attribute()
    {
        $path = public_path("/auction/images/");   // Server path

        if ($this->image4 && file_exists($path . $this->image4)) return "/auction/images/$this->image4";

        return '';
    }

}