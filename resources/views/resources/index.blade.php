{{-- STUB: menunggu Nando/Dwi --}}
<h1>Resources</h1>
@foreach($resources as $r)
    <p>{{ $r->title }} - {{ $r->provider_name }}</p>
@endforeach
