<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTO\PaginationData;
use Illuminate\Foundation\Http\FormRequest;

class GetInvoiceListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function getData(): PaginationData
    {
        $data = $this->validated();

        return new PaginationData(
            page: (int) ($data['page'] ?? 1),
            perPage: (int) ($data['per_page'] ?? 15),
        );
    }
}
