<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Lead;
use App\Models\QuotationTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class QuotationController extends Controller
{
    // public function store(Request $request)
    // {

    //     // dd($request->all());
    //     $validated = $request->validate([
    //         'customerName' => 'required|string|max:255',
    //         'projectName' => 'nullable|string|max:255',
    //         'items' => 'required|array',
    //         'subtotal' => 'required|numeric|min:0',
    //         'discount' => 'required|numeric|min:0',
    //         'tax' => 'required|numeric|min:0',
    //         'totalAmount' => 'required|numeric|min:0',
    //         'includeSetup' => 'required|boolean',
    //         'expressShipping' => 'required|boolean',
    //         'frontPage' => 'required|array',
    //         'backPage' => 'required|array',
    //         'attachments' => 'nullable|array',
    //         'customerId' => 'required|integer|exists:leads,id',
    //         'status' => 'required|string|in:draft,pending,approved,rejected',
    //         'pdfPath' => 'required|string',
    //         'setupDate' => 'nullable|date',
    //         'packupDate' => 'nullable|date'
    //     ]);

    //     try {
    //         $attachments = [];
    //         if (!empty($validated['attachments'])) {
    //             $quotationFolder = 'quotations/' . Str::uuid();
                
    //             foreach ($validated['attachments'] as $attachment) {
    //                 $fileData = base64_decode(preg_replace(
    //                     '#^data:\w+/\w+;base64,#i', 
    //                     '', 
    //                     $attachment['data']
    //                 ));

    //                 $fileName = Str::slug(pathinfo($attachment['name'], PATHINFO_FILENAME))
    //                           . '.' . pathinfo($attachment['name'], PATHINFO_EXTENSION);
                    
    //                 $filePath = "$quotationFolder/$fileName";
    //                 Storage::put($filePath, $fileData);

    //                 $attachments[] = [
    //                     'name' => $fileName,
    //                     'path' => $filePath,
    //                     'type' => $attachment['type'],
    //                     'size' => $attachment['size'] ?? null,
    //                     'width' => $attachment['width'] ?? null,
    //                     'height' => $attachment['height'] ?? null
    //                 ];
    //             }
    //         }
            
    //         $lead = Lead::findOrFail($validated['customerId']);

    //         // Create quotation using current date/time
    //         $quotation = Quotation::create([
    //             'customer_name' => $validated['customerName'],
    //             'project_name' => $validated['projectName'],
    //             'quotation_date' => now(),
    //             'items' => $validated['items'],
    //             'subtotal' => $validated['subtotal'],
    //             'discount' => $validated['discount'],
    //             'tax' => $validated['tax'],
    //             'total_amount' => $validated['totalAmount'],
    //             'include_setup' => $validated['includeSetup'],
    //             'express_shipping' => $validated['expressShipping'],
    //             'front_page' => $validated['frontPage'],
    //             'back_page' => $validated['backPage'],
    //             'attachments' => $attachments,
    //             'customer_id' => $lead->id,
    //             'status' => $validated['status'],
    //             'pdfPath' => $validated['pdfPath'],
    //             'setup_date' => $validated['setupDate'] ?? null,
    //             'packup_date' => $validated['packupDate'] ?? null
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Quotation created successfully',
    //             'data' => $quotation
    //         ], 201);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to create quotation',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    // public function store(Request $request)
    // {
    //     // Validate the request, including isResubmission
    //     $validated = $request->validate([
    //         'customerName' => 'required|string|max:255',
    //         'projectName' => 'nullable|string|max:255',
    //         'items' => 'required|array',
    //         'subtotal' => 'required|numeric|min:0',
    //         'discount' => 'required|numeric|min:0',
    //         'tax' => 'required|numeric|min:0',
    //         'totalAmount' => 'required|numeric|min:0',
    //         'includeSetup' => 'required|boolean',
    //         'expressShipping' => 'required|boolean',
    //         'frontPages' => 'required|array',
    //         'backPages' => 'required|array',
    //         'attachments' => 'nullable|array',
    //         'customerId' => 'required|integer|exists:leads,id',
    //         'status' => 'required|string|in:draft,pending,approved,rejected',
    //         'pdfPath' => 'required|string',
    //         'setupDate' => 'nullable|date',
    //         'packupDate' => 'nullable|date',
    //         'is_resubmission' => 'nullable|boolean', // Add validation for isResubmission
    //         'parent_quotation_id' => 'nullable|integer|exists:quotation,id'

    //     ]);

    //     try {
    //         // Process attachments
    //         $attachments = [];
    //         if (!empty($validated['attachments'])) {
    //             $quotationFolder = 'quotations/' . Str::uuid();
                
    //             foreach ($validated['attachments'] as $attachment) {
    //                 $fileData = base64_decode(preg_replace(
    //                     '#^data:\w+/\w+;base64,#i', 
    //                     '', 
    //                     $attachment['data']
    //                 ));

    //                 $fileName = Str::slug(pathinfo($attachment['name'], PATHINFO_FILENAME))
    //                         . '.' . pathinfo($attachment['name'], PATHINFO_EXTENSION);
                    
    //                 $filePath = "$quotationFolder/$fileName";
    //                 Storage::put($filePath, $fileData);

    //                 $attachments[] = [
    //                     'name' => $fileName,
    //                     'path' => $filePath,
    //                     'type' => $attachment['type'],
    //                     'size' => $attachment['size'] ?? null,
    //                     'width' => $attachment['width'] ?? null,
    //                     'height' => $attachment['height'] ?? null
    //                 ];
    //             }
    //         }
            
    //         $lead = Lead::findOrFail($validated['customerId']);
    //         $parentQuotationId = null;

    //         // Handle resubmission logic
    //         // if ($validated['is_resubmission'] === true) {
    //         //     // Find the original rejected quotation (e.g., by customerId and projectName, or pass a quotationId explicitly)
    //         //     $originalQuotation = Quotation::where('customer_id', $validated['customerId'])
    //         //         ->where('status', 'rejected')
    //         //         ->where('project_name', $validated['projectName'] ?? '')
    //         //         ->latest()
    //         //         ->first();

    //         //     if ($originalQuotation) {
    //         //         $parentQuotationId = $originalQuotation->id;
    //         //         // Optionally update the original quotation's status or add a note
    //         //         // $originalQuotation->update(['status' => 'archived']); // Example: archive the original
    //         //     } else {
    //         //         return response()->json([
    //         //             'success' => false,
    //         //             'message' => 'No rejected quotation found for resubmission',
    //         //         ], 400);
    //         //     }
    //         // }
    //         if ($validated['is_resubmission'] === true) {
    // // Replace original lookup logic with direct use of parent_quotation_id
    //             if (!empty($validated['parent_quotation_id'])) {
    //                 $originalQuotation = Quotation::where('id', $validated['parent_quotation_id'])
    //                     ->where('customer_id', $validated['customerId'])
    //                     ->where('status', 'rejected')
    //                     ->first();

    //                 if ($originalQuotation) {
    //                     $parentQuotationId = $originalQuotation->id;
    //                 } else {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'No rejected quotation found for resubmission',
    //                     ], 400);
    //                 }
    //             } else {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'parent_quotation_id is required for resubmission',
    //                 ], 400);
    //             }
    //         }


    //         // Create quotation using current date/time
    //         $quotation = Quotation::create([
    //             'customer_name' => $validated['customerName'],
    //             'project_name' => $validated['projectName'],
    //             'quotation_date' => now(),
    //             'items' => $validated['items'],
    //             'subtotal' => $validated['subtotal'],
    //             'discount' => $validated['discount'],
    //             'tax' => $validated['tax'],
    //             'total_amount' => $validated['totalAmount'],
    //             'include_setup' => $validated['includeSetup'],
    //             'express_shipping' => $validated['expressShipping'],
    //             'front_page' => $validated['frontPage'],
    //             'back_page' => $validated['backPage'],
    //             'attachments' => $attachments,
    //             'customer_id' => $lead->id,
    //             'status' => $validated['status'],
    //             'pdfPath' => $validated['pdfPath'],
    //             'setup_date' => $validated['setupDate'] ?? null,
    //             'packup_date' => $validated['packupDate'] ?? null,
    //             'parent_quotation_id' => $parentQuotationId, // Link to original quotation if resubmission
    //             'is_resubmission' => $validated['is_resubmission'] ?? false, // Track resubmission flag
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Quotation created successfully',
    //             'data' => $quotation,
    //             'parent_quotation_id' => $parentQuotationId, // Include in response for frontend reference
    //         ], 201);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to create quotation',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    // public function store(Request $request)
    // {
    //     // Validate the request
    //     $validated = $request->validate([
    //         'customerName' => 'required|string|max:255',
    //         'projectName' => 'nullable|string|max:255',
    //         'items' => 'required|array',
    //         'items.*.id' => 'required',
    //         'items.*.name' => 'required|string',
    //         'items.*.qty' => 'required|integer|min:1',
    //         'items.*.days' => 'required|integer|min:1',
    //         'items.*.price' => 'required|numeric|min:0',
    //         'items.*.vendor' => 'nullable|string',
    //         'items.*.available' => 'required|integer',
    //         'subtotal' => 'required|numeric|min:0',
    //         'discount' => 'required|numeric|min:0',
    //         'tax' => 'required|numeric|min:0',
    //         'totalAmount' => 'required|numeric|min:0',
    //         'includeSetup' => 'required|boolean',
    //         'expressShipping' => 'required|boolean',
    //         'frontPages' => 'required|array|min:1',
    //         'frontPages.*.title' => 'required|string',
    //         'frontPages.*.content' => 'required|string',
    //         'frontPages.*.attachments' => 'nullable|array',
    //         'frontPages.*.attachments.*.name' => 'required_with:frontPages.*.attachments|string',
    //         'frontPages.*.attachments.*.data' => 'required_with:frontPages.*.attachments|string', // Base64 data
    //         'frontPages.*.attachments.*.type' => 'required_with:frontPages.*.attachments|string',
    //         'backPages' => 'required|array|min:1',
    //         'backPages.*.title' => 'required|string',
    //         'backPages.*.content' => 'required|string',
    //         'backPages.*.attachments' => 'nullable|array',
    //         'backPages.*.attachments.*.name' => 'required_with:backPages.*.attachments|string',
    //         'backPages.*.attachments.*.data' => 'required_with:backPages.*.attachments|string',
    //         'backPages.*.attachments.*.type' => 'required_with:backPages.*.attachments|string',
    //         'customerId' => 'required|integer|exists:leads,id',
    //         'status' => 'required|string|in:draft,pending,approved,rejected',
    //         'pdfPath' => 'required|string',
    //         'setupDate' => 'nullable|date',
    //         'packupDate' => 'nullable|date',
    //         'is_resubmission' => 'nullable|boolean',
    //         'parent_quotation_id' => 'nullable|integer|exists:quotations,id',
    //         'ccEmails' => 'nullable|array',
    //         'ccEmails.*' => 'email',
    //         'sendViaWhatsApp' => 'nullable|boolean',
    //         'templateId' => 'nullable|integer|exists:quotation_templates,id',
    //     ]);

    //     try {
    //         // Process attachments for each page
    //         $frontPages = $validated['frontPages'];
    //         $backPages = $validated['backPages'];
    //         $quotationFolder = 'quotations/' . Str::uuid();

    //         foreach (['frontPages', 'backPages'] as $pageType) {
    //             foreach (${$pageType} as $pageIndex => $page) {
    //                 if (!empty($page['attachments'])) {
    //                     $pageAttachments = [];
    //                     foreach ($page['attachments'] as $attachment) {
    //                         $fileData = base64_decode(preg_replace(
    //                             '#^data:\w+/\w+;base64,#i',
    //                             '',
    //                             $attachment['data']
    //                         ));

    //                         $fileName = Str::slug(pathinfo($attachment['name'], PATHINFO_FILENAME))
    //                             . '.' . pathinfo($attachment['name'], PATHINFO_EXTENSION);
    //                         $filePath = "$quotationFolder/$fileName";
    //                         Storage::put($filePath, $fileData);

    //                         $pageAttachments[] = [
    //                             'name' => $fileName,
    //                             'path' => $filePath,
    //                             'type' => $attachment['type'],
    //                             'size' => $attachment['size'] ?? null,
    //                             'width' => $attachment['width'] ?? null,
    //                             'height' => $attachment['height'] ?? null,
    //                         ];
    //                     }
    //                     ${$pageType}[$pageIndex]['attachments'] = $pageAttachments;
    //                 }
    //             }
    //         }

    //         $lead = Lead::findOrFail($validated['customerId']);
    //         $parentQuotationId = null;

    //         // Handle resubmission logic
    //         if ($validated['is_resubmission'] === true) {
    //             if (!empty($validated['parent_quotation_id'])) {
    //                 $originalQuotation = Quotation::where('id', $validated['parent_quotation_id'])
    //                     ->where('customer_id', $validated['customerId'])
    //                     ->where('status', 'rejected')
    //                     ->first();

    //                 if ($originalQuotation) {
    //                     $parentQuotationId = $originalQuotation->id;
    //                 } else {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'No rejected quotation found for resubmission',
    //                     ], 400);
    //                 }
    //             } else {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'parent_quotation_id is required for resubmission',
    //                 ], 400);
    //             }
    //         }

    //         // Create quotation
    //         $quotation = Quotation::create([
    //             'customer_name' => $validated['customerName'],
    //             'project_name' => $validated['projectName'],
    //             'quotation_date' => now(),
    //             'items' => $validated['items'], // Store as JSON
    //             'subtotal' => $validated['subtotal'],
    //             'discount' => $validated['discount'],
    //             'tax' => $validated['tax'],
    //             'total_amount' => $validated['totalAmount'],
    //             'include_setup' => $validated['includeSetup'],
    //             'express_shipping' => $validated['expressShipping'],
    //             'front_pages' => $frontPages, // Store as JSON
    //             'back_pages' => $backPages,   // Store as JSON
    //             'customer_id' => $lead->id,
    //             'status' => $validated['status'],
    //             'pdf_path' => $validated['pdfPath'],
    //             'setup_date' => $validated['setupDate'] ?? null,
    //             'packup_date' => $validated['packupDate'] ?? null,
    //             'parent_quotation_id' => $parentQuotationId,
    //             'is_resubmission' => $validated['is_resubmission'] ?? false,
    //             'cc_emails' => $validated['ccEmails'] ?? [],
    //             'send_via_whatsapp' => $validated['sendViaWhatsApp'] ?? false,
    //             'template_id' => $validated['templateId'] ?? null,
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Quotation created successfully',
    //             'data' => $quotation,
    //             'parent_quotation_id' => $parentQuotationId,
    //         ], 201);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to create quotation',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'customerName' => 'required|string|max:255',
            'projectName' => 'nullable|string|max:255',
            'items' => 'required|array',
            'items.*.id' => 'required',
            'items.*.name' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.days' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.vendor' => 'nullable|string',
            'items.*.available' => 'required|integer',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'totalAmount' => 'required|numeric|min:0',
            'includeSetup' => 'required|boolean',
            'expressShipping' => 'required|boolean',
            'frontPages' => 'required|array|min:1',
            'frontPages.*.title' => 'required|string',
            'frontPages.*.content' => 'required|string',
            'frontPages.*.attachments' => 'nullable|array',
            'frontPages.*.attachments.*.name' => 'required_with:frontPages.*.attachments|string',
            'frontPages.*.attachments.*.data' => 'required_with:frontPages.*.attachments|string',
            'frontPages.*.attachments.*.type' => 'required_with:frontPages.*.attachments|string',
            'backPages' => 'required|array|min:1',
            'backPages.*.title' => 'required|string',
            'backPages.*.content' => 'required|string',
            'backPages.*.attachments' => 'nullable|array',
            'backPages.*.attachments.*.name' => 'required_with:backPages.*.attachments|string',
            'backPages.*.attachments.*.data' => 'required_with:backPages.*.attachments|string',
            'backPages.*.attachments.*.type' => 'required_with:backPages.*.attachments|string',
            'customerId' => 'required|integer|exists:leads,id',
            'status' => 'required|string|in:draft,pending,approved,rejected',
            'pdfPath' => 'required|string',
            'setupDate' => 'nullable|date',
            'packupDate' => 'nullable|date',
            'is_resubmission' => 'nullable|boolean',
            'parent_quotation_id' => 'nullable|integer|exists:quotations,id',
            'ccEmails' => 'nullable|array',
            'ccEmails.*' => 'email',
            'sendViaWhatsApp' => 'nullable|boolean',
            'templateId' => 'nullable|integer|exists:quotation_templates,id',
        ]);

        try {
            // Process attachments for each page
            $frontPages = $validated['frontPages'];
            $backPages = $validated['backPages'];
            $quotationFolder = 'quotations/' . Str::uuid();

            foreach (['frontPages', 'backPages'] as $pageType) {
                foreach (${$pageType} as $pageIndex => $page) {
                    if (!empty($page['attachments'])) {
                        $pageAttachments = [];
                        foreach ($page['attachments'] as $attachment) {
                            $fileData = base64_decode(preg_replace(
                                '#^data:\w+/\w+;base64,#i',
                                '',
                                $attachment['data']
                            ));

                            $fileName = Str::slug(pathinfo($attachment['name'], PATHINFO_FILENAME))
                                . '.' . pathinfo($attachment['name'], PATHINFO_EXTENSION);
                            $filePath = "$quotationFolder/$fileName";
                            Storage::put($filePath, $fileData);

                            $pageAttachments[] = [
                                'name' => $fileName,
                                'path' => $filePath,
                                'type' => $attachment['type'],
                                'size' => $attachment['size'] ?? null,
                                'width' => $attachment['width'] ?? null,
                                'height' => $attachment['height'] ?? null,
                            ];
                        }
                        ${$pageType}[$pageIndex]['attachments'] = $pageAttachments;
                    }
                }
            }

            $lead = Lead::findOrFail($validated['customerId']);
            $parentQuotationId = null;

            // Handle resubmission logic
            if ($validated['is_resubmission'] === true) {
                if (!empty($validated['parent_quotation_id'])) {
                    $originalQuotation = Quotation::where('id', $validated['parent_quotation_id'])
                        ->where('customer_id', $validated['customerId'])
                        ->where('status', 'rejected')
                        ->first();

                    if ($originalQuotation) {
                        $parentQuotationId = $originalQuotation->id;
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'No rejected quotation found for resubmission',
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'parent_quotation_id is required for resubmission',
                    ], 400);
                }
            }

            // Serialize arrays to JSON
            $itemsJson = json_encode($validated['items']);
            $frontPagesJson = json_encode($frontPages);
            $backPagesJson = json_encode($backPages);
            $ccEmailsJson = json_encode($validated['ccEmails'] ?? []);

            // Create quotation
            // dd($validated['pdfPath']);
            $quotation = Quotation::create([
                'customer_name' => $validated['customerName'],
                'project_name' => $validated['projectName'],
                'quotation_date' => now(),
                'items' => $itemsJson,
                'subtotal' => $validated['subtotal'],
                'discount' => $validated['discount'],
                'tax' => $validated['tax'],
                'total_amount' => $validated['totalAmount'],
                'include_setup' => $validated['includeSetup'],
                'express_shipping' => $validated['expressShipping'],
                'front_pages' => $frontPagesJson,
                'back_pages' => $backPagesJson,
                'customer_id' => $lead->id,
                'status' => $validated['status'],
                'pdfPath' => $validated['pdfPath'],
                'setup_date' => $validated['setupDate'] ?? null,
                'packup_date' => $validated['packupDate'] ?? null,
                'parent_quotation_id' => $parentQuotationId,
                'is_resubmission' => $validated['is_resubmission'] ?? false,
                'cc_emails' => $ccEmailsJson,
                'send_via_whatsapp' => $validated['sendViaWhatsApp'] ?? false,
                'template_id' => $validated['templateId'] ?? null,
                'quotation_number' => $validated['quotation_number'] ?? 'QTN-' . Str::upper(Str::random(11)),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Quotation created successfully',
                'data' => $quotation,
                'parent_quotation_id' => $parentQuotationId,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create quotation',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function save_pdf(Request $request)
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'pdf' => 'required|file|mimes:pdf', // Max 2MB
            ]);

            // Store the PDF in public/storage/quotations
            $file = $request->file('pdf');
            $filename = $file->getClientOriginalName();
            $path = $file->storeAs('quotations', $filename, 'public');

            // Return the relative path
            return response()->json([
                'file_path' => $path, // e.g., 'quotations/quotation-JohnDoe-123456789.pdf'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error saving PDF: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to save PDF',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $quotations = Quotation::with(['lead', 'parentQuotation'])
                ->get()
                ->map(function ($quotation) {
                    return [
                        'id' => $quotation->id,
                        'quotation_number' => $quotation->quotation_number,
                        'customer_name' => $quotation->customer_name,
                        'project_name' => $quotation->project_name,
                        'total_amount' => $quotation->total_amount,
                        'date' => ($quotation->quotation_date && $quotation->quotation_date !== '0000-00-00')
                            ? \Carbon\Carbon::parse($quotation->quotation_date)->format('Y-m-d')
                            : null,
                        'status' => $quotation->status === 'draft' ? 'pending' : $quotation->status,
                        'customer_id' => $quotation->customer_id,
                        'items' => $quotation->items,
                        'subtotal' => $quotation->subtotal,
                        'discount' => $quotation->discount,
                        'tax' => $quotation->tax,
                        'include_setup' => $quotation->include_setup,
                        'express_shipping' => $quotation->express_shipping,
                        'front_page' => $quotation->front_page,
                        'back_page' => $quotation->back_page,
                        'attachments' => $quotation->attachments,
                        'pdfPath' => $quotation->pdfPath,
                        'setupDate' => $quotation->setup_date,
                        'packupDate' => $quotation->packup_date,
                        'is_resubmission' => $quotation->is_resubmission ?? false,
                        'parent_quotation_number' => $quotation->parentQuotation
                            ? $quotation->parentQuotation->quotation_number
                            : null,
                        'parent_chain' => $quotation->allParents()->map(function ($parent, $index) {
                            return [
                                'id' => $parent->id,
                                'quotation_number' => $parent->quotation_number,
                                'status' => $parent->status,
                                'version' => $index === 0 ? 'Original' : 'Rev ' . $index
                            ];
                        })->reverse()->values(),
    
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $quotations
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch quotations',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function delete($id)
    {
        try {
            $quotation = Quotation::findOrFail($id);
            $quotation->delete();
            return response()->json([
                'success' => true,
                'message' => 'Quotation deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete quotation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function change_status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $quotation = Quotation::findOrFail($id);
        $quotation->status = $request->status;

        // Only set note if column exists and value is passed
        if ($request->has('note') && \Schema::hasColumn('quotations', 'note')) {
            $quotation->note = $request->note;
        }

        $quotation->save();

        return response()->json([
            'message' => 'Quotation status updated successfully.',
            'data' => $quotation
        ]);
    }
    public function saveItemsPdf(Request $request)
    {
        //  dd($request->quotation_id);
        try {
            // Validate request

                        $quotation = Quotation::findOrFail($request->quotation_id);

            
            $request->validate([
                'pdf' => 'required|file|mimes:pdf|max:2048',               
                'customer_name' => 'required|string',
                'quotation_number' => 'required|string',
            ]);

            // Store the PDF
            $pdfFile = $request->file('pdf');
            $fileName = time() . '_' . $pdfFile->getClientOriginalName(); // Prevent overwrites
            $path = $pdfFile->storeAs('quotations/items', $fileName, 'public');

            // Update quotation with items PDF path

           
            $quotation = Quotation::findOrFail($request->quotation_id);
            $quotation->items_pdf_path = $path;
            $quotation->save();

            return response()->json([
                'success' => true,
                'message' => 'Items PDF saved successfully',
                'file_path' => $path,
                'url' => Storage::url($path),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save items PDF: ' . $e->getMessage(),
            ], 500);
        }
    }

}