<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index() {
        $resources = Resource::all();
        return view('resources.index', compact('resources'));
    }

    public function create() {
        return view('resources.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'capacity' => 'nullable|integer',
            'location' => 'nullable',
        ]);

        Resource::create($request->all());
        return redirect()->route('resources.index')->with('success', 'Resource added successfully.');
    }

    public function edit($id) {
        $resource = Resource::findOrFail($id);
        return view('resources.edit', compact('resource'));
    }

    public function update(Request $request, $id) {
        $resource = Resource::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'capacity' => 'nullable|integer',
            'location' => 'nullable',
        ]);

        $resource->update($request->all());
        return redirect()->route('resources.index')->with('success', 'Resource updated successfully.');
    }

    public function destroy($id) {
        Resource::findOrFail($id)->delete();
        return redirect()->route('resources.index')->with('success', 'Resource deleted.');
    }
}
