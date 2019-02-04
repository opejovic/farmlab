<?php

namespace App\Http\Controllers;

use App\Http\Requests\VetRequest;
use App\Models\LabResult;
use App\Models\Practice;
use App\Models\User;
use Illuminate\Http\Request;

class PracticeVetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Practice $practice)
    {
        abort_unless($practice->id === auth()->user()->practice_id, 403);

        return view('vets.index', [
            'practice' => $practice,
            'vets' => $practice->allVets(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Practice $practice)
    {
        abort_unless($practice->id === auth()->user()->practice_id, 403);

        return view('vets.create', ['practice' => $practice]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param VetRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(VetRequest $request, Practice $practice)
    {
        abort_unless($practice->id === auth()->user()->practice_id, 403);
        
        $practice->addVet(request('name'), request('email'));

        flash('New vet added to the team.');

        return redirect(route('vets.index', $practice->id));
    }

    /**
     * Display the specified resource.
     *
     * @param User $vet
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Practice $practice, User $vet)
    {
        // temporary - create new middleware class for this
        abort_unless(
            auth()->user()->practice_id == $practice->id || 
            auth()->user()->isOfType(User::ADMIN, User::FARM_LAB_MEMBER), 403
        );

        return view('vets.show', [
            'practice' => $practice,
            'vet' => $vet, 
            'results' => $vet->results()->get(), 
            'processedResults' => $vet->results()->processed()->get(), 
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
    public function destroy(Practice $practice, User $vet)
    {
        abort_unless($practice->id === auth()->user()->practice_id, 403);

        User::findOrFail($vet->id)->delete();

        flash('Vet successfully removed.');

        return redirect(route('vets.index', $practice->id));
    }
}
