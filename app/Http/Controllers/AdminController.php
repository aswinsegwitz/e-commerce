<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $vendors = User::where('role', 'vendor')->get();

        return view('admin.vendor.index', ['vendors' => $vendors]);
    }

    public function create()
    {
        return view('admin.vendor.create');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.vendor.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Create a new vendor (user with role 'vendor')
        $vendor = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'vendor',
        ]);

        return redirect()->route('admin.vendor.index')->with('success', 'Vendor created successfully!');
    }


    public function edit(User $vendor)
    {
        return view('admin.vendor.edit', compact('vendor'));
    }


    public function update(Request $request, User $vendor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $vendor->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('vendors.edit', $vendor->id)
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];

        // Update password only if a new password is provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $vendor->update($data);

        return redirect()->route('admin.vendor.index')->with('success', 'Vendor updated successfully!');
    }

}
