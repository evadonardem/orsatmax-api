<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Air;
use Illuminate\Http\Request;

use App\Transformers\AirTransformer;

/**
 * Air resource representation.
 *
 * @Resource("Airs", uri="/airs")
 */
class AirController extends Controller
{
    /**
     * Show all airs
     *
     * Get a JSON representation of all the airs.
     *
     * @Get("/")
     * @Versions({"v1"})
     */
    public function index()
    {
        return $this->response->collection(
          Air::all(),
          new AirTransformer
        );
    }
}
