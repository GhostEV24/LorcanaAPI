<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Ajouter une carte à la wishlist.
     */
    public function add(Request $request)
    {
        $user = Auth::user();
        $card = Card::findOrFail($request->input('card_id'));

        $user->wishlist()->attach($card->id);

        return response()->json([
            'message' => 'Carte ajoutée !'
        ]);
    }

    /**
     * Retirer une carte de la wishlist.
     */
    public function delete(Request $request)
    {
        $user = Auth::user();
        $card = Card::findOrFail($request->input('card_id'));

        $user->wishlist()->detach($card->id);

        return response()->json([
            'message' => 'Carte retirée !'
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        return response()->json([
            'wishlist' => $user->wishlist
        ]);
    }


}
