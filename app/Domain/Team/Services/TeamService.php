<?php

namespace Domain\Team\Services;

use Domain\Team\Models\Team;
use Domain\Team\Repositories\TeamRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TeamService
{
    protected object $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * @param string $name
     *
     * @return Team|null
     */
    public function createTeam(string $name): ?Team
    {
        $team = new Team();
        $team->setTeamName($name);

        return $this->teamRepository->createTeam($team);
    }

    /**
     * @return Collection|null
     */
    public function getAllTeams(): ?Collection
    {
        return $this->teamRepository->getAllTeams();
    }

    /**
     * @param int $teamId
     *
     * @return array
     */
    public function addUserTeam(int $teamId): array
    {
        try {
            $team = $this->teamRepository->getTeamById($teamId);

            if ($team === null) {
                return [
                    'success' => false,
                    'response' => response()->json(['message' => 'This team is not found'], 404),
                ];
            }

            $this->teamRepository->addUserTeam($team, Auth::id());

            return [
                'success' => true,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'response' => response()->json(['error' => 'An error occurred while adding the user to the team'], 500),
            ];
        }
    }

    /**
     * @param int $teamId
     *
     * @return array
     */
    public function removeUserTeam(int $teamId): array
    {
        try {
            $team = $this->teamRepository->getTeamById($teamId);

            if ($team === null) {
                return [
                    'success' => false,
                    'response' => response()->json(['message' => 'This team is not found'], 404),
                ];
            }

            $this->teamRepository->removeUserTeam($team, Auth::id());

            return [
                'success' => true,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'response' => response()->json(['error' => 'An error occurred while removing the user from the team'], 500),
            ];
        }
    }
}
