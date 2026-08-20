{{-- STUB: menunggu Nando/Dwi --}}
<h1>Dashboard</h1>
@if(session('error')) <div>{{ session('error') }}</div> @endif
<p>Latest Assessment: {{ $latestAssessment ? $latestAssessment->total_resilience_score : 'None' }}</p>
<p>Next check in: {{ $nextCheckInAt }}</p>
<a href="/check-in">Mulai Check-in</a>

<form method="POST" action="/logout">@csrf<button>Logout</button></form>
