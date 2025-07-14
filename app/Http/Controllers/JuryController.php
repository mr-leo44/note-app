<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use App\Models\Jury;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class JuryController extends Controller
{
    // Afficher tous les jurys
    public function index()
    {
        $juries = User::whereHas('account', function ($q) {
            $q->where('accountable_type', Jury::class);
        })->paginate(15);
        return view('juries.index', compact('juries'));
    }

    // Stocker un nouveau jury
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make('12345678'), // Mot de passe par défaut, à changer ultérieurement
        ]);
        $jury = Jury::create();
        Account::create([
            'user_id' => $user->id,
            'accountable_type' => Jury::class,
            'accountable_id' => $jury->id,
        ]);
        return redirect()->route('juries.index')->with('success', 'Jury créé avec succès.');
    }

    // Mise à jour d'un jury
    public function update(Request $request, User $jury)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($jury->id)],
        ]);
        $jury->name = $validated['name'];
        $jury->username = $validated['username'];
        $jury->email = $validated['email'];
        if (!empty($validated['password'])) {
            $jury->password = Hash::make($validated['password']);
        }
        $jury->save();
        return redirect()->route('juries.index')->with('success', 'Jury modifié avec succès.');
    }

    // Suppression (soft delete)
    public function destroy(User $jury)
    {
        if ($jury->account) {
            $accountable = $jury->account->accountable;
            if ($accountable) {
                $accountable->delete();
            }
            $jury->account->delete();
        }
        $jury->delete();
        return redirect()->route('juries.index')->with('success', 'Jury supprimé.');
    }
}