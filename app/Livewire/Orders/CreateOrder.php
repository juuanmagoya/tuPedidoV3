<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use App\Models\Product;
use App\Models\Customer;
use App\Services\Order\OrderService;
use App\DTOs\Order\OrderDTO;

class CreateOrder extends Component
{
    // Cliente
    public ?int $customer_id = null;
    public string $customer_name = '';

    // Pedido
    public string $status = 'received';
    public string $order_type = 'in_store';
    public ?string $address = null;
    public string $payment_method = 'cash';
    public ?string $notes = null;

    // Productos
    public array $products = [];

    // Data
    public $customers;
    public $availableProducts;

    public function mount()
    {
        $this->customers = Customer::all();
        $this->availableProducts = Product::all();

        $this->products = [
            [
                'product_id' => '',
                'quantity' => 1,
                'unit_price' => 0,
            ]
        ];
    }

    protected function rules(): array
    {
        return [
            'customer_id' => ['nullable', 'exists:customers,id'],
            'customer_name' => ['required', 'string', 'max:255'],

            'order_type' => ['required', 'in:delivery,in_store'],
            'payment_method' => ['required'],

            'address' => ['nullable', 'string', 'max:255'],

            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'products.*.unit_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function updatedOrderType()
    {
        if ($this->order_type !== 'delivery') {
            $this->address = null;
        }
    }

    public function updatedCustomerId($value)
    {
        if ($value) {
            $customer = $this->customers->firstWhere('id', $value);
            $this->customer_name = $customer?->name ?? '';
        }
    }

    public function updatedProducts($value, $key)
    {
        if (str_ends_with($key, 'product_id')) {
            [$index] = explode('.', $key);
            $product = Product::find($value);

            if ($product) {
                $this->products[$index]['unit_price'] = $product->price;
            }
        }
    }

    public function addProduct()
    {
        $this->products[] = [
            'product_id' => '',
            'quantity' => 1,
            'unit_price' => 0,
        ];
    }

    public function removeProduct($index)
    {
        if (count($this->products) > 1) {
            unset($this->products[$index]);
            $this->products = array_values($this->products);
        }
    }

    public function getTotalProperty(): float
    {
        return collect($this->products)->sum(
            fn ($p) => $p['quantity'] * $p['unit_price']
        );
    }

    public function save(OrderService $orderService)
    {
        $data = $this->validate();

        if ($this->order_type === 'delivery' && empty($this->address)) {
            $this->addError('address', 'La dirección es obligatoria.');
            return;
        }

        $dto = OrderDTO::fromArray([
            ...$data,
            'status' => $this->status,
            'total' => $this->total,
        ]);

        $orderService->create($dto);

        session()->flash('success', 'Pedido creado correctamente.');

        return redirect()->route('orders.index');
    }

    public function render()
    {
        return view('livewire.orders.create-order');
    }
}
