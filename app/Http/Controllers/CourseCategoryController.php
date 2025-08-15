<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    public function index()
    {
        $course_categories = CourseCategory::orderByDesc('name')->paginate(15);
        return view('course-categories.index', compact('course_categories'));
    }

    public function show(CourseCategory $course_category)
    {
        return view('course-categories.show', compact(['course_category']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:course_categories,name',
            'short_name' => 'required|string|max:50|unique:course_categories,short_name',
            'category_alias' => 'nullable|string',
            'ue' => 'required|integer'
        ]);

        CourseCategory::create($validated);
        return redirect()->back()->with('success', 'Catégorie de cours créée avec succès.');
    }

    public function update(Request $request, CourseCategory $course_category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:course_categories,name,' . $course_category->id,
            'short_name' => 'required|string|max:255|unique:course_categories,short_name,' . $course_category->id,
            'category_alias' => 'nullable|string',
            'ue' => 'required|integer',
        ]);

        $course_category->update($validated);

        return redirect()->back()->with('success', 'Catégorie de cours mise à jour avec succès.');
    }

    public function destroy(CourseCategory $course_category)
    {
        $course_category->delete();
        return redirect()->back()->with('success', 'Catégorie de cours supprimée.');
    }
}
