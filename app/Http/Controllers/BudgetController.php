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
        //Tells the website, that the Budget-functions can only be used by a registered user
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $budgets = Budget_Relations::orderBy('id')
            ->where('user_id', auth()->user()->id)
            ->paginate(10);

        return view('budgets.b_index')->with('budgets', $budgets);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    public function leiterSearch(Request $request){

        //Tests if the required fields are filled
        $this->validate($request, 
            [
                'pfadiname' => 'required|string|max:255',
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

    public function addLeiter(Request $request){

        //Tests if the required fields are filled
        $this->validate($request, 
            [
                'leiterSelect' => 'required',
            ],
            [
                'budgetName.required' => "Wähle einen Leiter aus",
            ]
            );

        $budgetName = Budget_List::where('id', $request->input('budgetID'))
            ->select('budget_name')
            ->pluck('budget_name');

        $existingLeiter = Budget_Relations::orderBy('id')
            ->where('bid', $request->input('budgetID'))
            ->pluck('user_id')
            ->toArray();
        
        $newLeiter = new Budget_Relations;
        $newLeiter->bid = $request->input('budgetID');
        $newLeiter->user_id = $request->input('leiterSelect');
        $newLeiter->isCreator = 0;
        $newLeiter->budget_name = $budgetName[0];

        if(!in_array($request->input('leiterSelect'), $existingLeiter)){
            $newLeiter->save();

            return redirect('budgets/'.$request->input('budgetID'))
                ->with('success', 'Leiter "'.$newLeiter->user->name.'" erfolgreich hinzugefügt.');
        } else{
            return redirect('budgets/'.$request->input('budgetID'))
                ->with('error', 'Leiter "'.$newLeiter->user->name.'" wurde bereits hinzugefügt.');
        }

        

    }

    /**
     * Creates a new budget in budget_list and budget_relations
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createBudget(Request $request){

        //Tests if the required fields are filled
        $this->validate($request, 
            [
                'budgetName' => 'required|string|max:255',
            ],
            [
                'budgetName.required' => "der Budgetname muss ausgefüllt sein",
            ]
            );

        $budget_list = new Budget_list;
        $budget_list->budget_name = $request->input('budgetName');
        $budget_list->save();

        $budget_relation = new Budget_Relations;
        $budget_relation->bid = $budget_list->id;
        $budget_relation->user_id = auth()->user()->id;
        $budget_relation->isCreator = 0;
        $budget_relation->budget_name = $budget_list->budget_name;
        $budget_relation->save();


        return redirect('budgets')->with('success', 'Budget "'.$budget_list->budget_name.'" erfolgreich erstellt');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Tests if the required fields are filled
        $this->validate($request, 
            [
                'budgetPosten' => 'required|string|max:255',
                'content' => 'required|integer',
                'bid' => 'required|integer'
            ],
            [
                'budgetPosten.required' => "Budgetposten muss ausgewählt sein",
                'content.required' => "Ausgaben müssen ausgefüllt sein"
            ]
            );

        $budget = new Budget_Contents;
        $budget->user_id = auth()->user()->id;
        $budget->bid = $request->input('bid');
        $budget->budgetPosten = $request->input('budgetPosten');
        $budget->content = $request->input('content');
        if($request->input('notes')){
            $budget->notes = $request->input('notes');
        }
        $budget->budgeted = 0;
        $budget->save();

        return redirect('budgets/'.$budget->bid)->with('success', 'Budgetposten "'.$budget->budgetPosten.'" erfolgreich hinzugefügt');
        
        
    }

    /**
     * Adds new "Budgetposten" to an existing budget
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addBudgetPosten(Request $request){

        //Tests if the required fields are filled
        $this->validate($request, 
            [
                'budgetPosten' => 'required|string|max:255',
                'budgetiert' => 'required|integer',
                'bid' => 'required|integer'
            ],
            [
                'budgetiert.required' => "Das Feld 'Budgetiert' muss ausgefüllt sein",
                'budgetPosten.required' => "Das Feld 'neuer Budgetposten' muss ausgefüllt sein"
            ]
            );

        $budget = new Budget_Contents;
        $budget->user_id = auth()->user()->id;
        $budget->budgeted = $request->input('budgetiert');
        $budget->budgetPosten = $request->input('budgetPosten');
        $budget->bid = $request->input('bid');
        $budget->content = 0;
        $budget->notes = 'Budgetposten erstellt';
        $budget->save();

        return redirect('budgets/'.$budget->bid)->with('success', 'Budgetposten "'.$budget->budgetPosten.'" erfolgreich hinzugefügt');
    }

    public function deleteBudgetPosten($id, $budgetPosten){
        $toDelete = Budget_Contents::where('bid', $id)
            ->where('budgetPosten', $budgetPosten)
            ->get();
        
        foreach($toDelete as $deleteItem){
            $deleteItem->delete();
        }

        return redirect('budgets/'.$id)->with('success', 'Budgetposten "'.$budgetPosten.'" erfolgreich gelöscht');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $budgetName = Budget_List::where('id', $id)
            ->select('budget_name')
            ->first();

        $budgetData = Budget_Contents::orderBy('id')
            ->where('bid', $id)
            ->get();

        $budgetPostenList = Budget_Contents::orderBy('id')
            ->where('bid', $id)
            ->groupBy('budgetPosten')
            ->pluck('budgetPosten');

        $budget = DB::table('budget_contents')
            ->select(DB::raw('SUM(content) as content_sum, budgetPosten'))
            ->where('bid', $id)
            ->groupBy('budgetPosten')
            ->get();

        $allowedUsers = Budget_Relations::orderBy('id')
            ->where('bid', $id)
            ->pluck('user_id')
            ->toArray();
        
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
