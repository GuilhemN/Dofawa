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

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://staticns.ankama.com/dofus/www/game/items/200/' . $item->getIconId() . '.png');
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($curl);
        $response = curl_getinfo( $ch );
        curl_close($curl);

        if ($response['http_code'] != 200)
            throw $this->createNotFoundException('Image non disponible');

        $em = $this->getDoctrine()->getManager();
        $filename = tempnam('/tmp', 'item');
        file_put_contents($filename, $content);
        $file = new File($filename, false);

        $em = $this->getDoctrine()->getManager();
        $item->setFile($file);
        $em->flush($item);
        $items = $em->getRepository('DofItemsBundle:ItemTemplate')->findBy(['iconId' => $item->getIconId()]);
        foreach($items as $i)
            $i->setPath($item->getPath());

        $em->flush();

        return $this->redirect($this->generateUrl('dof_items_show', array('slug' => $item->getSlug())));
    }
}
