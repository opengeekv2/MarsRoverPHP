<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class TestMarsRover extends TestCase
{
    private MarsRover $marsRover;

    public function setUp(): void
    {
        $this->marsRover = new MarsRover(array_fill(0, 10, array_fill(0, 10, '.')));
    }

    public function testInitialState(): void
    {
        $this->assertEquals("0:0:N", $this->marsRover->command(""));
    }

    public function testMoveForward(): void
    {
        $this->assertEquals("0:1:N", $this->marsRover->command("M"));
    }

    public function testMoveManyForward(): void
    {
        $this->assertEquals("0:3:N", $this->marsRover->command("MMM"));
    }

    public function testRotateRight(): void
    {
        $this->assertEquals("0:0:E", $this->marsRover->command("R"));
    }

    public function testRotateTwiceRight(): void
    {
        $this->assertEquals("0:0:S", $this->marsRover->command("RR"));
    }

    public function testRotateThreeRight(): void
    {
        $this->assertEquals("0:0:W", $this->marsRover->command("RRR"));
    }

    public function testRotateFull(): void
    {
        $this->assertEquals("0:0:N", $this->marsRover->command("RRRR"));
    }

    public function testRotateLeft(): void
    {
        $this->assertEquals("0:0:W", $this->marsRover->command("L"));
    }

    public function testRotateTwiceLeft(): void
    {
        $this->assertEquals("0:0:S", $this->marsRover->command("LL"));
    }

    public function testRotateThreeLeft(): void
    {
        $this->assertEquals("0:0:E", $this->marsRover->command("LLL"));
    }

    public function testRotateFullLeft(): void
    {
        $this->assertEquals("0:0:N", $this->marsRover->command("LLLL"));
    }

    public function testRotateLeftAndMoveForward(): void
    {
        $this->assertEquals("9:0:W", $this->marsRover->command("LM"));
    }

    public function testRotateLeftTwiceAndMoveForward(): void
    {
        $this->assertEquals("0:9:S", $this->marsRover->command("LLM"));
    }

    public function testRotateLeftThreeTimesAndMoveForward(): void
    {
        $this->assertEquals("1:0:E", $this->marsRover->command("LLLM"));
    }

    public function testRotateFullLeftAndMoveForward(): void
    {
        $this->assertEquals("0:1:N", $this->marsRover->command("LLLLM"));
    }

    public function testRotateRightAndMoveForward(): void
    {
        $this->assertEquals("1:0:E", $this->marsRover->command("RM"));
    }

    public function testRotateRightTwiceAndMoveForward(): void
    {
        $this->assertEquals("0:9:S", $this->marsRover->command("RRM"));
    }

    public function testRotateRightThreeTimesAndMoveForward(): void
    {
        $this->assertEquals("9:0:W", $this->marsRover->command("RRRM"));
    }

    public function testRotateFullRightAndMoveForward(): void
    {
        $this->assertEquals("0:1:N", $this->marsRover->command("RRRRM"));
    }

    public function testCrossForward(): void
    {
        $this->assertEquals("0:0:N", $this->marsRover->command("MMMMMMMMMM"));
    }

    public function testCrossRight(): void
    {
        $this->assertEquals("0:0:E", $this->marsRover->command("RMMMMMMMMMM"));
    }

    public function testFrontObstacle(): void
    {
        $grid = array_fill(0, 10, array_fill(0, 10, '.'));
        $grid[1][0] = 'O';
        $this->marsRover = new MarsRover($grid);
        $this->assertEquals("O:0:0:N", $this->marsRover->command("M"));
    }

    public function testItDoesNotMoveAfterObstacle(): void
    {
        $grid = array_fill(0, 10, array_fill(0, 10, '.'));
        $grid[1][0] = 'O';
        $this->marsRover = new MarsRover($grid);
        $this->assertEquals("O:0:0:N", $this->marsRover->command("MMMMM"));
    }

    public function testMixedDirections(): void
    {
        $this->assertEquals("2:3:N", $this->marsRover->command("MMRMMLM"));
    }
}
