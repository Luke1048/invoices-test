<?php

declare(strict_types=1);

namespace App\Eloquent;

use App\DTO\InvoiceData;
use App\DTO\PaginationData;
use App\Enums\StatusEnum;
use App\Exceptions\InvalidAmountException;
use App\Exceptions\InvoiceNotUpdatableException;
use App\Models\Invoice;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class InvoiceEloquent
{
    public function __construct(
        private Invoice $model
    ) {
    }

    public function create(InvoiceData $data): Invoice
    {
        $invoice = clone $this->model;

        $invoice->number = $data->number;
        $invoice->supplier_name = $data->supplierName;
        $invoice->supplier_tax_id = $data->supplierTaxId;
        $invoice->net_amount = $data->netAmount;
        $invoice->vat_amount = $data->vatAmount;
        $invoice->gross_amount = $data->grossAmount;
        $invoice->currency = $data->currency;
        $invoice->status = $data->status;
        $invoice->issue_date = $data->issueDate;
        $invoice->due_date = $data->dueDate;

        $invoice->save();

        return $invoice;
    }

    public function list(PaginationData $pagination): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->orderByDesc('created_at')
            ->paginate(
                $pagination->perPage,
                ['*'],
                'page',
                $pagination->page
            );
    }

    public function item(string $id): ?Invoice
    {
        return $this->model->newQuery()->find($id);
    }

    public function update(InvoiceData $data): Invoice
    {
        $id = $data->id;
        $invoice = $this->item($id);

        if ($invoice->status !== StatusEnum::Pending->value) {
            throw new InvoiceNotUpdatableException($id);
        }

        $invoice->number = $data->number ?? $invoice->number;
        $invoice->supplier_name = $data->supplierName ?? $invoice->supplier_name;
        $invoice->supplier_tax_id = $data->supplierTaxId ?? $invoice->supplier_tax_id;

        $invoice->net_amount = $data->netAmount ?? $invoice->net_amount;
        $invoice->vat_amount = $data->vatAmount ?? $invoice->vat_amount;
        $invoice->gross_amount = $data->grossAmount ?? $invoice->gross_amount;

        $invoice->currency = $data->currency ?? $invoice->currency;
        $invoice->status = $data->status ?? $invoice->status;

        $invoice->issue_date = $data->issueDate ?? $invoice->issue_date;
        $invoice->due_date = $data->dueDate ?? $invoice->due_date;

        if (round($invoice->net_amount + $invoice->vat_amount, 2) !== round($invoice->gross_amount, 2)) {
            throw new InvalidAmountException(__('invoice.invalid_amount'));
        }

        $invoice->save();

        return $invoice;
    }
}
