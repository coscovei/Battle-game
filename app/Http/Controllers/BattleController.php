<?php

namespace App\Http\Controllers;

use App\Domain\Battle\BattleSimulator;
use App\Models\Battle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class BattleController extends Controller
{
    public function index(): View
    {
        return view('battles.index');
    }

    public function history(): View
    {
        $battles = Battle::with(['battleFighters.fighter'])
            ->latest('id')
            ->paginate(10);

        return view('battles.history', compact('battles'));
    }

    public function start(BattleSimulator $simulator): RedirectResponse
    {
        $battle = $simulator->startBattle();

        return redirect()->route('battles.show', $battle);
    }

    public function nextTurn(Battle $battle, BattleSimulator $simulator): RedirectResponse
    {
        $simulator->playNextTurn($battle);

        return redirect()->route('battles.show', $battle);
    }

    public function runToEnd(Battle $battle, BattleSimulator $simulator): RedirectResponse
    {
        $simulator->playToEnd($battle);

        return redirect()->route('battles.show', $battle);
    }

    public function show(Battle $battle): View
    {
        $battle->load([
            'battleFighters.fighter.skills',
            'turns.attacker.fighter',
            'turns.defender.fighter',
            'nextAttacker.fighter',
        ]);

        return view('battles.show', compact('battle'));
    }
}
