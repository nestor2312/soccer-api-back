<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Mail\EndDemoMail;
use Illuminate\Support\Facades\Log; 

class AuthController extends Controller
{
    public function login(Request $request)
    {
      $request->validate([
        'email' => 'required|email',
        'password' => 'required',
      ]);
    
      $credentials = $request->only('email', 'password');
    
      if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;
    
        return response()->json(['user' => $user, 'token' => $token]);
      }
    
      return response()->json(['message' => 'Datos de inicio de sesión no válidos'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); // Revocar solo el token actual
    
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function register(Request $request)
{

$userCount = User::count();
 if ($userCount >= 2) {
   return response()->json(
    [ 'message' => 'Solo se puede registrar un usuario' ], 400);
   }

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

     // ✅ Envía el correo después de crear el usuario
    Mail::to($user->email)->send(new WelcomeMail($user));

Mail::to($user->email)->later(now()->addDays(10), new endDemoMail($user));

    $token = $user->createToken('authToken')->plainTextToken;

    return response()->json([
      'token' => $token,
      'message' => 'Usuario registrado correctamente',
      'user' => $user], 201);
}

}
