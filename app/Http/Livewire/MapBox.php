<?php

namespace App\Http\Livewire;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

use Livewire\Component;
use App\Models\Location;

class MapBox extends Component
{
    use WithFileUploads;

    public $longtitude, $lattitude, $title, $description, $image;
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

    
    private function clearForm()
    {
        $this->longtitude = '';
        $this->lattitude = '';
        $this->title = '';
        $this->description = '';
        $this->image = '';
    }

    public function saveLocation()
    {
        $this->validate([
            'longtitude'        => 'required',
            'lattitude'         => 'required',
            'title'             => 'required',
            'description'       => 'required',
            'image'             => 'image|max:2048|required',
        ]);

        $imageName = md5($this->image.microtime()).'.'.$this->image->extension();

        Storage::putFileAs(
            'public/images',
            $this->image,
            $imageName
        );

        Location::create([
            'long'              => $this->longtitude,
            'lat'               => $this->lattitude,
            'title'             => $this->title,
            'description'       => $this->description,
            'image'             => $imageName,
        ]);

        $this->loadLocations();
        $this->clearForm();
        $this->dispatchBrowserEvent('locationAdded', $this->geoJson);
    }
    
    public function render()
    {
        $this->loadLocations();
        return view('livewire.map-box');
    }
}
