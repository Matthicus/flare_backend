<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flare;
use Illuminate\Support\Facades\Auth;

class FlareController extends Controller
{
    public function index()
    {
        return Flare::with(['user', 'place'])->latest()->get();
    }

    public function show($id)
    {
        $flare = Flare::with(['user', 'place'])->findOrFail($id);
        return response()->json($flare);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'note' => 'required|string|max:255',
            'category' => 'nullable|string|in:regular,blue,violet',
            'user_id' => 'required|exists:users,id', // replace with auth()->id() in prod
            'place' => 'nullable|array',
            'place.mapbox_id' => 'required_with:place|string',
            'place.name' => 'required_with:place|string',
            'photo' => 'nullable|image|max:5120',
        ]);

        $photoPath = null;
      if ($request->hasFile('photo')) {
      $photoPath = $request->file('photo')->store('flare_photos', 'public');
      }

        // Create or find place
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

        // Match to known place if within 200m
        $threshold = 200;
        $assignedKnownPlaceId = null;
        $knownPlaces = \App\Models\KnownPlace::all();

        $haversineDistance = function ($lat1, $lon1, $lat2, $lon2) {
            $earthRadius = 6371000;
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);
            $a = sin($dLat / 2) ** 2 +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            return $earthRadius * $c;
        };

        foreach ($knownPlaces as $knownPlace) {
            $distance = $haversineDistance(
                $validated['latitude'], $validated['longitude'],
                $knownPlace->lat, $knownPlace->lon
            );
            if ($distance <= $threshold) {
                $assignedKnownPlaceId = $knownPlace->id;
                break;
            }
        }

        $flare = Flare::create([
            'user_id' => $validated['user_id'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'note' => $validated['note'],
            'category' => $validated['category'] ?? 'regular',
            'place_id' => $place?->id,
            'known_place_id' => $assignedKnownPlaceId,
            'photo_path' => $photoPath,
        ]);

        return response()->json($flare->load('place', 'knownPlace'), 201);
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

    // ✅ Search for known places near a given location and return flare count
    public function nearbyKnownPlaces(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric',
        ]);

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 200);
        $earthRadius = 6371000;

        $knownPlaces = \App\Models\KnownPlace::selectRaw("
            *,
            (
                $earthRadius * acos(
                    cos(radians(?)) *
                    cos(radians(lat)) *
                    cos(radians(lon) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(lat))
                )
            ) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having("distance", "<", $radius)
        ->orderBy("distance")
        ->get();

        $results = $knownPlaces->map(function ($place) use ($radius, $earthRadius) {
            $flareCount = \App\Models\Flare::selectRaw("
                COUNT(*) as count
            ")->whereRaw("
                ($earthRadius * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                )) < ?
            ", [$place->lat, $place->lon, $place->lat, $radius])
            ->value('count');

            return [
                'id' => $place->id,
                'name' => $place->name,
                'lat' => $place->lat,
                'lon' => $place->lon,
                'distance' => round($place->distance),
                'flare_count' => $flareCount,
            ];
        });

        return response()->json($results);
    }
}
