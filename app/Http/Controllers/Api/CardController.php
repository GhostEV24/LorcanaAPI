<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Récupérer toutes les cartes
     */
    public function index()
    {
        return response()->json([
            "data" => CardResource::collection(Card::all())
        ]);
    }

    /**
     * Récupérer une carte spécifique
     */
    public function single($id)
    {
        $card = Card::findOrFail($id);
        return new CardResource($card);
    }
}
