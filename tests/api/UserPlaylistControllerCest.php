<?php

use Codeception\Util\HttpCode;

/**
 * Class IndexControllerCest
 *
 * @see \App\Http\Controllers\Api\PlaylistController
 */
class UserPlaylistControllerCest
{
    public function _before(ApiTester $I)
    {

    }


    public function testIndexPlaylistsTest(ApiTester $I)
    {
        $I->sendGet('/api/playlists');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
    }


    public function storePlaylist(ApiTester $I)
    {
        $I->sendPost('/api/playlists', [
            'name'       => 'Nový playlist',
            'is_private' => true,
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'name' => 'Nový playlist',
        ]);
    }


    /**
     * @throws Exception
     */
    public function showPlaylist(ApiTester $I)
    {
        $id = $this->preparePlaylist($I);

        $I->sendGet('/api/playlists/' . $id);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
    }


    public function tryToShowSomeonesPrivatePlaylist(ApiTester $I)
    {
        $I->sendGet('/api/playlists/4');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
    }


    public function updatePlaylist(ApiTester $I)
    {
        $id = $this->preparePlaylist($I);

        $I->sendPatch('/api/playlists/' . $id, [
            'name'       => 'Nový název',
            'is_private' => '0',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'name'       => 'Nový název',
            'is_private' => '0',
        ]);
    }


    public function destroyPlaylist(ApiTester $I)
    {
        $id = $this->preparePlaylist($I);

        $I->sendDelete('/api/playlists/' . $id);
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }


    /**
     * @param ApiTester $I
     *
     * @return array
     * @throws Exception
     */
    private function preparePlaylist(ApiTester $I): int
    {
        $I->sendPost('/api/playlists', [
            'name' => 'Playlist 55',
        ]);

        $id = $I->grabDataFromResponseByJsonPath('id');

        return $id[0];
    }
}
