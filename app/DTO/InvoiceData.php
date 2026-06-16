<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enums\CurrencyEnum;
use App\Enums\StatusEnum;
use Spatie\LaravelData\{Attributes\WithCast, Casts\EnumCast, Data};

final class InvoiceData extends Data
{
//    public function __construct(
//        public ?string $id,
//        public string $number,
//        public ?string $supplierName,
//        public ?string $supplierTaxId,
//        public float $netAmount,
//        public float $vatAmount,
//        public float $grossAmount,
//        #[WithCast(EnumCast::class, enumClass: CurrencyEnum::class)]
//        public ?CurrencyEnum $currency,
//        #[WithCast(EnumCast::class, enumClass: StatusEnum::class)]
//        public ?StatusEnum $status,
//        public string $issueDate,
//        public string $dueDate,
//    ) {}

    public function __construct(
        public ?string $id,
        public ?string $number,
        public ?string $supplierName,
        public ?string $supplierTaxId,
        public ?float $netAmount,
        public ?float $vatAmount,
        public ?float $grossAmount,
        #[WithCast(EnumCast::class, enumClass: CurrencyEnum::class)]
        public ?CurrencyEnum $currency,
        #[WithCast(EnumCast::class, enumClass: StatusEnum::class)]
        public ?StatusEnum $status,
        public ?string $issueDate,
        public ?string $dueDate,
    ) {}
}
