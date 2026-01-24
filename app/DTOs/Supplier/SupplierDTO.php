<?php

namespace App\DTOs\Supplier;

class SupplierDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $taxId,
        public readonly ?string $phone,
        public readonly ?string $email,
        public readonly ?string $address,
        public readonly ?string $contactName,
        public readonly ?string $paymentTerms,
        public readonly ?string $notes,
        public readonly bool $isActive = true
    ) {}

    /**
     * Crea el DTO a partir de un Request validado
     */
    public static function fromRequest($request): self
    {
        return new self(
            name: $request->input('name'),
            taxId: $request->input('tax_id'),
            phone: $request->input('phone'),
            email: $request->input('email'),
            address: $request->input('address'),
            contactName: $request->input('contact_name'),
            paymentTerms: $request->input('payment_terms'),
            notes: $request->input('notes'),
            isActive: $request->input('is_active', true),
        );
    }

    /**
     * Convierte el DTO en array para el Repository
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'tax_id' => $this->taxId,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'contact_name' => $this->contactName,
            'payment_terms' => $this->paymentTerms,
            'notes' => $this->notes,
            'is_active' => $this->isActive,
        ];
    }
}
