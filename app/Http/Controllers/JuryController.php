<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Jury;
use App\Models\User;
use App\Models\Period;
use App\Models\Account;
use App\Models\Promotion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class JuryController extends Controller
{
    // Afficher tous les jurys
    public function index()
    {
        $juries = User::whereHas('account', function ($q) {
            $q->where('accountable_type', Jury::class);
        })
        ->with(['account.accountable.promotions'])
        ->paginate(15);

        // Injecter les promotions directement sur chaque User pour simplifier l'accès dans la vue
        foreach ($juries as $jury) {
            $jury->promotions = optional(optional($jury->account)->accountable)->promotions ?? collect();
        }

        return view('juries.index', compact('juries'));
    }

    // Stocker un nouveau jury
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'promotions' => 'required|array|min:1',
            'promotions.*' => 'required|exists:promotions,id',
        ]);
        DB::beginTransaction();
        try {
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

            $currentPeriod = Period::where('current', true)->first();
            if (!$currentPeriod) {
                DB::rollBack();
                return back()->with('error', 'Aucune période en cours n\'est définie.');
            }

            // Vérifier pour chaque promotion si elle est déjà assignée à un autre jury pour la période courante
            $alreadyAssigned = [];
            foreach ($validated['promotions'] as $promotionId) {
                $exists = DB::table('jury_promotion')
                    ->where('promotion_id', $promotionId)
                    ->where('period', $currentPeriod->name)
                    ->whereNull('deleted_at')
                    ->exists();
                if ($exists) {
                    $promotionName = \App\Models\Promotion::find($promotionId)->name;
                    $alreadyAssigned[] = $promotionName;
                }
            }
            if (count($alreadyAssigned) > 0) {
                DB::rollBack();
                return back()->withErrors(['Certaines promotions sont déjà affectées à un jury pour la période en cours : ' . implode(', ', $alreadyAssigned)]);
            }

            // Ajout direct des promotions avec la période courante
            foreach ($validated['promotions'] as $promotionId) {
                $jury->promotions()->attach($promotionId, ['period' => $currentPeriod->name]);
            }
            DB::commit();
            return redirect()->route('juries.index')->with('success', 'Jury créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la création du jury.');
        }
    }

    // Mise à jour d'un jury
    public function update(Request $request, User $jury)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($jury->id)],
            'promotions' => 'required|array|min:1',
            'promotions.*' => 'required|exists:promotions,id'
        ]);

        DB::beginTransaction();
        try {
            $jury->name = $validated['name'];
            $jury->username = $validated['username'];
            $jury->email = $validated['email'];
            if (!empty($validated['password'])) {
                $jury->password = Hash::make($validated['password']);
            }
            $jury->save();

            $currentPeriod = Period::where('current', true)->first();
            if (!$currentPeriod) {
                DB::rollBack();
                return back()->with('error', 'Aucune période en cours n\'est définie.');
            }

            // Vérifier pour chaque promotion si elle est déjà assignée à un autre jury pour la période courante
            $alreadyAssigned = [];
            foreach ($validated['promotions'] as $promotionId) {
                $exists = DB::table('jury_promotion')
                    ->where('promotion_id', $promotionId)
                    ->where('period', $currentPeriod->name)
                    ->whereNull('deleted_at')
                    ->where('jury_id', '!=', $jury->account->accountable->id)
                    ->exists();
                    
                    $alreadyExists = DB::table('jury_promotion')
                    ->where('promotion_id', $promotionId)
                    ->where('period', $currentPeriod->name)
                    ->whereNull('deleted_at')
                    ->where('jury_id', $jury->account->accountable->id)
                    ->exists();
                if ($exists || $alreadyExists) {
                    $promotionName = Promotion::find($promotionId)->name;
                    $alreadyAssigned[] = $promotionName;
                }
            }
            if (count($alreadyAssigned) > 0) {
                DB::rollBack();
                return back()->with('warning', 'Cette promotion a déjà été affectée à un jury pour la période en cours.');
            }

            // Mettre à jour les promotions pour la période courante
            $juryModel = $jury->account->accountable;
            // On retire toutes les promotions pour la période courante
            DB::table('jury_promotion')
                ->where('jury_id', $juryModel->id)
                ->where('period', $currentPeriod->name)
                ->delete();
            // On assigne les nouvelles promotions pour la période courante
            foreach ($validated['promotions'] as $promotionId) {
                $juryModel->promotions()->attach($promotionId, ['period' => $currentPeriod->name]);
            }

            DB::commit();
            return redirect()->route('juries.index')->with('success', 'Jury modifié avec succès.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour du jury.');
        }
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
    public function assignPromotionToJury(Request $request, Jury $jury)
    {
        $validated = $request->validate([
            'promotion_id' => 'required|exists:promotions,id',
        ]);
        $currentPeriod = Period::where('current', true)->first();
        if (!$currentPeriod) {
            return redirect()->back()->withErrors(['Aucune période en cours n\'est définie.']);
        }
        // Vérifier si la promotion est déjà assignée à un autre jury pour la période courante
        $exists = DB::table('jury_promotion')
            ->where('promotion_id', $validated['promotion_id'])
            ->where('period', $currentPeriod->name)
            ->whereNull('deleted_at')
            ->where('jury_id', '!=', $jury->id)
            ->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['Cette promotion est déjà assignée à un autre jury pour la période en cours.']);
        }
        // On retire toute ancienne assignation de cette promotion à ce jury (pour n'importe quelle période)
        $jury->promotions()->detach($validated['promotion_id']);
        // On assigne la promotion à ce jury pour la période courante
        $jury->promotions()->attach($validated['promotion_id'], ['period' => $currentPeriod->name]);
        return redirect()->back()->with('success', 'Promotion assignée au jury avec succès.');
    }

    public function resetPassword(Request $request, User $jury)
    {
        // Récupérer le modèle User et son Jury associé
        $accountable = optional($jury->account)->accountable;
        if (!$accountable || !$accountable instanceof \App\Models\Jury) {
            return response()->json(['error' => 'Jury introuvable.'], 404);
        }
        // Génère un mot de passe aléatoire entre 8 et 14 caractères avec Str
        $length = rand(8, 14);
        $pwd = Str::random($length);
        $jury->password = Hash::make($pwd);
        $jury->save();
        return response()->json(['password' => $pwd]);
    }
}