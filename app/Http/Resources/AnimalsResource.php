<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnimalsResource extends JsonResource
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
            "name" => $this->name,
            "gender" => $this->gender,
            "food" => $this->food,
            "description" => $this->description,
            "image" => $this->image,
            "category" => [
                "category_id" => $this->categories_id,
                "category_name" => $this->category->name
            ],
            "albums" => AlbumsResource::collection($this->albums),
            "author" => [
                "author_name" => $this->user->name,
                "author_email" => $this->user->email
            ]
        ];
    }
}
