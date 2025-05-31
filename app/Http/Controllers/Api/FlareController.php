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
            'user_id' => 'required|exists:users,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'note' => 'required|string|max:255',
            'category' => 'nullable|string',
            'place' => 'nullable|array',
            'place.mapbox_id' => 'required_with:place|string',
            'place.name' => 'required_with:place|string',
        ]);

         $place = null;
     if (isset($validated['place'])) {
        $placeData = $validated['place'];
        $place = \App\Models\Place::firstOrCreate(
            ['mapbox_id' => $placeData['mapbox_id']],
            [
                'name' => $placeData['name'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
            ]
        );
    }
        
        $flare = Flare::create([
            'user_id' => $request->input('user_id'), // change back into auth for production
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'note' => $validated['note'],
            'category' => $validated['category'] ?? 'regular',
            'place_id' => $place?->id,
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
