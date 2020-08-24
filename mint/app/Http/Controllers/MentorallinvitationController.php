<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Collaboration;
use App\User;

class MentorallinvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $menteeRequests = Collaboration::where('status_rqs', 'pending')->where('mentor_id', $id)->get();
        return view('mentorAllInvitation', ['menteeRequests' => $menteeRequests]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $acceptCollab = Collaboration::find($id);
        $acceptCollab->status_rqs = "connected";
        // return response()->json($acceptCollab);
        $acceptCollab->save();
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
        $declineCollab = Collaboration::find($id);
        $declineCollab->delete();
        if ($declineCollab) {
            return response()->json(['msg'=>"Invitation decline for $id"]);
        }else{
            return response()->json(['msg'=>'Something wrong happened, Invitation decline not worked']);
        }
    }
}
