<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $promotions = Promotion::orderBy('created_at', 'desc')->paginate(10);

        if ($request->wantsJson()) {
            return response()->json($promotions);
        }

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percent' => 'required|integer|min:1|max:99',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'sometimes|boolean',
        ]);

        Promotion::create($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'Акция создана!');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percent' => 'required|integer|min:1|max:99',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'sometimes|boolean',
        ]);
        
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        $promotion->update($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'Акция обновлена!');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Акция удалена!');
    }
}