<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PetpoojaService
{
    public function fetchOrdersForSalesDate(string $salesDate): array
    {
        /*
         |--------------------------------------------------------------------------
         | Petpooja Date Rule
         |--------------------------------------------------------------------------
         | If we need 25 Feb sales, Petpooja order_date should be 26 Feb.
         */
        $petpoojaOrderDate = Carbon::parse($salesDate)->addDay()->toDateString();

        $baseUrl = rtrim(config('services.petpooja.base_url'), '/');
        $endpoint = config('services.petpooja.get_orders_endpoint');
        $url = $baseUrl . $endpoint;

        $payload = [
            'app_key' => config('services.petpooja.app_key'),
            'app_secret' => config('services.petpooja.app_secret'),
            'access_token' => config('services.petpooja.access_token'),
            'restID' => config('services.petpooja.rest_id'),
            'order_date' => $petpoojaOrderDate,
            'refId' => '',
        ];

        $cookieName = config('services.petpooja.cookie_name');
        $cookieValue = config('services.petpooja.cookie_value');

        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Cookie' => "{$cookieName}={$cookieValue}",
            ])
            ->timeout(60)
            ->send('GET', $url, [
                'json' => $payload,
            ]);

        if (!$response->successful()) {
            throw new \Exception('Petpooja API failed: ' . $response->body());
        }

        return [
            'sales_date' => $salesDate,
            'petpooja_order_date' => $petpoojaOrderDate,
            'raw' => $response->json(),
        ];
    }

    public function extractItems(array $response): array
    {
        $items = [];

        $this->walkArray($response, $items);

        return $items;
    }

    private function walkArray(array $data, array &$items): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if ($this->looksLikeItem($value)) {
                    $items[] = [
                        'item_name' => $this->readItemName($value),
                        'quantity' => $this->readQuantity($value),
                        'price' => $this->readPrice($value),
                        'total' => $this->readTotal($value),
                    ];
                }

                $this->walkArray($value, $items);
            }
        }
    }

    private function looksLikeItem(array $row): bool
    {
        $keys = array_map(fn ($key) => Str::lower((string) $key), array_keys($row));

        $hasName = count(array_intersect($keys, [
            'itemname',
            'item_name',
            'item name',
            'name',
            'title',
            'product_name',
        ])) > 0;

        $hasQty = count(array_intersect($keys, [
            'quantity',
            'qty',
            'item_quantity',
        ])) > 0;

        return $hasName && $hasQty;
    }

    private function readItemName(array $row): string
    {
        foreach (['itemname', 'item_name', 'item name', 'name', 'title', 'product_name'] as $key) {
            if (isset($row[$key])) {
                return trim((string) $row[$key]);
            }
        }

        return '';
    }

    private function readQuantity(array $row): float
    {
        foreach (['quantity', 'qty', 'item_quantity'] as $key) {
            if (isset($row[$key])) {
                return (float) $row[$key];
            }
        }

        return 0;
    }

    private function readPrice(array $row): float
    {
        foreach (['price', 'rate', 'item_price'] as $key) {
            if (isset($row[$key])) {
                return (float) $row[$key];
            }
        }

        return 0;
    }

    private function readTotal(array $row): float
    {
        foreach (['total', 'item_total', 'subtotal', 'amount'] as $key) {
            if (isset($row[$key])) {
                return (float) $row[$key];
            }
        }

        return $this->readPrice($row) * $this->readQuantity($row);
    }

    public function normalizeName(string $name): string
    {
        $name = strtolower(trim($name));
        $name = preg_replace('/\s+/', ' ', $name);

        return $name;
    }
}