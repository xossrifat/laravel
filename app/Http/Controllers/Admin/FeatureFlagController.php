<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeatureFlag;

class FeatureFlagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $features = FeatureFlag::orderBy('created_at', 'desc')->get();
        return view('admin.features.index', compact('features'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.features.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:feature_flags,key',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_enabled' => 'boolean'
        ]);

        FeatureFlag::create([
            'key' => $request->key,
            'name' => $request->name,
            'description' => $request->description,
            'is_enabled' => $request->has('is_enabled') ? true : false,
        ]);

        return redirect()->route('admin.features.index')
            ->with('success', 'ফিচার ফ্ল্যাগ সফলভাবে তৈরি করা হয়েছে।');
    }

    /**
     * Toggle the specified resource status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggle(Request $request, $id)
    {
        $feature = FeatureFlag::findOrFail($id);
        $feature->is_enabled = !$feature->is_enabled;
        $feature->save();

        return redirect()->route('admin.features.index')
            ->with('success', 'ফিচার ফ্ল্যাগ স্ট্যাটাস সফলভাবে পরিবর্তন করা হয়েছে।');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $feature = FeatureFlag::findOrFail($id);
        return view('admin.features.edit', compact('feature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $feature = FeatureFlag::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_enabled' => 'boolean'
        ]);

        $feature->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_enabled' => $request->has('is_enabled') ? true : false,
        ]);

        return redirect()->route('admin.features.index')
            ->with('success', 'ফিচার ফ্ল্যাগ সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $feature = FeatureFlag::findOrFail($id);
        $feature->delete();

        return redirect()->route('admin.features.index')
            ->with('success', 'ফিচার ফ্ল্যাগ সফলভাবে মুছে ফেলা হয়েছে।');
    }
} 