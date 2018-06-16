<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;

use App\Air;
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
     * @param  \App\Air  $air
     * @return \Illuminate\Http\Response
     */
    public function show(Air $air)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Air  $air
     * @return \Illuminate\Http\Response
     */
    public function edit(Air $air)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Air  $air
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Air $air)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Air  $air
     * @return \Illuminate\Http\Response
     */
    public function destroy(Air $air)
    {
        //
    }
}
