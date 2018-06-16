<?php
namespace App\Transformers;

use App\Air;
use League\Fractal\TransformerAbstract;

class AirTransformer extends TransformerAbstract
{
    public function transform(Air $air) {
        return [
          'id' => (int) $air->id,
          'component_name' => (string) $air->component_name,
          'alias' => (string) $air->alias,
          'aqi_no' => (string) $air->aqi_no,
          'carbon_no' => (int) $air->carbon_no,
          'cas' => (string) $air->cas
        ];
    }
}
