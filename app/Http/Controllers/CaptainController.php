<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Mail\RejectionNotification;
use App\Models\PendingUserChanges;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CaptainController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(string $uuid)
    {
        $user = User::where(
            ['id' => PendingUserChanges::where(
                ['url_key' => $uuid, 'user_change_statuses_id' => 1, 'user_id' => Auth::id()])
                    ->first()
                    ?->desired_value]
        )->first();
        if($user)
            return view('captain.confirm-player', ["user" => $user]);
        else
            return redirect('/my-team')
                ->with(["title" => "Nieznane żądanie. Spróbuj ponownie.", "theme" => 2]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $uuid)
    {
        $action = ControllerHelper::getAction($uuid);
        if(!$action instanceof RedirectResponse) {
            $targetUser = User::where(["id" => intval($action->desired_value)]);
            $targetUser
                ->first()
                ->update(["awaiting_review" => false, "is_reserve" =>
                    Team::with("user")
                        ->where(["id" => $targetUser->team_id])
                        ->whereHas("user", function($query) { $query->where(["is_reserve" => false, "awaiting_review" => false]); })
                        ->count() > 5
                ]);
            $action->update(["user_change_statuses_id" => 4]);
            return redirect("/my-team")->with(["title" => "Potwierdzono!"]);
        }
        else
            return redirect("/my-team")->with(["title" => "Nieznane żądanie. Spróbuj ponownie.", "theme" => 2]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $action = ControllerHelper::getAction($uuid);
        if(!is_null($action)){
            $user = User::where(['id' => intval($action->desired_value)])->first();
            Mail::to($user->email)->queue(new RejectionNotification($user->name, $user->team->name));
            PendingUserChanges::where(["user_change_types_id" => 4, "desired_value" => intval($action->desired_value)   ])->delete();
            $user->delete();
            return redirect("/my-team")->with(["title" => "Odrzucono."]);
        }
        else
            return redirect("/my-team")->with(["title" => "Nieznane żądanie. Spróbuj ponownie.", "theme" => 2]);
    }
}
