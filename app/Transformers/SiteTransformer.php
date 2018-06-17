<?php
namespace App\Transformers;

use App\Models\Site;
use League\Fractal\TransformerAbstract;

class SiteTransformer extends TransformerAbstract
{
    protected $defaultFields = [
        'id',
        'instrument_name'
    ];

    protected $availableFields = [
        'formal_name',
        'short_name'
    ];

    public function transform(Site $site) {
        $data = [];
        foreach($this->defaultFields as $defaultField) {
           $data[$defaultField] = $site->{$defaultField};
        }

        $showFields = request()->header('x-show-site-fields');
        $showFields = explode(',', $showFields);
        if(!empty($showFields)) {
          $showFields = array_intersect($showFields, $this->availableFields);
          foreach ($showFields as $showField) {
            $data[$showField] = $site->{$showField};
          }
        }

        return $data;
    }
}
