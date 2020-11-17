<?php

namespace App\Http\Controllers;

use App\AboutUs;
use App\Admin;
use App\Cashier;
use App\CategoryProduct;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\ProfileUser;
use App\Unit;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('admin.home');
    }

    public function UsersSales()
    {
        $sales = User::where('status', 1)->paginate(10);
        return view('admin.users-sales', compact('sales'));
    }

    public function UsersCashier()
    {
        $cashier = Cashier::where('status', 1)->paginate(10);
        return view('admin.users-cashier', compact('cashier'));
    }

    public function storeUserSales(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'username' => 'required|min:3|unique:users,username',
            'password' => 'required|min:6',
        ]);
        $newUser = new User([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password)
        ]);
        $save = $newUser->save();
        if ($save) {
            $profile = new ProfileUser([
                'fullname' => $request->name,
                'gender' => null,
                'birth_date' => null,
                'photo' => 'user-img/user-img-default.png',
                'user_id' => $newUser->id,
                'user_type' => 3
            ]);
            $profile->save();
        }
        return redirect()->back()->with('success', "success added user");
    }

    public function storeUserCashier(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'username' => 'required|min:3|unique:cashiers,username',
            'password' => 'required|min:6',
        ]);
        $newUser = new Cashier([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password)
        ]);
        $newUser->save();
        return redirect()->back()->with('success', "success added user");
    }

    public function settingsPage()
    {
        $aboutUs = AboutUs::first();
        return view('admin.settings', compact('aboutUs'));
    }

    public function updateInfoPage(Request $request)
    {
        $request->validate([
            'info_name_company' => 'required|min:4',
            'info_phone_company' => 'numeric|min:6',
        ]);

        $updateInfo = AboutUs::first()->update([
            'name' => $request->info_name_company
        ]);
        if ($request->has('info_phone_company')) {
            AboutUs::first()->update([
                'phone' => $request->info_phone_company
            ]);
        }

        if ($request->has('info_alamat_company')) {
            AboutUs::first()->update([
                'address' => $request->info_alamat_company
            ]);
        }

        if ($request->has('info_aboutus_company')) {
            AboutUs::first()->update([
                'about' => $request->info_aboutus_company
            ]);
        }
        return redirect()->back();
    }

    public function updatePassword(Request $request)
    {
        $user = Admin::findOrFail(Auth::user()->id);

        $request->validate([
            'oldpassword' => 'required|min:6',
            'newpass' => 'required_with:newpass2|min:6',
            'newpass2' => 'required|min:6'
        ]);
        if (Hash::check($request->oldpassword, $user->password)) {
            $user->fill([
                'password' => Hash::make($request->newpass)
            ])->save();

            return redirect()->back()->with('success', 'Password changed');
        } else {
            return redirect()->back()->with('error', 'Password does not match');
        }
    }

    public function editUserSales($id)
    {
        $dataUser = User::where('id', $id)->first();
        if ($dataUser == null) {
            abort(404);
        }

        return view('admin.edit-user-sales', compact('dataUser'));
    }

    public function updateDataUser(Request $request)
    {
        $request->validate([
            'info_name' => 'required|min:3',
            'info_phone' => 'numeric|min:6',
        ], [
            'info_name.min' => 'nama minimal :min huruf'
        ]);

        $id = $request->iduser;
        $update = User::where('id', $id)->update([
            'name' => $request->info_name,
            'phone' => $request->info_phone,
            'address' => $request->info_alamat
        ]);
        if ($update) {
            return redirect()->back()->with('success', 'Data sales berhasil di update');
        }
        return redirect()->back()->with('error', 'gagal di update');
    }

    public function changeStatusUser(Request $request)
    {
        $userType = $request->type;

        if ($userType == "cashier") {
            // dd($request);
            $cek = Cashier::where('id', $request->iduser)->first();
            if ($cek != null) $cek->update(['status' => 0]);
        } elseif ($userType == "sales") {
            $cek = User::where('id', $request->iduser)->first();
            if ($cek != null) $cek->update(['status' => 0]);
        }
        return redirect()->back();
    }
    public function changeStatus(Request $request)
    {
        $itemType = $request->type;

        if ($itemType == "unit") {
            $cek = Unit::where('id', $request->id)->first();
            if ($cek != null) $cek->update(['status' => 0]);
        } elseif ($itemType == "category") {
            $cek = CategoryProduct::where('id', $request->id)->first();
            if ($cek != null) $cek->update(['status' => 0]);
        }
        return redirect()->back();
    }

    public function changePass(Request $request)
    {
        dd($request);
    }
}
