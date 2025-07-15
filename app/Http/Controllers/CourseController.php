<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::orderByDesc('name')->paginate(15);
        return view('courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:courses,name',
        ]);
        Course::create($validated);
        return redirect()->route('courses.index')->with('success', 'Cours créé avec succès.');
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:courses,name,' . $course->id,
        ]);
        $course->update($validated);
        return redirect()->route('courses.index')->with('success', 'Cours modifié avec succès.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Cours supprimé.');
    }
    public function assignPromotion(Request $request, Course $course)
    {
        $validated = $request->validate([
            'promotion_id' => 'required|exists:promotions,id',
            'maxima' => 'required|numeric|min:0|max:100',
        ]);
        $course->promotions()->syncWithoutDetaching([
            $validated['promotion_id'] => ['maxima' => $validated['maxima']]
        ]);
        return redirect()->back()->with('success', 'Promotion assignée au cours avec succès.');
    }
    public function updateMaxima(Request $request, Course $course, $promotionId)
    {
        $validated = $request->validate([
            'maxima' => 'required|numeric|min:0|max:100',
        ]);
        $course->promotions()->updateExistingPivot($promotionId, ['maxima' => $validated['maxima']]);
        return redirect()->back()->with('success', 'Maxima mis à jour avec succès.');
    }

    public function detachPromotion(Course $course, $promotionId)
    {
        $course->promotions()->detach($promotionId);
        return redirect()->back()->with('success', 'Promotion retirée du cours avec succès.');
    }
}
