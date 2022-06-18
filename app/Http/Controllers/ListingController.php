<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListingRequest;
use App\Http\Requests\UpdateListingRequest;
use App\Http\Resources\ListingResource;
use App\Models\Image;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $listings = Listing::with('images')
        ->when($request->query('type'),function($query,$type) {
            $query->where('type',$type);
        })
        ->when($request->query('limit'),function($query,$limit) {
            $query->limit($limit);
        })
        ->when($request->query('offer'),function($query) {
            $query->where('offer',1);
        })
        ->orderByDesc('created_at')
        ->get(); 

        return ListingResource::collection($listings); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreListingRequest $request)
    {
   
        $listing = Listing::create([
            'user_id'        => auth()->id(),
            'type'           => $request->type,
            'name'           => $request->name,
            'bedrooms'       => $request->bedrooms,
            'bathrooms'      => $request->bathrooms,
            'parking'        => $request->parking,
            'furnished'      => $request->furnished,
            'address'        => $request->address,
            'offer'          => $request->offer,
            'regular_price'  => $request->regularPrice,
            'discounted_price' => $request->discountedPrice,
        ]);

        foreach($request->images as $image) {
            $path = Storage::disk('public')->put('listings',$image);
            Image::create([
                'listing_id' => $listing->id,
                'image_path' => $path,
            ]);
        }
        return new ListingResource($listing);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Listing $listing)
    {        
        return new ListingResource($listing);
    }

    public function edit(Listing $listing) {
        
        if(auth()->id() !== $listing->user_id) {
            return response([
                'message' => 'Your not allowed to edit this listing'
            ],403);
        }

        return new ListingResource($listing);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateListingRequest $request, Listing $listing)
    {
        
        $listing->update([
            'type'           => $request->type,
            'name'           => $request->name,
            'bedrooms'       => $request->bedrooms,
            'bathrooms'      => $request->bathrooms,
            'parking'        => $request->parking,
            'furnished'      => $request->furnished,
            'address'        => $request->address,
            'offer'          => $request->offer,
            'regular_price'  => $request->regularPrice,
            'discounted_price' => $request->discountedPrice,
        ]);

        if($request->has('images')) {
            foreach ($listing->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                Image::find($image->id)->delete();
            }

            foreach($request->images as $image) {
                $path = Storage::disk('public')->put('listings',$image);
                Image::create([
                    'listing_id' => $listing->id,
                    'image_path' => $path,
                ]);
            }
        }

        return new ListingResource($listing);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Listing $listing)
    {
        foreach ($listing->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $listing->delete();

        return response([],201);
    }

    public function getLandLordData(Request $request) {
        $request->validate([
            'id' => 'required'
        ]);

        $user = Listing::with('user:id,email,name')->findOrFail($request->id);
        
        $data = [
            'name' => $user->user->name,
            'email' => $user->user->email
        ];
        return response(['data' => $data],200);
    }
}

