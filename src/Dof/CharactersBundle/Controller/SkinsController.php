<?php
namespace Dof\CharactersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dof\CharactersBundle\Entity\Spell;

use Symfony\Component\HttpFoundation\File\File;

class SkinsController extends Controller
{
    public function addImageAction(Spell $spell){
        if(!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
            throw $this->createAccessDeniedException();

        try {
            $icon = file_get_contents('http://staticns.ankama.com/dofus/www/game/spells/55/sort_' . $spell->getIconId() . '.png');
        }
        catch(Exception $e){
            throw $this->createNotFoundException('Image non trouvé', $e);
        };

        $filename = tempnam('/tmp', 'spell');
        file_put_contents($filename, $icon);
        $file = new File($filename, false);

        $em = $this->getDoctrine()->getManager();
        $spell->setFile($file);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('dof_spell_show', array('slug' => $spell->getSlug())));
    }
}
