<?php

namespace App\Http\Controllers;

use App\AboutUs;
use App\Cashier;
use App\User;
use Illuminate\Http\Request;

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
        $sales = User::paginate(10);
        return view('admin.users-sales', compact('sales'));
    }

    public function UsersCashier()
    {
        $cashier = Cashier::paginate(10);
        return view('admin.users-cashier', compact('cashier'));
    }

    public function storeUserSales(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'username' => 'required|min:3',
            'password' => 'required|min:6',
        ]);
        // $encpass = Hash::make($request->password, [
        //     'rounds' => 12,
        // ]);
        $newUser = new User([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password)
        ]);
        $newUser->save();
        return redirect()->back()->with('success', "success added user");
    }

    public function storeUserCashier(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'username' => 'required|min:3',
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
            'info_name' => 'required|min:4',
            'info_phone' => 'numeric|min:6',
        ]);

        $id = $request->iduser;
        if ($request->has('info_name')) {
            User::where('id', $id)->first()->update([
                'name' => $request->info_name
            ]);
        }
        if ($request->has('info_phone')) {
            User::where('id', $id)->first()->update([
                'phone' => $request->info_phone
            ]);
        }

        if ($request->has('info_alamat')) {
            User::where('id', $id)->first()->update([
                'address' => $request->info_alamat
            ]);
        }

        return redirect()->back();
    }
}
