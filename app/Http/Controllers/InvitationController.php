<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    public function accept(Invitation $invitation)
    {
        if ($invitation->receiver_id !== Auth::id() || $invitation->status !== 0) {
            abort(403);
        }
        // Ajoute l'utilisateur au projet
        $invitation->project->users()->attach(Auth::id(), ['role' => 'member']);
        $invitation->status = 1; // acceptée
        $invitation->save();
        return back()->with('success', 'Invitation acceptée, vous faites maintenant partie du projet.');
    }

    public function refuse(Invitation $invitation)
    {
        if ($invitation->receiver_id !== Auth::id() || $invitation->status !== 0) {
            abort(403);
        }
        $invitation->status = 2; // refusée
        $invitation->save();
        return back()->with('success', 'Invitation refusée.');
    }
} 