{{-- STUB: menunggu Nando/Dwi --}}
<h1>Register</h1>
@if($errors->any()) <div>{{ $errors->first() }}</div> @endif
<form method="POST" action="/register">
    @csrf
    <input type="email" name="email">
    <input type="password" name="password">
    <input type="number" name="semester" value="1">
    <input type="text" name="faculty" value="FT">
    <input type="text" name="major" value="IF">
    <button type="submit">Register</button>
</form>
