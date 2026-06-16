<?php

declare(strict_types=1);

use App\Enums\CurrencyEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number')->unique();
            $table->string('supplier_name')->nullable();
            $table->string('supplier_tax_id')->nullable();
            $table->decimal('net_amount', 15, 2);
            $table->decimal('vat_amount', 15, 2);
            $table->decimal('gross_amount', 15, 2);
            $table->string('currency', 3)->default(CurrencyEnum::UAH->value);
            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default(StatusEnum::Pending->value);
            $table->date('issue_date');
            $table->date('due_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
