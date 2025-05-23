<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Mail\AcceptationNotification;
use App\Mail\RejectionNotification;
use App\Models\PendingUserChanges;
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
            $targetUser = User::where(["id" => intval($action->desired_value)])->first();
            $targetUser
                ->update(["awaiting_review" => false, "is_reserve" =>
                    User::with("team")
                        ->where(["is_reserve" => false, "awaiting_review" => false])
                        ->whereHas("team", fn($q) => $q->where("id", $targetUser->team_id))
                        ->count() > 5]);
            $action->update(["user_change_statuses_id" => 4]);
            Mail::to($targetUser->email)->queue(new AcceptationNotification($targetUser->name, $targetUser->team->handle));
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
        if(!$action instanceof RedirectResponse){
            $user = User::where(['id' => intval($action->desired_value)])->first();
            Mail::to($user->email)->queue(new RejectionNotification($user->name, $user->team->handle));
            PendingUserChanges::where(["desired_value" => $action->desired_value])->delete();
            $user->delete();
            return redirect("/my-team")->with(["title" => "Odrzucono."]);
        }
        else
            return redirect("/my-team")->with(["title" => "Nieznane żądanie. Spróbuj ponownie.", "theme" => 2]);
    }
    public function changeStatus(){

    }
}
