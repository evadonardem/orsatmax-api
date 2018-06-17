<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Site;
use Illuminate\Http\Request;

use App\Transformers\SiteTransformer;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        //
    }
}
