<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use DomainException;
use App\DTOs\Order\OrderFilterDTO;
use App\DTOs\Order\OrderDTO;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;


class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService
    ) {}

    /**
     * Listado de pedidos
     */
    public function index(Request $request)
    {
        $filterDTO = new OrderFilterDTO(
            status: $request->input('status'),
            customerName: $request->input('customer'),
            fromDate: $request->input('from'),
            toDate: $request->input('to'),
        );

        return view('orders.index', [
            'orders' => $this->orderService->search($filterDTO),
        ]);
    }


    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('orders.create', [
        'customers' => Customer::orderBy('name')->get(),
        'products' => Product::orderBy('name')->get(),
    ]);
    }
    /**
     * Almacenar nuevo pedido
     */
    public function store(StoreOrderRequest $request, OrderService $orderService)
    {
        $orderService->create($request->validated());

        return redirect()
            ->route('orders.index')
            ->with('success', 'Pedido creado correctamente.');
    }

    /**
     * Ver detalle del pedido
     */
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    /**
     * Formulario de edición
     */
    public function edit(Order $order)
    {
        if (! $order->canBeEdited()) {
            return redirect()
                ->route('orders.index')
                ->with('error', 'El pedido no puede editarse en su estado actual.');
        }

        
        return view('orders.edit', [
        'customers' => Customer::orderBy('name')->get(),
        'products' => Product::orderBy('name')->get(),
        'order' => $order,
    ]); 
    }

    /// Actualizar pedido
    public function update( UpdateOrderRequest $request, Order $order, OrderService $orderService) 
    {
        

        try {
            $dto = OrderDTO::fromArray(
                array_merge(
                    $request->validated(),
                    ['status' => $order->status] // 🔒 status inmutable
                )
            );

            $orderService->update($order, $dto);

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Pedido actualizado correctamente.');
        } catch (\DomainException $e) {
            return back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    /**
     * Cambiar estado del pedido
     */
    public function changeStatus(Request $request, Order $order)
    {
        try {
            $this->orderService->changeStatus(
                $order,
                $request->input('status')
            );

            return back()->with('success', 'Estado actualizado.');
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancelar pedido
     */
    public function cancel(Order $order)
    {
        try {
            $this->orderService->cancel($order);

            return back()->with('success', 'Pedido cancelado.');
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function invoice(Order $order)
    {
        $order->load([
            'products.product',
            'customer'
        ]);

        $pdf = Pdf::loadView('orders.invoice-pdf', [
            'order' => $order
        ]);

        return $pdf->stream("factura-pedido-{$order->id}.pdf");
    }

}
