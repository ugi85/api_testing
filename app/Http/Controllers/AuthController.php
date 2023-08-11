<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
       //login token dan peran berdasarkan tabel user
    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['message' => 'Validation error'], 422);
    //     }

    //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         $user = Auth::user();
    //         $token = $user->createToken('api-token')->plainTextToken;

    //         return response()->json(['token' => $token, 'user' => $user]);
    //     }

    //     return response()->json(['message' => 'Invalid credentials'], 401);
    // }

    //login dengan token dan peran(role)
    public function login(Request $request)
    {
        // Mendapatkan email dan password dari permintaan (request)
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Login berhasil
            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;

            // Mendapatkan peran pengguna sebagai array
            $roles = $user->getRoleNames();
           // Mendapatkan permissions dari setiap peran yang dimiliki pengguna
            $permissions = $user->getPermissionNames();


            // Menyusun respons JSON dengan token dan informasi peran pengguna
            return response()->json([
                'token' => $token,
                'roles' => $roles->toArray(),
                'permissions'=> $permissions->toArray()
            ]);
        } else {
            // Login gagal
            return response()->json(['message' => 'Login gagal'], 401);
        }
    }


    //login hanya mendapatkan token
    // public function login(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();
    //         $token = $user->createToken('api-token')->plainTextToken;

    //         return response()->json([
    //             'token' => $token
    //         ]);
    //     }

    //     return response()->json(['error' => 'Invalid credentials'], 401);
    // }




    //register function
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'role' => 'nullable|in:user,admin', // Validasi peran (opsional)
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Buat pengguna baru
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        // Atur peran berdasarkan pilihan pengguna atau peran default 'user'
        $user->role = $request->role ?? 'user';

        $roleName = $request->input('role', 'user');
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            // Jika peran tidak ditemukan, tetapkan peran default sebagai "user"
            $role = Role::where('name', 'user')->first();
        }

        $user->save();
        $user->assignRole($role);


    //     // Atur peran berdasarkan pilihan pengguna atau peran default 'user'
    //     $user->role = $request->role ?? 'user';


    //      // Tetapkan peran default "user" jika peran tidak ada dalam permintaan atau tidak valid
    // $roleName = $request->input('role', 'user');
    // $role = Role::findByName($roleName);
    // if (!$role) {
    //     $role = Role::findByName('user');
    // }
    // $user->assignRole($role);


    //     // $role = Role::findByName($request['role']);
    //     // //database in role model
    //     // $user->assignRole($role);


    //     $user->save();

        return response()->json([
            'status' => '200',
            'success' => true,
            'message' => 'Register successfully',
            'data' => $user
        ]);
    }

    public function index()
    {
        {
            $users = User::with('roles')->get();
            return response()->json($users);
        }
    }

}
