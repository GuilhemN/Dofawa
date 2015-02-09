<?php

namespace Dof\Bundle\User\CharacterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use XN\Annotations as Utils;

use Symfony\Component\HttpFoundation\Request;
use Dof\Bundle\UserBundle\Entity\User;
use Dof\Bundle\User\CharacterBundle\Entity\PlayerCharacter;

class CharacterController extends Controller
{
    /**
     * @Utils\Secure("ROLE_USER")
     */
    public function indexAction(User $user = null){
        $em = $this->getDoctrine()->getManager();
        return $this->render('DofUserCharacterBundle:Character:index.html.twig', [
            'user' => $user,
            'breeds' => $em->getRepository('DofCharacterBundle:Breed')->findAll()
        ]);
    }

    /**
     * @ParamConverter("user", options={"mapping": {"user": "slug"}})
     * @ParamConverter("character", options={"mapping": {"character": "slug"}})
     */
    public function showAction(User $user, PlayerCharacter $character) {
        if($character->getOwner() != $user)
            throw $this->createNotFoundException();
        if(!$character->canSee())
            throw $this->createAccessDeniedException();

        return $this->render('DofUserCharacterBundle:Character:show.html.twig', ['character' => $character]);
    }

    /**
     * @Utils\Secure("ROLE_USER")
     */
    public function createAction(User $user = null, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->checkUser($user);
        if(!$request->request->has('character'))
            throw new \Exception('New character data needed');
        else
            $data = $request->request->get('character');

        $character = new PlayerCharacter();
        //Name
        if(empty($data['name']))
            $data['name'] = 'Personnage sans nom';
        $character->setName($data['name']);
        // Level
        $data['level'] = round((int) $data['level']);
        if($data['level'] > 200) $data['level'] = 200; else if($data['level'] < 1) $data['level'] = 1;
        $character->setLevel($data['level']);
        // Breed
        $breed = $em->getRepository('DofCharacterBundle:Breed')->find($data['breed']);
        if($breed === null)
            throw new \Exception('Breed not found');
        $character->setBreed($breed);
        $character->setVisible((boolean) $data['visible']);
        $em->persist($character);
        $em->flush($character);
        return $this->redirect($this->generateUrl('dof_user_character_homepage'));
    }

    /**
     * @Utils\Secure("ROLE_USER")
     */
    public function removeAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $character = $em->getRepository('DofUserCharacterBundle:PlayerCharacter')->find($request->request->get('id'));
        if(!$character->canWrite())
            throw $this->createAccessDeniedException();
        $em->remove($character);
        $em->flush();
        return $this->redirect($this->generateUrl('dof_user_character_homepage'));
    }

    private function checkUser(User $user = null) {
        if($user === null)
            return $this->getUser();
        if(!$this->get('security.context')->isGranted('ROLE_ADMIN') && $user !== $this->getUser())
            throw $this->createAccessDeniedException();
        return $user;
    }
}
