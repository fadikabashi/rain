<?php

namespace App\Services;

use App\Models\QuoteRequestBatch;
use Illuminate\Support\Facades\File;

class QuotePdfDataBuilder
{
    public function build(QuoteRequestBatch $batch): array
    {
        $batch->loadMissing(['items.product.category']);

        $config = config('quote_pdf');
        $labels = $config['labels'];

        $logoDataUrl = $this->resolveLogoDataUrl($config['logo_path'] ?? null);

        $sections = [];
        $grandTotal = 0.0;

        $grouped = $batch->items->groupBy(function ($item) {
            return $item->product?->category_id ?? 0;
        });

        foreach ($grouped as $items) {
            $firstItem = $items->first();
            $category = $firstItem->product?->category;
            $title = $category?->name_en ?? 'Uncategorized';

            $rows = [];
            $sectionTotal = 0.0;
            $index = 1;

            foreach ($items as $item) {
                $product = $item->product;
                $unit = $this->resolveUnitPrice($item, $product);
                $qty = (int) $item->quantity;
                $amount = round($unit * $qty, 2);
                $sectionTotal += $amount;
                $grandTotal += $amount;

                $rows[] = [
                    'index' => $index,
                    'code' => $product ? sprintf('PRD-%05d', $product->id) : '—',
                    'name' => $product?->name_en ?? '—',
                    'description' => $this->plainTextExcerpt($product?->description_en ?? ''),
                    'line_note' => $item->line_note,
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'line_total' => $amount,
                    'image_url' => $product ? asset($product->photo) : null,
                ];

                $index++;
            }

            $sections[] = [
                'title' => $title,
                'rows' => $rows,
                'section_total' => round($sectionTotal, 2),
            ];
        }

        $grandTotal = round($grandTotal, 2);

        return [
            'meta' => [
                'quote_id' => $batch->id,
                'currency' => $labels['currency'],
                'created_at_iso' => $batch->created_at?->toIso8601String(),
            ],
            'branding' => [
                'company_name' => $config['company_name'],
                'tagline' => $config['tagline'],
                'phone' => $config['phone'],
                'whatsapp' => $config['whatsapp'],
                'address' => $config['address'],
                'logo_data_url' => $logoDataUrl,
            ],
            'customer' => [
                'name' => $batch->customer_name,
                'email' => $batch->customer_email,
                'phone' => $batch->customer_phone,
                'company_name' => $batch->company_name,
                'location_line' => $batch->company_name,
                'message' => $batch->message,
            ],
            'labels' => $labels,
            'sections' => $sections,
            'grand_total' => $grandTotal,
        ];
    }

    /**
     * @param  \App\Models\QuoteRequestItem  $item
     * @param  \App\Models\Product|null  $product
     */
    private function resolveUnitPrice($item, $product): float
    {
        if ($item->quoted_price !== null) {
            return round((float) $item->quoted_price, 2);
        }

        if ($product) {
            return round((float) $product->price_after_discount, 2);
        }

        return 0.0;
    }

    private function plainTextExcerpt(string $htmlOrText, int $maxLength = 280): string
    {
        $text = trim(html_entity_decode(strip_tags($htmlOrText), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        if (mb_strlen($text) <= $maxLength) {
            return $text;
        }

        return mb_substr($text, 0, $maxLength) . '…';
    }

    private function resolveLogoDataUrl(?string $publicRelativePath): ?string
    {
        if ($publicRelativePath === null || $publicRelativePath === '') {
            return null;
        }

        $path = public_path(ltrim($publicRelativePath, '/'));

        if (! File::isFile($path)) {
            return null;
        }

        $mime = File::mimeType($path) ?: 'image/png';
        $data = base64_encode(File::get($path));

        return 'data:' . $mime . ';base64,' . $data;
    }
}
