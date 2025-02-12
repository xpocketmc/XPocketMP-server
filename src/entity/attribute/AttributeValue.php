<?php

declare(strict_types=1);

namespace pocketmine\entity\attribute;

use function max;
use function min;

class AttributeValue {
    private float $currentValue;
    private bool $desynchronized = true;

    public function __construct(
        private string $id,
        private float $minValue,
        private float $maxValue,
        private float $defaultValue,
        private bool $shouldSend = true
    ) {
        if ($minValue > $maxValue || $defaultValue > $maxValue || $defaultValue < $minValue) {
            throw new \InvalidArgumentException("Invalid range: min=$minValue, max=$maxValue, default=$defaultValue");
        }
        $this->currentValue = $defaultValue;
    }

    public function getId(): string {
        return $this->id;
    }

    public function isSyncable(): bool {
        return $this->shouldSend;
    }

    public function getMinValue(): float {
        return $this->minValue;
    }

    public function getMaxValue(): float {
        return $this->maxValue;
    }

    public function getDefaultValue(): float {
        return $this->defaultValue;
    }

    public function getValue(): float {
        return $this->currentValue;
    }

    public function setValue(float $value, bool $fit = false): void {
        if ($value < $this->minValue || $value > $this->maxValue) {
            if (!$fit) {
                throw new \InvalidArgumentException("Value out of range: $value (allowed: {$this->minValue} - {$this->maxValue})");
            }
            $value = min(max($value, $this->minValue), $this->maxValue);
        }

        if ($this->currentValue !== $value) {
            $this->desynchronized = true;
            $this->currentValue = $value;
        }
    }

    public function resetToDefault(): void {
        $this->setValue($this->defaultValue, true);
    }

    public function isDesynchronized(): bool {
        return $this->desynchronized;
    }

    public function markSynchronized(): void {
        $this->desynchronized = false;
    }
}