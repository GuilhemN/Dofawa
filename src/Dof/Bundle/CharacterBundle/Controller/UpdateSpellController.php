<?php

namespace Dof\Bundle\CharacterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use XN\Annotations as Utils;
use Dof\Bundle\CharacterBundle\Entity\Spell;

class UpdateSpellController extends Controller
{
    const ACCESS_ROLE = 'ROLE_SUPER_ADMIN';

    public function addImageAction(Spell $spell)
    {
        $this->denyAccessUnlessGranted(self::ACCESS_ROLE);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://staticns.ankama.com/dofus/www/game/spells/55/sort_'.$spell->getIconId().'.png');
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($curl);
        $response = curl_getinfo($curl);
        curl_close($curl);

        if ($response['http_code'] != 200) {
            throw $this->createNotFoundException('Image non disponible');
        }

        $em = $this->getDoctrine()->getManager();
        $filename = tempnam('/tmp', 'spell');
        file_put_contents($filename, $content);
        $file = new File($filename, false);

        $em = $this->getDoctrine()->getManager();
        $spell->setFile($file);
        $em->flush($spell);
        $spells = $em->getRepository('DofCharacterBundle:Spell')->findBy(['iconId' => $spell->getIconId()]);
        foreach ($spells as $s) {
            $s->setPath($spell->getPath());
        }

        $em->flush();

        return $this->redirect($this->generateUrl('dof_spell_show', array('slug' => $spell->getSlug())));
    }

    public function changeVisibilityAction(Spell $spell)
    {
        $this->denyAccessUnlessGranted(self::ACCESS_ROLE);
        
        $spell->setPubliclyVisible(!$spell->getPubliclyVisible());
        $this->getDoctrine()->getManager()->flush($spell);

        return $this->redirect($this->generateUrl('dof_spell_show', array('slug' => $spell->getSlug())));
    }
}
