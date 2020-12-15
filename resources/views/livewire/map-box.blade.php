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
            const defaultLocation = [112.53364103048705, -7.881279579072043];

            mapboxgl.accessToken = '{{env('MAPBOX_KEY')}}';
            var map = new mapboxgl.Map({
                container: 'map',
                center: defaultLocation,
                zoom: 11.15,
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
