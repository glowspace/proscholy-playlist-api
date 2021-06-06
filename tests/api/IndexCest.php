<?php

use Codeception\Util\HttpCode;

class IndexCest
{
    public function _before(ApiTester $I)
    {
    }


    // tests
    public function showIndex(ApiTester $I)
    {
        $I->sendGet('/');

        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}
