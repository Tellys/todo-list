<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NossaResource extends JsonResource
{
    public $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function getAllFields(array $controllerDataCampos = [])
    {
        foreach ($controllerDataCampos as $k => $v) {
            switch ($k) {
                default:
                    $r[$k] = $this->$k;
                    break;
            }
        }

        $r['id'] = $this->id;

        $r['deleted_at'] = $this->deleted_at !== null ? $this->deleted_at->format('d/m/Y H:i:s') :  null;
        $r['created_at'] = $this->created_at !== null ? $this->created_at->format('d/m/Y H:i:s') :  null;
        $r['updated_at'] = $this->updated_at !== null ? $this->updated_at->format('d/m/Y H:i:s') :  null;

        return $r;
    }
}
