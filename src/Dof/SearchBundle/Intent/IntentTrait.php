<?hh
namespace Dof\SearchBundle\Intent;

use XN\DependencyInjection\ServiceArray;

use Symfony\Component\Templating\EngineInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

trait IntentTrait implements IntentInterface {
    private EngineInterface $tp;
    private ObjectManager $em;
    private RequestStack $rs;

    public function __construct(ServiceArray $sa) {
        $this->tp = $sa[0];
        $this->em = $sa[1];
        $this->rs = $sa[2];
    }

    public function getLocale() {
        return $this->rs->getCurrentRequest()->getLocale();
    }
}
