<?php

namespace App\Http\Resources\V4;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonVoiceResource extends JsonResource
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
            'role' => $this['role'],
            'anime' => [
                'mal_id' => $this['anime']['mal_id'],
                'url' => $this['anime']['url'],
                'images' => $this['anime']['images'],
                'title' => $this['anime']['title']
            ],
            'character' => [
                'mal_id' => $this['character']['mal_id'],
                'url' => $this['character']['url'],
                'images' => $this['character']['images'],
                'name' => $this['character']['name']
            ]
        ];
    }
}