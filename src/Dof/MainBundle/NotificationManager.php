<?php

namespace Dof\MainBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Dof\UserBundle\Entity\User;
use Dof\MainBundle\Entity\Notification;

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
            $ent = $em->getRepository($notification->getClass())->find($notification->getClassId());
            if($ent === null)
                continue;

            $translationParams = array();
            $pathParams = array();

            $metadatas = $this->getMetadataByType($notification->getType());

            if(isset($metadatas['translationParams']['dynamic']))
                foreach($metadatas['translationParams']['dynamic'] as $k => $var) {
                    $fields = explode(".", $var);

                    $value = $ent;
                    foreach($fields as $field)
                        if($field == 'localeName')
                            $value = $value->getName($this->di->get('translator')->getLocales());
                        elseif(strtolower($field) == 'currentuser')
                            $value = $this->di->get('security.context')->getToken()->getUser();
                        else{
                            echo $field;
                            $value = $value->{'get' . ucfirst($field)}();
                        }

                    $translationParams[$k] = $value;
                }
            if(isset($metadatas['translationParams']['static']))
                $translationParams += $metadatas['translationParams']['static'];

            $return[$i]['translationString'] = $metadatas['translationString'];
            $return[$i]['translationParams'] = $translationParams;


            if(isset($metadatas['pathParams']['dynamic']))
                foreach($metadatas['pathParams']['dynamic'] as $k => $var) {
                    $fields = explode(".", $var);

                    $value = $ent;
                    foreach($fields as $field)
                        $value = $ent->{'get' . ucfirst($field)}();

                    $pathParams[$k] = $value;
                }
            if(isset($metadatas['pathParams']['static']))
                $pathParams += $metadatas['pathParams']['static'];

            $return[$i]['path'] = $metadatas['path'];
            $return[$i]['pathParams'] = $pathParams;

            $return[$i]['createdAt'] = $notification->getCreatedAt();
            $return[$i]['isRead'] = $notification->getIsRead();

            $i++;
        }

        return $return;
    }

    protected function getMetadataByType($type){
        return $this->metadatas[$type];
    }
}
