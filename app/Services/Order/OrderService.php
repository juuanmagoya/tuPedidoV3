<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Product;
use App\DTOs\Order\OrderDTO;
use App\DTOs\Order\OrderFilterDTO;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Services\ProductService;
use DomainException;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;


class OrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ProductService $productService
    ) {}

    /**
     * Crear pedido
     */
    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data) {

            // 🧠 Resolver customer_name
            if (!empty($data['customer_id'])) {
                $customer = Customer::findOrFail($data['customer_id']);
                $data['customer_name'] = $customer->name;
            }

            if (empty($data['customer_name'])) {
                throw new \DomainException('El nombre del cliente es obligatorio');
            }

            // 🔒 Estado inicial fijo
            $data['status'] = 'received';

            // 📦 Construimos el DTO (incluye productos y total)
            $orderDTO = OrderDTO::fromArray($data);

            // 🔍 Validar stock ANTES de persistir
            foreach ($orderDTO->products as $productDTO) {

                $product = Product::lockForUpdate()
                    ->findOrFail($productDTO->productId);

                if ($product->stock < $productDTO->quantity) {
                    throw new \DomainException(
                        "Stock insuficiente para {$product->name}"
                    );
                }
            }

            // 💾 Persistir pedido + items
            $order = $this->orderRepository->create($orderDTO);

            // 🔽 Impactar stock
            foreach ($orderDTO->products as $productDTO) {

                $product = Product::find($productDTO->productId);

                $this->productService->decreaseStock(
                    $product,
                    $productDTO->quantity
                );
            }

            return $order;
        });
    }


    /**
     * Actualizar pedido
     */
public function update(Order $order, OrderDTO $dto): Order
{
    if (! $order->canBeEdited()) {
        throw new DomainException(
            'El pedido no puede editarse en su estado actual.'
        );
    }

    return DB::transaction(function () use ($order, $dto) {

        // 🧠 Resolver customer_name SIN romper el DTO
        if ($dto->customerId) {
            $customer = Customer::findOrFail($dto->customerId);

            $dto = new OrderDTO(
                customerId: $dto->customerId,
                customerName: $customer->name,
                status: $dto->status,
                orderType: $dto->orderType,
                address: $dto->address ?? $customer->address,
                paymentMethod: $dto->paymentMethod,
                notes: $dto->notes,
                total: $dto->total,
                products: $dto->products, // 🔴 CLAVE
            );
        }

        // 🔺 revertir stock viejo
        foreach ($order->products as $item) {
            $this->productService->increaseStock(
                Product::findOrFail($item->product_id),
                $item->quantity
            );
        }

        // 🔄 update real (con products intactos)
        $updatedOrder = $this->orderRepository->update($order, $dto);

        // 🔻 aplicar nuevo stock
        foreach ($updatedOrder->products as $item) {
            $this->productService->decreaseStock(
                Product::findOrFail($item->product_id),
                $item->quantity
            );
        }

        return $updatedOrder;
    });
}



        /**
         * Cancelar pedido
         */
        public function cancel(Order $order): Order
        {
            if ($order->status === 'delivered') {
                throw new DomainException(
                    'No se puede cancelar un pedido entregado.'
                );
            }

            return DB::transaction(function () use ($order) {

                foreach ($order->products as $item) {
                    $product = Product::findOrFail($item->product_id);

                    $this->productService->increaseStock(
                        $product,
                        $item->quantity
                    );
                }

                return $this->orderRepository->updateStatus(
                    $order,
                    'canceled'
                );
            });
        }

        /**
         * Cambiar estado
         */
        public function changeStatus(Order $order, string $newStatus): Order
        {
            $allowedTransitions = [
                'received'   => ['preparing', 'canceled'],
                'preparing'  => ['on_the_way', 'canceled'],
                'on_the_way' => ['delivered'],
            ];

            if (
                ! isset($allowedTransitions[$order->status]) ||
                ! in_array($newStatus, $allowedTransitions[$order->status])
            ) {
                throw new DomainException('Cambio de estado no permitido.');
            }

            return $this->orderRepository->updateStatus($order, $newStatus);
        }

        /**
         * Buscar pedidos
         */
        public function search(OrderFilterDTO $filters)
        {
            return $this->orderRepository->search($filters);
        }
    }
