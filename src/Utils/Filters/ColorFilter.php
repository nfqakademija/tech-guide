<?php

namespace App\Utils\Filters;


use App\Entity\Answer;
use App\Entity\ShopCategory;
use Doctrine\ORM\EntityManagerInterface;
use Stichoza\GoogleTranslate\TranslateClient;

class ColorFilter extends Filter
{
    private const type = 'Color';
    private $translator;

    /**
     * ColorFilter constructor.
     *
     * @param EntityManagerInterface $entityManager
     *
     * @throws \Exception
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->influenceAreas = $this->findInfluenceAreas([self::type]);

        $this->translator = new TranslateClient();
        $this->translator->setSource('en')->setTarget('lt');
    }


    /**
     * @param string       $pageContent
     * @param ShopCategory $shopCategory
     *
     * @return array
     */
    public function filter(string $pageContent, ShopCategory $shopCategory) : array
    {
        $filters = $this->retrieveFilters($shopCategory);

        if (
            isset(self::$influenceBounds[self::type][0])
            && \count($filters) > 0
        ) {
            $answers = $this->influenceAreas[0]->getQuestions()[0]->getAnswers()
                ->filter(function (Answer $answer) {
                    return $answer->getValue() === self::$influenceBounds[self::type][0];
                });

            $colorName = '';
            /**
             * @var Answer $answer
             */
            foreach ($answers as $answer) {
                $colorName = $this->translator->translate($answer->getContent() . ' color');
                $colorName = mb_substr(explode(' ', $colorName)[0], 0, -1);
            }

            $regexes = $this->findRegexes($filters[0]);
            $regex = str_replace('colorName', $colorName, $regexes[0]->getContentRegex());
            preg_match_all($regex, $pageContent, $matches);

            return [$filters[0]->getUrlParameter(), $matches[1]];
        }

        return [null, []];
    }
}