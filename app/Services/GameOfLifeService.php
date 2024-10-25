<?php

namespace App\Services;

class GameOfLifeService
{
    /**
     * Parse the content of the uploaded generation file into game parameters.
     *
     * @param string $content
     * @return array [int generation, array gridSize, array grid]
     */
    public function parseFile($content)
    {
        // Split content by line and extract generation and grid size
        $lines = explode("\n", trim($content));
        $generation = intval(explode(' ', $lines[0])[1]);

        list($rows, $cols) = explode(' ', $lines[1]);
        $gridSize = ['rows' => intval($rows), 'cols' => intval($cols)];

        // Initialize grid from lines
        $grid = [];
        for ($i = 2; $i < count($lines); $i++) {
            $grid[] = str_split(trim($lines[$i]));
        }

        return [$generation, $gridSize, $grid];
    }

    /**
     * Compute the next generation grid state based on Conway's Game of Life rules.
     *
     * @param array $grid
     * @param array $gridSize ['rows' => int, 'cols' => int]
     * @return array Updated grid for the next generation
     */
    public function getNextGeneration(array $grid, array $gridSize): array
    {
        $nextGrid = $grid;

        for ($row = 0; $row < $gridSize['rows']; $row++) {
            for ($col = 0; $col < $gridSize['cols']; $col++) {
                // Calculate number of alive neighbors for each cell
                $aliveNeighbors = $this->countAliveNeighbors($grid, $row, $col, $gridSize);

                // Apply Conway's Game of Life rules
                if ($grid[$row][$col] === '*') {
                    // Cell dies if underpopulated or overpopulated
                    if ($aliveNeighbors < 2 || $aliveNeighbors > 3) {
                        $nextGrid[$row][$col] = '.';
                    }
                } elseif ($grid[$row][$col] === '.' && $aliveNeighbors === 3) {
                    // Cell becomes alive by reproduction
                    $nextGrid[$row][$col] = '*';
                }
            }
        }

        return $nextGrid;
    }

    /**
     * Count the number of alive neighbors for a specific cell.
     *
     * @param array $grid
     * @param int $row
     * @param int $col
     * @param array $gridSize ['rows' => int, 'cols' => int]
     * @return int Number of alive neighbors
     */
    private function countAliveNeighbors(array $grid, int $row, int $col, array $gridSize): int
    {
        $aliveNeighbors = 0;

        // Loop through the 3x3 grid surrounding the cell
        for ($i = -1; $i <= 1; $i++) {
            for ($j = -1; $j <= 1; $j++) {
                // Skip the cell itself
                if ($i === 0 && $j === 0) continue;

                $newRow = $row + $i;
                $newCol = $col + $j;

                // Check if neighbor is within grid boundaries
                if ($newRow >= 0 && $newRow < $gridSize['rows'] && $newCol >= 0 && $newCol < $gridSize['cols']) {
                    // Increment count if neighbor is alive
                    if ($grid[$newRow][$newCol] === '*') {
                        $aliveNeighbors++;
                    }
                }
            }
        }

        return $aliveNeighbors;
    }
}
