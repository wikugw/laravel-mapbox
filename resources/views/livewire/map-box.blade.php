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
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">longtitude</label>
                            <input wire:model="longtitude" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">lattitude</label>
                            <input wire:model="lattitude" type="text" class="form-control">
                        </div>
                    </div>
                </div>
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
                                    <td><img src="${image}" alt="image-${title}" loading="lazy" class="img-fluid"></td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td>${description}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    `;

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
