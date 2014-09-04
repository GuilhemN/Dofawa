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

        $em = $this->di->get('doctrine')->getEntityManager();

        $notification = new Notification();

        $notification
            ->setType($type)
            ->setClass($em->getClassMetadata($ent)->getName())
            ->setClassId($ent->getId())
            ->setIsRead($is_read)
        ;

        $em->persist($notification);

        return $this;
    }

    public function transformNotifications($notifications){
        $em = $this->di->get('doctrine')->getEntityManager();
        $notifications = (array) $notifications;

        $i = 0;
        foreach($notifications as $notification){
            $ent = $em->getRepository($notification->getClass())->find($notification->getClassId());
            if($ent === null)
                continue;

            $translationParams = array();
            $pathParams = array();

            $metadatas = $this->getMetadataByType($notification->getType());

            if(isset($dynamics = $metadatas['translationParams']['dynamic']))
                foreach($dynamics as $k => $var) {
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
            if(isset($statics = $metadatas['translationParams']['static']))
                $translationParams += $statics;

            $return[$i]['translationString'] = $metadatas['translationString'];
            $return[$i]['translationParams'] = $translationParams;


            if(isset($dynamics = $metadatas['pathParams']['dynamic']))
                foreach($dynamics as $k => $var) {
                    $fields = explode(".", $var);

                    $value = $ent;
                    foreach($fields as $field)
                        $value = $ent->{'get' . ucfirst($field)}();

                    $pathParams[$k] = $value;
                }
            if(isset($statics = $metadatas['pathParams']['static']))
                $pathParams += $statics;

            $return[$i]['path'] = $metadatas['path'];
            $return[$i]['pathParams'] = $pathParams;

            $return[$i]['createdAt'] = $ent->getCreatedAt();

            $i++;

            return $return;
        }
    }

    protected function getMetadataByType($type){
        return $this->metadatas[$type];
    }
}
