<?php
namespace App\Transformers;

use App\Models\TXO;
use League\Fractal\TransformerAbstract;

class TXOTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'componentValues'
    ];

    protected $defaultFields = [
        'id',
        'filename_id',
        'filename'
    ];

    protected $availableFields = [
        'software_version'
    ];

    public function transform(TXO $txo) {
        $data = [];
        foreach($this->defaultFields as $defaultField) {
           $data[$defaultField] = $txo->{$defaultField};
        }

        $showFields = request()->header('x-show-txo-fields');
        $showFields = explode(',', $showFields);
        if(!empty($showFields)) {
          $showFields = array_intersect($showFields, $this->availableFields);
          foreach ($showFields as $showField) {
            $data[$showField] = $txo->{$showField};
          }
        }

        return $data;
    }

    public function includeComponentValues(TXO $txo) {
      return $this->collection($txo->componentValues, new ComponentValueTransformer);
    }
}
