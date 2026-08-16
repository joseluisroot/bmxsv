<?php

use App\Services\Performance\HitResolverService;
use App\Services\Performance\TimingEventProcessor;
use CodeIgniter\Test\CIUnitTestCase;

final class TimingEventProcessorTest extends CIUnitTestCase
{
    public function testRejectsEmptyPayload(): void
    {
        $processor = new TimingEventProcessor(new FakeTimingHitResolver());

        $result = $processor->process([]);

        $this->assertFalse($result['success']);
        $this->assertSame(400, $result['status_code']);
    }

    public function testRequiresHitOrChipIdentity(): void
    {
        $processor = new TimingEventProcessor(new FakeTimingHitResolver());

        $result = $processor->process([
            'device_code' => 'ESP32-GATE',
            'timing_point_code' => 'TP01',
            'timestamp_ms' => 1000,
        ]);

        $this->assertFalse($result['success']);
        $this->assertSame(400, $result['status_code']);
        $this->assertStringContainsString('hit_entrenamiento_id o chip_code', $result['message']);
    }

    public function testRoutesKnownHitEventsToHitResolver(): void
    {
        $resolver = new FakeTimingHitResolver();
        $processor = new TimingEventProcessor($resolver);

        $result = $processor->process([
            'device_code' => 'ESP32-GATE',
            'timing_point_code' => 'TP01',
            'hit_entrenamiento_id' => 10,
            'timestamp_ms' => 1000,
        ]);

        $this->assertTrue($result['success']);
        $this->assertSame('hit', $result['mode']);
        $this->assertSame(1, $resolver->hitCalls);
        $this->assertSame(0, $resolver->chipCalls);
    }

    public function testRoutesChipEventsToChipResolver(): void
    {
        $resolver = new FakeTimingHitResolver();
        $processor = new TimingEventProcessor($resolver);

        $result = $processor->process([
            'device_code' => 'ESP32-GATE',
            'timing_point_code' => 'TP01',
            'chip_code' => 'CHIP-LUCAS',
            'timestamp_ms' => 1000,
        ]);

        $this->assertTrue($result['success']);
        $this->assertSame('chip', $result['mode']);
        $this->assertSame(0, $resolver->hitCalls);
        $this->assertSame(1, $resolver->chipCalls);
    }
}

final class FakeTimingHitResolver extends HitResolverService
{
    public int $hitCalls = 0;
    public int $chipCalls = 0;

    public function __construct()
    {
    }

    public function registerPassByHit(array $payload): array
    {
        $this->hitCalls++;
        return ['success' => true, 'status_code' => 201, 'mode' => 'hit'];
    }

    public function registerPassByChip(array $payload): array
    {
        $this->chipCalls++;
        return ['success' => true, 'status_code' => 201, 'mode' => 'chip'];
    }
}
