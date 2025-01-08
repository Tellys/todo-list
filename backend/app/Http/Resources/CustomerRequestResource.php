<?php

namespace App\Http\Resources;

class CustomerRequestResource extends NossaResource
{
    public $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return $this->getAllFields((new \App\Http\Controllers\Api\CustomerRequestController())->data['campos']);
    }
}
