<?php

namespace App\Http\Controllers;

use App\infoprofessionnelle;
use Illuminate\Http\Request;
use DB ;
class infoprofessionnelleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allinfo= infoprofessionnelle::all();
        return response()->json($allinfo);
    }

    public function getinfopro( $id)
    {
        $infopro=infoprofessionnelle::findOrFail($id);
        return response()->json($infopro);
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
        $infopro= new infoprofessionnelle();
        $infopro->id_employee= $request->id_employee;
        $infopro->email= $request->email;
        $infopro->email_pro= $request->email_pro;
        $infopro->phone_pro= $request->phone_pro;
        $infopro->type_employee= $request->type_employee;
        $infopro->post= $request->post;
        $infopro->code_point= $request->code_point;
        $infopro->code_pin= $request->code_pin;
        $infopro->save();
       return response()->json('added succefuly ');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\infoprofessionnelle  $infoprofessionnelle
     * @return \Illuminate\Http\Response
     */
    public function show(infoprofessionnelle $infoprofessionnelle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\infoprofessionnelle  $infoprofessionnelle
     * @return \Illuminate\Http\Response
     */
    public function edit(infoprofessionnelle $infoprofessionnelle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\infoprofessionnelle  $infoprofessionnelle
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $infopro=infoprofessionnelle::findOrFail($id);
        $infopro->id_employee= $request->id_employee;
        $infopro->email= $request->email;
        $infopro->email_pro= $request->email_pro;
        $infopro->phone_pro= $request->phone_pro;
        $infoprro->type_employee= $request->type_employee;
        $infopro->post= $request->post;
        $infopro->code_point= $request->code_point;
        $infopro->code_pin= $request->code_pin;
        $infopro->save();
       return response()->json('added succefuly ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\infoprofessionnelle  $infoprofessionnelle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $infopro=infoprofessionnelle::findOrFail($id);
        $infopro->delete();
        return response()->json('deleted succefuly');
    }
}
