<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'status' => $this->status,
            'status_desc' => config('status.'.$this->status),
            'user' => new UserResource($this->user),
            'tags' => $this->tags != null ? explode("|", $this->tags) : [],
            'created' => $this->created_at->format('d/m/Y H:i'),
            'created_at' => $this->created_at
        ];
    }
}
