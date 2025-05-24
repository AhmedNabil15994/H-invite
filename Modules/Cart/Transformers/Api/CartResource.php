<?php

namespace Modules\Cart\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use IlluminateAgnostic\Collection\Support\Carbon;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
         return [
            'id' => $this['id'],
            'name' => $this['name'],
            'quantity'  => $this['quantity'],
            'price' => number_format($this['price'],3),
            'attributes' => [
                'item_id' => $this['attributes']['item_id'],
                'type' => $this['attributes']['type'],
                'image' => $this['attributes']['image'],
                'discount' => $this['attributes']['product']['discount'] ?? 0,
                'category' => $this['attributes']['product']['category'],
            ],
        ];
    }
}
