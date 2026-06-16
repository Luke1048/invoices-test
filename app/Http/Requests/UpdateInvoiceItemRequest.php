<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTO\InvoiceData;
use App\Enums\CurrencyEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateInvoiceItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'uuid', Rule::exists('invoices', 'id')],
            // 'number' => ['nullable', 'string', 'unique:invoices,number'],
            'number' => ['nullable', 'string', Rule::unique('invoices', 'number')->ignore($this->route('id'), 'id')],

            // Rule::unique('invoices', 'number')->ignore($this->route('id')),
            'supplier_name' => ['nullable', 'string'],
            'supplier_tax_id' => ['nullable', 'string'],
            'net_amount' => ['nullable', 'numeric', 'gt:0'],
            'vat_amount' => ['nullable', 'numeric', 'gte:0'],
            'gross_amount' => ['nullable', 'numeric'],
            'currency' => ['nullable', 'string', 'size:3'],
            'status' => ['nullable', new Enum(StatusEnum::class)],
            'issue_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
        ];
    }

    public function validationData(): array
    {
        return array_merge($this->all(), [
            'id' => $this->route('id'),
        ]);
    }

    /* public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $netAmount = (float) $this->input('net_amount');
            $vatAmount = (float) $this->input('vat_amount');
            $grossAmount = (float) $this->input('gross_amount');

            if (round($netAmount + $vatAmount, 2) !== round($grossAmount, 2)) {
                $validator->errors()->add('gross_amount', __('invoice.gross_amount'));
            }
        });
    }  */

    public function getData(): InvoiceData
    {
        $data = $this->validated();

        return new InvoiceData(
            id: $data['id'],
            number: $data['number'] ?? null,
            supplierName: $data['supplier_name'] ?? null,
            supplierTaxId: $data['supplier_tax_id'] ?? null,
            netAmount: isset($data['net_amount']) ? (float) $data['net_amount'] : null,
            vatAmount: isset($data['vat_amount']) ? (float) $data['vat_amount'] : null,
            grossAmount: isset($data['gross_amount']) ? (float) $data['gross_amount'] : null,
            currency: isset($data['currency'])
                ? CurrencyEnum::from($data['currency'])
                : CurrencyEnum::UAH,
            status: isset($data['status'])
                ? StatusEnum::from($data['status'])
                : StatusEnum::Pending,
            issueDate: $data['issue_date'] ?? null,
            dueDate: $data['due_date'] ?? null,
        );
    }
}
