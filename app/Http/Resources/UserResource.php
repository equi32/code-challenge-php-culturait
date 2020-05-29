<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         $data = parent::toArray($request);
         //Quito los que no necesito
         unset($data['created_at']);
         unset($data['updated_at']);
         //Retorno
         return $data;
    }
}
