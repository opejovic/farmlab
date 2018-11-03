<?php

namespace App\Http\Controllers;

use App\Models\LabResult;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource. 
     * Return a home view, depending on the users type.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LabResult $labresult)
    {
        $user = auth()->user();

        if (!\Auth::check()) {
            return view('auth.login');
        } elseif ($user->type === User::ADMIN) {
            return view('home.admin', compact('user'));
        } elseif ($user->type === User::FARM_LAB_MEMBER) {
            return view('home.member', compact('user'));
        } elseif ($user->type === User::PRACTICE_ADMIN) {
            return view('home.practice', compact('user'));
        } elseif ($user->type === User::VET) {
            $resultsByStatus = $labresult->fetchByStatus(); // tmp

            return view('home.vet', compact('resultsByStatus', 'user'));
        }

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
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }
}
