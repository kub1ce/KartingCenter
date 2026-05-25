<?php

namespace App\Http\Controllers;

use App\Enums\KartStatus;
use App\Models\Kart;
use App\Models\KartType;
use Illuminate\Http\Request;

class KartController extends Controller
{
    public function index(Request $request)
    {
        $karts = Kart::with('kartType')->paginate(15);

        if ($request->wantsJson()) {
            return response()->json($karts);
        }

        return view('admin.karts.index', compact('karts'));
    }

    public function create()
    {
        $kartTypes = KartType::all();
        $statuses = KartStatus::cases();

        return view('admin.karts.create', compact('kartTypes', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|string|max:10|unique:karts',
            'type_id' => 'required|exists:kart_types,id',
            'status' => 'required|string',
        ]);

        $validated['status'] = KartStatus::from($validated['status'])->value;

        Kart::create($validated);

        return redirect()->route('admin.karts.index')->with('success', 'Карт успешно добавлен!');
    }

    public function edit(Kart $kart)
    {
        $kartTypes = KartType::all();
        $statuses = KartStatus::cases();

        return view('admin.karts.edit', compact('kart', 'kartTypes', 'statuses'));
    }

    public function update(Request $request, Kart $kart)
    {
        $validated = $request->validate([
            'number' => 'required|string|max:10|unique:karts,number,' . $kart->id,
            'type_id' => 'required|exists:kart_types,id',
            'status' => 'required|string',
        ]);

        $validated['status'] = KartStatus::from($validated['status'])->value;

        $kart->update($validated);

        return redirect()->route('admin.karts.index')->with('success', 'Карт обновлен!');
    }

    public function destroy(Kart $kart)
    {
        $kart->delete();
        return redirect()->route('admin.karts.index')->with('success', 'Карт удален!');
    }
}