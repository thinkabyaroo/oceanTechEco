<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            $this->photo=asset('storage/photo/'.$this->photo);
        }
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'description'=>$this->description,
            'brand_id'=>$this->brand_id,
            'category_id'=>$this->category_id,
            'photo'=>$this->photo,
        ];
    }
}
