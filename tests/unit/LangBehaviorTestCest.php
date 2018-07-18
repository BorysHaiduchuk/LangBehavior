<?php


class LangBehaviorTestCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function tryToTest(UnitTester $I)
    {
        $model = new \app\models\Page();
        $model->save();
    }
}
