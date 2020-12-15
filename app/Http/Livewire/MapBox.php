<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MapBox extends Component
{
    public $longtitude, $lattitude;
    
    public function render()
    {
        return view('livewire.map-box');
    }
}
