<?php

namespace App\Api\V1\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\Air;
use Illuminate\Http\Request;
use App\Transformers\SiteTransformer;
use App\Transformers\TXOTransformer;

/**
 * Site resource representation.
 *
 * @Resource("Site", uri="/sites")
 */
class SiteController extends Controller
{
    /**
     * Show all sites
     *
     * Get a JSON representation of all the sites.
     *
     * @Get("/")
     * @Versions({"v1"})
     * @Request(headers={"Accept": "application/vnd.orsatmax.v1+json", "x-show-site-fields": "formal_name,short_name"})
     */
    public function index()
    {
      return $this->response->collection(
        Site::orderBy('instrument_name', 'asc')->get(),
        new SiteTransformer
      );
    }

    /**
     * Show site dumps
     *
     * Get a JSON representation of all the site dumps.
     *
     * @Post("/{site_id}/dumps")
     * @Versions({"v1"})
     * @Request({"standard": "E|C|Q|B|S, "sample_date": "YYYY-MM-DD", "component_names": "" }, headers={"Accept": "application/vnd.orsatmax.v1+json"})
     */
    public function siteDumps(Request $request, $id) {
        $standard = $request->input('standard');
        $sample_date = $request->input('sample_date');
        $component_names = $request->input('component_names');

        $airIDs = [];
        if(
          !is_null($component_names) ||
          !empty($component_names)
        ) {
          $airs = Air::whereIn('component_name', $component_names)
            ->orWhereIn('alias', $component_names)
            ->get();
          foreach($airs as $air) {
            $airIDs[] = $air->id;
          }
        }

        $site = Site::with(['dumps' => function($query) use ($standard, $sample_date, $airIDs) {
            $query->where('sample_date', '>=', $sample_date);
            $query->where('sample_date', '<=', $sample_date);
            $query->whereRaw("SUBSTRING(LOWER(filename),-9,1) = '".$standard."'");
            if(!empty($airIDs)) {
              $query->with(['componentValues' => function($query) use ($airIDs) {
                $query->whereHas('air', function($query) use ($airIDs) {
                  $query->whereIn('id', $airIDs);
                });
              }]);
            }
        }])
        ->where('id', '=', $id)
        ->first();
        $dumps = $site->dumps;
        $dumps = $dumps->filter(function(&$txo) {
            $componentValues = $txo->componentValues;
            $componentValues = $componentValues->filter(function($componentValue) {
                return $componentValue->air ? true : false;
            });
            $txo->componentValues = $componentValues;
            return true;
        });

        if(
          !is_null($component_names) ||
          !empty($component_names)
        ) {
          $dumps = $dumps->filter(function($txo) {
            return $txo->componentValues->count() > 0;
          });
        }
        return $this->response->collection(
          $dumps,
          new TXOTransformer
        );
    }

}
