<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\InvoiceData;
use App\DTO\PaginationData;
use App\Eloquent\InvoiceEloquent;
use App\Exceptions\InvoiceNotFoundException;
use App\Models\Invoice;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class InvoiceService
{
    public function __construct(
        private InvoiceEloquent $eloquent,
    ) {
    }

    public function create(InvoiceData $data): Invoice
    {
        return $this->eloquent->create($data);
    }

    public function list(PaginationData $pagination): LengthAwarePaginator
    {
        return $this->eloquent->list($pagination);
    }

    public function item(string $id): Invoice
    {
        $invoice = $this->eloquent->item($id);

        return $invoice
            ?? throw new InvoiceNotFoundException($id);
    }

    public function update(InvoiceData $data): Invoice
    {
        return $this->eloquent->update($data);
    }
}
