<?php

namespace App\DTOs\Order;

class OrderFilterDTO
{
    public function __construct(
        public readonly ?string $status = null,
        public readonly ?string $customerName = null,
        public readonly ?string $fromDate = null,
        public readonly ?string $toDate = null,
    ) {}

    public function hasStatus(): bool
    {
        return ! is_null($this->status);
    }

    public function hasCustomer(): bool
    {
        return ! is_null($this->customerName);
    }

    public function hasDateRange(): bool
    {
        return $this->fromDate && $this->toDate;
    }
}
