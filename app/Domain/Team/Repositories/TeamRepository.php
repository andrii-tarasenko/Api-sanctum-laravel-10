<?php

namespace Domain\Team\Repositories;

use Domain\Team\Models\Team;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TeamRepository
{
    /**
     * @param int $id
     *
     * @return Team|null
     */
    public function getTeamById(int $id): ?Team
    {
        return Team::all()->find($id);
    }

    /**
     * @return Collection
     */
    public function getAllTeams(): Collection
    {
        return Team::all();
    }

    /**
     * @param Team $team
     *
     * @return bool
     */
    public function createTeam(Team $team): bool
    {
        return $team->save();
    }

    /**
     * @param Team $team
     *
     * @param int $userId
     *
     * @return void
     */
    public function addUserTeam(Team $team, int $userId): void
    {
        $team->users()->attach($userId);
    }

    /**
     * @param Team $team
     *
     * @return void
     */
    public function removeUserFromTeam(Team $team): void
    {
        $team->users()->detach(Auth::id());
    }
}
