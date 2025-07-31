<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'short_name' => 'required|string|max:50|unique:departments,short_name',
            'faculty_id' => 'required|exists:faculties,id',
        ]);
        Department::create($validated);
        return redirect()->back()->with('success', 'Département créé avec succès.');
    }

    public function show(Department $department)
    {
        return view('departments.show', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'short_name' => 'required|string|max:50|unique:departments,short_name,' . $department->id,
            'faculty_id' => 'required|exists:faculties,id',
        ]);
        $department->update($validated);
        return redirect()->back()->with('success', 'Département modifié avec succès.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->back()->with('success', 'Département supprimé avec succès.');
    }
}
