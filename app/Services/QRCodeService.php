<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Generate QR Code for product
     */
    public function generateProductQR($product)
    {
        $data = json_encode([
            'type' => 'product',
            'id' => $product->id,
            'sku' => $product->sku,
            'name' => $product->name,
            'price' => $product->price,
        ]);

        $filename = 'qrcodes/products/product_' . $product->id . '_' . time() . '.svg';
        $path = storage_path('app/public/' . $filename);

        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        // Generate QR Code as SVG (no imagick/gd required)
        QrCode::format('svg')
            ->size(300)
            ->generate($data, $path);

        return $filename;
    }

    /**
     * Generate QR Code for member
     */
    public function generateMemberQR($member)
    {
        $data = json_encode([
            'type' => 'member',
            'id' => $member->id,
            'member_code' => $member->member_code,
            'name' => $member->user->name,
        ]);

        $filename = 'qrcodes/members/member_' . $member->id . '_' . time() . '.svg';
        $path = storage_path('app/public/' . $filename);

        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        // Generate QR Code as SVG (no imagick/gd required)
        QrCode::format('svg')
            ->size(300)
            ->generate($data, $path);

        return $filename;
    }

    /**
     * Delete QR Code file
     */
    public function deleteQR($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
