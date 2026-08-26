{{-- STUB: menunggu Nando/Dwi --}}
<h1>Results</h1>
<p>Total Score: {{ $assessment->total_resilience_score }}</p>

<h2>Matches</h2>
@foreach($matches as $m)
    <p>{{ $m->resource->title }} - {{ $m->reason }}</p>
@endforeach

<h2>AI Advisor</h2>
@foreach($chatHistory as $chat)
    <p><b>{{ $chat['role'] }}:</b> {{ $chat['message'] }}</p>
@endforeach

<form method="POST" action="/results/{{ $assessment->id }}/chat">
    @csrf
    <input type="text" name="message">
    <button>Send</button>
</form>
