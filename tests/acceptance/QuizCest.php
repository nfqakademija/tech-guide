<?php


class QuizCest
{
    /**
     * @param AcceptanceTester $I user
     * @throws Exception
     */
    public function doQuiz(AcceptanceTester $I)
    {
        $I->amOnPage('/#');
        $I->waitForElementNotVisible('.loader');
        $I->waitForElement('.main-button');
        $I->canSee('Start quiz', '.main-button');
        $I->click('.main-button');
        $I->waitForElement('.igXdbl');
        $I->canSee('Start again', '.sidedrawer__navigation');
        $I->canSee('Your quizes', '.sidedrawer__navigation');
        $I->canSee('Guidebot', '.sidedrawer__navigation');

        $buttons = [
            'Smartphones',
            'The cheaper the better!',
            'Only a few times a year',
            'Every other day!',
            'Of course it is!',
            'Black',
            'Show me what you got!',
        ];

        foreach ($buttons as $button) {
            $I->click($button);
            $I->wait(2);
        }

        $I->see('It was great to talk to you. I hope you`ll like our offers!');
        $I->waitForElement('.results-summary', 30);
        $I->canSee('Topocentras', '.sidedrawer__navigation');
        $I->click('Topocentras');
        $I->canSee('1a', '.sidedrawer__navigation');
        $I->click('1a');
        $I->canSee('Technorama', '.sidedrawer__navigation');
        $I->click('Technorama');
    }
}