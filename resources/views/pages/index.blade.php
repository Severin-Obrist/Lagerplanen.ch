@extends('layouts.app') 

@section('content')
    <!-- Splashscreen, wennman die Webseite aufruft -->
    <div class="jumbotron text-center"> <h1>Willkommen</h1> 
        <p>Willkommen auf Lagerplanen.ch, melde dich an oder erstelle ein Benutzerkonto.</p> 
        <p> 
            <a class="btn btn-primary btn-lg" href="/login" role="button">Anmelden</a> 
            <a class="btn btn-success btn-lg" href="/register" role="button">Registrieren</a> 
        </p> 
    </div>
@endsection
