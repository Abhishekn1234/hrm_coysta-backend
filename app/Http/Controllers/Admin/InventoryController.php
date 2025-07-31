<?php

namespace App\Http\Controllers\Admin;

use App\Model\Inventory;
use App\Models\Lead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CPU\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;





class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }

        $search = $request['search'];
        $query_param = ['search' => $request['search']];

        if ($request->has('search')) {
            $inventories = Inventory::where(function ($q) use ($search) {
                $q->Where('item_name', 'like', "%{$search}%");
            });
        } else {
            $inventories = Inventory::where('item_name', 'like', "%%");
        }

        $counts = $inventories;
        $inventories = $inventories->orderBy('id', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.inventory.view', compact('inventories', 'search', 'query_param', 'counts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // Unique ID for this whole inventory batch
        $customerId = $request->customerId;
        $baseInventoryId = (string) Str::uuid();

        $productGroupId = 1; // To increment per main item in categories

        foreach ($request->categories as $category) {
            foreach ($category['items'] as $mainItem) {
                // Insert main item (is_parent_sub = false, parent_sub_id = null)
                $mainInventory = new Inventory();
                $mainInventory->base_inventory_id = (string) Str::uuid();
                $mainInventory->product_group_id = $productGroupId;
                $mainInventory->parent_sub_id = null;
                $mainInventory->is_parent_sub = false;

                // Map fields from mainItem
                $mainInventory->item_name = $mainItem['name'] ?? null;
                $mainInventory->hsn_code = $mainItem['sku'] ?? null;
                $mainInventory->stock_category = $category['name'] ?? null;
                $mainInventory->unit = $mainItem['unit'] ?? null;
                $mainInventory->worth = $mainItem['worth'] ?? 0;
                $mainInventory->stock_count = $mainItem['stock'] ?? 0;
                $mainInventory->is_parent_sub = 1;
                // Add other nullable fields here as needed, e.g.:
                // $mainInventory->vendor = $mainItem['vendor'] ?? null;
                $mainInventory->created_at = Carbon::now();
                $mainInventory->updated_at = Carbon::now();
                $mainInventory->client_id = $customerId;

                $mainInventory->save();

                // Recursively save subItems
                $this->saveSubItems($mainItem['subItems'], $baseInventoryId, $productGroupId, $mainInventory->id, $category['name']);

                $productGroupId++;
            }
        }

        // Optionally, return response or redirect
        return response()->json(['message' => 'Inventory saved successfully']);
    }

    private function saveSubItems(array $subItems, string $baseInventoryId, int $productGroupId, int $parentId, string $categoryName)
    {
        foreach ($subItems as $subItem) {
            $inventory = new Inventory();
            $inventory->base_inventory_id = $baseInventoryId;
            $inventory->product_group_id = $productGroupId;
            $inventory->parent_sub_id = $parentId;
            

            $inventory->item_name = $subItem['name'] ?? null;
            $inventory->hsn_code = $subItem['sku'] ?? null;
            $inventory->stock_category = $categoryName;
            $inventory->unit = $subItem['unit'] ?? null;
            $inventory->worth = $subItem['worth'] ?? 0;
            $inventory->stock_count = $subItem['stock'] ?? 0;
            $inventory->created_at = Carbon::now();
            $inventory->updated_at = Carbon::now();

            $inventory->save();

            // Recursive call for any sub-subItems
            if (!empty($subItem['subItems'])) {
                $this->saveSubItems($subItem['subItems'], $baseInventoryId, $productGroupId, $inventory->id, true, $categoryName);
            }
        }
    }

    public function save(Request $request)
    {
        $item_names = $request->name;
        $hsn_codes = $request->hsn;
        $stock_categories = $request->stock_category;
        $units = $request->unit;
        $worths = $request->worth;
        $vendors = $request->vendor;

        $inventory = new Inventory();
        $inventory->item_name = $item_names;
        $inventory->hsn_code = $hsn_codes;
        $inventory->stock_category = $stock_categories;
        $inventory->unit = $units;
        $inventory->worth = $worths ?? 0.00;
        $inventory->vendor = $vendors ?? null;
        $inventory->save();

        
        // Toastr::success('inventory added successfully!');
        // return response()->json(['message' => 'Inventories Saved successfully'],$inventory);
        return response()->json($inventory); // Defaults to 200

    }
    // function new_list() {
    // $inventory = new Inventory();
    // $allData = $inventory->all();

    // return response()->json([
    //     'status' => 'success',
    //     'data' => $allData,
    // ], 200); 
    // }

    function new_list() {
            $inventory = new Inventory();
            $allData = $inventory->all()->toArray(); // get all items as array

            // Group by stock_category (or fallback)
            $categories = [];

            // Index all items by id for quick lookup
            $itemsById = [];
            foreach ($allData as $item) {
                $itemsById[$item['id']] = $item;
            }

            // Helper function to recursively get subItems
            function getSubItems($parentId, $allData) {
                $subs = [];
                foreach ($allData as $item) {
                    if ($item['parent_sub_id'] === $parentId) {
                        $subs[] = [
                            'id' => $item['id'],
                            'name' => $item['item_name'],
                            'sku' => $item['hsn_code'] ?? '',
                            'hsn_code' => $item['hsn_code'] ?? '',
                            'category' => $item['stock_category'],
                            'stock' => $item['stock_count'],
                            'minStock' => 5, // default or customize
                            'unit' => $item['unit'],
                            'worth' => floatval($item['worth']),
                            'status' => 'Active',
                            'subItems' => getSubItems($item['id'], $allData) // recursive
                            ];
                        }
                    }
                    return $subs;
                }

                // First, gather main items grouped by category
                foreach ($allData as $item) {
                    $categoryName = $item['stock_category'] ?: 'Default Category';

                    // Only top-level items (parent_sub_id === null)
                    if ($item['parent_sub_id'] === null) {
                        if (!isset($categories[$categoryName])) {
                            $categories[$categoryName] = [];
                        }
                        $categories[$categoryName][] = [
                            'id' => $item['id'],
                            'name' => $item['item_name'],
                            'sku' => $item['hsn_code'] ?? '',
                            'hsn_code' => $item['hsn_code'] ?? '',
                            'category' => $categoryName,
                            'stock' => $item['stock_count'],
                            'minStock' => 5,
                            'unit' => $item['unit'],
                            'worth' => floatval($item['worth']),
                            'status' => 'Active',
                            'subItems' => getSubItems($item['id'], $allData)
                        ];
                    }
                }

                // Format categories array for output
                $formattedCategories = [];
                foreach ($categories as $categoryName => $items) {
                    $formattedCategories[] = [
                        'name' => $categoryName,
                        'items' => $items,
                    ];
                }

                return response()->json([
                    'status' => 'success',
                    'categories' => $formattedCategories,
                ], 200);
        //dd( $formattedCategories);
    }

    



    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {

        $inventory = Inventory::find($id);
        if (isset($inventory)) {
            // $reports_to = User::where(['status' => '1', 'id' => $staff->reports_to])->first();
            // if ($reports_to) {
            //     $staff->reports_to_name = $reports_to->name . ' ( ' . $reports_to->user_type . ' )';
            // } else {
            //     $staff->reports_to_name = "";
            // }

            return view('admin-views.inventory.show', compact('inventory'));
        }
        Toastr::error('inventory not found!');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inventory = Inventory::find($id);
        if (isset($inventory)) {
            return view('admin-views.inventory.edit', compact('inventory'));
        }
        Toastr::error('inventory not found!');
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }

        $inventory = Inventory::find($id);

        $item_names = $request->item_name;
        $hsn_codes = $request->hsn_code;
        $stock_categories = $request->stock_category;
        $units = $request->unit;
        $worths = $request->worth;
        $vendors = $request->vendor;
        $descriptions = $request->description;
        $model_nos = $request->model_no;
        $gm_codes = $request->gm_code;
        $brand_names = $request->brand_name;
        $purchase_prices = $request->purchase_price;
        $lengths = $request->length;
        $heights = $request->height;
        $widths = $request->width;
        $weights = $request->weight;
        $volumes = $request->volume;
        $currents = $request->current;
        $powers = $request->power;
        $rental_informations = $request->rental_information;


        // $inventory->base_inventory_id = 0;
        $inventory->item_name = $item_names[0];
        $inventory->hsn_code = $hsn_codes[0];
        $inventory->stock_category = $stock_categories[0];
        $inventory->unit = $units[0];
        $inventory->worth = $worths[0] ?? $inventory->worth;
        $inventory->vendor = $vendors[0] ?? $inventory->vendor;
        $inventory->description = $descriptions[0] ?? $inventory->description;
        $inventory->model_no = $model_nos[0] ?? $inventory->model_no;
        $inventory->gm_code = $gm_codes[0] ?? $inventory->gm_code;
        $inventory->brand_name = $brand_names[0] ?? $inventory->brand_name;
        $inventory->purchase_price = $purchase_prices[0] ?? $inventory->purchase_price;
        $inventory->length = $lengths[0] ?? $inventory->length;
        $inventory->height = $heights[0] ?? $inventory->height;
        $inventory->width = $widths[0] ?? $inventory->width;
        $inventory->weight = $weights[0] ?? $inventory->weight;
        $inventory->volume = $volumes[0] ?? $inventory->volume;
        $inventory->current = $currents[0] ?? $inventory->current;
        $inventory->power = $powers[0] ?? $inventory->power;
        $inventory->rental_information = $rental_informations[0] ?? $inventory->rental_information;
        $inventory->save();

        // $base_inventory_ids[1] = $inventory->id;

        if (!is_null($item_names[1] ?? null)) {
            foreach ($item_names as $i => $item_name) {
                if ($i === 0) continue;
                $sub_inventory = new Inventory();
                $sub_inventory->base_inventory_id = $inventory->id;
                $sub_inventory->item_name = $item_names[$i];
                $sub_inventory->hsn_code = $hsn_codes[$i];
                $sub_inventory->stock_category = $stock_categories[$i];
                $sub_inventory->unit = $units[$i];
                $sub_inventory->worth = $worths[$i] ?? 0.00;
                $sub_inventory->vendor = $vendors[$i] ?? null;
                $sub_inventory->description = $descriptions[$i] ?? null;
                $sub_inventory->model_no = $model_nos[$i] ?? null;
                $sub_inventory->gm_code = $gm_codes[$i] ?? null;
                $sub_inventory->brand_name = $brand_names[$i] ?? null;
                $sub_inventory->purchase_price = $purchase_prices[$i] ?? 0.00;
                $sub_inventory->length = $lengths[$i] ?? 0.00;
                $sub_inventory->height = $heights[$i] ?? 0.00;
                $sub_inventory->width = $widths[$i] ?? 0.00;
                $sub_inventory->weight = $weights[$i] ?? 0.00;
                $sub_inventory->volume = $volumes[$i] ?? 0.00;
                $sub_inventory->current = $currents[$i] ?? 0.00;
                $sub_inventory->power = $powers[$i] ?? 0.00;
                $sub_inventory->rental_information = $rental_informations[$i] ?? 0.00;
                $sub_inventory->save();
            }
        }

        Toastr::success('inventory updated successfully!');
        return back();
    }
    public function updatenew(Request $request)
    {
        $customerId = $request->customerId;
        $categoryName = $request->name;

        $productGroupId = 1;

        foreach ($request->items as $mainItem) {
            // Check if main item exists
            $mainInventory = Inventory::find($mainItem['id']);

            if ($mainInventory) {
                // Update existing main item
                $mainInventory->item_name = $mainItem['name'] ?? $mainInventory->item_name;
                $mainInventory->hsn_code = $mainItem['sku'] ?? $mainInventory->hsn_code;
                $mainInventory->stock_category = $categoryName;
                $mainInventory->unit = $mainItem['unit'] ?? $mainInventory->unit;
                $mainInventory->worth = $mainItem['worth'] ?? $mainInventory->worth;
                $mainInventory->stock_count = $mainItem['stock'] ?? $mainInventory->stock_count;
                $mainInventory->client_id = $customerId;
                $mainInventory->updated_at = now();
                $mainInventory->save();
            } else {
                // If not exists, insert it (fallback)
                $mainInventory = new Inventory();
                $mainInventory->base_inventory_id = (string) Str::uuid();
                $mainInventory->product_group_id = $productGroupId;
                $mainInventory->parent_sub_id = null;
                $mainInventory->is_parent_sub = true;

                $mainInventory->item_name = $mainItem['name'] ?? null;
                $mainInventory->hsn_code = $mainItem['sku'] ?? null;
                $mainInventory->stock_category = $categoryName;
                $mainInventory->unit = $mainItem['unit'] ?? null;
                $mainInventory->worth = $mainItem['worth'] ?? 0;
                $mainInventory->stock_count = $mainItem['stock'] ?? 0;
                $mainInventory->client_id = $customerId;

                $mainInventory->created_at = now();
                $mainInventory->updated_at = now();
                $mainInventory->save();
            }

            // Recursively update subitems
            $this->updateSubItems($mainItem['subItems'], $mainInventory->id, $categoryName, $customerId);

            $productGroupId++;
        }

        return response()->json(['message' => 'Inventory updated successfully']);
    }
    private function updateSubItems($subItems, $parentId, $categoryName, $customerId)
    {
        foreach ($subItems as $subItem) {
            $existing = Inventory::find($subItem['id']);

            if ($existing) {
                $existing->item_name = $subItem['name'] ?? $existing->item_name;
                $existing->hsn_code = $subItem['sku'] ?? $existing->hsn_code;
                $existing->stock_category = $categoryName;
                $existing->unit = $subItem['unit'] ?? $existing->unit;
                $existing->worth = $subItem['worth'] ?? $existing->worth;
                $existing->stock_count = $subItem['stock'] ?? $existing->stock_count;
                $existing->updated_at = now();
                $existing->save();
            } else {
                $existing = new Inventory();
                $existing->base_inventory_id = (string) Str::uuid();
                $existing->product_group_id = null; // Subitems don’t need group id
                $existing->parent_sub_id = $parentId;
                $existing->is_parent_sub = false;

                $existing->item_name = $subItem['name'] ?? null;
                $existing->hsn_code = $subItem['sku'] ?? null;
                $existing->stock_category = $categoryName;
                $existing->unit = $subItem['unit'] ?? null;
                $existing->worth = $subItem['worth'] ?? 0;
                $existing->stock_count = $subItem['stock'] ?? 0;
                $existing->client_id = $customerId;

                $existing->created_at = now();
                $existing->updated_at = now();
                $existing->save();
            }

            // Recursive for sub-sub-items
            if (!empty($subItem['subItems'])) {
                $this->updateSubItems($subItem['subItems'], $existing->id, $categoryName, $customerId);
            }
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $inventory = Inventory::find($request->id);
        $inventory->delete();
        return response()->json();
    }
//    public function customer_inventories()
//     {
//         // Fetch all leads
//         $leads = Lead::all();

//         // Fetch all inventories
//         $inventories = Inventory::all();

//         // Group inventories by client_id

//         //dd($inventories);
//         $grouped = $inventories->groupBy('client_id');

//         // Build response structure
//         $customers = $leads->map(function ($lead) use ($grouped) {
//             $inventoryList = $grouped->get($lead->id, collect());

//             // Get parent inventories (no parent_sub_id)
//             $parents = $inventoryList->whereNull('parent_sub_id')->values();

//             // Recursively attach subitems
//             $structuredInventory = $parents->map(function ($item) use ($inventoryList) {
//                 return $this->buildInventoryTree($item, $inventoryList);
//             });

//             return [
//                 'id' => $lead->id,
//                 'name' => $lead->company ?? $lead->lead_name,
//                 'contactPerson' => $lead->lead_name,
//                 'email' => $lead->lead_email,
//                 'phone' => $lead->lead_phone,
//                 'type' => $lead->lead_status ?? 'New',
//                 'location' => 'N/A', // Adjust if you have address/location
//                 'projects' => [], // Fill from another table if available
//                 'inventory' => $structuredInventory,
//             ];
//         });

//         return response()->json($customers);
//     }
    // public function customer_inventories()
    //     {
    //         $leads = Lead::all();
    //         $inventories = Inventory::all();
    //         $grouped = $inventories->groupBy('client_id');

    //         $customers = $leads->map(function ($lead) use ($grouped) {
    //             $inventoryList = $grouped->get($lead->id, collect());

    //             // Top-level items with no parent (categories)
    //             $parents = $inventoryList->whereNull('parent_sub_id')->values();

    //            // dump($parents);

    //             // Group those parents by their category name (e.g., item_name or a proper field)
    //            $groupedByCategory = $parents->groupBy('stock_category');

    //             $structuredInventory = $groupedByCategory->map(function ($items, $categoryName) use ($inventoryList) {
    //                 return [
    //                     'inventory name' => $categoryName, // This will now be the stock_category like "Marriage Set", "Displays"
    //                     'items' => $items->map(function ($item) use ($inventoryList) {
    //                         return $this->buildInventoryTree($item, $inventoryList);
    //                     })->values()
    //                 ];
    //             })->values();

    //             return [
    //                 'id' => $lead->id,
    //                 'name' => $lead->company ?? $lead->lead_name,
    //                 'contactPerson' => $lead->lead_name,
    //                 'email' => $lead->lead_email,
    //                 'phone' => $lead->lead_phone,
    //                 'type' => $lead->lead_status ?? 'New',
    //                 'location' => 'N/A',
    //                 'projects' => [],
    //                 'inventory' => $structuredInventory,
    //             ];
    //         });

    //         return response()->json($customers);
    //     }

        public function customer_inventories()
            {
                $leads = Lead::all();
                $inventories = Inventory::all();
                $grouped = $inventories->groupBy('client_id');

                $customers = $leads->map(function ($lead) use ($grouped) {
                    $inventoryList = $grouped->get($lead->id, collect());

                    // Top-level items with no parent
                    $parents = $inventoryList->whereNull('parent_sub_id')->values();

                    // 🔁 Group by stock_category (e.g., "marriage set")
                    $groupedByCategory = $parents->groupBy('stock_category');

                    // Create structured inventory
                    $structuredInventory = $groupedByCategory->map(function ($items, $categoryName) use ($inventoryList) {
                        return [
                            'inventory name' => $categoryName,
                            'items' => $items->map(function ($item) use ($inventoryList) {
                                return $this->buildInventoryTree($item, $inventoryList);
                            })->values()
                        ];
                    })->values();

                    return [
                        'id' => $lead->id,
                        'name' => $lead->company ?? $lead->lead_name,
                        'contactPerson' => $lead->lead_name,
                        'email' => $lead->lead_email,
                        'phone' => $lead->lead_phone,
                        'type' => $lead->lead_status ?? 'New',
                        'location' => 'N/A',
                        'projects' => [], // Optional
                        'inventory' => $structuredInventory,
                    ];
                });

                return response()->json($customers);
            }


    private function buildInventoryTree($item, $allItems)
    {
        $children = $allItems->where('parent_sub_id', $item->id)->values();

        return [
            'id' => $item->id,
            'name' => $item->item_name,
            'available' => (int) $item->stock_count,
            'price' => (float) $item->worth,
            'vendor' => $item->vendor ?? 'N/A',
            'days' => 7, // Hardcoded or calculate if needed
            'subItems' => $children->map(function ($child) use ($allItems) {
                return $this->buildInventoryTree($child, $allItems);
            })
        ];
    }
}
