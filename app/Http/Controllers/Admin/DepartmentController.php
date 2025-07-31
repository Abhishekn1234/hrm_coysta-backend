<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    // GET all departments
    public function index()
    {
        return response()->json(Department::all());
    }

    // POST create department
    public function store(Request $request)
    {
        $validated = $request->validate([
            'departmentName' => 'required|string|max:255',
            'description' => 'nullable|string',
            'departmentHead' => 'required|string|max:255',
        ]);

        $department = Department::create([
            'department_name' => $validated['departmentName'],
            'description' => $validated['description'],
            'department_head' => $validated['departmentHead'],
        ]);

        return response()->json([
            'message' => 'Department created successfully',
            'department' => $department
        ]);
    }

    // GET department by ID
    public function show($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        return response()->json($department);
    }

    // PATCH/PUT update department
    public function update(Request $request, $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        $validated = $request->validate([
            'departmentName' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'departmentHead' => 'sometimes|required|string|max:255',
        ]);

        $department->update([
            'department_name' => $validated['departmentName'] ?? $department->department_name,
            'description' => $validated['description'] ?? $department->description,
            'department_head' => $validated['departmentHead'] ?? $department->department_head,
        ]);

        return response()->json([
            'message' => 'Department updated successfully',
            'department' => $department
        ]);
    }

    // DELETE department
    public function destroy($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        $department->delete();

        return response()->json(['message' => 'Department deleted successfully']);
    }
}
