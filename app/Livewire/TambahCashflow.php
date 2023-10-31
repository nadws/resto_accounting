<?php

namespace App\Livewire;

use Livewire\Component;

class TambahCashflow extends Component
{
    public $nominal = 0;
    public function setNominal($set)
    {
        $this->nominal = $set * 1000;
    }
    public function render()
    {
        return view('livewire.tambah-cashflow');
    }
}
