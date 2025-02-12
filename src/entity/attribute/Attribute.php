<?php

declare(strict_types=1);

namespace pocketmine\entity\attribute;

class Attribute {
    private AttributeValue $value;

    public function __construct(
        private string $id,
        float $minValue,
        float $maxValue,
        float $defaultValue,
        private bool $shouldSend = true
    ) {
        $this->value = new AttributeValue($minValue, $maxValue, $defaultValue);
    }

    public function getId(): string {
        return $this->id;
    }

    public function isSyncable(): bool {
        return $this->shouldSend;
    }

    public function getValue(): float {
        return $this->value->getValue();
    }

    public function setValue(float $value): void {
        $this->value->setValue($value, true);
    }

    public function resetToDefault(): void {
        $this->value->resetToDefault();
    }

    public function isDesynchronized(): bool {
        return $this->value->isDesynchronized();
    }

    public function markSynchronized(): void {
        $this->value->markSynchronized();
    }
}
