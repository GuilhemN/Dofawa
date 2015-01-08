<?hh
namespace Dof\SearchBundle\Intent;

use Symfony\Component\Templating\EngineInterface;

trait IntentTrait {
    public function __construct(
    private EngineInterface $tp
    ) { }
}
