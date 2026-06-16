<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateInvoiceItemRequest;
use App\Http\Resources\InvoiceResource;
use App\Services\InvoiceService;

class CreateInvoiceController extends Controller
{
    public function __construct(
        private readonly InvoiceService $service
    ) {}

    public function __invoke(CreateInvoiceItemRequest $request): InvoiceResource
    {
        return new InvoiceResource($this->service->create($request->getData()));
    }
}
