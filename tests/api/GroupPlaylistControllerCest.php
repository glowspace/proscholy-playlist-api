<?php

use Codeception\Util\HttpCode;

class GroupPlaylistControllerCest
{
    /**
     * @var \ApiTester
     */
    protected $tester;


    // tests
    public function indexGroupPlaylists(ApiTester $I)
    {
        $group_id = $this->prepareGroup($I);

        $I->sendGet('/api/group/' . $group_id . '/playlists');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
    }


    /**
     * @throws Exception
     * @see \App\Http\Controllers\Api\Group\PlaylistController::show()
     */
    public function showGroupPlaylist(ApiTester $I)
    {
        $group_id = $this->prepareGroup($I);
        $id       = $this->prepareGroupPlaylist($I, $group_id);

        $I->sendGet('/api/group/' . $group_id . '/playlists/' . $id);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
    }


    public function createGroupPlaylist(ApiTester $I)
    {
        $group_id = $this->prepareGroup($I);
        $id       = $this->prepareGroupPlaylist($I, $group_id);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'name' => 'Nový skupinový playlist',
        ]);
    }


    public function testCreateToBadGroup(ApiTester $I)
    {
        $I->sendPost('/api/group/2/playlists', [
            'name'       => 'Playlist do cizí skupiny',
            'is_private' => true,
        ]);

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
    }


    public function testUpdate(ApiTester $I)
    {
        $id = $this->prepareGroupPlaylist($I);

        $I->sendPatch('/api/group/1/playlists/' . $id, [
            'name'       => 'Nový název',
            'is_private' => false,
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'name'       => 'Nový název',
            'is_private' => false,
        ]);
    }


    /**
     * @throws Exception
     */
    public function deleteGroupPlaylist(ApiTester $I)
    {
        $id = $this->prepareGroupPlaylist($I);

        $I->sendDelete('/api/group/1/playlists/' . $id);
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }


    /**
     * @param ApiTester $I
     *
     * @return array
     * @throws Exception
     */
    private function prepareGroupPlaylist(ApiTester $I, $group_id): int
    {
        $I->sendPost('/api/group/' . $group_id . '/playlists', [
            'name' => 'Skupinový playlist ',
        ]);

        $id = $I->grabDataFromResponseByJsonPath('id');

        return $id[0];
    }


    private function prepareGroup(ApiTester $I): int
    {
        $I->sendPost('/api/groups/', [
            'name' => 'Nová skupina',
        ]);

        return $I->grabDataFromResponseByJsonPath('id')[0];
    }
}
