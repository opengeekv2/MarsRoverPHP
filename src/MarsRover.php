<?php

declare(strict_types=1);

class RoverState
{
    private const GRID_SIZE = 10;
    private const COS_90 = 0;
    private const SIN_90 = 1;
    private bool $blocked = false;

    public function __construct(
        private array $grid,
        private int $xStep = 0,
        private int $yStep = 1,
        private int $x = 0,
        private int $y = 0,
        private array $compass = ['N', 'E', 'S', 'W']
    ) {
    }

    public function checkObstacleInNextPosition(int $x, int $y): bool
    {
        return $this->grid[$x][$y] == 'O';
    }

    public function moveForward(): RoverState
    {
        $xPos = $this->x + $this->xStep;
        if ($xPos >= 0) {
            $xPos = $xPos%self::GRID_SIZE;
        } else {
            $xPos = $xPos%self::GRID_SIZE + self::GRID_SIZE;
        }

        $yPos = $this->y + $this->yStep;
        if ($yPos >= 0) {
            $yPos = $yPos%self::GRID_SIZE;
        } else {
            $yPos = $yPos%self::GRID_SIZE + self::GRID_SIZE;
        }

        if ($this->checkObstacleInNextPosition($xPos, $yPos)) {
            $this->blocked = true;
            return $this;
        }

        return new RoverState(
            $this->grid,
            $this->xStep,
            $this->yStep,
            $xPos,
            $yPos,
            $this->compass
        );
    }

    public function rotateRight(): RoverState
    {
        if ($this->blocked) {
            return $this;
        }
        $first = $this->compass[0];
        $this->compass = array_slice($this->compass, 1);
        array_push($this->compass, $first);

        return new RoverState(
            $this->grid,
            $this->xStep*self::COS_90 + $this->yStep*self::SIN_90,
            -$this->xStep*self::SIN_90 + $this->yStep*self::COS_90,
            $this->x,
            $this->y,
            $this->compass
        );
    }

    public function rotateLeft(): RoverState
    {
        if ($this->blocked) {
            return $this;
        }
        $last = [array_pop($this->compass)];
        array_push($last, ...$this->compass);

        return new RoverState(
            $this->grid,
            $this->xStep*self::COS_90 - $this->yStep*self::SIN_90,
            $this->xStep*self::SIN_90 + $this->yStep*self::COS_90,
            $this->x,
            $this->y,
            $last
        );
    }

    public function getCurrentDirection(): string
    {
        return $this->compass[0];
    }

    public function getState(): string
    {
        $str_state = strval($this->x) . ':' . strval($this->y) . ':' . $this->getCurrentDirection();
        if ($this->blocked) {
            $str_state = 'O:' . $str_state;
        }
        return $str_state;
    }
}

class MarsRover
{
    protected RoverState $state;

    public function __construct(array $grid)
    {
        $this->state = new RoverState($grid);
    }

    protected function getState(): string
    {
        return $this->state->getState();
    }

    protected function moveForward(): void
    {
        $this->state = $this->state->moveForward();
    }

    protected function rotateRight(): void
    {
        $this->state = $this->state->rotateRight();
    }

    protected function rotateLeft(): void
    {
        $this->state = $this->state->rotateLeft();
    }

    public function command(string $commands): string
    {
        foreach (str_split($commands) as $char) {
            if ($char == 'R') {
                $this->rotateRight();
            } elseif ($char == 'L') {
                $this->rotateLeft();
            } elseif ($char == 'M') {
                $this->moveForward();
            }
        }

        return $this->getState();
    }
}
