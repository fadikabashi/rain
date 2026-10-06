<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Http\Requests\StoreAdRequest;
use App\Http\Requests\UpdateAdRequest;
use App\Services\Helpers\CacheHelper;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ads = Ad::orderBy('id','Asc')->get();
        return view('admin.ad.index')->with('ads', $ads);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ad.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAdRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdRequest $request)
    {
        $ad = new ad();
        if ($request->hasFile('ad_1_555')) {
            if ($request->file('ad_1_555')->isValid()) {
                $path = $request->file('ad_1_555')->store('users','public_file');
                $ad->ad_1_555 = 'files/'.$path;
            }
        }
        if ($request->hasFile('ad_2_555')) {
            if ($request->file('ad_2_555')->isValid()) {
                $path = $request->file('ad_2_555')->store('users','public_file');
                $ad->ad_2_555 = 'files/'.$path;
            }
        }
        if ($request->hasFile('ad_1_350')) {
            if ($request->file('ad_1_350')->isValid()) {
                $path = $request->file('ad_1_350')->store('users','public_file');
                $ad->ad_1_350 = 'files/'.$path;
            }
        }
        if ($request->hasFile('ad_2_350')) {
            if ($request->file('ad_2_350')->isValid()) {
                $path = $request->file('ad_2_350')->store('users','public_file');
                $ad->ad_2_350 = 'files/'.$path;
            }
        }
        if ($request->hasFile('ad_3_350')) {
            if ($request->file('ad_3_350')->isValid()) {
                $path = $request->file('ad_3_350')->store('users','public_file');
                $ad->ad_3_350 = 'files/'.$path;
            }
        }
        if ($request->hasFile('ad_1_1110')) {
            if ($request->file('ad_1_1110')->isValid()) {
                $path = $request->file('ad_1_1110')->store('users','public_file');
                $ad->ad_1_1110 = 'files/'.$path;
            }
        }
        if ($request->hasFile('ad_1_shop')) {
            if ($request->file('ad_1_shop')->isValid()) {
                $path = $request->file('ad_1_shop')->store('users','public_file');
                $ad->ad_1_shop = 'files/'.$path;
            }
        }
        $ad ->save();
        CacheHelper::clearAdsCache();
        toastr()->success('تم حفظ بيانات العميل بنجاح !!');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ad  $ad
     * @return \Illuminate\Http\Response
     */
    public function show(Ad $ad)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ad  $ad
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $ad = ad::find($id);
        return view('admin.ad.edit')->with('ad', $ad);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAdRequest  $request
     * @param  \App\Models\Ad  $ad
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdRequest $request, $id)
    {
        $ad=ad::find($id);
        if ($request->hasFile('ad_1_555')) {
            if ($request->file('ad_1_555')->isValid()) {
                $path = $request->file('ad_1_555')->store('users','public_file');
                $ad->ad_1_555 = 'files/'.$path;
            }
        }
        if ($request->hasFile('ad_2_555')) {
            if ($request->file('ad_2_555')->isValid()) {
                $path = $request->file('ad_2_555')->store('users','public_file');
                $ad->ad_2_555 = 'files/'.$path;
            }
        }
        if ($request->hasFile('ad_1_350')) {
            if ($request->file('ad_1_350')->isValid()) {
                $path = $request->file('ad_1_350')->store('users','public_file');
                $ad->ad_1_350 = 'files/'.$path;
            }
        }
        if ($request->hasFile('ad_2_350')) {
            if ($request->file('ad_2_350')->isValid()) {
                $path = $request->file('ad_2_350')->store('users','public_file');
                $ad->ad_2_350 = 'files/'.$path;
            }
        }
        if ($request->hasFile('ad_3_350')) {
            if ($request->file('ad_3_350')->isValid()) {
                $path = $request->file('ad_3_350')->store('users','public_file');
                $ad->ad_3_350 = 'files/'.$path;
            }
        }
        if ($request->hasFile('ad_1_1110')) {
            if ($request->file('ad_1_1110')->isValid()) {
                $path = $request->file('ad_1_1110')->store('users','public_file');
                $ad->ad_1_1110 = 'files/'.$path;
            }
        }
        if ($request->hasFile('ad_1_shop')) {
            if ($request->file('ad_1_shop')->isValid()) {
                $path = $request->file('ad_1_shop')->store('users','public_file');
                $ad->ad_1_shop = 'files/'.$path;
            }
        }
        $ad ->save();
        CacheHelper::clearAdsCache();
        toastr()->success('تم حفظ بيانات العميل بنجاح !!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ad  $ad
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ad =  ad::find($id)->delete();
            CacheHelper::clearAdsCache();
            toastr()->success('تم حذف بيانات العميل بنجاح !!');
            return back();
    }
}
