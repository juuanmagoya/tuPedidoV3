<?php

namespace App\Services;

use App\DTOs\CategoryData;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function store(CategoryData $data)
    {
        $imagePath = null;

        if ($data->image) {
            $imagePath = $data->image->store('categories', 'public');
        }

        return $this->categoryRepository->create([
            'name' => $data->name,
            'description' => $data->description,
            'image' => $imagePath,
        ]);
    }
    public function update(Category $category, CategoryData $data)
    {
        $imagePath = $category->image;

        if ($data->image) {
            // borrar imagen anterior
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $imagePath = $data->image->store('categories', 'public');
        }

        $category->update([
            'name' => $data->name,
            'description' => $data->description,
            'image' => $imagePath,
        ]);

        return $category;
    }

    public function delete(Category $category): void
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();
    }

}
