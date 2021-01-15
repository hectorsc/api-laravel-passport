<?php

namespace App\Http\Controllers\API;

use App\CEO;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CEOResource;

class CEOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ceos = CEO::all();
        return response(['ceos' => CEOResource::collection($ceos), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|max:25',
            'company_name' => 'required|max:25',
            'company_headquarters' => 'required|max:30',
            'year' => 'required|max:4',
            'what_company_does' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors(), 'Validation Error']);
        }

        $ceo = CEO::create($data);
        return response([ 'ceo' => new CEOResource($ceo), 'message' => 'Created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CEO  $cEO
     * @return \Illuminate\Http\Response
     */
    public function show(CEO $ceo)
    {
        return response(['ceo' => new CEOResource($ceo), 'message' => 'Retrieved successfully', 200]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CEO  $cEO
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CEO $ceo)
    {
        $request->validate([
            'name' => 'required|max:25',
            'company_name' => 'required|max:25',
            'company_headquarters' => 'required|max:30',
            'year' => 'required|max:4',
            'what_company_does' => 'required'
        ]);

        $ceo->update($request->all());
        return response(['ceo' => new CEOResource($ceo), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CEO  $cEO
     * @return \Illuminate\Http\Response
     */
    public function destroy(CEO $ceo)
    {
        $ceo->delete();
        return response(['message' => 'Deleted']);
    }
}
