<?php

namespace App\DTOs;

use Illuminate\Http\UploadedFile;

class CategoryData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly ?UploadedFile $image
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            name: $request->string('name'),
            description: $request->string('description'),
            image: $request->file('image')
        );
    }
}
