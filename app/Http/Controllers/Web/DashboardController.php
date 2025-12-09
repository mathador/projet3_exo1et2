<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Notes\NoteService;
use App\Services\Tags\TagService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected NoteService $noteService,
        protected TagService $tagService
    ) {}

    /**
     * Affiche le tableau de bord avec les notes et tags.
     *
     * @return View
     */
    public function index(): View
    {
        // Les données sont chargées par les composants Livewire
        // Ce contrôleur peut être utilisé pour préparer des données partagées si nécessaire
        return view('dashboard');
    }
}

