@extends('layouts.app')

@section('content')
    <a href="{{ route('battles.index') }}" class="top-link">← Back to home</a>

    <div class="page-header">
        <h1 class="page-title">Battle History</h1>
        <p class="page-subtitle">Browse previously simulated battles.</p>
    </div>

    <div class="actions-row">
        <form method="POST" action="{{ route('battles.start') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Run New Battle</button>
        </form>
    </div>

    @if($battles->isEmpty())
        <div class="card">
            <p class="empty-state">No battles found yet.</p>
        </div>
    @else
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Hero</th>
                    <th>Monster</th>
                    <th>Winner</th>
                    <th>Turns</th>
                    <th>End Reason</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($battles as $battle)
                    @php
                        $hero = $battle->battleFighters->firstWhere('role', 'hero');
                        $monster = $battle->battleFighters->firstWhere('role', 'monster');

                        $winnerClass = match($battle->winner) {
                            'hero' => 'badge badge-success',
                            'monster' => 'badge badge-danger',
                            'draw' => 'badge badge-warning',
                            default => 'badge badge-neutral',
                        };
                    @endphp

                    <tr>
                        <td>{{ $battle->id }}</td>
                        <td>{{ $hero?->fighter?->name ?? 'Unknown' }}</td>
                        <td>{{ $monster?->fighter?->name ?? 'Unknown' }}</td>
                        <td>
                                <span class="{{ $winnerClass }}">
                                    {{ ucfirst($battle->winner ?? 'unknown') }}
                                </span>
                        </td>
                        <td>{{ $battle->turns_played }}</td>
                        <td>{{ $battle->end_reason ?? '-' }}</td>
                        <td>{{ $battle->created_at?->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('battles.show', $battle) }}">View</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        @if ($battles->hasPages())
            <div class="pagination">
                @if ($battles->onFirstPage())
                    <span class="muted">Previous</span>
                @else
                    <a href="{{ $battles->previousPageUrl() }}">← Previous</a>
                @endif

                <span>Page {{ $battles->currentPage() }} of {{ $battles->lastPage() }}</span>

                @if ($battles->hasMorePages())
                    <a href="{{ $battles->nextPageUrl() }}">Next →</a>
                @else
                    <span class="muted">Next</span>
                @endif
            </div>
        @endif
    @endif
@endsection
