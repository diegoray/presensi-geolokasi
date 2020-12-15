<?php

namespace App\Http\Livewire;

use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Location;
use Illuminate\Support\Facades\Storage;

class MapLocation extends Component
{
    use WithFileUploads;

    public $count = 5;
    public $locationId, $long, $lat, $name, $description, $image;
    public $geoJson;
    public $imageUrl; 
    public $isEdit = false;
    
    private function loadLocations(){
        $locations = Location::orderBy('created_at', 'desc')->get();

        $costumLocations = [];

        foreach($locations as $location){
            $costumLocations[] = [
                'type' => 'Feature',
                'geometry' => [
                    'coordinates' => [$location->long, $location->lat],
                    'type' => 'Point'
                ],
                'properties' => [
                    'locationId' => $location->id,
                    'title' => $location->name,
                    'image' => $location->image,
                    'description' => $location->description,
                ], 
            ];
        }

        $geoLocation = [
            'type' => 'FeatureCollection',
            'features' => $costumLocations
        ];

        $geoJson = collect($geoLocation)->toJson();
        $this->geoJson = $geoJson;
    }

    public function clearForm(){
        $this->long = '';
        $this->lat = '';
        $this->name = '';
        $this->description = '';
        $this->image = '';
    }

    public function saveLocation() {
        $this->validate([
            'long' => 'required',
            'lat' => 'required',
            'name' => 'required',
            'description' => 'required',
            'image' => 'image|max:2048|required',
        ]);

        $imageName = md5($this->image.microtime()).'.'.$this->image->extension();

        Storage::putFileAs(
            'public/images',
            $this->image,
            $imageName
        );

        Location::create([
            'long' => $this->long,
            'lat' => $this->lat,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $imageName,
        ]);

        $this->loadLocations();
        $this->clearForm();
        $this->dispatchBrowserEvent('locationAdded', $this->geoJson);
    }

    public function updateLocation(){  
        $this->validate([
            'long' => 'required',
            'lat' => 'required',
            'name' => 'required',
            'description' => 'required',
        ]);

        $location = Location::findOrFail($this->locationId);

        if($this->image){
            $imageName = md5($this->image.microtime()).'.'.$this->image->extension();

            Storage::putFileAs(
                'public/images',
                $this->image,
                $imageName
            );

            $updateData = [
                'name' => $this->name,
                'description' => $this->description,
                'image' => $imageName,
            ];
        }else{
            $updateData = [
                'name' => $this->name,
                'description' => $this->description,
            ];
        }   

        $location->update($updateData);

        session()->flash('info', 'Product Updated Successfully');

        $this->imageUrl = "";
        $this->clearForm();
        $this->loadLocations();    
        $this->isEdit = false;
        $this->dispatchBrowserEvent('updateLocation', $this->geoJson);        
    }

    public function deleteLocationById(){
        $location = Location::findOrFail($this->locationId);
        $location->delete();

        $this->imageUrl = "";
        $this->clearForm();
        $this->isEdit = false;
        $this->dispatchBrowserEvent('deleteLocation', $location->id);           
    }

    public function findLocationById($id){
        $location = Location::findOrFail($id);

        $this->locationId = $id;
        $this->long = $location->long;
        $this->lat = $location->lat;
        $this->name = $location->name;
        $this->description = $location->description;
        $this->isEdit = true;
        $this->imageUrl = $location->image;
    }

    public function render()
    {
        $this->loadLocations();
        return view('livewire.map-location');
    }

    public function previewImage(){
        if(!$this->isEdit) {
            $this->validate([
                'image' => 'image|max:2048'
            ]);
        }        
    }
}
