@extends('layouts.app')

@section('content')
    <?php 
    $budgetArray = [];
    ?>
    @if(count($budget) > 0)
        <table class='table table-striped mt-2'>
            <tr>
                <th>Budgetposten</th>
                <th>Eintrag</th>
            </tr>
            @foreach($budget as $posten => $wert) <!-- iteriert durch die verschiedenen budgetposten in einem budget -->
                <?php 
                echo $wert->budgetPosten.": ".$wert->content; 
                echo "</br>";
                echo $wert;
                echo "</br>";
                echo $posten;
                echo "</br>";
                if(array_key_exists($wert->budgetPosten, $budgetArray)){
                    echo $wert->budgetPosten." ist im array";
                }else{
                    echo $wert->budgetposten." ist nicht im array";
                    array_push($budgetArray, $wert);
                }
                /*
                foreach($budgetArray as $eintrag){
                    echo $eintrag;
                }*/
                echo "</br>";
                ?>
            @endforeach
                
        </table>
    @else
        <p>Keine Einträge im Buget</p>
    @endif
@endsection