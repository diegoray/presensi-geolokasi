<?php

namespace App\Http\Livewire;

use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Location;
use App\Models\Test;
use Illuminate\Support\Facades\Storage;
use Amp\Loop;
use App\Models\User;

class MapLocation extends Component
{
    use WithFileUploads;

    public $count = 5;
    public $locationId, $long, $lat, $name, $description, $image;
    public $geoJson;
    public $pegawai;
    public $imageUrl; 
    public $isEdit = false;
    
    // simpan ke lokasi ke db
    public $longDb, $latDb;

    public function hitungJarak($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);
        
            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

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
        $this->pegawai = '';
    }

    public function saveLocation() {
        $this->validate([
            'long' => 'required',
            'lat' => 'required',
            'name' => 'required',
            'description' => 'required',
            'pegawai' => 'required|array',
            'image' => 'image|max:2048|required',
        ]);

        $imageName = md5($this->image.microtime()).'.'.$this->image->extension();

        Storage::putFileAs(
            'public/images',
            $this->image,
            $imageName
        );

        // Location::create([
        //     'long' => $this->long,
        //     'lat' => $this->lat,
        //     'name' => $this->name,
        //     'description' => $this->description,
        //     'image' => $imageName,
        // ]);

        $loc = Location::create([
            'long' => $this->long,
            'lat' => $this->lat,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $imageName,
        ]);

        $loc->users()->sync($this->pegawai);

        $this->loadLocations();
        $this->clearForm();
        $this->dispatchBrowserEvent('locationAdded', $this->geoJson);
    }
    
    public function updateLocation(){  
        $this->validate([
            'long' => 'required',
            'lat' => 'required',
            'name' => 'required',
            // 'pegawai' => 'required|array',
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
        $location->users()->detach();
        $location->delete();

        $this->imageUrl = "";
        $this->clearForm();
        $this->isEdit = false;
        $this->dispatchBrowserEvent('deleteLocation', $location->id);           
    }

    public function findLocationById($id){
        $location = Location::findOrFail($id);
        // dd($location->users->pluck('id'));


        $this->locationId = $id;
        $this->long = $location->long;
        $this->lat = $location->lat;
        $this->name = $location->name;
        $this->description = $location->description;
        $this->isEdit = true;
        $this->imageUrl = $location->image;
        // dd($this->isEdit);
    }

    public function render()
    {
        $this->loadLocations();
        return view('livewire.map-location', [
            'pegawais' => User::get(),
            'lokasis' => Location::get(),
        ]);
    }

    public function previewImage(){
        if(!$this->isEdit) {
            $this->validate([
                'image' => 'image|max:2048'
            ]);
        }        
    }
    
    public function savePresensi()
    {
        $id = 6;
        $lokasiPresensi = Location::where('id', $id)->first();
        // dd($lokasiPresensi->long);
        // dd($lokasiPresensi->lat);
        $aa = $this->hitungJarak($lokasiPresensi->long, $lokasiPresensi->lat, $this->longDb, $this->latDb, "K");
        dd($aa);
        // Test::create([
        //     'longDb' => $this->longDb,
        //     'latDb' => $this->latDb,
        // ]);
        // dd($myWatcherId);
        
        // hitung distance disini
        
        // Loop::run(function () {
            // $myWatcherId = [];
                // $myWatcherId = [
                //     'longDb' => $this->longDb,
                //     'latDb' => $this->latDb,
                // ];
            // Loop::repeat(1000, function () use ($myWatcherId) {
            // });

            // Loop::delay(10000, function () use ($myWatcherId) {
            //     dd($myWatcherId);
            // });

            // if(distance >= 0.5){

            // } else {

            // }

            // dd($myWatcherId);
            // Loop::delay(10000, function () use ($myWatcherId) {
            //     Test::create($myWatcherId);
                // Loop::cancel($myWatcherId);
        //     });
        // });
        
    }
    // Test::create($savePresensi);
    
    protected $listeners = [
        'set:latitude-longitude' => 'setLatitudeLongitude'
    ];

    public function setLatitudeLongitude($lat, $long) 
    {
        $this->latDb = $lat;
        $this->longDb = $long;
    }
}
