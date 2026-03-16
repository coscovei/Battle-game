@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Battle Simulator</h1>
        <p class="page-subtitle">Kratos vs Wild Beast</p>
    </div>

    <div class="card">
        <p>
            Start a new battle, then run it turn by turn or jump straight to the end.
        </p>

        <div class="actions-row">
            <form method="POST" action="{{ route('battles.start') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Run Battle</button>
            </form>

            <a href="{{ route('battles.history') }}" class="btn btn-secondary">View History</a>
        </div>
    </div>
@endsection
