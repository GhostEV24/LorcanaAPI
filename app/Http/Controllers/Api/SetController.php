<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SetResource;
use App\Models\Card;
use App\Models\Set;
use Illuminate\Http\Request;

class SetController extends Controller
{
    public function index()
    {
        //récupération des sets
        $sets = Set::all();
        //envoi en json
        return SetResource::collection($sets);
    }

    public function single($id)
    {
        //récupération du set
        $set = Set::findOrFail($id);
        //envoi en json
        return new SetResource($set);
    }

    public function cards($id)
    {
        $set = Set::findOrFail($id);

        return response()->json(
            [
                "data" => $set->cards
            ]
        );
    }
}
