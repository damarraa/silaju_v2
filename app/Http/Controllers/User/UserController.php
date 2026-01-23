<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Rayon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['rayon', 'roles'])->paginate(10);
        return view('pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $rayons = Rayon::orderBy('nama')->get();
        return view('pages.users.create', compact('rayons', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = DB::transaction(function () use ($validated) {
            $rayonId = ($validated['role'] === 'petugas') ? $validated['rayon_id'] : null;
            $newUser = User::create([
                'rayon_id' => $rayonId,
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $newUser->assignRole($validated['role']);
            return $newUser;
        });

        $user->refresh();

        return redirect()
            ->route('users.index')
            ->with('success', "User <strong>{$user->name}</strong> berhasil dibuat.<br>ID Petugas: <strong>{$user->identity_number}</strong>");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['rayon', 'roles', 'pjus'])->findOrFail($id);
        return view('pages.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $rayons = Rayon::orderBy('nama')->get();

        $user->load('roles');
        return view('pages.users.edit', compact('user', 'roles', 'rayons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $user) {
            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }

            if ($validated['role'] !== 'petugas') {
                $validated['rayon_id'] = null;
            }

            $user->update([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'rayon_id' => $validated['rayon_id'],
                ...($validated['password'] ? ['password' => $validated['password']] : []),
            ]);

            $user->syncRoles($validated['role']);
        });

        return redirect()
            ->route('users.index')
            ->with('success', "Data user <strong>{$user->name}</strong> berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', "User <strong>{$userName}</strong> berhasil dihapus.");
    }
}
