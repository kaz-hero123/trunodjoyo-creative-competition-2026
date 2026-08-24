{{-- STUB: menunggu Nando/Dwi --}}
<h1>Check In</h1>
@if($errors->any()) <div>{{ collect($errors->all())->implode(', ') }}</div> @endif
<form method="POST" action="/check-in">
    @csrf
    @foreach($questionsByDimension as $dim => $questions)
        <h2>{{ $dim }}</h2>
        @foreach($questions as $key => $q)
            <label>{{ $q['statement'] }}</label>
            <input type="number" name="answers[{{ $key }}]" value="3" min="1" max="5">
        @endforeach
    @endforeach
    <button type="submit">Submit</button>
</form>
