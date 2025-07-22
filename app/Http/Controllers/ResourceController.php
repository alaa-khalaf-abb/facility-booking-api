<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use App\Models\Category;

class ResourceController extends Controller
{
    public function index() {
        $resources = Resource::all();
        return view('resources.index', compact('resources'));
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
        Resource::findOrFail($id)->delete();
        return redirect()->route('resources.index')->with('success', 'Resource deleted.');
    }
}
