<?php

namespace App\Http\Controllers;


use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $users = User::orderBy('id','Desc')->where('user_type_id',1)->get();
        $user_types = UserType::all();

        return view('users.index')
        ->with('users',$users)
        ->with('user_types',$user_types)
        ;
    }

    public function store(UserStoreRequest $request)
    {

        $users = new User();
        $users->name = $request->name;
        $users->email = $request->email;
        $users->password =  Hash::make($request->password);
        $users->user_type_id = $request->user_type_id;
        $users->phone = $request->phone;
        $users->is_active = $request->is_active;
        if ($request->hasFile('profile_pic')) {
            if ($request->file('profile_pic')->isValid()) {
                $path = $request->file('profile_pic')->store('users','public_file');
                $users->profile_pic = 'files/'.$path;
            }
        }

        $users->save();

        toastr()->success('تم حفظ بيانات المستخدم بنجاح !!');
        return back();
    }


    public function edit($id)
    {
        $user = User::find($id);
        $user_types = UserType::all();

        return view('users.edit')
        ->with('user',$user)
        ->with('user_types',$user_types)
        ;
    }



    public function update(UserUpdateRequest $request,$id)
    {

        $users =  User::find($id);
        $users->name = $request->name;
        $users->email = $request->email;
        if(isset($request->password) && $request->password != null)
        {
            $users->password =  Hash::make($request->password);
        }
        $users->user_type_id = $request->user_type_id;
        $users->phone = $request->phone;
        $users->is_active = $request->is_active;
        if ($request->hasFile('profile_pic')) {
            if ($request->file('profile_pic')->isValid()) {
                $path = $request->file('profile_pic')->store('users','public_file');
                $users->profile_pic = 'files/'.$path;
            }
        }

        $users->update();

        toastr()->success('تم حفظ بيانات المستخدم بنجاح !!');
        return back();
    }

    public function destroy($id)
    {

            $users =  User::find($id)->delete();
            toastr()->success('تم حذف بيانات المستخدم بنجاح !!');
            return back();

    }

}
