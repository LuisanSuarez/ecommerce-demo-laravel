<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'attrDemo' => $this->attrDemo,
            'total' => $this->total,
            'created_at' => $this->created_at,
            'order_items' => OrderItemResource::collection($this->whenLoaded('orderItems'))

        ];
    }
}
