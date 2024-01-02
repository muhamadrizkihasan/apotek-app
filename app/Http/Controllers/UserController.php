<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('name', 'ASC')->simplePaginate(5);
        return view('user.index', compact('users'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email:dns|unique:users',
            'role' => 'required',
        ]);

        $email = substr($request->email, 0, 3);
        $name = substr($request->name, 0, 3);

        // Gabungkan dan hash email dan nama
        $hashedPassword = Hash::make($email . $name);

        User::create([
            'name'  => $request->name,
            'email'  => $request->email,
            'role' => $request->role,
            'password' => $hashedPassword
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan data pengguna!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);

        return view('user.edit', compact('user'));
        
        // User::create($validatedData);
    }
    
    /**
     * Update the specified resource in storage.
     */
    // $validatedData['password'] = Hash::make($validatedData['password']);

    // User::create($validatedData);
    // $validatedData['password'] = bcrypt($validatedData['password']);
    // $validatedData['password'] = Hash::make($validatedData['password']);

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$id,
            'role'  => 'required',
            'password' => ''
        ]);

        // $hashedPassword['password'] = Hash::make($validatedData['password']);;
        $hashedPassword = Hash::make($request->password);
        
        User::where('id', $id)->update([
            'name'  => $request->name,
            'email'  => $request->email,
            'role' => $request->role,
            'password' => $hashedPassword,
        ]);

        return redirect()->route('user.home')->with('success', 'Berhasil mengubah data pengguna!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'Berhasil menghapus data!');
    }

    // Karena function diakses setelah form di submit, jadi perlu parameter request
    public function authlogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        // Simpan data dari inputan email dan password ke dalam variable untuk memudahkan pemanggilannya
        $user = $request->only(['email', 'password']);

        // attempt : Mengecek kecocokan email dan password kemudian menyimpannya ke dalam kelas Auth (memberi identitas data riwayat login ke projectnya)
        if (Auth::attempt($user)) {
            // Perbedaan redirect() dan redirect()->route ?? redirect() -> path / redirect /  
            return redirect('/dashboard');
        } else {
            return redirect()->back()->with('failed', 'Login gagal, silahkan coba lagi!');
        }
    }

    public function logout()
    {
        // Menghapus / menghilangkan data session login
        Auth::logout();
        return redirect()->route('login')->with('logout', 'Anda telah logout!');
    }
}
