<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LorcanaService;

class LorcanaController extends Controller
{
    protected $lorcanaService;

    public function __construct(LorcanaService $lorcanaService)
    {
        $this->lorcanaService = $lorcanaService;
    }

    /**
     * Récupérer toutes les cartes.
     */
    public function index()
    {
        $cards = $this->lorcanaService->getAllCards();
        return response()->json($cards);
    }

    /**
     * Récupérer une carte par son ID.
     */
    public function show($id)
    {
        $card = $this->lorcanaService->getCardById($id);
        return response()->json($card);
    }
}
