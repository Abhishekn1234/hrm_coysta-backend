<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorNote;
use App\Models\VendorContactPerson;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\PurchaseOrder;
class VendorController extends Controller
{
    // List all vendors
 public function index()
{
    $vendors = Vendor::with(['contactPersons', 'purchaseOrders', 'bills','notes']) // Add bills
                     ->withCount('contactPersons')
                     ->get();

    return response()->json($vendors);
}

public function getBills(Vendor $vendor)
{
    return response()->json($vendor->bills()->with('purchaseOrder')->get());
}

public function addBill(Request $request, Vendor $vendor)
{
    $request->validate([
        'po_id' => 'required|exists:purchase_orders,id',
        'amount' => 'required|numeric',
        'date' => 'required|date',
        'status' => 'required|string|in:Paid,Due',
    ]);

    $bill = new Bill();
    $bill->vendor_id = $vendor->id;
    $bill->po_id = $request->po_id;
    $bill->amount = $request->amount;
    $bill->date = $request->date;
    $bill->status = $request->status;
    $bill->save();

    return response()->json(['message' => 'Bill created', 'bill' => $bill]);
}
// Get all POs for a vendor
public function getPurchaseOrders($vendorId)
{
    $orders = PurchaseOrder::where('vendor_id', $vendorId)->get();
    return response()->json($orders);
}

// Create new PO for a vendor
public function createPurchaseOrder(Request $request, $vendorId)
{
    $latest = PurchaseOrder::latest()->first();
    $nextId = $latest ? $latest->id + 1 : 1;
    $poId = 'PO-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

    $po = PurchaseOrder::create([
        'vendor_id' => $vendorId,
        'date' => $request->input('date', now()),
        'amount' => $request->input('amount'),
        'status' => $request->input('status', 'Ordered'),
    ]);

    return response()->json([
        'po_id' => $poId,
        'data' => $po
    ]);
}


    // Create vendor with contact persons
    public function store(Request $request)
{
    $vendor = Vendor::create($request->only([
        'salutation', 'name', 'phone', 'email', 'gst_no', 'type', 'material', 'address',
        'organization', 'login_enabled'  // Added the missing fields
    ]));

    if ($request->has('contact_persons')) {
        foreach ($request->contact_persons as $person) {
            $vendor->contactPersons()->create($person);
        }
    }

    return response()->json(['message' => 'Vendor created', 'vendor' => $vendor]);
}
    // Show single vendor
  public function show($id)
{
    $vendor = Vendor::with(['contactPersons', 'purchaseOrders', 'bills','notes']) // Add bills
                    ->findOrFail($id);

    return response()->json($vendor);
}



// VendorNoteController.php

public function getNotes($vendorId)
{
    return VendorNote::where('vendor_id', $vendorId)->latest()->get();
}

public function createNote(Request $request, $vendorId)
{
    $request->validate(['note' => 'required|string']);

    $note = VendorNote::create([
        'vendor_id' => $vendorId,
        'note' => $request->note
    ]);

    return response()->json($note, 201);
}

    // Update vendor and contact persons
    public function update(Request $request, $id)
{
    $vendor = Vendor::findOrFail($id);

    // Update vendor base fields
    $vendor->update($request->only([
        'salutation',
        'name',
        'phone',
        'email',
        'gst_no',
        'type',
        'material',
        'address',
        'login_enabled',
        'organization',
        'username',
        'password', // You may hash this if needed
    ]));

    // Update Contact Persons
    $vendor->contactPersons()->delete();

    if ($request->has('contact_persons') && is_array($request->contact_persons)) {
        foreach ($request->contact_persons as $person) {
            $vendor->contactPersons()->create([
                'name' => $person['name'] ?? '',
                'designation' => $person['designation'] ?? null,
                'work_email' => $person['email'] ?? null,
                'work_phone' => $person['phone'] ?? null,
            ]);
        }
    }

    return response()->json(['message' => 'Vendor updated successfully']);
}

    // Delete vendor
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return response()->json(['message' => 'Vendor deleted']);
    }

    // Count vendors
    public function count()
{
    $total = Vendor::count();
    $material = Vendor::where('type', 'Material')->count();
    $service = Vendor::where('type', 'Service')->count();

    return response()->json([
        'total' => $total,
        'material' => $material,
        'service' => $service,
    ]);
}

}