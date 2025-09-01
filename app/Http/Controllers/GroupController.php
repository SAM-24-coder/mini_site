<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\View\View;

class GroupController extends Controller
{
    //
    public function index(): View
    {
        $groups = Group::latest()->paginate(10);
        return view('Groups.index', compact('groups'));
    }
    
    public function create_group(): View
    {
        return view('Groups.create_group');
    }
    
    public function store_group(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'tag' => 'required|string|max:255|unique:groups,tag',
        'description' => 'nullable|string',
    ]);

    $validated['created_by'] = auth()->id();

    Group::create($validated);

    return redirect()->route('admin.groups.index')->with('success', 'Groupe créé avec succès');
}
    public function destroy(Group $group)
    {
        $group->delete();
        return back()->with('success', 'Groupe supprimé');
    }
}
