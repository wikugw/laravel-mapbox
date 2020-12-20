<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Location;

class MapBox extends Component
{
    public $longtitude, $lattitude;
    public $geoJson;

    private function loadLocations()
    {
        $locations = Location::orderBy('created_at', 'desc')->get();

        $customLocation = [];

        foreach($locations as $location){
            $customLocation[] = [
                'type' => 'Feature',
                'geometry' => [
                    'coordinates' => [$location->long, $location->lat],
                    'type' => 'Point'
                ],
                'properties' => [
                    'locationId' => $location->id,
                    'title' => $location->title,
                    'image' => $location->image,
                    'description' => $location->description
                ]
            ];
        }

        $geoLocation = [
            'type' => 'FeatureCollection',
            'features' => $customLocation
        ];

        $geoJson = collect($geoLocation)->toJson();
        $this->geoJson = $geoJson;
    }
    
    public function render()
    {
        $this->loadLocations();
        return view('livewire.map-box');
    }
}
