<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Model\Lead;
use Illuminate\Database\Eloquent\Model;
use App\Models\LeadDetail;
use App\Models\LeadAttachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;




class CrmController extends Controller
{
    //  public function store(Request $request)
    // {
    //    $validated = $request->validate([
    //     'name' => 'required|string|max:255',
    //     'phone' => 'required|string|max:255',
    //     'email' => 'required|email',
    //     'referredBy' => 'nullable|string|max:255',
    //     'referralMethod' => 'nullable|string|in:email,phone',
    //     'referralContact' => 'nullable|string|max:255',
    //     'leadSource' => 'nullable|string|max:255',
    //     'ceoApproval'=>'nullable|string|max:255', 
    //     'company'=>'nullable|string|max:255',             
    //     ]);
    //    // return $validated;
    //     $lead = new Lead();
    //     $lead->lead_name = $validated['name'];
    //     $lead->lead_email = $validated['email'];
    //     $lead->lead_sourse = $validated['leadSource'] ?? null;
    //     $lead->lead_notes = $validated['referralMethod'] ?? null;
    //     $lead->lead_phone = $validated['phone'] ?? null;
    //     $lead->user_id = auth()->id();// if needed
    //     $lead->company = $validated['company'] ?? null;
    //     $lead->lead_status = 'hot'; 
    //     $lead->ceo_approval = $validated['ceoApproval'] ?? null;;// or any default value

    //     $lead->save();
    //     $leadDetail = new LeadDetail();
    //     $leadDetail->lead_id = $lead->id;
    //   //  $leadDetail->lead_id = $lead->id;
    //     $leadDetail->type = 'crud';
    //     $leadDetail->task_title = 'Account Created';
        
    //     $leadDetail->save();  // <-- foreign key



