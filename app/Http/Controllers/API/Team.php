<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Team as ModelsTeam;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;

class Team extends BaseController
{

    /**
     * Send all teams
     *
     * @return JsonResponse
     */
    public function index()
    {
        $teams = ModelsTeam::all();

        if ($teams->isEmpty()) {
            return $this->sendError($teams, 'Teams not found');
        }

        return $this->sendResponse($teams, 'All teams was send');
    }


    /**
     * add new Team
     *
     * @param Request $request
     *
     * @return JsonResponse|void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(),  'Name is required');
        }

        $team = new ModelsTeam();
        $team->setTeamName($request->input('name'));

        if ($team->save()) {
            return $this->sendResponse($team, 'Team was created');
        }
    }

    /**
     * get Team object
     *
     * @param int $id
     *
     * @return JsonResponse|object
     */
    private function getUserTeam(int $id)
    {
        $team = ModelsTeam::find($id);

        if (empty($team)) {
            return $this->sendError($team, 'Team not found');
        }

        return $team;
    }

    /**
     * Add user to team
     *
     * @param int $teamId
     *
     * @return JsonResponse
     */
    public function addUserToTeam(int $teamId)
    {
        $updateUserTeam = $this->getUserTeam($teamId);
        $updateUserTeam->users()->attach(Auth::id());

        return $this->sendResponse($updateUserTeam, 'User added to team');
    }

    /**
     * Remove user from team
     *
     * @param int $teamId
     * @param int $userId
     *
     * @return JsonResponse
     */
    public function removeUserFromTeam(int $teamId, int $userId)
    {
        $updateUserTeam = $this->getUserTeam($teamId);
        $updateUserTeam->users()->detach($userId);

        return $this->sendResponse($updateUserTeam, 'User deleted from the team');
    }
}
