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
        $I->waitForElementNotVisible('.backdrop');
        $I->waitForElement('.main-button');
        $I->canSee('Start quiz', '.main-button');
        $I->click('.main-button');
        $I->waitForElement('.igXdbl');

        $buttons = [
            'Smartphones',
            'The cheaper the better!',
            'Only a few times a year',
            'Every other day!',
            'Of course it is!',
            'Black',
        ];

        foreach ($buttons as $button) {
            $I->click($button);
            $I->wait(3);
        }

        $I->see('I hope you liked our offers! ' .
            'If you want to view them again, just click on the arrow that has appeared on the right.');
        $I->waitForElement('.results-summary', 30);

    }
}