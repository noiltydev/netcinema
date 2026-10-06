<?php

declare(strict_types=1);

namespace App\Values\Source;

use Illuminate\Contracts\Support\Arrayable;

class SourceProvider implements Arrayable
{
    private function __construct(
        public string $providerName,
        public string $externalId,
    )
    {
    }

    public static function make(string $providerName, string $externalId): self
    {
        return new self($providerName, $externalId);
    }

    public function toArray(): array
    {
        return [
            'provider_name' => $this->providerName,
            'external_id' => $this->externalId,
        ];
    }
}
