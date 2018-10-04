@extends('layouts.app')

<!-- Dashboard/Home -->

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Du bist angemeldet!
                    
                    <!-- Shows all your own budgets-->
                    @if(count($budgets) > 0)
                        <table class="table table-striped mt-2">
                            @foreach($budgets as $budget)
                                <tr>
                                    <td>
                                        <b>{{$budget->budget_name}}</b>
                                    </td>
                                    <td>
                                        <a href="/budgets/{{$budget->bid}}" class="btn btn-outline-primary">öffnen</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <p class="mt-2">Keine Budgets vorhanden</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
