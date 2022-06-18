<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $images = [];
        foreach ($this->images as $image) {
            $images[] = [
                'image_path' => asset('storage/'.$image->image_path)
            ];
        }

        return [
            'id'                => $this->id,
            'type'              => $this->type,
            'name'              => $this->name,
            'bedrooms'          => $this->bedrooms,
            'bathrooms'         => $this->bathrooms,
            'parking'           => $this->parking,
            'furnished'         => $this->furnished,
            'address'           => $this->address,
            'offer'             => $this->offer,
            'regularPrice'      => $this->regular_price,
            'discountedPrice'   => $this->discounted_price,
            'latitude'          => $this->latitude,
            'longitude'         => $this->longitude,
            'images'            => $images
         ];
    }
}
