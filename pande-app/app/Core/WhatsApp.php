<?php
/**
 * WhatsApp Integration Service
 * Pande Computers - Official WhatsApp Order Fulfillment Workflow
 */

declare(strict_types=1);

namespace App\Core;

class WhatsApp {
    /**
     * Normalize Tanzanian/international phone number to digits only (e.g. 255787311342)
     */
    public static function normalizePhoneNumber(string $phone): string {
        $digits = preg_replace('/\D+/', '', $phone);
        
        // If starts with 0 (e.g. 0787311342), convert to 255787311342
        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            $digits = '255' . substr($digits, 1);
        }
        
        // If starts with 7 (e.g. 787311342), add 255
        if (str_starts_with($digits, '7') && strlen($digits) === 9) {
            $digits = '255' . $digits;
        }

        return $digits;
    }

    /**
     * Build prefilled WhatsApp message for an order
     */
    public static function buildOrderMessage(array $order, array $items): string {
        $storeName = defined('APP_NAME') ? APP_NAME : 'PANDE COMPUTERS';
        
        $msg = "Hello {$storeName}, I would like to confirm my order.\n\n";
        $msg .= "Order Number: " . $order['order_number'] . "\n";
        $msg .= "Customer: " . $order['customer_name'] . "\n";
        $msg .= "Phone: " . $order['customer_phone'] . "\n\n";
        
        $msg .= "Items:\n";
        foreach ($items as $item) {
            $lineTotal = number_format((float)$item['line_total'], 0, '.', ',');
            $msg .= "- {$item['product_name']} x {$item['quantity']} — TZS {$lineTotal}\n";
        }
        
        $msg .= "\n";
        $subtotal = number_format((float)$order['subtotal'], 0, '.', ',');
        $deliveryFee = number_format((float)$order['delivery_fee'], 0, '.', ',');
        $total = number_format((float)$order['total_amount'], 0, '.', ',');
        
        $msg .= "Subtotal: TZS {$subtotal}\n";
        $msg .= "Delivery: TZS {$deliveryFee}\n";
        $msg .= "Total: TZS {$total}\n\n";
        
        $address = !empty($order['delivery_address']) ? $order['delivery_address'] : 'Collection at Store';
        $city = !empty($order['delivery_city']) ? ', ' . $order['delivery_city'] : '';
        $msg .= "Delivery Address: {$address}{$city}\n";
        $msg .= "Delivery Method: " . ucfirst($order['delivery_method']) . "\n\n";
        
        $msg .= "Please confirm product availability and provide payment instructions.\n\n";
        $msg .= "Thank you.";

        return $msg;
    }

    /**
     * Generate full wa.me link with encoded message
     */
    public static function generateClickToChatLink(string $phone, string $message): string {
        $normalizedPhone = self::normalizePhoneNumber($phone);
        $encodedMessage = rawurlencode($message);
        return "https://wa.me/{$normalizedPhone}?text={$encodedMessage}";
    }
}
