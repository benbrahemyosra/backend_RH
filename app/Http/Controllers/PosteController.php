<?php

namespace App\Http\Controllers;

use App\poste;
use Illuminate\Http\Request;
use DB ;

class PosteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allposte= poste::paginate(5);
        return response()->json($allposte);
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
        $poste= new poste();
        $poste->name_post= $request->name_post;
        $poste->salary_post= $request->salary_post;
        $poste->save();
       return response()->json('added succefuly');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function show(poste $poste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function edit(poste $poste)
    {
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\poste  $poste
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $poste= poste::findOrFail($id);
        $poste->name_post = $request->name_post;
        $poste->salary_post = $request->salary_post;
        $poste->save();
       return response()->json('updated succefuly');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $postes=poste::findOrFail($id);
        $postes->delete();
        return response()->json('deleted succefuly');
    }

}
