<?php
namespace App\Http\Controllers\Edit;

use App\User;
use Validator;
use Hash;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class UserEditController extends Controller
{
    public function getEditNameForm($id) {
        return view('edit-name', ['user' => User::findOrFail($id)]);
    }

    public function getEditEmailForm($id) {
        return view('edit-email', ['user' => User::findOrFail($id)]);
    }

    public function getChangePasswordForm($id) {
        return view('change-password', ['user' => User::findOrFail($id)]);
    }

    public function getDeleteUserForm($id) {
        if (Auth::user()['attributes']['id'] != $id) {
            return view('delete-user', ['user' => User::findOrFail($id)]);
        } else {
            return Redirect::to('/edit-profiles')
                ->with('flash_message', 'You can\'t delete current user');
        }
    }

    public function editName(Request $request)
    {
        Validator::extend('hashmatch', function($attributes, $value, $parameters)
        {
            $user = Auth::user();
            return Hash::check($value, $user->$parameters[0]);
        });
        $messages = array(
            'hashmatch' => 'The password you have entered didn\'t match edited account password.'
        );
        $rules =  [
            'name' => 'required|max:255|unique:users',
            'password' => 'required|hashmatch:password',
        ];
        $v = Validator::make($request->all(), $rules, $messages );

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        } else {
            $id = substr(strrchr($_SERVER['REQUEST_URI'], "/"), 1);
            $user = User::find($id);
            $user->name = $request['name'];
            $user->save();
            return Redirect::to('/edit-profiles')
                ->with('flash_message', 'You have successfully changed username');
        }
    }

    public function editEmail(Request $request)
    {
        Validator::extend('hashmatch', function($attributes, $value, $parameters)
        {
            $user = Auth::user();
            return Hash::check($value, $user->$parameters[0]);
        });
        $messages = array(
            'hashmatch' => 'The password you have entered didn\'t match edited account password.'
        );
        $rules =  [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|hashmatch:password',
        ];
        $v = Validator::make($request->all(), $rules, $messages );

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        } else {
            $id = substr(strrchr($_SERVER['REQUEST_URI'], "/"), 1);
            $user = User::find($id);
            $user->email = $request['email'];
            $user->save();
            return Redirect::to('/edit-profiles')
                ->with('flash_message', 'You have successfully changed email');
        }
    }

    public function changePassword(Request $request)
    {
        Validator::extend('hashmatch', function($attributes, $value, $parameters)
        {
            $user = Auth::user();
            return Hash::check($value, $user->$parameters[0]);
        });
        $messages = array(
            'hashmatch' => 'The password you have entered didn\'t match edited account password.'
        );
        $rules =  [
            'password' => 'required|hashmatch:password',
            'new_password' => 'required|confirmed|min:6'
        ];
        $v = Validator::make($request->all(), $rules, $messages );

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        } else {
            $id = substr(strrchr($_SERVER['REQUEST_URI'], "/"), 1);
            $user = User::find($id);
            $user->password = bcrypt($request['new_password']);
            $user->save();
            return Redirect::to('/edit-profiles')
                ->with('flash_message', 'You have successfully changed password');
        }
    }

    public function deleteUser(Request $request)
    {
        Validator::extend('hashmatch', function($attributes, $value, $parameters)
        {
            $user = Auth::user();
            return Hash::check($value, $user->$parameters[0]);
        });
        $messages = array(
            'hashmatch' => 'The password you have entered didn\'t match edited account password.'
        );
        $rules =  [
            'password' => 'required|hashmatch:password'
        ];
        $v = Validator::make($request->all(), $rules, $messages );

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        } else {
            $id = substr(strrchr($_SERVER['REQUEST_URI'], "/"), 1);
            $user = User::find($id);
            $user->delete();
            return Redirect::to('/edit-profiles')
                ->with('flash_message', 'You have successfully deleted user record');
        }
    }
}