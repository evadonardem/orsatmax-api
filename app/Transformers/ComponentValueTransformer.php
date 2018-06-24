<?php
namespace App\Transformers;

use App\Models\ComponentValue;
use League\Fractal\TransformerAbstract;

class ComponentValueTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'air'
    ];

    protected $defaultFields = [
        'id',
        'channel',
        'peak',
        'amount',
        'time',
        'area',
        'method_rt',
        'status',
        'date_added',
        'data_acquisition_time'
    ];

    protected $availableFields = [
        'component_name'
    ];

    public function transform(ComponentValue $componentValue) {
        $data = [];
        foreach($this->defaultFields as $defaultField) {
           $data[$defaultField] = $componentValue->{$defaultField};
        }

        $showFields = request()->header('x-show-componentvalue-fields');
        $showFields = explode(',', $showFields);
        if(!empty($showFields)) {
          $showFields = array_intersect($showFields, $this->availableFields);
          foreach ($showFields as $showField) {
            $data[$showField] = $componentValue->{$showField};
          }
        }

        return $data;
    }

    public function includeAir(ComponentValue $componentValue) {
        return ($componentValue->air) ? $this->item($componentValue->air, new AirTransformer) : $this->null();
    }
}
