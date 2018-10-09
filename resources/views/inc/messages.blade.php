<!-- Fragt ab, ob Nachrichten auf der Seite Vorhanden sind -->
@if(count($errors) >0 )
    @foreach($errors->all() as $error)
        <div class="alert alert-warning">
            {{$error}}
        </div>
    @endforeach
@endif

<!-- Ist eine 'Success' Nachricht vorhanden, wird sie grün angezeigt-->
@if(session('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
@endif

<!-- Ist eine 'Success' Nachricht vorhanden, wird sie gelb angezeigt-->
@if(session('error'))
    <div class="alert alert-warning">
        {{session('error')}}
    </div>
@endif
