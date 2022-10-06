<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlbumsDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "image" => $this->image,
            "animals" => [
                "animals_id" => $this->animals_id,
                "animals_name" => $this->animals->name,
                "animals_category" => $this->animals->category->name
            ]
        ];
    }
}
