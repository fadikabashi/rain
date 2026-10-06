<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebConfig;
use Illuminate\Http\Request;

class webconfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.webconfig.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveOrUpdate(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'key' => 'required|string', // Assuming 'key' is the identifier for the configuration
            'value' => 'required|string',
        ]);
        $key = $request->input('key');
        $value = $request->input('value');

        // Attempt to find the configuration by key
        $config = WebConfig::where('key', $key )->first();

        // If the configuration exists, update its value; otherwise, create a new one
        if ($config) {
            $config->update(['value' => $value]);
        } else {
            WebConfig::create([
                'key' => $key,
                'value' => $value,
            ]);
        }

        // Return a response
        return response()->json(['message' => 'Configuration saved successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
