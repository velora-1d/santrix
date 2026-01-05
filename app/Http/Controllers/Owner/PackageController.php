<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::orderBy('sort_order')->orderBy('price')->get();
        return view('owner.packages.index', compact('packages'));
    }

    public function create()
    {
        // Provide standard feature list template
        $defaultFeatures = Package::defaultFeatures();
        return view('owner.packages.create', compact('defaultFeatures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:packages,slug',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'duration_months' => 'required|integer|min:1',
            'feature_names' => 'array',
            'feature_included' => 'array',
        ]);

        $features = [];
        if ($request->has('feature_names')) {
            foreach ($request->feature_names as $index => $name) {
                if (!empty($name)) {
                    $features[] = [
                        'name' => $name,
                        'included' => isset($request->feature_included[$index]) && $request->feature_included[$index] == '1',
                    ];
                }
            }
        }

        Package::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'duration_months' => $request->duration_months,
            'description' => $request->description,
            'features' => $features,
            'is_featured' => $request->has('is_featured'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('owner.packages.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Package $package)
    {
        // Merge existing features with defaults to ensure all options are available/visible if structure changes
        // For simplicity, just use what's in DB, but maybe user wants to add more.
        // We'll trust DB data.
        return view('owner.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:packages,slug,' . $package->id,
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'duration_months' => 'required|integer|min:1',
            'feature_names' => 'array',
        ]);

        $features = [];
        if ($request->has('feature_names')) {
            foreach ($request->feature_names as $index => $name) {
                if (!empty($name)) {
                    $features[] = [
                        'name' => $name,
                        'included' => isset($request->feature_included[$index]) && $request->feature_included[$index] == '1',
                    ];
                }
            }
        }

        $package->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'duration_months' => $request->duration_months,
            'description' => $request->description,
            'features' => $features,
            'is_featured' => $request->has('is_featured'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('owner.packages.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('owner.packages.index')->with('success', 'Paket berhasil dihapus.');
    }
}
