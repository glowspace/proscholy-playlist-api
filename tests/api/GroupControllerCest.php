<?php

use Codeception\Util\HttpCode;

class GroupControllerCest
{
    public function _before(ApiTester $I)
    {
    }


    // tests
    public function indexGroups(ApiTester $I)
    {
        $I->sendGet('/api/groups');

        $I->seeResponseCodeIs(HttpCode::OK);
    }


    public function createGroup(ApiTester $I)
    {
        $I->sendPost('/api/groups/', [
            'name' => 'Nová skupina',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
    }


    public function showGroup(ApiTester $I)
    {
        $id = $this->prepareGroup($I);

        $I->sendGet('/api/groups/' . $id);
        $I->seeResponseCodeIs(HttpCode::OK);
    }


    public function updateGroup(ApiTester $I)
    {
        $id = $this->prepareGroup($I);

        $I->sendPatch('/api/groups/' . $id, [
            'name'              => 'Změna názvu',
            'dashboard_message' => '<p>Test</p>',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'name'              => 'Změna názvu',
            'dashboard_message' => '<p>Test</p>',
        ]);
    }


    public function deleteGroup(ApiTester $I)
    {
        $id = $this->prepareGroup($I);
        $I->sendDelete('/api/groups/' . $id);

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }


    /**
     * @throws Exception
     */
    private function prepareGroup(ApiTester $I)
    {
        $I->sendPost('/api/groups/', [
            'name' => 'Nová skupina',
        ]);

        return $I->grabDataFromResponseByJsonPath('id')[0];
    }
}
