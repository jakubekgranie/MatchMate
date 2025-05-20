<?php

namespace App\Http\Controllers;

use App\Models\PendingUserChanges;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaptainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect("/")->with(["title" => "Route incomplete.", "theme" => 2]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $uuid)
    {
        //  NIEKOMPLETNE!!!
        $action = PendingUserChanges::where(['user_id' => Auth::id(),'url_key' => $uuid, 'user_change_statuses_id' => 1])->first();
        if(!is_null($action)) {
            User::where(
                ['id' => intval(PendingUserChanges::where(
                    ['url_key' => $uuid, 'user_change_statuses_id' => 1, 'user_id' => Auth::id()])
                    ->first()
                    ->desired_value)
                ]
            )->update(["awaiting_review" => false]);
            return redirect("/profile");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
