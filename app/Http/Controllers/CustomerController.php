<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Str;
class CustomerController extends Controller
{
    public function storeInvoice(Request $request, $customerId)
{
    $validated = $request->validate([
        'issue_date' => 'required|date',
        'due_date' => 'required|date|after_or_equal:issue_date',
        'amount' => 'required|numeric|min:0',
        'status' => 'required|string'
    ]);

    $invoice = Invoice::create([
        'customer_id' => $customerId,
        'invoice_id' => 'INV-' . strtoupper(Str::random(6)),
        'issue_date' => $validated['issue_date'],
        'due_date' => $validated['due_date'],
        'amount' => $validated['amount'],
        'status' => $validated['status'],
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Invoice created successfully',
        'data' => $invoice
    ], 201);
}

public function getInvoices($customerId)
{
    $invoices = Invoice::where('customer_id', $customerId)->get();

    return response()->json([
        'success' => true,
        'data' => $invoices
    ]);
}
    // ✅ GET: List all customers
    public function index()
{
    $customers = Customer::with([
        'gstDetails',
        'shippingAddresses',
        'contactPersons',
        'projects',
        'invoices','estimates'
    ])->get();

    return response()->json($customers);
}

    // ✅ POST: Store new customer with related data
        public function store(Request $request)
        {
            Log::info('🚀 CustomerController@store called', ['data' => $request->all()]);
            info('📥 Store endpoint reached');

            $data = $request->validate([
                'profile_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',

                'customer_type' => 'required|string',
                'company_name' => 'nullable|string',
                'display_name' => 'required|string',
                'owner_name' => 'nullable|string',
                'primary_contact_name' => 'required|string',
                'primary_contact_phone' => 'required|string',
                'email' => 'nullable|email',
                'pan_no' => 'nullable|string',
                'organization' => 'nullable|string',
                'login_enabled' => 'nullable|boolean',

                'gst_details' => 'array',
                'gst_details.*.gst_number' => 'nullable|string',
                'gst_details.*.registered_address' => 'nullable|string',
                'gst_details.*.place_of_supply' => 'nullable|string',

                'shipping_addresses' => 'array',
                'shipping_addresses.*.address' => 'nullable|string',
                'shipping_addresses.*.city' => 'nullable|string',
                'shipping_addresses.*.state' => 'nullable|string',
                'shipping_addresses.*.pincode' => 'nullable|string',

                'contact_persons' => 'array',
                'contact_persons.*.contact_name' => 'nullable|string',
                'contact_persons.*.designation' => 'nullable|string',
                'contact_persons.*.work_email' => 'nullable|email',
                'contact_persons.*.work_phone' => 'nullable|string',
                'contact_persons.*.personal_email' => 'nullable|email',
                'contact_persons.*.personal_phone' => 'nullable|string',
            ]);

            // ✅ Log validated data
            Log::info('✅ Validated data:', $data);

            // Handle file upload
            if ($request->hasFile('profile_logo')) {
                $path = $request->file('profile_logo')->store('logos', 'public');
                $data['profile_logo'] = $path;
                Log::info('🖼️ Profile logo stored at:', ['path' => $path]);
            }

            // Create customer
            $customer = Customer::create($data);
            Log::info('✅ Customer saved to DB:', ['customer' => $customer]);

            // Create related data
            if (!empty($data['gst_details'])) {
                $customer->gstDetails()->createMany($data['gst_details']);
                Log::info('🧾 GST Details saved:', $data['gst_details']);
            }

            if (!empty($data['shipping_addresses'])) {
                $customer->shippingAddresses()->createMany($data['shipping_addresses']);
                Log::info('📦 Shipping addresses saved:', $data['shipping_addresses']);
            }

            if (!empty($data['contact_persons'])) {
                $customer->contactPersons()->createMany($data['contact_persons']);
                Log::info('👥 Contact persons saved:', $data['contact_persons']);
            }

            return response()->json([
                'message' => 'Customer created successfully',
                'customer' => $customer
            ]);
        }



    // ✅ GET: Show customer by ID
    public function show(string $id)
{
    $customer = Customer::with([
        'gstDetails',
        'shippingAddresses',
        'contactPersons',
        'projects',
        'invoices',
        'estimates' // 👈 add this line
    ])->findOrFail($id);

    return response()->json($customer);
}


    // ✅ PUT: Update customer
    public function update(Request $request, string $id)
{
    $customer = Customer::findOrFail($id);

    $data = $request->validate([
        'profile_logo' => 'nullable|string',
        'customer_type' => 'required|string',
        'company_name' => 'nullable|string',
        'display_name' => 'required|string',
        'owner_name' => 'nullable|string',
        'primary_contact_name' => 'required|string',
        'primary_contact_phone' => 'required|string',
        'email' => 'nullable|email',
        'pan_no' => 'nullable|string',
        'username'=>'nullable',
        'password'=>'nullable',
        'organization'=>'nullable'
    ]);

    $customer->update($data);

    // ✅ Update GST Details if sent
    if ($request->has('gst_details')) {
        // assuming one-to-many relation: customer->gstDetails()
        $customer->gstDetails()->delete();
        foreach ($request->gst_details as $gst) {
            $customer->gstDetails()->create([
                'gst_number' => $gst['gst_number'],
                'place_of_supply' => $gst['place_of_supply'],
            ]);
        }
    }

    // ✅ Update Shipping Addresses if sent
    if ($request->has('shipping_addresses')) {
        $customer->shippingAddresses()->delete();
        foreach ($request->shipping_addresses as $addr) {
            $customer->shippingAddresses()->create([
                'address' => $addr['address'],
                'city' => $addr['city'],
                'state' => $addr['state'],
                'pincode' => $addr['pincode'],
            ]);
        }
    }

    // ✅ Update Contact Persons if sent
    if ($request->has('contact_persons')) {
        $customer->contactPersons()->delete();
        foreach ($request->contact_persons as $person) {
            $customer->contactPersons()->create([
                'contact_name' => $person['contact_name'],
                'designation' => $person['designation'],
                'work_email' => $person['work_email'],
                'work_phone' => $person['work_phone'],
            ]);
        }
    }

    return response()->json(['message' => 'Customer updated successfully', 'customer' => $customer]);
}


    // ✅ DELETE: Delete customer
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }

    // ✅ GET: Count of all customers
   

    // ✅ GET: Count of customers per month (last 12 months)
    public function countPerMonth()
    {
        $monthlyCounts = Customer::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month', 'DESC')
            ->take(12)
            ->get();

        return response()->json($monthlyCounts);
    }

    // ✅ GET: Count of customers involved in business
  
    public function count()
{
    return response()->json(['total' => Customer::count()]);
}

public function countBusinessCustomers()
{
    return response()->json(['business' => Customer::where('customer_type', 'Business')->count()]);
}

public function countIndividualCustomers()
{
    return response()->json(['individual' => Customer::where('customer_type', 'Individual')->count()]);
}

}