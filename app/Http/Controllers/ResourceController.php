<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use App\Models\Category;

class ResourceController extends Controller
{
    public function index() {
        $resources = Resource::with('category')->get();
        $categories = \App\Models\Category::all();
        return view('resources.index', compact('resources', 'categories'));
    }

    public function create() {
        $categories = Category::all();
        return view('resources.create', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'capacity' => 'nullable|integer',
            'location' => 'nullable',
            'category_id' => 'required|exists:categories,id',
        ]);

        Resource::create($request->all());
        return redirect()->route('resources.index')->with('success', 'Resource added successfully.');
    }

    public function edit($id) {
        $resource = Resource::findOrFail($id);
        $categories = Category::all();
        return view('resources.edit', compact('resource', 'categories'));
    }

    public function update(Request $request, $id) {
        $resource = Resource::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'capacity' => 'nullable|integer',
            'location' => 'nullable',
            'category_id' => 'required|exists:categories,id',
        ]);

        $resource->update($request->all());
        return redirect()->route('resources.index')->with('success', 'Resource updated successfully.');
    }

    public function destroy($id) {
        $resource = Resource::findOrFail($id);
        // Delete all related bookings first
        $resource->bookings()->delete();
        $resource->delete();
        return redirect()->route('resources.index')->with('success', 'Resource and all related bookings deleted.');
    }

    public function ajaxIndex(Request $request) {
        $query = Resource::with('category');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        $resources = $query->get();
        return response()->json($resources);
    }
}
