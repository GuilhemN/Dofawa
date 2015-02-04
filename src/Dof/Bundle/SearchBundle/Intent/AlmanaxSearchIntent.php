<?hh
namespace Dof\Bundle\SearchBundle\Intent;

use Dof\Bundle\QuestBundle\QuestType;

class AlmanaxSearchIntent
{
    use IntentTrait;

    public function process(array $entities, $intent) : ?string {
        $quest = $this->em->getRepository('DofQuestBundle:Quest')
            ->findOneBy(['type' => QuestType::ALMANAX, 'season' => true]);

        return !empty($quest) ? $this->render('DofSearchBundle:Intent:almanax.html.twig', ['quest' => $quest]) : "Quête almanax du jour non trouvée.";
    }
}
