<?php
namespace Dof\AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as BaseController;

class CRUDOwnController extends BaseController{

  /**
   * return the Response object associated to the edit action
   *
   *
   * @param mixed $id
   *
   * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   *
   * @return Response
   */
  public function editAction($id = null)
  {
      // the key used to lookup the template
      $templateKey = 'edit';

      $id = $this->get('request')->get($this->admin->getIdParameter());
      $object = $this->admin->getObject($id);

      if (!$object) {
          throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
      }

      if (false === $this->admin->isGranted('EDITFULL', $object)
        or ($object->getCreator() && $object->getOwner()) != $this->get('security.context')->getToken()->getUser()) {
          throw new AccessDeniedException();
      }

      return parent::editAction($id);
  }
}
