<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\Language;
use App\Skill;

class EditmentorprofileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

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
        //return ('editmentorprofile');
    }

    public function initSkill()
    {
        $skills = Skill::all();
        return response()->json([$skills]);
    }
    // public function mentorlanguages()
    // {
    //     $languages = language::all();
    //     return view('register_mentor', ['languages' => $languages]);
    // }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $mentor = User::find($id);


        $languages = User::find($id)->languages;
        $allLanguages =  Language::all();
        $langChosen = collect();

        foreach ($allLanguages as $lang) {
            $chosen = false;
            foreach ($languages as $mentorLang) {
                if ($lang->id == $mentorLang->id) {
                    $chosen = true;
                }
            }
            $langChosen->push(['id' => $lang->id, 'language' => $lang->languages, 'chosen' => $chosen]);
        }

        $skills = User::find($id)->skills;
        $allSkills = Skill::all();
        $skillChosen = collect();

        foreach ($allSkills as $skill) {

            $chosen = false;
            foreach ($skills as $mentorSkill) {
                if ($skill->id == $mentorSkill->id) {
                    $chosen = true;
                }
            }

            $skillChosen->push(['id' => $skill->id, 'skill' => $skill->skill, 'chosen' => $chosen]);
        }
        return view('editmentorprofile', compact('mentor', 'skills', 'languages', 'langChosen', 'skillChosen'));
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
        //
        DB::table('users')->where('id', $id)->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'linkedin' => $request->linkedin,
            'pitch' => $request->pitch
        ]);

        // dd($request->skillChkBox);

        // updating new mentor skills and languages
        DB::table('skills_intermediate')->where('user_id', $id)->delete();
        foreach ($request->skillChkBox as $skillId) {
            DB::table('skills_intermediate')->insert([
                'user_id' => $request->id,
                'skill_id' => $skillId
            ]);
        }
        DB::table('languages_intermediate')->where('user_id', $id)->delete();
        foreach ($request->langChkBox as $langId) {
            DB::table('languages_intermediate')->insert([
                'user_id' => $request->id,
                'language_id' => $langId
            ]);
        }
        //dd($request->id);
        return redirect("/mentor/" . $request->id);
        //return redirect()->route('mentor', ['id' => $request->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
