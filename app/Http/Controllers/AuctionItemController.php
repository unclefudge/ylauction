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

class AuctionItemController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check authorisation
        if (!Auth::user()->admin)
            return abort(404);
    }

    public function show($id)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check authorisation
        if (!Auth::user()->admin)
            return abort(404);

        return view('admin/items/create');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function edit($id)
    {
        // Check authorisation
        if (!Auth::user()->admin)
            return abort(404);

        $item = AuctionItem::findOrFail($id);

        return view('admin/items/edit', compact('item'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        // Check authorisation
        if (!Auth::user()->admin)
            return abort(404);

        // Validate
        $rules = ['name' => 'required', 'description' => 'required', 'image1' => 'required_without:image2,image3,image4'];
        $mesgs = [
            'name.required'           => 'The name is required.',
            'description.required'    => 'The description is required.',
            'image1.required_without' => 'At least one image is required.',
        ];
        request()->validate($rules, $mesgs);

        $item_request = request()->except('image1', 'image2', 'image3', 'image4');
        //dd(request()->all());
        $item = AuctionItem::create($item_request);

        // Handle attached images
        if (request()->image1) $item->attachImage('1');
        if (request()->image2) $item->attachImage('2');
        if (request()->image3) $item->attachImage('3');
        if (request()->image4) $item->attachImage('4');

        //Toastr::success("Item created");

        return redirect("/admin");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        // Check authorisation
        if (!Auth::user()->admin)
            return abort(404);

        $item = AuctionItem::findOrFail($id);

        // Validate
        $rules = ['name' => 'required', 'description' => 'required',];
        $mesgs = [
            'name.required'        => 'The name is required.',
            'description.required' => 'The description is required.',
        ];
        request()->validate($rules, $mesgs);

        $item_request = request()->except('image1', 'image2', 'image3', 'image4');
        //dd(request()->all());
        $item->update($item_request);

        // Handle attached images
        $path = public_path("/auction/images/");   // Server path

        /*
        if (request()->image1)
            $item->attachImage('1');
        elseif (request('previous_image1')) {
            // Delete file
            if ($item->image1 && file_exists($path . $item->image1))
                unlink($path . $item->image1);
            $item->image1 = null;
            $item->save();
        }*/

        if (request()->image1) $item->attachImage('1');
        if (request()->image2) $item->attachImage('2');
        if (request()->image3) $item->attachImage('3');
        if (request()->image4) $item->attachImage('4');

        //Toastr::success("Item created");

        return redirect("/admin");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Check authorisation
        if (!Auth::user()->admin)
            return abort(404);

        $item = AuctionItem::findOrFail($id);
        $path = public_path("/auction/images/");   // Server path
        if ($item->image1 && file_exists($path . $item->image1))
            unlink($path . $item->image1);
        if ($item->image2 && file_exists($path . $item->image2))
            unlink($path . $item->image2);
        if ($item->image3 && file_exists($path . $item->image3))
            unlink($path . $item->image3);
        if ($item->image3 && file_exists($path . $item->image4))
            unlink($path . $item->image4);
        $item->delete();

        return redirect("/admin");

    }
}
