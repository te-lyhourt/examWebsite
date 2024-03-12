<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

// use GuzzleHttp\Psr7\Response;
// use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function all_user()
    {
        $user = Auth::user();
        $userRole = json_decode($user->role)[0];
        if ($userRole == 'user') {
            return redirect()->route('page.project');
        }
        
        // Get the ID of the currently authenticated user
        $authUserId = Auth::id();

        $users = User::where('id', '!=', $authUserId)
            ->select('id', 'email', 'role', 'created_at', 'created_by')
            ->orderBy('created_at', 'desc') // Sort by creation date in descending order
            ->get();
        return Inertia('UserPage', [
            'users' => $users
        ]);
    }
    public function add_user(Request $request)
    {

        // Validation rules
        $validator = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'created_by' => 'required|integer|exists:users,id',
        ]);

        // Create user
        $user = User::create([
            'email' => $validator['email'],
            'password' => bcrypt($validator['password']),
            'role' => json_encode([$validator['role']]),
            'created_by' => $validator['created_by'],
        ]);
    }

    public function search(Request $request)
    {
        $validator = $request->validate([
            'id' => 'required|integer|exists:users,id'
        ]);
        $user = User::findOrFail([
            'id' => $validator(['id'])
        ]);
    }
    public function delete(Request $request)
    {
        $validator = $request->validate([
            //validataion rule
            'users.*' => 'required|integer|exists:users,id'
        ]);
        User::whereIn('id', $validator['users'])->delete();
    }
}
