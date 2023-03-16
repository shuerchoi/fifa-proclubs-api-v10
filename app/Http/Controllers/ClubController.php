<?php

namespace App\Http\Controllers;

use App\Enums\MatchTypes;
use App\Enums\Platforms;
use App\Services\ProClubsApiService;
use App\Services\ResultService;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public int $clubId;
    public int $clubIds;
    public string $platform;
    public string $player1;
    public string $player2;

    public function __construct(Request $request)
    {
        $this->clubId = (int) $request->route('clubId') ?? 0;
        $this->clubIds = (int) $request->route('clubIds') ?? 0;
        $this->platform = (string) $request->route('platform') ?? '';
        $this->player1 = (string) $request->route('player1') ?? '';
        $this->player2 = (string) $request->route('player2') ?? '';
    }

    public function index(int $clubId, string $platform)
    {
        return ProClubsApiService::clubsInfo(Platforms::getPlatform($platform), $clubId);
    }

    public function members(int $clubId, string $platform)
    {
        return ProClubsApiService::memberStats(Platforms::getPlatform($platform), $clubId);
    }

    public function career(int $clubId, string $platform)
    {
        return ProClubsApiService::careerStats(Platforms::getPlatform($platform), $clubId);
    }

    public function season(int $clubId, string $platform)
    {
        return ProClubsApiService::seasonStats(Platforms::getPlatform($platform), $clubId);
    }

    public function settings(string $clubName, string $platform)
    {
        return ProClubsApiService::settings(Platforms::getPlatform($platform), $clubName);
    }

    public function search(string $clubName, string $platform)
    {
        return ProClubsApiService::search(Platforms::getPlatform($platform), $clubName);
    }

    public function league(int $clubId, string $platform, ResultService $resultService)
    {
        return ProClubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::LEAGUE);
    }

    public function leaderboard(string $platform, string $leaderboardType)
    {
        return ProClubsApiService::leaderboard(Platforms::getPlatform($platform), $leaderboardType);
    }

    public function cup(int $clubId, string $platform)
    {
        return ProClubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::CUP);
    }

    public function player(int $clubId, string $platform, string $playerName)
    {
        return ProClubsApiService::playerStats(Platforms::getPlatform($platform), $clubId, $playerName);
    }

    public function squad(ResultService $resultService)
    {
        $data = [
            'squad' => $resultService->getCachedData($this->clubId, $this->platform, 'squad'),
        ];

//        Accept the parameters from routes or other inputs
//        Call some logic classes/methods, passing those parameters
//        Return the result: view, redirect, JSON return, etc.

        return view('club.squad', $data);
    }

    public function compare(ResultService $resultService)
    {
        $data = $resultService->getPlayerComparisonData($this->clubId, $this->platform, $this->player1, $this->player2);
        dump($data);
        return view('club.compare', $data);
    }

    public function ranking(ResultService $resultService)
    {
        $data = [
            'rankings' =>  $resultService->getRankingData($this->clubId, $this->platform),
            'perMatchRankings' => $resultService->getCustomRankingData($this->clubId, $this->platform)
        ];

        dump($data);
        return view('club.rankings', $data);
    }
}
