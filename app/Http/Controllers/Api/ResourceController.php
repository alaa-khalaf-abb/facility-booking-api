<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index()
    {
        return Resource::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'capacity' => 'nullable|integer',
            'location' => 'nullable',
        ]);

        $resource = Resource::create($validated);

        return response()->json($resource, 201);
    }

    public function show($id)
    {
        $resource = Resource::find($id);
        if (!$resource) {
            return response()->json(['error' => 'Resource not found'], 404);
        }
        return response()->json($resource);
    }

    public function update(Request $request, $id)
    {
        $resource = Resource::find($id);
        if (!$resource) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'capacity' => 'nullable|integer',
            'location' => 'nullable',
        ]);

        $resource->update($validated);

        return response()->json($resource);
    }

    public function destroy($id)
    {
        $resource = Resource::find($id);
        if (!$resource) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        $resource->delete();

        return response()->json(['message' => 'Resource deleted']);
    }
}
