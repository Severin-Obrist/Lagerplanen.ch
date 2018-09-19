<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Budget_Contents;
use App\Budget_Relations;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Tests if the required fields are filled
        $this->validate($request, [
            'budgetPosten' => 'required',
            'content' => 'required',
            'bid' => 'required'
        ]);

        $budget = new Budget_Contents;
        $budget->user_id = auth()->user()->id;
        $budget->bid = $request->input('bid');
        $budget->budgetPosten = $request->input('budgetPosten');
        $budget->content = $request->input('content');
        $budget->notes = $request->input('notes');
        $budget->budgeted = 0;
        $budget->save();

        return redirect('budgets/'.$request->input('bid'))->with('success', 'Erfolgreich');
        
        
    }

    public function addBudgetPosten(Request $request){
        $budget = new Budget_Contents;
        $budget->user_id = auth()->user()->id;
        $budget->budgeted = $request->input('budgetiert');
        $budget->budgetPosten = $request->input('budgetPosten');
        $budget->bid = $request->input('bid');
        $budget->content = 0;
        $budget->notes = ' ';
        $budget->save();

        return redirect('budgets/'.$request->input('bid'))->with('success', 'Erfolgreich');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $budgetData = Budget_Contents::orderBy('id')
            ->where('bid', $id)
            ->get();

        $budgetID = DB::table('budget_contents')
            ->select('bid')
            ->where('bid', $id)
            ->pluck('bid')
            ->first();

        $budgetPostenList = Budget_Contents::orderBy('id')
            ->where('bid', $id)
            ->groupBy('budgetPosten')
            ->pluck('budgetPosten');

        $budget = DB::table('budget_contents')
            ->select(DB::raw('SUM(content) as content_sum, budgetPosten'))
            ->where('bid', $id)
            ->groupBy('budgetPosten')
            ->get();
        
        return view('budgets.b_show')
            ->with('budget', $budget)
            ->with('budgetData', $budgetData)
            ->with('budgetPostenList', $budgetPostenList)
            ->with('budgetID', $budgetID);
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
        /*
        $budget = new Budget_Contents;
        $budget->budgetiert = $request->input('budgetiert');
        $budget->budgetPosten = $request->input('budgetPosten');
        */
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
