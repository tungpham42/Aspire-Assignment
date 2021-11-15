<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use Auth;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->name == 'admin') {
            return Loan::orderBy('created_at', 'asc')->get();  //returns values in ascending order
        } else {
            return 'Admin only';
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [ //inputs are not empty or null
            'amount' => 'required|integer|gt:50000',
            'term' => 'required|integer|gte:1|lte:52',
        ]);

        $loan = new Loan;
        $loan->amount = $request->input('amount'); //retrieving user inputs
        $loan->term = $request->input('term');  //retrieving user inputs
        $loan->save(); //storing values as an object
        return $loan; //returns the stored value if the operation was successful.
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Loan::findorFail($id); //searches for the object in the database using its id and returns it.
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function repay($id)
    {
        $loan = Loan::findorFail($id);
        $loan->repayment = $loan->amount / $loan->term;
        $loan->save();
        return number_format($loan->repayment, 2) . ' VND';
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
        $this->validate($request, [ //inputs are not empty or null
            'amount' => 'required|integer|gt:50000',
            'term' => 'required|integer|gte:1|lte:52',
        ]);

        $loan = Loan::findorFail($id); // uses the id to search values that need to be updated.
        $loan->amount = $request->input('amount'); //retrieving user inputs
        $loan->term = $request->input('term');  //retrieving user inputs
        $loan->save(); //storing values as an object
        return $loan; //returns the stored value if the operation was successful.
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loan = Loan::findorFail($id); //searching for object in database using ID
        if($loan->delete()){ //deletes the object
            return 'Deleted successfully'; //shows a message when the delete operation was successful.
        }
    }
}
