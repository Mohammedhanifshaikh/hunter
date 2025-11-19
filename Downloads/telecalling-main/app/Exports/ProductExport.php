<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::with('category:id,category_name')
            ->select('id', 'category_id', 'product_name', 'product_slug', 'status', 'created_at')
            ->get()
            ->map(function ($product) {
                $product->category_name = $product->category ? $product->category->category_name : null;
                return $product;
            })
            ->map(function ($product) {
                return [
                    'ID' => $product->id,
                    'CategoryID' => $product->category_id,
                    'CategoryName' => $product->category_name,
                    'ProductName' => $product->product_name,
                    'ProductSlug' => $product->product_slug,
                    'Status' => $product->status,
                    'CreatedAt' => $product->created_at->toIso8601String(),
                ];
            });
    }

    public function headings(): array
    {
        return ['ID', 'CategoryID', 'CategoryName', 'ProductName', 'ProductSlug', 'Status', 'CreatedAt'];
    }
}