    //     return response()->json([
    //         'success' => true,
    //         'lead' => $lead,
    //     ], 201);
    // }
    public function store(Request $request)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|email',
                'referredBy' => 'nullable|string|max:255',
                'referralMethod' => 'nullable|string|in:email,phone',
                'referralContact' => 'nullable|string|max:255',
                'leadSource' => 'nullable|string|max:255',
                'ceoApproval' => 'nullable|string|max:255', 
                'company' => 'nullable|string|max:255',
                'attachments' => 'nullable|array', // Add validation for attachments
                'attachments.*' => 'file|max:10240', // 10MB per file
            ]);

            $lead = new Lead();
            $lead->lead_name = $validated['name'];
            $lead->lead_email = $validated['email'];
            $lead->lead_sourse = $validated['leadSource'] ?? null;
            $lead->lead_notes = $validated['referralMethod'] ?? null;
            $lead->lead_phone = $validated['phone'] ?? null;
            $lead->user_id = auth()->id(); // if needed
            $lead->company = $validated['company'] ?? null;
            $lead->lead_status = 'hot'; 
            $lead->ceo_approval = $validated['ceoApproval'] ?? null;
            $lead->save();

            // Save lead details
            $leadDetail = new LeadDetail();
            $leadDetail->lead_id = $lead->id;
            $leadDetail->type = 'crud';
            $leadDetail->task_title = 'Account Created';
            $leadDetail->save();

            // Handle file uploads
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    // Store file in storage/app/public/lead_attachments
                    $path = $file->store('lead_attachments', 'public');
                    
                    // Create attachment record
                    $attachment = new LeadAttachment();
                    $attachment->lead_id = $lead->id;
                    $attachment->original_name = $file->getClientOriginalName();
                    $attachment->path = $path;
                    $attachment->mime_type = $file->getMimeType();
                    $attachment->size = $file->getSize();
                    $attachment->save();
                    
                    $attachments[] = [
                        'id' => $attachment->id,
                        'original_name' => $attachment->original_name,
                        'path' => $attachment->path,
                        'url' => Storage::url($path), // Public URL
                        'mime_type' => $attachment->mime_type,
                        'size' => $attachment->size,
                        'created_at' => $attachment->created_at,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'lead' => $lead,
                'attachments' => $attachments,
            ], 201);
        }
    
    // public function updateLead(Request $request, Lead $lead)
    // {

    //     // dd($lead);
    //     $validated = $request->validate([
    //         'name' => 'sometimes|string|max:255',
    //         'phone' => 'sometimes|string|max:255',
    //         'email' => 'sometimes|email',
    //         'referredBy' => 'sometimes|string|max:255',
    //         'referralMethod' => 'sometimes|string|in:email,phone',
    //         'referralContact' => 'sometimes|string|max:255',
    //         'leadSource' => 'sometimes|string|max:255',
    //         'ceoApproval' => 'sometimes|string|max:255',
    //         'company' => 'sometimes|string|max:255',
    //         'status' => 'sometimes|string|in:warm,hot,cold,lost,client',
    //         'attachments' => 'sometimes|array',
    //         'attachments.*' => 'file|max:10240',
    //     ]);

    //     // Update lead attributes
    //     if (array_key_exists('name', $validated)) {
    //         $lead->lead_name = $validated['name']; 
    //     }
    //     if (array_key_exists('phone', $validated)) {
    //         $lead->lead_phone = $validated['phone']; 
    //     }
    //     if (array_key_exists('email', $validated)) {
    //         $lead->lead_email = $validated['email']; 
    //     }
    //     if (array_key_exists('referredBy', $validated)) {
    //         $lead->referred_by = $validated['referredBy']; 
    //     }
    //     if (array_key_exists('referralMethod', $validated)) {
    //         $lead->lead_notes = $validated['referralMethod']; 
    //     }
    //     if (array_key_exists('referralContact', $validated)) {
    //         $lead->referral_contact = $validated['referralContact']; 
    //     }
    //     if (array_key_exists('leadSource', $validated)) {
    //         $lead->lead_sourse = $validated['leadSource']; 
    //     }
    //     if (array_key_exists('ceoApproval', $validated)) {
    //         $lead->ceo_approval = $validated['ceoApproval']; 
    //     }
    //     if (array_key_exists('company', $validated)) {
    //         $lead->company = $validated['company']; 
    //     }
    //     if (array_key_exists('status', $validated)) {
    //         $lead->lead_status = $validated['status']; 
    //     }
    //     // dd($validated);
    //     $lead->save();

    //     // Handle file attachments if present
    //     $attachments = [];

    //     if ($request->hasFile('attachments')) {
    //         foreach ($request->file('attachments') as $file) {
    //             $path = $file->store('lead_attachments', 'public');            

    //             $attachment = new LeadAttachment();
    //             $attachment->lead_id = $lead->id;
    //             $attachment->original_name = $file->getClientOriginalName();
    //             $attachment->path = $path;
    //             $attachment->mime_type = $file->getMimeType();
    //             $attachment->size = $file->getSize();
    //             $attachment->save();

    //             $attachments[] = [
    //                 'id' => $attachment->id,
    //                 'original_name' => $attachment->original_name,
    //                 'path' => $attachment->path,
    //                 'url' => Storage::url($path),
    //                 'mime_type' => $attachment->mime_type,
    //                 'size' => $attachment->size,
    //                 'created_at' => $attachment->created_at,
    //             ];
    //         }
    //     }
    
    //     return response()->json([
    //         'success' => true,
    //         'lead' => $lead,
    //         'attachments' => $attachments,
    //     ]);
    // }
    // public function updateLead(Request $request, Lead $lead)
    // {
    //     // Validate from the "lead" array in the payload
    //     $input = $request->input('lead', []);

    //     $validated = Validator::make($input, [
    //         'name' => 'sometimes|string|max:255',
    //         'phone' => 'sometimes|string|max:255',
    //         'email' => 'sometimes|email',
    //         'referredBy' => 'sometimes|string|max:255',
    //         'referralMethod' => 'sometimes|string|in:email,phone',
    //         'referralContact' => 'sometimes|string|max:255',
    //         'leadSource' => 'sometimes|string|max:255',
    //         'ceoApproval' => 'sometimes|string|max:255',
    //         'company' => 'sometimes|string|max:255',
    //         'status' => 'sometimes|string|in:warm,hot,cold,lost,client',
    //     ])->validate();

    //     // Update lead attributes
    //     if (array_key_exists('name', $validated)) {
    //         $lead->lead_name = $validated['name']; 
    //     }
    //     if (array_key_exists('phone', $validated)) {
    //         $lead->lead_phone = $validated['phone']; 
    //     }
    //     if (array_key_exists('email', $validated)) {
    //         $lead->lead_email = $validated['email']; 
    //     }
    //     if (array_key_exists('referredBy', $validated)) {
    //         $lead->referred_by = $validated['referredBy']; 
    //     }
    //     if (array_key_exists('referralMethod', $validated)) {
    //         $lead->lead_notes = $validated['referralMethod']; 
    //     }
    //     if (array_key_exists('referralContact', $validated)) {
    //         $lead->referral_contact = $validated['referralContact']; 
    //     }
    //     if (array_key_exists('leadSource', $validated)) {
    //         $lead->lead_sourse = $validated['leadSource']; 
    //     }
    //     if (array_key_exists('ceoApproval', $validated)) {
    //         $lead->ceo_approval = $validated['ceoApproval']; 
    //     }
    //     if (array_key_exists('company', $validated)) {
    //         $lead->company = $validated['company']; 
    //     }
    //     if (array_key_exists('status', $validated)) {
    //         $lead->lead_status = $validated['status']; 
    //     }
    //     $lead->save();

    //     // Handle file attachments if present
    //     $attachments = [];

    //     if ($request->hasFile('lead.attachments')) {
    //         foreach ($request->file('lead.attachments') as $file) {
    //             $path = $file->store('lead_attachments', 'public');            

    //             $attachment = new LeadAttachment();
    //             $attachment->lead_id = $lead->id;
    //             $attachment->original_name = $file->getClientOriginalName();
    //             $attachment->path = $path;
    //             $attachment->mime_type = $file->getMimeType();
    //             $attachment->size = $file->getSize();
    //             $attachment->save();

    //             $attachments[] = [
    //                 'id' => $attachment->id,
    //                 'original_name' => $attachment->original_name,
    //                 'path' => $attachment->path,
    //                 'url' => Storage::url($path),
    //                 'mime_type' => $attachment->mime_type,
    //                 'size' => $attachment->size,
    //                 'created_at' => $attachment->created_at,
    //             ];
    //         }
    //     }
    
    //     return response()->json([
    //         'success' => true,
    //         'lead' => $lead->fresh(), // refresh from DB to reflect all attributes
    //         'attachments' => $attachments,
    //     ]);
    // }
