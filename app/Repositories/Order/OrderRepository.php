<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\DTOs\Order\OrderDTO;
use App\DTOs\Order\OrderFilterDTO;
use Illuminate\Support\Facades\DB;
use App\Models\OrderProduct;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Crear pedido y productos
     */
    public function create(OrderDTO $orderDTO): Order
    {
        $order = Order::create($orderDTO->toArray());

        foreach ($orderDTO->products as $productDTO) {
            $order->products()->create(
                $productDTO->toArray()
            );
        }

        return $order->load('products');
    }

    /**
     * Buscar pedido por ID
     */
    public function find(int $id): ?Order
    {
        return Order::with('products')->find($id);
    }

    /**
     * Actualizar pedido y productos
     */
public function update(Order $order, OrderDTO $dto): Order
{
    return DB::transaction(function () use ($order, $dto) {

        // 1️⃣ Update del pedido
        $order->update($dto->toArray());

        // 2️⃣ Borrar items actuales
        OrderProduct::where('order_id', $order->id)->delete();

        // 3️⃣ Crear items nuevos (EXPLÍCITO, sin relación)
        foreach ($dto->products as $productDTO) {
            OrderProduct::create([
                'order_id'   => $order->id,
                'product_id' => $productDTO->productId,
                'quantity'   => $productDTO->quantity,
                'unit_price' => $productDTO->unitPrice,
                'subtotal'   => $productDTO->subtotal,
            ]);
        }

        return $order->load('products.product');
    });
}


    /**
     * Cambiar estado
     */
    public function updateStatus(Order $order, string $status): Order
    {
        $order->update([
            'status' => $status
        ]);

        return $order;
    }

    /**
     * Buscar pedidos con filtros
     */
    public function search(OrderFilterDTO $filters)
    {
        return Order::query()
            ->with('products')
            ->when(
                $filters->hasStatus(),
                fn ($q) => $q->where('status', $filters->status)
            )
            ->when(
                $filters->hasCustomer(),
                fn ($q) =>
                    $q->where('customer_name', 'like', "%{$filters->customerName}%")
            )
            ->when(
                $filters->hasDateRange(),
                fn ($q) =>
                    $q->whereBetween('created_at', [
                        $filters->fromDate . ' 00:00:00',
                        $filters->toDate . ' 23:59:59',
                    ])
            )
            ->orderByDesc('created_at')
            ->paginate(15);
    }
}
