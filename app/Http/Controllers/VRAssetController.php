<?php

namespace App\Http\Controllers;

use App\Models\VRAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VRAssetController extends Controller
{
    // Show all VR assets (user view)
    public function index()
    {
        $assets = VRAsset::active()->get();
        return view('vr-assets', compact('assets'));
    }

    // Admin: List all assets
    public function adminList()
    {
        $assets = VRAsset::all();
        return view('admin.vr-assets.list', compact('assets'));
    }

    // Admin: Show create form
    public function create()
    {
        $categories = [
            'Relaxation' => 'Relaxation',
            'Meditation' => 'Meditation',
            'Inspiration' => 'Inspiration',
            'Breathing' => 'Breathing Exercises',
            'Therapy' => 'Therapy Sessions',
            'Mindfulness' => 'Mindfulness',
            'Nature' => 'Nature Experiences',
        ];
        return view('admin.vr-assets.create', compact('categories'));
    }

    // Admin: Store new asset
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'category' => 'required|string|max:100',
            'duration_minutes' => 'required|integer|min:1|max:120',
            'file_type' => 'required|in:video,audio,model,interactive',
            'difficulty_level' => 'required|integer|between:1,5',
            'therapeutic_benefits' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'file' => 'nullable|file|max:102400', // 100MB max
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vr-assets/images', 'public');
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('vr-assets/files', 'public');
        }

        $asset = VRAsset::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'duration_minutes' => $validated['duration_minutes'],
            'file_type' => $validated['file_type'],
            'difficulty_level' => $validated['difficulty_level'],
            'therapeutic_benefits' => $validated['therapeutic_benefits'],
            'image_path' => $imagePath,
            'file_path' => $filePath,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.vr-assets.list')
                       ->with('success', 'VR Asset created successfully.');
    }

    // Admin: Show edit form
    public function edit(VRAsset $vrAsset)
    {
        $categories = [
            'Relaxation' => 'Relaxation',
            'Meditation' => 'Meditation',
            'Inspiration' => 'Inspiration',
            'Breathing' => 'Breathing Exercises',
            'Therapy' => 'Therapy Sessions',
            'Mindfulness' => 'Mindfulness',
            'Nature' => 'Nature Experiences',
        ];
        return view('admin.vr-assets.edit', compact('vrAsset', 'categories'));
    }

    // Admin: Update asset
    public function update(Request $request, VRAsset $vrAsset)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'category' => 'required|string|max:100',
            'duration_minutes' => 'required|integer|min:1|max:120',
            'file_type' => 'required|in:video,audio,model,interactive',
            'difficulty_level' => 'required|integer|between:1,5',
            'therapeutic_benefits' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'file' => 'nullable|file|max:102400',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($vrAsset->image_path) {
                Storage::disk('public')->delete($vrAsset->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('vr-assets/images', 'public');
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($vrAsset->file_path) {
                Storage::disk('public')->delete($vrAsset->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('vr-assets/files', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $vrAsset->update($validated);

        return redirect()->route('admin.vr-assets.list')
                       ->with('success', 'VR Asset updated successfully.');
    }

    // Admin: Delete asset
    public function destroy(VRAsset $vrAsset)
    {
        // Delete associated files
        if ($vrAsset->image_path) {
            Storage::disk('public')->delete($vrAsset->image_path);
        }
        if ($vrAsset->file_path) {
            Storage::disk('public')->delete($vrAsset->file_path);
        }

        $vrAsset->delete();

        return redirect()->route('admin.vr-assets.list')
                       ->with('success', 'VR Asset deleted successfully.');
    }

    // User: Get single asset details
    public function show(VRAsset $vrAsset)
    {
        if (!$vrAsset->is_active) {
            abort(404);
        }
        return view('vr-asset-detail', compact('vrAsset'));
    }

    // API: Get assets by category
    public function byCategory($category)
    {
        $assets = VRAsset::active()->byCategory($category)->get();
        return response()->json($assets);
    }

    // API: Get most popular assets
    public function popular()
    {
        $assets = VRAsset::active()->mostPopular(10)->get();
        return response()->json($assets);
    }

    // API: Get highest rated assets
    public function topRated()
    {
        $assets = VRAsset::active()->highestRated(10)->get();
        return response()->json($assets);
    }

    // API: Increment usage count
    public function incrementUsage(VRAsset $vrAsset)
    {
        $vrAsset->increment('usage_count');
        return response()->json(['success' => true]);
    }
}
