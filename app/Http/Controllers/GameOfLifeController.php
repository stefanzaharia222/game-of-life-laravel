<?php

namespace App\Http\Controllers;

use App\Services\GameOfLifeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameOfLifeController extends Controller
{
    protected GameOfLifeService $gameService;

    public function __construct(GameOfLifeService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * Display the main game view.
     *
     * @return View
     */
    public function index(): View
    {
        return view('game.index');
    }

    /**
     * Load the initial game state from an uploaded file and parse its contents.
     *
     * @param Request $request
     * @return View
     */
    public function loadFile(Request $request): View
    {
        $file = $request->file('generation_file');

        // Retrieve and validate file contents
        $content = file_get_contents($file);
        list($generation, $gridSize, $grid) = $this->gameService->parseFile($content);

        return view('game.index', [
            'generation' => $generation,
            'gridSize' => $gridSize,
            'grid' => $grid
        ]);
    }

    /**
     * Calculate and display the next generation of the game grid.
     *
     * @param Request $request
     * @return View
     */
    public function calculateNextGeneration(Request $request): View
    {
        // Decode JSON-encoded grid and grid size
        $grid = json_decode($request->input('grid'), true);
        $gridSize = json_decode($request->input('gridSize'), true);

        // Calculate the next grid state
        $nextGrid = $this->gameService->getNextGeneration($grid, $gridSize);

        return view('game.index', [
            'generation' => $request->input('generation') + 1,
            'gridSize' => $gridSize,
            'grid' => $nextGrid
        ]);
    }
}
