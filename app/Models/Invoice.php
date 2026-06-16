<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $id UUID primary key
 * @property string $number Unique invoice number
 * @property ?string $supplier_name Supplier name
 * @property ?string $supplier_tax_id Supplier tax identification number
 * @property float $net_amount Net amount (without VAT)
 * @property float $vat_amount VAT amount
 * @property float $gross_amount Total amount (net + VAT)
 * @property string $currency Currency code (e.g. UAH, USD, EUR)
 * @property string $status Invoice status (pending, approved, rejected)
 * @property Carbon $issue_date Invoice issue date
 * @property Carbon $due_date Payment due date
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class Invoice extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'number',
        'supplier_name',
        'supplier_tax_id',
        'net_amount',
        'vat_amount',
        'gross_amount',
        'currency',
        'status',
        'issue_date',
        'due_date',
    ];

    protected $casts = [
        'net_amount' => 'float',
        'vat_amount' => 'float',
        'gross_amount' => 'float',
        'issue_date' => 'date',
        'due_date' => 'date',
    ];
}
