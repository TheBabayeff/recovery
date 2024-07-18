<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class DownloadPdfController extends Controller
{

    public function download(Ticket $record)
    {

        // Customer məlumatlarını yaratmaq
        $customer = new Buyer([
            'name' => $record->customer->name,
            'custom_fields' => [
                'email' => $record->customer->email,
                'phone' => $record->customer->phone,
                'address' => $record->customer->address,
                'passport' => $record->customer->passport,
            ],
        ]);



        // Ticket itemlarını yaratmaq və boş olan service əlaqələrini yoxlamaq
        $items = $record->items->map(function ($item) {
            if (!$item->service) {
                // Əgər service boşdursa, xüsusi bir hərəkət edə bilərik (məsələn, default bir ad təyin etmək)
                return InvoiceItem::make('Unknown Service')
                    ->pricePerUnit($item->price)
                    ->quantity($item->qty);
            }

            return InvoiceItem::make($item->service->name)
                ->pricePerUnit($item->price)
                ->quantity($item->qty);
        });

        // Engineer adını yaratmaq
        $engineerName = $record->engineer->name;
        // Invoice yaradılması
        $invoice = Invoice::make()
            ->buyer($customer)
        ->logo(public_path('images/logo-2.webp'))
            ->serialNumberFormat($record->number);

        // Hər bir itemı invoice-a əlavə etmək
        foreach ($items as $item) {
            $invoice->addItem($item);
        }
        // HTML şablonuna engineer adını göndərmək
        $data = [
            'invoice' => $invoice,
            'engineerName' => $engineerName,
        ];

        // Qeydlər əlavə etmək
        $notes = "<br>Cihazın Modeli: {$record->device_model}<br>
                Cihazın Seriya nömrəsi: {$record->device_serial_number}<br>
                Cihazın görünüşü: {$record->device_appearance}<br>";


        if ($record->engineer_note) {
            $notes .= "<br><strong style='font-size: 14px;'>Mühəndis rəyi</strong><br>";
            $notes .= "<table style='width:100%; border: 1px solid black;'>
                           <tr>
                               <td>{$record->engineer_note}</td>
                           </tr>
                       </table><br><br><br>";
        }

        $invoice->notes($notes);
        // PDF-i qaytarmaq
        return $invoice->stream();
    }
}
