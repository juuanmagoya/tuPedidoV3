<?php

namespace App\Repositories\Input;

use App\Models\Input;
use Illuminate\Support\Collection;

interface InputRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): ?Input;

    public function create(array $data): Input;

    public function update(Input $input, array $data): Input;

    public function delete(Input $input): void;
    
    public function updateStock(Input $input, float $stock): void;

}
