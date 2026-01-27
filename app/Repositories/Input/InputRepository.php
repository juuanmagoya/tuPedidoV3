<?php

namespace App\Repositories\Input;

use App\Models\Input;
use Illuminate\Support\Collection;

class InputRepository implements InputRepositoryInterface
{
    public function all(): Collection
    {
        return Input::orderBy('name')->get();
    }

    public function findById(int $id): ?Input
    {
        return Input::find($id);
    }

    public function create(array $data): Input
    {
        return Input::create($data);
    }

    public function update(Input $input, array $data): Input
    {
        $input->update($data);

        return $input;
    }
    public function updateStock(Input $input, float $stock): void
    {
        $input->update(['stock' => $stock]);
    }


    public function delete(Input $input): void
    {
        $input->delete();
    }
}
