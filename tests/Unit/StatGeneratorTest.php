<?php

namespace Tests\Unit;

use App\Domain\Battle\StatGenerator;
use App\Models\Fighter;
use PHPUnit\Framework\TestCase;

class StatGeneratorTest extends TestCase
{
    public function test_generated_stats_are_within_range(): void
    {
        $fighter = new Fighter([
            'name' => 'Kratos',
            'type' => 'hero',
            'health_min' => 65,
            'health_max' => 100,
            'strength_min' => 75,
            'strength_max' => 90,
            'defence_min' => 40,
            'defence_max' => 50,
            'speed_min' => 40,
            'speed_max' => 50,
            'luck_min' => 10,
            'luck_max' => 20,
        ]);

        $generator = new StatGenerator();

        for ($i = 0; $i < 100; $i++) {
            $stats = $generator->generate($fighter);

            $this->assertGreaterThanOrEqual(65, $stats['health_start']);
            $this->assertLessThanOrEqual(100, $stats['health_start']);

            $this->assertGreaterThanOrEqual(75, $stats['strength']);
            $this->assertLessThanOrEqual(90, $stats['strength']);

            $this->assertGreaterThanOrEqual(40, $stats['defence']);
            $this->assertLessThanOrEqual(50, $stats['defence']);

            $this->assertGreaterThanOrEqual(40, $stats['speed']);
            $this->assertLessThanOrEqual(50, $stats['speed']);

            $this->assertGreaterThanOrEqual(10, $stats['luck_percent']);
            $this->assertLessThanOrEqual(20, $stats['luck_percent']);

            $this->assertSame($stats['health_start'], $stats['health_current']);
        }
    }
}
