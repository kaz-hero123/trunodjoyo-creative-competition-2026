{{-- STUB: menunggu Nando/Dwi --}}
<h1>Login</h1>
@if($errors->any()) <div>{{ $errors->first() }}</div> @endif
<form method="POST" action="/login">
    @csrf
    <input type="email" name="email" value="student@demo.com">
    <input type="password" name="password" value="password">
    <button type="submit">Login</button>
</form>
