<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class QuotationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customerName' => 'required|string|max:255',
            'projectName' => 'nullable|string|max:255',
            'items' => 'required|array',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'totalAmount' => 'required|numeric|min:0',
            'includeSetup' => 'required|boolean',
            'expressShipping' => 'required|boolean',
            'frontPage' => 'required|array',
            'backPage' => 'required|array',
            'attachments' => 'nullable|array',
            'customerId' => 'required|integer|exists:leads,id',
            'status' => 'required|string|in:draft,pending,approved,rejected'
        ]);

        try {
            $attachments = [];
            if (!empty($validated['attachments'])) {
                $quotationFolder = 'quotations/' . Str::uuid();
                
                foreach ($validated['attachments'] as $attachment) {
                    $fileData = base64_decode(preg_replace(
                        '#^data:\w+/\w+;base64,#i', 
                        '', 
                        $attachment['data']
                    ));

                    $fileName = Str::slug(pathinfo($attachment['name'], PATHINFO_FILENAME))
                              . '.' . pathinfo($attachment['name'], PATHINFO_EXTENSION);
                    
                    $filePath = "$quotationFolder/$fileName";
                    Storage::put($filePath, $fileData);

                    $attachments[] = [
                        'name' => $fileName,
                        'path' => $filePath,
                        'type' => $attachment['type'],
                        'size' => $attachment['size'] ?? null,
                        'width' => $attachment['width'] ?? null,
                        'height' => $attachment['height'] ?? null
                    ];
                }
            }
            
            $lead = Lead::findOrFail($validated['customerId']);

            // Create quotation using current date/time
            $quotation = Quotation::create([
                'customer_name' => $validated['customerName'],
                'project_name' => $validated['projectName'],
                'date' => now(), // Use current date/time
                'items' => $validated['items'],
                'subtotal' => $validated['subtotal'],
                'discount' => $validated['discount'],
                'tax' => $validated['tax'],
                'total_amount' => $validated['totalAmount'],
                'include_setup' => $validated['includeSetup'],
                'express_shipping' => $validated['expressShipping'],
                'front_page' => $validated['frontPage'],
                'back_page' => $validated['backPage'],
                'attachments' => $attachments,
                'customer_id' => $lead->id,
                'status' => $validated['status']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Quotation created successfully',
                'data' => $quotation
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create quotation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}