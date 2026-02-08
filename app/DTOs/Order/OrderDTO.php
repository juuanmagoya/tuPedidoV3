<?php

namespace App\DTOs\Order;

class OrderDTO
{
    /**
     * @param OrderProductDTO[] $products
     */
    public function __construct(
        public readonly ?int $customerId,
        public readonly ?string $customerName,
        public readonly string $status,
        public readonly string $orderType,
        public readonly ?string $address,
        public readonly string $paymentMethod,
        public readonly ?string $notes,
        public readonly float $total,
        public readonly array $products,
    ) {}

    /**
     * Crear DTO desde el request ya procesado por el Service
     */
    public static function fromArray(array $data): self
    {
        $rawProducts = $data['products'] ?? [];

        $products = array_map(
            fn ($product) => OrderProductDTO::fromArray($product),
            $rawProducts
        );


        $total = array_reduce(
            $products,
            fn (float $sum, OrderProductDTO $product) => $sum + $product->subtotal,
            0
        );

        return new self(
            customerId: $data['customer_id'] ?? null,
            customerName: $data['customer_name'] ?? null, 
            status: $data['status'],
            orderType: $data['order_type'],
            address: $data['address'] ?? null,
            paymentMethod: $data['payment_method'],
            notes: $data['notes'] ?? null,
            total: $total,
            products: $products,
        );
    }

    /**
     * Convertir DTO a array (sin productos)
     */
    public function toArray(): array
    {
        return [
            'customer_id'    => $this->customerId,
            'customer_name'  => $this->customerName,
            'status'         => $this->status,
            'order_type'     => $this->orderType,
            'address'        => $this->address,
            'payment_method' => $this->paymentMethod,
            'notes'          => $this->notes,
            'total'          => $this->total,
        ];
    }
}
