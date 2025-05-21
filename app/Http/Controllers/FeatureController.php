<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeatureFlag;

class FeatureController extends Controller
{
    /**
     * একটি নির্দিষ্ট ফিচার সক্রিয় আছে কিনা চেক করে
     *
     * @param Request $request
     * @param string $featureKey সেটিং টেবিলে থাকা কী নাম
     * @return \Illuminate\Http\Response
     */
    public function checkFeature(Request $request, $featureKey)
    {
        // যাচাই করুন ফিচারটি সেটিংসে সক্রিয় আছে কিনা
        $isEnabled = FeatureFlag::isEnabled($featureKey);
        
        if (!$isEnabled) {
            return redirect()->route('coming-soon');
        }
        
        // ফিচার সক্রিয় আছে, মূল রাউটে চালিয়ে যান
        return $request->next();
    }
    
    /**
     * কামিং সুন পেজ দেখান
     *
     * @return \Illuminate\Http\Response
     */
    public function comingSoon()
    {
        return view('coming-soon');
    }
    
    /**
     * ফিচার ফ্ল্যাগ সক্রিয় বা নিষ্ক্রিয় করুন (অ্যাডমিন অ্যাকশন)
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function toggleFeature(Request $request)
    {
        $request->validate([
            'feature_key' => 'required|string',
            'enabled' => 'required|boolean',
        ]);
        
        $featureKey = $request->feature_key;
        $value = $request->enabled;
        
        if (FeatureFlag::toggle($featureKey, $value)) {
            return response()->json([
                'success' => true,
                'message' => $value ? 'ফিচার সফলভাবে সক্রিয় করা হয়েছে!' : 'ফিচার সফলভাবে নিষ্ক্রিয় করা হয়েছে!'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'ফিচার আপডেট করা যায়নি। ফিচার খুঁজে পাওয়া যায়নি।'
        ], 404);
    }
} 