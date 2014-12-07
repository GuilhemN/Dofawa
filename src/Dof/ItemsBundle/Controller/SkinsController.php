<?php
namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dof\ItemsBundle\Entity\ItemTemplate;

use Symfony\Component\HttpFoundation\File\File;

class SkinsController extends Controller
{

    public function addImageAction(ItemTemplate $item){
        if(!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
            throw $this->createAccessDeniedException();

        try {
            $icon = file_get_contents('http://staticns.ankama.com/dofus/www/game/items/200/' . $item->getIconId() . '.png');
        }
        catch(Exception $e){
            throw $this->createNotFoundException('Image non trouvé', $e);
        };

        $filename = tempnam('/tmp', 'item');
        file_put_contents($filename, $icon);
        $file = new File($filename, false);

        $em = $this->getDoctrine()->getManager();
        $item->setFile($file);
        $items = $em->getRepository('DofItemsBundle:ItemTemplate')->findBy(['iconId' => $item->getIconId()]);
        foreach($items as $i)
            $i->setPath($item->getPath());

        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('dof_items_show', array('slug' => $item->getSlug())));
    }
}
