<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\DTOs\Order\OrderDTO;
use App\DTOs\Order\OrderFilterDTO;

interface OrderRepositoryInterface
{
    /**
     * Crear un pedido
     */
    public function create(OrderDTO $orderDTO): Order;

    /**
     * Buscar pedido por ID
     */
    public function find(int $id): ?Order;

    /**
     * Actualizar un pedido existente
     */
    public function update(Order $order, OrderDTO $orderDTO): Order;

    /**
     * Cambiar estado del pedido
     */
    public function updateStatus(Order $order, string $status): Order;

    /**
     * Buscar pedidos con filtros
     */
    public function search(OrderFilterDTO $filters);
}