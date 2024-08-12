<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Domain\Team\Services\TeamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Team extends BaseController
{
    private TeamService $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    /**
     * Send all teams.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $team = $this->teamService->getAllTeams();

        if ($team === null) {
            return $this->sendError(422, 'Teams not found');
        }

        return $this->sendResponse($team, 'Teams retrieved successfully');
    }

    /**
     * add new Team.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Name is required');
        }

        $team = $this->teamService->createTeam($request['name']);

        if ($team === null) {
            return $this->sendError(422, 'Team not created');
        }

        return $this->sendResponse(null, 'Team was created');
    }

    /**
     * Add user to team.
     *
     * @param int $teamId
     *
     * @return JsonResponse
     */
    public function addUserToTeam(int $teamId): JsonResponse
    {
        $createMemberTeam = $this->teamService->addUserTeam($teamId);

        if ($createMemberTeam['success'] === false) {
            return $createMemberTeam['response'];
        }

        return $this->sendResponse(null, 'User added to team');
    }

    /**
     * Remove user from team.
     *
     * @param int $teamId
     *
     * @return JsonResponse
     */
    public function removeUserFromTeam(int $teamId): JsonResponse
    {
        $removeMemberTeam = $this->teamService->removeUserTeam($teamId);

        if ($removeMemberTeam['success'] === false) {
            return $removeMemberTeam['response'];
        }

        return $this->sendResponse(null, 'User added to team');
    }
}
