<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceResource;
use App\Services\InvoiceService;

class GetInvoiceItemController extends Controller
{
    public function __construct(
        private readonly InvoiceService $service
    ) {}

    public function __invoke(string $id): InvoiceResource
    {
        return new InvoiceResource($this->service->item($id));
    }
}
