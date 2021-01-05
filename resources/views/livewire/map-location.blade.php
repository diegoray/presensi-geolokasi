<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Lokasi Presensi
                    </div>
                    <div class="card-body">
                        <div wire:ignore id='map' style='width: 100%; height: 60vh;'></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Form
                    </div>
                    <div class="card-body">
                        <form @if($isEdit)
                            wire:submit.prevent="updateLocation"
                            @else
                            wire:submit.prevent="saveLocation"
                            @endif>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="longtitude">Longtitude</label>
                                        <input wire:model="long" {{$isEdit ? 'disabled' : null}} type="text" name="longtitude" id="longtitude" class="form-control">
                                        @error('long')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="latitude">Latitude</label>
                                        <input wire:model="lat" {{$isEdit ? 'disabled' : null}} type="text" name="latitude" id="latitude" class="form-control">
                                        @error('lat')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input wire:model="name" type="text" name="name" id="name" class="form-control">
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Deskripsi</label>
                                        <textarea wire:model="description" name="description" id="description" class="form-control"></textarea>
                                        @error('description')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <div class="custom-file">
                                            <input wire:model="image" type="file" name="image" id="customFile" class="custom-file-input">
                                            <label class="custom-file-label" for="customFile">Choose File</label>
                                            @error('image')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        @if ($image)
                                            <img src="{{$image->temporaryUrl()}}" class="img-fluid" alt="Preview Image">
                                        @endif
                                        @if($imageUrl && !$image)                                
                                            <img src="{{asset('/storage/images/'.$imageUrl)}}" class="img-fluid" alt="Preview Image">
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn active btn-{{$isEdit ? 'success text-white' : 'primary'}} btn-block">{{$isEdit ? 'Update Location' : 'Submit Location'}}</button>
                                        @if($isEdit)
                                        <button wire:click="deleteLocationById" type="button" class="btn btn-danger active btn-block">Delete Location</button>
                                        @endif
                                    </div>


                                </div>

                            </div>
                        </form>
                        <button id="cekLokasi" class="btn btn-warning btn-block">Cek Lokasi</button>
                        <button wire:click="savePresensi" id="triggerPresensi" class="btn btn-success btn-block">Absen</button>
                        {{-- <form wire:submit.prevent="savePresensi">
                            <input wire:model="longD" type="text" name="longtitudeDb" id="longtitudeDb">
                            <input wire:model="latD" type="text" name="longtitudeDb" id="longtitudeDb">
                            <button type="submit" id="triggerPresensi" class="btn btn-success btn-block">Absen</button>

                        </form> --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
</div>

<div id="info" style="display:none"></div>


@push('script')
    <script>
        document.addEventListener('livewire:load', () => {
            const defaultLocation = [115.16508060343324, -8.818462592874965]
            const coordinateInfo = document.getElementById('info')
            

            mapboxgl.accessToken = '{{env("MAPBOX_KEY")}}';
            var map = new mapboxgl.Map({
                container: 'map',
                center: defaultLocation,
                zoom: 12,
                style: 'mapbox://styles/mapbox/streets-v11'
            });

            const loadLocations = (geoJson) => {
                geoJson.features.forEach((location) => {
                    const {geometry, properties} = location
                    const {iconSize, locationId, title, image, description} = properties

                    let markerElement = document.createElement('div')
                    markerElement.className = 'marker' + locationId
                    markerElement.id = locationId
                    markerElement.style.backgroundImage = 'url(https://seeklogo.com/images/M/mapbox-logo-D6FDDD219C-seeklogo.com.png)'
                    markerElement.style.backgroundSize = 'cover'
                    markerElement.style.width = '30px'
                    markerElement.style.height = '30px'

                    const imageStorage = '{{asset("/storage/images")}}' + '/' + image

                    const content = `
                    <div style="overflow-y: auto; max-height: 400px; width:100%">
                        <table class="table table-sm mt-2">
                            <tbody>
                                <tr>
                                    <td>Title</td>
                                    <td>${title}</td>
                                </tr>
                                <tr>
                                    <td>Picture</td>
                                    <td><img src="${imageStorage}" loading="lazy", class="img-fluid"></td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td>${description}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>`

                    const popUp = new mapboxgl.Popup({
                        offset: 25
                    }).setHTML(content).setMaxWidth("400px")

                    markerElement.addEventListener('click', (e) => {   
                        const locationId = e.toElement.id                  
                        @this.findLocationById(locationId)
                    })

                    new mapboxgl.Marker(markerElement)
                    .setLngLat(geometry.coordinates)
                    .setPopup(popUp)
                    .addTo(map)
                })
            }

            loadLocations({!! $geoJson !!})

            window.addEventListener('locationAdded', (e) => {
                swal({
                    title: "Location Added!",
                    text: "Your location has been save sucessfully!",
                    icon: "success",
                    button: "Ok",
                }).then((value) => {
                    loadLocations(JSON.parse(e.detail))
                });
            })

            window.addEventListener('updateLocation', (e) => {       
                swal({
                    title: "Location Update!",
                    text: "Your location updated sucessfully!",
                    icon: "success",
                    button: "Ok",
                }).then((value) => {
                    loadLocations(JSON.parse(e.detail))
                    $('.mapboxgl-popup').remove();
                });
            })

            window.addEventListener('deleteLocation', (e) => { 
                swal({
                    title: "Location Delete!",
                    text: "Your location deleted sucessfully!",
                    icon: "success",
                    button: "Ok",
                }).then((value) => {
                    $('.marker' + e.detail).remove();
                    $('.mapboxgl-popup').remove();
                });
            })

            map.addControl(new mapboxgl.NavigationControl())

            // map.addControl(new mapboxgl.GeolocateControl({
            //     positionOptions: {
            //     enableHighAccuracy: true
            //     },
            //     trackUserLocation: true
            // }))

            // Initialize the geolocate control.
            var geolocate = new mapboxgl.GeolocateControl({
            positionOptions: {
            enableHighAccuracy: true
            },
            trackUserLocation: true
            });
            // Add the control to the map.
            map.addControl(geolocate);
            const cekLokasi = document.getElementById("cekLokasi")
            cekLokasi.onclick = function() {
                geolocate.trigger();
            }

            geolocate.on('geolocate', function(position) {
                console.log(position.coords.latitude)
                console.log(position.coords.longitude)
                const lat = position.coords.latitude
                const long = position.coords.longitude
                window.livewire.emit('set:latitude-longitude', lat, long)
            });
            

            map.on('click', (e) => {
                // const longtitude = e.lngLat.lng
                // const lattitude = e.lngLat.lat

                // @this.long = longtitude
                // @this.lat = lattitude
                if(@this.isEdit){
                    // const longtitude = e.lngLat.lng
                    // const lattitude = e.lngLat.lat

                    @this.isEdit = false;
                    @this.long = e.lngLat.lng
                    @this.lat = e.lngLat.lat
                    @this.name = "";
                    @this.description = "";
                    @this.imageUrl = "";

                }else{
                    coordinateInfo.innerHTML = JSON.stringify(e.point) + '<br />' + JSON.stringify(e.lngLat.wrap());
                    @this.long = e.lngLat.lng;
                    @this.lat = e.lngLat.lat;
                }
            })
        })
        
    </script>
@endpush
