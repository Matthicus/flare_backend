<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flare;
use Illuminate\Support\Facades\Auth;

class FlareController extends Controller
{
    public function index() {
        return Flare::with('user')->latest()->get();
    }

       public function show($id)
    {
        $flare = Flare::with('user')->findOrFail($id);
        return response()->json($flare);
    }

      public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'note' => 'required|string|max:255',
            'category' => 'nullable|string',
        ]);
        
        $flare = Flare::create([
            'user_id' => Auth::id(),
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'note' => $validated['note'],
            'category' => $validated['category'] ?? 'regular',
        ]);

        return response()->json($flare, 201);
    }

  public function destroy($id)
    {
        $flare = Flare::findOrFail($id);

        if ($flare->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $flare->delete();
        return response()->json(['message' => 'Flare deleted']);
    }

}