// public function updateLead(Request $request, Lead $lead)
// {
//     // Validate directly from $request
//     $validated = $request->validate([
//         'lead' => ['sometimes' => 'array'],
//         'lead.name' => 'sometimes|string|max:255',
//         'lead.phone' => 'sometimes|string|max:255',
//         'lead.email' => 'sometimes|email',
//         'lead.referredBy' => 'sometimes|string|max:255',
//         'lead.referralMethod' => 'sometimes|string|in:email,phone',
//         'lead.referralContact' => 'sometimes|string|max:255',
//         'lead.leadSource' => 'sometimes|nullable|string|max:255',
//         'lead.ceoApproval' => 'sometimes|string|max:255',
//         'lead.company' => 'sometimes|string|max:255',
//         'lead.status' => 'sometimes|string|in:warm,hot,cold,lost,client',
//     ]);

//     // Update attributes
//     $leadData = $validated['lead'] ?? [];

//     foreach ($leadData as $field => $value) {
//         match ($field) {
//             'name' => $lead->lead_name = $value,
//             'phone' => $lead->lead_phone = $value,
//             'email' => $lead->lead_email = $value,
//             'referredBy' => $lead->referred_by = $value,
//             'referralMethod' => $lead->lead_notes = $value,
//             'referralContact' => $lead->referral_contact = $value,
//             'leadSource' => $lead->lead_sourse = $value,
//             'ceoApproval' => $lead->ceo_approval = $value,
//             'company' => $lead->company = $value,
//             'status' => $lead->lead_status = $value,
//             default => null,
//         };
//     }
//     $lead->save();

//     // Handle files if present
//     $attachments = [];

//     if ($request->hasFile('lead.attachments')) {
//         foreach ($request->file('lead.attachments') as $file) {
//             $path = $file->store('lead_attachments', 'public');            

//             $attachment = new LeadAttachment();
//             $attachment->lead_id = $lead->id;
//             $attachment->original_name = $file->getClientOriginalName();
//             $attachment->path = $path;
//             $attachment->mime_type = $file->getMimeType();
//             $attachment->size = $file->getSize();
//             $attachment->save();

//             $attachments[] = [
//                 'id' => $attachment->id,
//                 'original_name' => $attachment->original_name,
//                 'path' => $attachment->path,
//                 'url' => Storage::url($path),
//                 'mime_type' => $attachment->mime_type,
//                 'size' => $attachment->size,
//                 'created_at' => $attachment->created_at,
//             ];
//         }
//     }
  
