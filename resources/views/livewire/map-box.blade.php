<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    MapBox
                </div>
                <div class="card-body">
                    <div wire:ignore id='map' style='width: 100%; height: 75vh;'></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-header bg-dark text-white">
                Form
            </div>
            <div class="card-body">
                <form 
                    @if ($isEdit)
                        wire:submit.prevent="updateLocation"
                    @else
                        wire:submit.prevent="saveLocation"
                    @endif
                >
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">longtitude</label>
                                <input wire:model="longtitude" type="text" class="form-control">
                                @error('longtitude')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">lattitude</label>
                                <input wire:model="lattitude" type="text" class="form-control">
                                @error('lattitude')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Title</label>
                        <input wire:model="title" type="text" class="form-control">
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea wire:model="description" class="form-control"></textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Picture</label>
                        <div class="custom-file">
                            <input wire:model="image" type="file" class="custom-file-input" id="customFileInput">
                            <label for="" class="custom-file-label">Choose file</label>
                        </div>
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="{{ $image }}" class="img-fluid">
                        @endif
                        @if ($imageUrl && !$image)
                            <img src="{{ asset('storage/images/' .$imageUrl) }}" alt="{{ $image }}" class="img-fluid">                         
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-dark text-white btn-block">{{$isEdit ? 'Update' : 'Submit'}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>
@push('scripts')
    <script>
        document.addEventListener('livewire:load', () => {
            const defaultLocation = [106.69823134523938, -6.357728199385079];

            mapboxgl.accessToken = '{{env('MAPBOX_KEY')}}';
            var map = new mapboxgl.Map({
                container: 'map',
                center: defaultLocation,
                zoom: 11.15,
            });

            const loadLocations = (geoJson) => {
                geoJson.features.forEach((location) => {
                    const {geometry, properties} = location;
                    const {iconSize, locationId, title, image, description} = properties;

                    let markerElement = document.createElement('div');
                    markerElement.className = 'marker' + locationId;
                    markerElement.id = locationId;
                    markerElement.style.backgroundImage = 'url(https://docs.mapbox.com/help/demos/custom-markers-gl-js/mapbox-icon.png)';
                    markerElement.style.backgroundSize = 'cover';
                    markerElement.style.width = '50px';
                    markerElement.style.height = '50px';

                    const imageStorage = '{{asset('/storage/images')}}' + '/' + image

                    const content = `
                    <div style="overflow-y:auto; max-height:400px; width:100%">
                        <table class="table table-sm mt-2">
                            <tbody>
                                <tr>
                                    <td>Title</td>
                                    <td>${title}</td>
                                </tr>
                                <tr>
                                    <td>Picture</td>
                                    <td><img src="${imageStorage}" alt="image-${title}" loading="lazy" class="img-fluid"></td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td>${description}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    `;

                    markerElement.addEventListener('click', (e) => {
                        const locationId = e.toElement.id;
                        @this.findLocationById(locationId);
                    });

                     const popUp = new mapboxgl.Popup({
                         offset:25
                     }).setHTML(content).setMaxWidth("400px");

                    new mapboxgl.Marker(markerElement)
                    .setLngLat(geometry.coordinates)
                    .setPopup(popUp)
                    .addTo(map);
                });
            }

            loadLocations({!! $geoJson !!});

            window.addEventListener('locationAdded', (e) => {
                loadLocations(JSON.parse(e.detail));
            });

            window.addEventListener('updateLocation', (e) => {
                loadLocations(JSON.parse(e.detail));
                $('.mapboxgl-popup').remove();
            });

            const style = 'dark-v10';
            map.setStyle(`mapbox://styles/mapbox/${style}`);

            map.addControl(new mapboxgl.NavigationControl());

            map.on('click', (e) => {
                const longtitude = e.lngLat.lng;
                const latitude = e.lngLat.lat;

                @this.longtitude = longtitude;
                @this.lattitude = latitude;
            });
        });
    </script>
@endpush
