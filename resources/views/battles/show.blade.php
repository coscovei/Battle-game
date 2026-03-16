@extends('layouts.app')

@section('content')
    <a href="{{ route('battles.history') }}" class="top-link">← Back to history</a>

    <div class="page-header">
        <h1 class="page-title">Battle #{{ $battle->id }}</h1>
        <p class="page-subtitle">Detailed result and turn-by-turn log.</p>
    </div>

    @php
        $hero = $battle->getHero();
        $monster = $battle->getMonster();

        $winnerClass = match($battle->winner) {
            'hero' => 'badge badge-success',
            'monster' => 'badge badge-danger',
            'draw' => 'badge badge-warning',
            default => 'badge badge-neutral',
        };
    @endphp

    <div class="summary-grid">
        <div class="summary-box">
            <div class="summary-label">Status</div>
            <div class="summary-value">{{ ucfirst($battle->status) }}</div>
        </div>

        <div class="summary-box">
            <div class="summary-label">Winner</div>
            <div class="summary-value">
                <span class="{{ $winnerClass }}">{{ ucfirst($battle->winner ?? 'pending') }}</span>
            </div>
        </div>

        <div class="summary-box">
            <div class="summary-label">Turns Played</div>
            <div class="summary-value">{{ $battle->turns_played }}</div>
        </div>

        <div class="summary-box">
            <div class="summary-label">End Reason</div>
            <div class="summary-value">{{ $battle->end_reason ?? '-' }}</div>
        </div>
    </div>

    @if($battle->isPending())
        <div class="actions-row">
            <form method="POST" action="{{ route('battles.next-turn', $battle) }}">
                @csrf
                <button type="submit" class="btn btn-primary">Run Next Turn</button>
            </form>

            <form method="POST" action="{{ route('battles.run-to-end', $battle) }}">
                @csrf
                <button type="submit" class="btn btn-secondary">Run To End</button>
            </form>
        </div>

        @if($battle->nextAttacker)
            <div class="card" style="margin-bottom: 20px;">
                <p style="margin: 0 0 8px 0;">
                    <strong>Next attacker:</strong> {{ $battle->nextAttacker->fighter->name }}
                </p>
                <p style="margin: 0;">
                    <strong>Next turn number:</strong> {{ $battle->current_turn_number + 1 }}
                </p>
            </div>
        @endif
    @endif

    <h2>Fighters</h2>

    <div class="grid-2">
        <div class="card">
            <h3>{{ $hero?->fighter?->name }} (Hero)</h3>

            <div class="stats-list">
                <div><strong>Health start:</strong> {{ $hero?->health_start }}</div>
                <div><strong>Health current:</strong> {{ $hero?->health_current }}</div>
                <div><strong>Health end:</strong> {{ $hero?->health_end ?? '-' }}</div>
                <div><strong>Strength:</strong> {{ $hero?->strength }}</div>
                <div><strong>Defence:</strong> {{ $hero?->defence }}</div>
                <div><strong>Speed:</strong> {{ $hero?->speed }}</div>
                <div><strong>Luck:</strong> {{ $hero?->luck_percent }}%</div>
            </div>
            <p><strong>Skills:</strong></p>
            <ul class="skill-list">
                @forelse($hero?->fighter?->skills ?? [] as $skill)
                    <li>
                        {{ $skill->name }}
                        ({{ $skill->trigger_phase }}, {{ $skill->trigger_chance_percent }}%)
                    </li>
                @empty
                    <li>No skills</li>
                @endforelse
            </ul>
        </div>

        <div class="card">
            <h3>{{ $monster?->fighter?->name }} (Monster)</h3>

            <div class="stats-list">
                <div><strong>Health start:</strong> {{ $monster?->health_start }}</div>
                <div><strong>Health current:</strong> {{ $monster?->health_current }}</div>
                <div><strong>Health end:</strong> {{ $monster?->health_end ?? '-' }}</div>
                <div><strong>Strength:</strong> {{ $monster?->strength }}</div>
                <div><strong>Defence:</strong> {{ $monster?->defence }}</div>
                <div><strong>Speed:</strong> {{ $monster?->speed }}</div>
                <div><strong>Luck:</strong> {{ $monster?->luck_percent }}%</div>
            </div>

            <p><strong>Skills:</strong></p>
            <ul class="skill-list">
                @forelse($monster?->fighter?->skills ?? [] as $skill)
                    <li>
                        {{ $skill->name }}
                        ({{ $skill->trigger_phase }}, {{ $skill->trigger_chance_percent }}%)
                    </li>
                @empty
                    <li>No skills</li>
                @endforelse
            </ul>
        </div>
    </div>

    <h2 style="margin-top: 28px;">Turn Log</h2>

    @forelse($battle->turns as $turn)
        <div class="turn-card">
            <div class="turn-title">Turn {{ $turn->turn_number }}</div>

            <p class="turn-meta">
                <strong>Attacker:</strong> {{ $turn->attacker->fighter->name }}
                &nbsp;|&nbsp;
                <strong>Defender:</strong> {{ $turn->defender->fighter->name }}
            </p>

            <p class="turn-meta">
                <strong>Base damage:</strong> {{ $turn->base_damage }}
                &nbsp;|&nbsp;
                <strong>Final damage:</strong> {{ $turn->final_damage }}
            </p>

            <p class="turn-meta">
                <strong>Defender HP:</strong>
                {{ $turn->defender_hp_before }} → {{ $turn->defender_hp_after }}
            </p>

            <p class="turn-meta">
                <strong>Lucky evade:</strong>
                {{ $turn->was_lucky_evade ? 'Yes' : 'No' }}
            </p>

            @if($turn->notes)
                <p><strong>Notes:</strong> {{ $turn->notes }}</p>
            @endif
        </div>
    @empty
        <div class="card">
            <p class="empty-state">No turns have been played yet.</p>
        </div>
    @endforelse
@endsection