//     return response()->json([
//         'success' => true,
//         'lead' => $lead->fresh(), // refresh from DB to reflect all attributes
//         'attachments' => $attachments,
//     ]);
// }
    public function updateLead(Request $request, Lead $lead)
        {
            // Validate directly from $request
            $validated = $request->validate([
                'lead' => ['sometimes' => 'array'],
                'lead.name' => 'sometimes|string|max:255',
                'lead.phone' => 'sometimes|string|max:255',
                'lead.email' => 'sometimes|email',
                'lead.referredBy' => 'sometimes|string|max:255',
                'lead.referralMethod' => 'sometimes|string|in:email,phone',
                'lead.referralContact' => 'sometimes|string|max:255',
                'lead.leadSource' => 'sometimes|nullable|string|max:255',
                'lead.ceoApproval' => 'sometimes|string|max:255',
                'lead.company' => 'sometimes|string|max:255',
                'lead.status' => 'sometimes|string|in:warm,hot,cold,lost,client',
                'lead.attachmentsToDelete' => 'sometimes|array',
                'lead.attachmentsToDelete.*' => 'integer|exists:lead_attachments,id',
                'lead.attachments' => 'sometimes|array',
                'lead.attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx',
            ]);

            // Update attributes first
            $leadData = $validated['lead'] ?? [];

            foreach ($leadData as $field => $value) {
                match ($field) {
                    'name' => $lead->lead_name = $value,
                    'phone' => $lead->lead_phone = $value,
                    'email' => $lead->lead_email = $value,
                    'referredBy' => $lead->referred_by = $value,
                    'referralMethod' => $lead->lead_notes = $value,
                    'referralContact' => $lead->referral_contact = $value,
                    'leadSource' => $lead->lead_sourse = $value,
                    'ceoApproval' => $lead->ceo_approval = $value,
                    'company' => $lead->company = $value,
                    'status' => $lead->lead_status = $value,
                    default => null,
                };
            }
            $lead->save();

            // Handle deletions first
            if (!empty($leadData['attachmentsToDelete'])) {
                $idsToDelete = $leadData['attachmentsToDelete'];

                $attachments = LeadAttachment::whereIn('id', $idsToDelete)
                                            ->where('lead_id', $lead->id)
                                            ->get();

                foreach ($attachments as $attachment) {
                    Storage::delete($attachment->path);
                    $attachment->delete();
                }
            }

            // Handle files if present
            $attachments = [];

            if ($request->hasFile('lead.attachments')) {
                foreach ($request->file('lead.attachments') as $file) {
                    $path = $file->store('lead_attachments', 'public');            

                    $attachment = new LeadAttachment();
                    $attachment->lead_id = $lead->id;
                    $attachment->original_name = $file->getClientOriginalName();
                    $attachment->path = $path;
                    $attachment->mime_type = $file->getMimeType();
                    $attachment->size = $file->getSize();
                    $attachment->save();

                    $attachments[] = [
                        'id' => $attachment->id,
                        'original_name' => $attachment->original_name,
                        'path' => $attachment->path,
                        'url' => Storage::url($path),
                        'mime_type' => $attachment->mime_type,
                        'size' => $attachment->size,
                        'created_at' => $attachment->created_at,
                    ];
                }
            }
        
            return response()->json([
                'success' => true,
                'lead' => $lead->fresh(), // refresh from DB
                'attachments' => $attachments,
            ]);
        }



    


   

    // public function list()
    // {
    //     $leads = Lead::orderBy('created_at', 'desc')->get();

    //     $formattedLeads = $leads->map(function ($lead) {
    //         $created = Carbon::parse($lead->created_at);

    //         if (!empty($lead->lead_status)) {
    //         $status = strtolower($lead->lead_status); // preserve original
    //         } else {
    //             if ($created->isToday()) {
    //                 $status = 'hot';
    //             } elseif ($created->gt(now()->subWeek())) {
    //                 $status = 'warm';
    //             } elseif ($created->gt(now()->subWeeks(2))) {
    //                 $status = 'cold';
    //             } else {
    //                 $status = 'lost';
    //             }
    //         }
    //          $details = LeadDetail::where('lead_id', $lead->id)
    //         ->select('id', 'task_title', 'due_date', 'notes', 'type')
    //         ->get()
    //         ->map(function ($detail) use ($lead) {
    //             $detail->lead_id = $lead->id;
    //             return $detail;
    //         });

    //         return [
    //             'id' => $lead->id,
    //             'name' => $lead->lead_name,
    //             'email' => $lead->lead_email,
    //             'phone' => $lead->lead_phone,
    //             'company' => $lead->company ?? '',
    //             'status' => $status,
    //             'time' => $created->diffForHumans(),
    //             'leadSource' => $lead->lead_sourse,
    //             'details' => $details, 
    //         ];
    //     });

    //     return response()->json($formattedLeads);
    // }
   

    public function list()
    {
        $leads = Lead::orderBy('created_at', 'desc')->get();

        $formattedLeads = $leads->map(function ($lead) {
            $created = Carbon::parse($lead->created_at);

            // Status logic
            if (!empty($lead->lead_status)) {
                $status = strtolower($lead->lead_status);
            } else {
                if ($created->isToday()) {
                    $status = 'hot';
                } elseif ($created->gt(now()->subWeek())) {
                    $status = 'warm';
                } elseif ($created->gt(now()->subWeeks(2))) {
                    $status = 'cold';
                } else {
                    $status = 'lost';
                }
            }

            // Lead details
            $details = LeadDetail::where('lead_id', $lead->id)
                ->select('id', 'task_title', 'due_date', 'notes', 'type')
                ->get()
                ->map(function ($detail) use ($lead) {
                    $detail->lead_id = $lead->id;
                    return $detail;
                });

            // Lead attachments
            $attachments = LeadAttachment::where('lead_id', $lead->id)
            ->select('id', 'original_name', 'path', 'mime_type', 'size', 'created_at')
            ->get();

            return [
                'id' => $lead->id,
                'name' => $lead->lead_name,
                'email' => $lead->lead_email,
                'phone' => $lead->lead_phone,
                'company' => $lead->company ?? '',
                'status' => $status,
                'time' => $created->diffForHumans(),
                'leadSource' => $lead->lead_sourse,
                'details' => $details,
                'attachments' => $attachments, // ✅ added
            ];
        });

        return response()->json($formattedLeads);
    }

    public function addTask(Request $request, Lead $lead)
    {
        $request->validate([
            'task_title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $task = LeadDetail::create([
            'lead_id' => $lead->id,
            'task_title' => $request->task_title,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
            'action'=>'started',
        ]);

        return response()->json($task);
    }
    public function updateLeadStatus(Request $request, $leadId)
            {
            try {
                $validated = $request->validate([
                    'status' => 'required|string|in:warm,hot,cold,lost,client',
                ]);

                $lead = Lead::find($leadId);
                if (!$lead) {
                    return response()->json(['error' => 'Lead not found'], 404);
                }

                $lead->lead_status = $validated['status'];
                $saved = $lead->save();

                if (!$saved) {
                    return response()->json(['error' => 'Failed to save lead'], 500);
                }

                return response()->json([
                    'message' => 'Status updated successfully',
                    'lead' => $lead,
                ]);

            } catch (\Throwable $e) {
                return response()->json([
                    'error' => 'Server error',
                    'exception' => $e->getMessage(),
                ], 500);
            }
        
    }
    public function activities(Request $request)
    {
            $validated = $request->validate([
            'action' => 'required|string|in:call,email,sms,whatsapp',
            'leadId' => 'required|exists:leads,id',
            'timestamp' => 'required|date',
        ]);

        // Format the action title
        $actionText = match ($validated['action']) {
            'call' => 'Call performed',
            'email' => 'Email sent',
            'sms' => 'SMS sent',
            'whatsapp' => 'WhatsApp message sent',
            default => 'Activity recorded',
        };

        $detail = LeadDetail::create([
            'lead_id' => $validated['leadId'],
            'task_title' => $actionText,
            'due_date' => $validated['timestamp'],
            'type' => 'tab_actions',
        ]);

        return response()->json(['message' => 'Activity recorded', 'detail' => $detail], 201);

       
    }
    public function destroy(Lead $lead)
    {
        try {
            $lead->delete();
            return response()->json(['message' => 'Lead deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete lead.', 'error' => $e->getMessage()], 500);
        }
    }
    
    
    public function task_store(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'task_title' => 'required|string',
        ]);

        // Find the detail record by lead_id, type, and task_title
        $detail = LeadDetail::where('lead_id', $lead->id)
            ->where('type', $validated['type'])
            ->where('task_title', $validated['task_title'])
            ->first();

        if (!$detail) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $detail->action = 'completed';
        $detail->save();

        return response()->json(['message' => 'Task action updated successfully']);
    }




    //
}
