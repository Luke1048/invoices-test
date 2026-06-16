<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GetInvoiceListRequest;
use App\Http\Resources\InvoiceListResource;
use App\Services\InvoiceService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetInvoiceListController extends Controller
{
    public function __construct(
        private readonly InvoiceService $service
    ) {}

    public function __invoke(GetInvoiceListRequest $request): AnonymousResourceCollection
    {
        return InvoiceListResource::collection($this->service->list($request->getData()));
    }
}
