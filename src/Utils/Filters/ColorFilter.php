<?php

namespace App\Utils\Filters;

use App\Entity\Answer;
use App\Entity\Regex;
use App\Entity\ShopCategory;
use Doctrine\ORM\EntityManagerInterface;
use Stichoza\GoogleTranslate\TranslateClient;

class ColorFilter extends Filter
{
    private $translator;

    /**
     * ColorFilter constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param array                  $influenceBounds
     *
     * @throws \Exception
     */
    public function __construct(EntityManagerInterface $entityManager, array $influenceBounds)
    {
        parent::__construct($entityManager, $influenceBounds, 'Color');

        $this->translator = new TranslateClient();
        $this->translator->setSource('en')->setTarget('lt');
    }


    /**
     * @param string       $pageContent
     * @param ShopCategory $shopCategory
     *
     * @return array
     */
    public function filter(string $pageContent, ShopCategory $shopCategory) : array {
        /**
         * @var Regex[] $regexes
         */
        $regexes = $this->retrieveRegexes($shopCategory, $this->influenceArea);

        if (isset($this->influenceBounds[$this->type][0])) {
            if (\count($regexes) > 0) {
                $answers
                    = $this->influenceArea->getQuestions()[0]->getAnswers()
                    ->filter(function (Answer $answer) {
                        return $answer->getValue()
                            === $this->influenceBounds[$this->type][0];
                    });

                $colorName = '';
                /**
                 * @var Answer $answer
                 */
                foreach ($answers as $answer) {
                    $colorName
                        = $this->translator->translate($answer->getContent()
                        . ' color');
                    $colorName = mb_substr(explode(' ', $colorName)[0], 0, -1);
                }

                $regex = str_replace('colorName', $colorName, $regexes[0]->getContentRegex());
                preg_match_all($regex, $pageContent, $matches);

                $this->isUsed = true;
                return [$regexes[0]->getUrlParameter(), $matches[1]];
            }

            $this->checkUsage($shopCategory->getCategory());
        }

        return [null, []];
    }
}
