<?php

namespace Dof\MainBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Dof\UserBundle\Entity\User;
use Dof\MainBundle\Entity\Notification;

use XN\Common\DateFormat;

class NotificationManager
{

    /**
     * @var ContainerInterface
     */
    private $di;

    protected $metadatas = [
            'message.receive' => array(
                'translationString' => 'message.receive',
                'translationParams' => array(
                    'dynamic' => array('%by%' => 'sender.username')
                ),
                'path' => 'fos_message_thread_view',
                'pathParams' => array(
                    'static' => array(),
                    'dynamic' => array('threadId' => 'thread.id')
                )
            ),
            'badge.receive' => array(
                'translationString' => 'badge.receive',
                'translationParams' => array(
                    'dynamic' => array('%name%' => 'localeName')
                ),
                'path' => 'dof_profile_userpage',
                'pathParams' => array(
                    'static' => array(),
                    'dynamic' => array('slug' => 'currentUser.slug')
                )
            ),
            'welcome' => array(
                'translationString' => 'welcome',
                'translationParams' => array(
                    'dynamic' => array('%name%' => 'currentUser.username')
                ),
                'path' => 'dof_main_homepage',
            ),
        ];

    public function __construct(ContainerInterface $di)
    {
        $this->di = $di;
    }

    public function addNotification($ent, $type, $user = null, $is_read = false){
        if(!($user instanceof User)){
            $user = $this->di->get('security.context')->getToken()->getUser();
            if($user === null)
                return $this;
        }

        $em = $this->di->get('doctrine')->getManager();

        $notification = new Notification();

        $notification
            ->setType($type)
            ->setClass($em->getClassMetadata(get_class($ent))->getName())
            ->setClassId($ent->getId())
            ->setIsRead($is_read)
            ->setOwner($user)
        ;

        $em->persist($notification);

        return $this;
    }

    public function transformNotifications($notifications){
        $em = $this->di->get('doctrine')->getEntityManager();
        $notifications = (array) $notifications;

        $return = array();
        $i = 0;
        foreach($notifications as $notification){
            if($notification->getClass() !== null){
                $ent = $em->getRepository($notification->getClass())->find($notification->getClassId());
                $noClass = false;
            }
            else
                $noClass = true;

            if(isset($ent) && $ent === null)
                continue;

            if($notification->getMessage() !== null){
                // Notif manuelle
                $return[$i]['message'] = $notification->getMessage();
                $return[$i]['path'] = $notification->getPath();
            }
            else {
                // Notif automatique et traduite
                $metadatas = $this->getMetadataByType($notification->getType());

                $translationParams = array();
                $pathParams = array();

                if(!$noClass && isset($metadatas['translationParams']['dynamic']))
                    foreach($metadatas['translationParams']['dynamic'] as $k => $var) {
                        $fields = explode(".", $var);

                        $value = $ent;
                        foreach($fields as $field)
                            if($field == 'localeName')
                                $value = $value->getName($this->di->get('translator')->getLocales());
                            elseif($field == 'currentUser')
                                $value = $this->di->get('security.context')->getToken()->getUser();
                            else
                                $value = $value->{'get' . ucfirst($field)}();

                        $translationParams[$k] = $value;
                    }
                if(isset($metadatas['translationParams']['static']))
                    $translationParams += $metadatas['translationParams']['static'];

                $return[$i]['message'] = $this->di->get('translator')->trans($metadatas['translationString'], $translationParams, 'notifications');

                // Chemin
                if(!$noClass && isset($metadatas['pathParams']['dynamic']))
                    foreach($metadatas['pathParams']['dynamic'] as $k => $var) {
                        $fields = explode(".", $var);

                        $value = $ent;
                        foreach($fields as $field)
                            if($field == 'localeName')
                                $value = $value->getName($this->di->get('translator')->getLocales());
                            elseif($field == 'currentUser')
                                $value = $this->di->get('security.context')->getToken()->getUser();
                            else
                                $value = $value->{'get' . ucfirst($field)}();

                        $pathParams[$k] = $value;
                    }
                if(isset($metadatas['pathParams']['static']))
                    $pathParams += $metadatas['pathParams']['static'];

                $return[$i]['path'] = $this->di->get('router')->generate($metadatas['path'], $pathParams);

            }

            $return[$i]['createdAt'] = DateFormat::formatDate($this->di->get('translator'), $notification->getCreatedAt());
            $return[$i]['isRead'] = $notification->getIsRead();

            $i++;
        }

        return $return;
    }

    protected function getMetadataByType($type){
        return $this->metadatas[$type];
    }
}
