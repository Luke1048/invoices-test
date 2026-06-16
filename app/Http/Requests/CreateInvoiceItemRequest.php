<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTO\InvoiceData;
use App\Enums\CurrencyEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateInvoiceItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'number' => ['required', 'string', 'unique:invoices,number'],
            'supplier_name' => ['nullable', 'string'],
            'supplier_tax_id' => ['nullable', 'string'],
            'net_amount' => ['required', 'numeric', 'gt:0'],
            'vat_amount' => ['required', 'numeric', 'gte:0'],
            'gross_amount' => ['required', 'numeric'],
            'currency' => ['nullable', 'string', 'size:3'],
            'status' => ['nullable', new Enum(StatusEnum::class)],
            'issue_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:issue_date'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $netAmount = (float) $this->input('net_amount');
            $vatAmount = (float) $this->input('vat_amount');
            $grossAmount = (float) $this->input('gross_amount');

            if (round($netAmount + $vatAmount, 2) !== round($grossAmount, 2)) {
                $validator->errors()->add('gross_amount', __('invoice.gross_amount'));
            }
        });
    }

    public function getData(): InvoiceData
    {
        $data = $this->validated();

        return new InvoiceData(
            id: $data['id'] ?? null,
            number: $data['number'],
            supplierName: $data['supplier_name'] ?? null,
            supplierTaxId: $data['supplier_tax_id'] ?? null,
            netAmount: (float) $data['net_amount'],
            vatAmount: (float) $data['vat_amount'],
            grossAmount: (float) $data['gross_amount'],
            currency: isset($data['currency'])
                ? CurrencyEnum::from($data['currency'])
                : CurrencyEnum::UAH,
            status: isset($data['status'])
                ? StatusEnum::from($data['status'])
                : StatusEnum::Pending,
            issueDate: $data['issue_date'],
            dueDate: $data['due_date'],
        );
    }
}
