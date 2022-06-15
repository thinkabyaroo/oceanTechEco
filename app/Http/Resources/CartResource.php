<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->photo === null){
            $this->photo = asset('user-default.jpg');
        }else{
            $this->photo = asset('storage/photo/'.$this->photo);
        }
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "amount"=>$this->amount,
            "quantity"=>$this->quantity,
            "user_id"=>$this->user_id,
            "photo"=>$this->photo,
            "product_id"=>$this->product_id,
        ];
    }
}
