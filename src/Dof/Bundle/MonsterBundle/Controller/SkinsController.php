<?php

namespace Dof\Bundle\MonsterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use XN\Annotations as Utils;
use Dof\Bundle\MonsterBundle\Entity\Monster;

/**
 * @Utils\Secure("ROLE_SUPER_ADMIN")
 */
class SkinsController extends Controller
{
    public function addImageAction(Monster $monster)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://staticns.ankama.com/dofus/renderer/look/'.bin2hex($monster->getLook()).'/full/1/150_220-10.png');
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($curl);
        $response = curl_getinfo($curl);
        curl_close($curl);

        if ($response['http_code'] != 200) {
            throw $this->createNotFoundException('Image non disponible');
        }

        $em = $this->getDoctrine()->getManager();
        $filename = tempnam('/tmp', 'monster');
        file_put_contents($filename, $content);
        $file = new File($filename, false);

        $em = $this->getDoctrine()->getManager();
        $monster->setFile($file);
        $em->flush($monster);
        $monsters = $em->getRepository('DofMonsterBundle:Monster')->findBy(['look' => $monster->getLook()]);
        foreach ($monsters as $m) {
            $m->setPath($monster->getPath());
        }

        $em->flush();

        return $this->redirect($this->generateUrl('dof_monster_show', array('slug' => $monster->getSlug())));
    }
}
