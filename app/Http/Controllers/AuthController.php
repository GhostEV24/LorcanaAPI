<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['token' => $user->createToken('API Token')->plainTextToken], 201);

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['email' => 'Identifiants invalides']);
        }

        return response()->json(['message' => 'Connexion réussie', 'token' => $user->createToken('API Token')->plainTextToken]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function myCards(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'cards' => $user->ownedCards()->get()->map(function ($card) {
                return [
                    'id' => $card->id,
                    'name' => $card->name,
                    'quantity' => $card->pivot->quantity,
                    'edition' => $card->pivot->edition,
                ];
            })
        ]);
    }


    public function addCardToCollection(Request $request)
    {
        $user = $request->user();
        $card = Card::findOrFail($request->input('card_id'));

        $quantity = $request->input('quantity', 1); // Par défaut 1 exemplaire
        $edition = $request->input('edition', 'standard'); // Par défaut standard

        // Vérifier si l'utilisateur possède déjà cette carte
        if ($user->ownedCards()->where('card_id', $card->id)->exists()) {
            // Mise à jour du nombre d'exemplaires
            $user->ownedCards()->updateExistingPivot($card->id, [
                'quantity' => DB::raw("quantity + $quantity"),
                'edition' => $edition
            ]);
        } else {
            // Ajout d'une nouvelle carte à la collection
            $user->ownedCards()->attach($card->id, [
                'quantity' => $quantity,
                'edition' => $edition
            ]);
        }

        return response()->json(['message' => 'Carte ajoutée à votre collection !']);
    }


}
