<?php

declare(strict_types=1);

namespace App\Values\Source;

use App\Enums\SourceProviderName as ProviderName;
use Illuminate\Contracts\Support\Arrayable;

final readonly class SourceProvider implements Arrayable
{
    private function __construct(
        public ProviderName $providerName,
        public string $externalId,
    )
    {
    }

    public static function make(ProviderName $providerName, string $externalId): self
    {
        return new self(
            providerName: $providerName,
            externalId: $externalId,
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'provider_name' => $this->providerName,
            'external_id' => $this->externalId,
        ];
    }
}
