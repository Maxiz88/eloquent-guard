<?php

namespace Maxis\EloquentGuard\Livewire\Pulse;

use Illuminate\Contracts\View\View;
use Laravel\Pulse\Livewire\Card;
use Livewire\Attributes\Lazy;
use Illuminate\Contracts\Support\Renderable;

#[Lazy]
class EloquentGuard extends Card
{
    public function render(): \Illuminate\Contracts\View\View
    {
        $pulse = app(\Laravel\Pulse\Pulse::class);

        $nPlusOne = $pulse->aggregate(
            'eloquent_guard:nPlusOne',
            'max',
            $this->periodAsInterval()
        )->map(fn($row) => (object) [
            'type' => 'nPlusOne',
            'key'  => $row->key,
            'value' => (int) $row->max,
        ]);

        $slowQueries = $pulse->aggregate(
            'eloquent_guard:slowQuery',
            'max',
            $this->periodAsInterval()
        )->map(fn($row) => (object) [
            'type' => 'slowQuery',
            'key'  => $row->key,
            'value' => (int) $row->max,
        ]);

        $alerts = $nPlusOne->merge($slowQueries);

        return view('eloquent-guard::livewire.pulse.eloquent-guard', [
            'alerts' => $alerts,
        ]);
    }


    public function placeholder(): Renderable
    {
        return view('pulse::components.placeholder', [
            'cols' => $this->cols,
            'rows' => $this->rows,
        ]);
    }
}
