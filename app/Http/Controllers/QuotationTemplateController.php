<?php
// app/Http/Controllers/QuotationTemplateController.php
namespace App\Http\Controllers;

use App\Models\QuotationTemplate;
use Illuminate\Http\Request;

class QuotationTemplateController extends Controller
{
    // List all templates
    public function index()
    {
        $templates = QuotationTemplate::get();
            
        return response()->json($templates);
    }

    // Create new template
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'front_pages' => 'required|json',
            'back_pages' => 'required|json',
            'is_default' => 'sometimes|boolean'
        ]);

        $template = QuotationTemplate::create([
            ...$validated
            
        ]);

        return response()->json($template, 201);
    }

    // Get single template
    public function show(QuotationTemplate $template)
    {
        // Authorization check
        if ($template->user_id !== auth()->id() && !$template->is_default) {
            abort(403, 'Unauthorized');
        }

        return response()->json($template);
    }

    // Update template
    public function update(Request $request, QuotationTemplate $template)
    {
        // Authorization check
        if ($template->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'front_pages' => 'sometimes|json',
            'back_pages' => 'sometimes|json',
            'is_default' => 'sometimes|boolean'
        ]);

        $template->update($validated);

        return response()->json($template);
    }

    // Delete template
    public function destroy(QuotationTemplate $template)
    {
        // Authorization check
        if ($template->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $template->delete();

        return response()->json(null, 204);
    }
}
