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
use App\StockActivity;
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
        $sales = User::where('status', 1)->get();
        return view('admin.home', compact('sales'));
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
            'password' => bcrypt($request->password),
            'phone' => $request->new_phone,
            'address' => $request->new_alamat
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

    public function indexStockActivities()
    {
        $stockActivity = StockActivity::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.a_stock', compact('stockActivity'));
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
    public function editUserCashier($id)
    {
        $dataUser = Cashier::where('id', $id)->first();
        if ($dataUser == null) {
            abort(404);
        }

        return view('admin.edit-user-cashier', compact('dataUser'));
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
    public function updateDataCashier(Request $request)
    {
        $request->validate([
            'info_name' => 'required|min:3',
        ], [
            'info_name.min' => 'nama minimal :min huruf'
        ]);

        $id = $request->iduser;
        $update = Cashier::where('id', $id)->update([
            'name' => $request->info_name,
        ]);
        if ($update) {
            return redirect()->back()->with('success', 'Data berhasil di update');
        }
        return redirect()->back()->with('error', 'gagal di update');
    }

    public function changeStatusUser(Request $request)
    {
        $userType = $request->type;
        $sukses = false;
        if ($userType == "cashier") {
            $cek = Cashier::where('id', $request->iduser)->first();
            if ($cek != null) $cek->update(['status' => 0]);
            $sukses = 1;
        } elseif ($userType == "sales") {
            $cek = User::where('id', $request->iduser)->first();
            if ($cek != null) $cek->update(['status' => 0]);
            $sukses = 1;
        }
        if ($sukses) {
            return redirect()->back()->with('success', 'berhasil dihapus');
        }
        return redirect()->back()->with('error', 'gagal dihapus');
    }
    public function changeStatus(Request $request)
    {
        $itemType = $request->type;
        $sukses = false;
        if ($itemType == "unit") {
            $cek = Unit::where('id', $request->id)->first();
            if ($cek != null) $cek->update(['status' => 0]);
            $sukses = 1;
        } elseif ($itemType == "category") {
            $cek = CategoryProduct::where('id', $request->id)->first();
            if ($cek != null) $cek->update(['status' => 0]);
            $sukses = 1;
        }
        if ($sukses) {
            return redirect()->back()->with('success', 'berhasil dihapus');
        }
        return redirect()->back()->with('error', 'gagal dihapus');
    }

    public function changePass(Request $request)
    {
        $request->validate([
            'oldpassword' => 'required|min:6',
            'newpass' => 'required_with:newpass2|min:6',
            'newpass2' => 'required|min:6',
            'iduser' => 'required',
            'typeUser' => 'required',
        ]);

        $typeUser = $request->typeUser;
        if ($request->newpass != $request->newpass2) {
            return redirect()->back()->with('error', 'Kata sandi tidak sama');
        }
        $iduser = $request->iduser;
        if ($typeUser == "sales") {
            $user = User::findOrFail($iduser);
        } elseif ($typeUser == "cashier") {
            $user = Cashier::findOrFail($iduser);
        } else {
            return redirect()->back();
        }

        if (Hash::check($request->oldpassword, $user->password)) {
            $user->fill([
                'password' => Hash::make($request->newpass)
            ])->save();
            return redirect()->back()->with('success', 'Berhasil mengubah kata sandi');
        } else {
            return redirect()->back()->with('error', 'Kata sandi lama salah');
        }
    }
}
