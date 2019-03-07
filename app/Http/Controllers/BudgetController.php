<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Budget_Contents;
use App\Budget_Relations;
use App\Budget_List;
use App\User;
use DB;

class BudgetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Bewirkt, dass die BudgetController-Funktionen nur von angemeldeten Benutzern aufgerufen werden können
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //liste aller Budgets, auf die man Zugriff hat
        $budgets = Budget_Relations::orderBy('id')
            ->where('user_id', auth()->user()->id)
            ->paginate(10);

        $budgetsCreator = Budget_Relations::orderBy('id')
            ->where('isCreator', 1)
            ->get();

        //zeigt die view b_index an mit der Variable $budgets
        return view('budgets.b_index')
            ->with('budgets', $budgets)
            ->with('budgetsCreator', $budgetsCreator);
    }

    public function viewQuittung($quittung_path){
        return view('budgets.b_quittung')
            ->with('quittung_path', $quittung_path);
    }

    /**
     * Show the form for creating a new resource.
     * 
     *  ----- nicht gebraucht
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Sucht nach allen Leitern, welche den gesuchten 
     * Pfadinamen haben und gibt sie an die Seite b_addLeiter weiter
     * 
     * @return \Illuminate\Http\Response
     */
    public function leiterSearch(Request $request){

        //bestimmt, ob die benötigten Felder ausgefüllt sind
        $this->validate($request, 
            [
                'pfadiname' => [
                    'required', 
                    'string', 
                    'max:255',
                ],
            ],
            [
                'budgetName.required' => "der Pfadiname muss ausgefüllt sein",
            ]
            );
        
        $leiterArray = User::orderBy('name')
            ->where('pfadiname', $request->input('pfadiname'))
            ->get();

        $budgetID = $request->input('bid');
        
        return view('budgets.b_addLeiter')
            ->with('leiterArray', $leiterArray)
            ->with('budgetID', $budgetID);
        
    }

    /**
     * fügt einen neuen Leiter der budget_relations Tabelle
     * hinzu und gibt ihm somit zugriff auf das spezifizierte Budget
     * 
     * @return \Illuminate\Http\Response
     */
    public function addLeiter(Request $request){

        //bestimmt, ob die benötigten Felder ausgefüllt sind
        $this->validate($request, 
            [
                'leiterSelect' => 'required', //ist erfordert, muss ein text sein und max 255 Zeichen sein
            ],
            [
                'budgetName.required' => "Wähle einen Leiter aus", //Individuelle Fehlernachricht
            ]
            );

        //Filtert den Budgetnamen des Budgets $id heraus
        $budgetName = Budget_List::where('id', $request->input('budgetID'))
            ->select('budget_name')
            ->pluck('budget_name');

        //Filtert alle Leiter heraus, welche schon Zugriff auf das Budget $id haben
        $existingLeiter = Budget_Relations::orderBy('id')
            ->where('bid', $request->input('budgetID'))
            ->pluck('user_id')
            ->toArray();
        
        //Fügt einen neuen Leiter zum Budget $id hinzu
        $newLeiter = new Budget_Relations;
        $newLeiter->bid = $request->input('budgetID');
        $newLeiter->user_id = $request->input('leiterSelect');
        $newLeiter->isCreator = 0;
        $newLeiter->budget_name = $budgetName[0];

        if(!in_array($request->input('leiterSelect'), $existingLeiter)){
            //wenn der Leiter nicht schon Zugriff auf das Budget hat, wird der neue Leiter auch gespeichert
            $newLeiter->save();

            //schickt den Benutzer zurück zu der Seite des Budgets mit einer Erfolgsnachricht 
            return redirect('budgets/'.$request->input('budgetID'))
                ->with('success', 'Leiter "'.$newLeiter->user->name.'" erfolgreich hinzugefügt.');
        } else{
            //wenn der neue Leiter schon zugriff auf das Budget hat, wird nicht gespeichert und ein Error zurückgegeben
            
            //schickt den Benutzer zurück zu der Seite des Budgets mit einer Fehlersnachricht
            return redirect('budgets/'.$request->input('budgetID'))
                ->with('error', 'Leiter "'.$newLeiter->user->name.'" wurde bereits hinzugefügt.');
        }

        

    }

    /**
     * Fügt eine neues Budget den Tabellen
     * budget_list und budget_relations hizu
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createBudget(Request $request){

        //bestimmt, ob die benötigten Felder ausgefüllt sind
        $this->validate($request, 
            [
                'budgetName' => [
                    'required', 
                    'string', 
                    'max:255',
                ], 
            ],
            [
                'budgetName.required' => "der Budgetname muss ausgefüllt sein", 
            ]
            );

        //Kreiert einen neuen Eintrag in budget_list und speichert ihn
        $budget_list = new Budget_list;
        $budget_list->budget_name = $request->input('budgetName');
        $budget_list->save();

        //Kreiert einen neuen Eintrag in budget_relations und speichert ihn
        $budget_relation = new Budget_Relations;
        $budget_relation->bid = $budget_list->id;
        $budget_relation->user_id = auth()->user()->id;
        $budget_relation->isCreator = 1;
        $budget_relation->budget_name = $budget_list->budget_name;
        $budget_relation->save();

        //schickt den Benutzer zum 'budgets'-Index zurück und schickt eine Erfolgsnachricht mit 
        return redirect('budgets')->with('success', 'Budget "'.$budget_list->budget_name.'" erfolgreich erstellt');
    }

    /**
     * speichert neue Ausgaben zum Budget $id und Budgetposten $bid hinzu
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //bestimmt, ob die benötigten Felder ausgefüllt sind
        $this->validate($request, 
            [
                'budgetPosten' => [                 //ist erfordert, muss ein text sein und max 255 Zeichen sein
                    'required',
                    'string',
                    'max:255'
                ], 
                'content' => [                      //ist erfordert und muss eine Nummer sein
                    'required', 
                    'numeric',
                ], 
                'quittung_image' => [
                    'image',
                    'nullable',
                ]
            ],
            [
                'budgetPosten.required' => "Budgetposten muss ausgewählt sein.",
                'content.required' => "Ausgaben müssen ausgefüllt sein.",
                'content.numeric' => "Gib eine Gültige Zahl ein.",
                'quittung_image.image' => "Muss ein Bild sein."
            ]
            );

        //Handle File upload
        if($request->hasFile('quittung_image')){
            //get Filename with the extension
            $filenameWithExt = $request->file('quittung_image')->getClientOriginalName();
            //get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just extension
            $extension = $request->file('quittung_image')->getClientOriginalExtension();
            //Filename to store => filename_(time).jpg
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('quittung_image')->storeAs('public/quittung_image', $fileNameToStore);
        } else{
            //Sets image to filler
            $fileNameToStore = 'noimage.jpg';
        }
        
        //kreiert einen neuen eintrag in budget_contents und speichert ihn
        $budget = new Budget_Contents;
        $budget->user_id = auth()->user()->id;
        $budget->bid = $request->input('bid');
        $budget->budgetPosten = $request->input('budgetPosten');
        $budget->content = $request->input('content');
        $budget->quittung_image = $fileNameToStore;
        //falls etwas in das 'notes' feld eingetragen wird, so wird es in die Tabelle eingefügt
        //(so gelöst, damit nicht 'NULL' eingefügt wird)
        if($request->input('notes')){
            $budget->notes = $request->input('notes');
        }
        $budget->budgeted = 0;
        $budget->save();

        //schickt den Benutzer zur Budgetseite zurück und schickt eine Erfolgsnachricht mit 
        return redirect('budgets/'.$budget->bid)->with('success', 'Budgetposten "'.$budget->budgetPosten.'" erfolgreich hinzugefügt');
        
        
    }

    /**
     * Fügt neue Budgetposten zum Budget $id hinzu
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addBudgetPosten(Request $request){

        //bestimmt, ob die benötigten Felder ausgefüllt sind
        $this->validate($request, 
            [
                'budgetPosten' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'budgetiert' => [
                    'required',
                    'numeric',
                ],
                'bid' => [
                    'required', 
                    'integer',
                ],
            ],
            [
                'budgetiert.required' => "Das Feld 'Budgetiert' muss ausgefüllt sein",
                'budgetPosten.required' => "Das Feld 'neuer Budgetposten' muss ausgefüllt sein"
            ]
            );

        //Kreiert einen neuen Eintrag in budget_contents mit dem neuen Budgetposten mit einigen Standartwerten und speicher ihn
        $budget = new Budget_Contents;
        $budget->user_id = auth()->user()->id;
        $budget->budgeted = $request->input('budgetiert');
        $budget->budgetPosten = $request->input('budgetPosten');
        $budget->bid = $request->input('bid');
        $budget->content = 0;
        $budget->notes = 'Budgetposten erstellt';
        $budget->save();

        //schickt den Benutzer zur Budgetseite zurück und schickt eine Erfolgsnachricht mit 
        return redirect('budgets/'.$budget->bid)->with('success', 'Budgetposten "'.$budget->budgetPosten.'" erfolgreich hinzugefügt');
    }

    public function deleteBudgetPosten($id, $budgetPosten){

        //Filtert den alle Einträge zu demBudgetposten heraus, den der Benutzer löschen will
        $toDelete = Budget_Contents::where('bid', $id)
            ->where('budgetPosten', $budgetPosten)
            ->get();
        
        //Iteriert durch alle Einträge durch und löscht jeden einzeln
        foreach($toDelete as $deleteItem){
            $deleteItem->delete();
        }

        //schickt den Benutzer zur Budgetseite zurück und schickt eine Erfolgsnachricht mit 
        return redirect('budgets/'.$id)->with('success', 'Budgetposten "'.$budgetPosten.'" erfolgreich gelöscht');
    }

    /**
     * Zeigt das ausgewählte Budget $id an
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Filtert den Budgetnamen aus budget_list
        $budgetName = Budget_List::where('id', $id)
            ->select('budget_name')
            ->first();

        //Filtert alle Daten zum Budget aus budget_contents ohne sie zu gruppieren o.ä.
        $budgetData = Budget_Contents::orderBy('id')
            ->where('bid', $id)
            ->get();

        //Eine Liste aller Budgetposten für ein Formular auf der Budgetseite
        $budgetPostenList = Budget_Contents::orderBy('id')
            ->where('bid', $id)
            ->groupBy('budgetPosten')
            ->pluck('budgetPosten');

        //Filtert die Summe der Ausgaben der Budgetposten und gruppiert die Daten zu den Budgetposten
        $budget = DB::table('budget_contents')
            ->select(DB::raw('SUM(content) as content_sum, budgetPosten'))
            ->where('bid', $id)
            ->groupBy('budgetPosten')
            ->get();

        //Eine Liste mit aller Benutzern, die Zugriff auf das Budget haben, um unbefugten Zutritt zu vermeiden
        $allowedUsers = Budget_Relations::orderBy('id')
            ->where('bid', $id)
            ->pluck('user_id')
            ->toArray();
        
        //schickt den Benutzer zur Budgetseite mit den folgenden Variablen
        return view('budgets.b_show')
            ->with('budget', $budget)
            ->with('budgetData', $budgetData)
            ->with('budgetPostenList', $budgetPostenList)
            ->with('budgetID', $id)
            ->with('allowed', $allowedUsers)
            ->with('budgetName', $budgetName);
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * ----- nicht gebraucht
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     * 
     *  ----- nicht gebraucht
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     * 
     *  ----- nicht gebraucht
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
