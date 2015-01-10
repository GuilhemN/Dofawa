<?hh
namespace Dof\SearchBundle\Intent;

use XN\DependencyInjection\ServiceArray;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

trait IntentTrait implements IntentInterface {
    private \Twig_Environment $twig;
    private ObjectManager $em;
    private RequestStack $rs;

    public function __construct(ServiceArray $sa) {
        $this->twig = $sa[0];
        $this->em = $sa[1];
        $this->rs = $sa[2];
    }

    public function getLocale() {
        return $this->rs->getCurrentRequest()->getLocale();
    }

    public function render($template, $context)
    {
        return $this->twig->loadTemplate($template)->render($context);
    }

    public function renderBlock($template, $block, $context)
    {
        return $this->twig->loadTemplate($template)->renderBlock($block, $context);
    }
}
