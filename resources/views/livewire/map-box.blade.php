<div>
    <div id='map' style='width: 400px; height: 300px;'></div>
</div>
@push('scripts')
    <script>
        mapboxgl.accessToken = 'pk.eyJ1Ijoid2lrdWd3IiwiYSI6ImNraW13eGwzZzAxN3oyeXBjZzR6NzFwcGgifQ.P2Ix-OUqTOO9yuvwjmz0tw';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11'
        });
    </script>
@endpush
