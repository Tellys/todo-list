<?php

namespace App\Http\Resources;

class TennisCourtOpeningHourCollection extends NossaResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        $r = $this->getAllFields((new \App\Http\Controllers\Api\TennisCourtOpeningHourController())->data['campos']);
        //$r['purchase_expiration_time'] = $this->purchase_expiration_time !== null ? $this->purchase_expiration_time->format('H:i') :  null;

        return $r;
    }
}
