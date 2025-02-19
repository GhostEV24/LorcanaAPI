<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LorcanaService
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = "https://lorcanajson.org/files/current/fr/allCards.json";
    }

    /**
     * Récupérer toutes les cartes depuis l'API externe.
     */
    public function getAllCards()
    {
        $response = Http::get("https://lorcanajson.org/files/current/fr/allCards.json");
        $data = $response->json();

        return $data['cards'] ?? []; // Retourne uniquement les cartes
    }

    public function getCardById($id)
    {
        $cards = $this->getAllCards();

        // Vérifier si l'ID existe (en string ou int)
        $card = collect($cards)->first(function ($item) use ($id) {
            return (string) $item['id'] === (string) $id;
        });

        return $card ?? ['error' => 'Carte non trouvée'];
    }

    public function getAllSets()
    {
        $response = Http::get("https://lorcanajson.org/files/current/fr/allCards.json");
        $data = $response->json();

        return $data['sets'] ?? []; // Retourne uniquement les sets
    }


    public function getSetById($id)
    {
        $sets = $this->getAllSets();

        // Recherche du set par ID
        $set = collect($sets)->first(function ($item) use ($id) {
            return (string) $item['id'] === (string) $id;
        });

        return $set ?? ['error' => 'Set non trouvé'];
    }

    public function getCardsBySet($setId)
    {
        // Récupère toutes les cartes
        $cards = $this->getAllCards();

        // Filtrer les cartes qui appartiennent au set demandé
        $setCards = collect($cards)->filter(function ($card) use ($setId) {
            // Vérifie si le 'setCode' de la carte correspond à l'ID du set
            return isset($card['setCode']) && (string) $card['setCode'] === (string) $setId;
        });

        // Si aucune carte n'est trouvée, renvoyer un message d'erreur
        if ($setCards->isEmpty()) {
            return ['error' => 'Aucune carte trouvée pour ce set.'];
        }

        return $setCards->values()->all(); // Retourne les cartes filtrées
    }


}
