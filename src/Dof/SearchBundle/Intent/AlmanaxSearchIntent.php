<?hh
namespace Dof\SearchBundle\Intent;

use Dof\QuestBundle\QuestType;

class ItemSearchIntent
{
    use IntentTrait;

    public function process(array $entities, $intent) : ?string {
        $quest = $this->em->getRepository('DofQuestBundle:Quest')
            ->findOneBy(['type' => QuestType::ALMANAX, 'season' => true]);

        return !empty($quest) ? $this->tp->render('DofSearchBundle:Intent:almanax.html.twig', ['quest' => $quest]) : null;
    }
}
