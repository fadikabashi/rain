<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{
    public function edit_profile()
    {
        return view('auth.edit_profile')
        ;
    }

    public function update_profile(UpdateProfileRequest $request)
    {
        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if ($request->hasFile('profile_pic')) {
            if ($request->file('profile_pic')->isValid()) {
                $path = $request->file('profile_pic')->store('users','public_file');
                $user->profile_pic = 'files/'.$path;
            }
        }
        $user->update();

        toastr()->success('تم تعديل المستخدم بنجاح !!');
        return back();
    }

    public function update_password(UpdatePasswordRequest $request)
    {
        $user = User::find(Auth::id());
        $user->password = Hash::make($request->password);
        $user->update();

        toastr()->success('تم تعديل كلمة السر بنجاح !!');
        return back();
    }
}
