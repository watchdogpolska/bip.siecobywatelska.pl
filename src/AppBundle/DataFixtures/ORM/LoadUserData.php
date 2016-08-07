<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        $userManager = $this->container->get('fos_user.user_manager');
        $credentials = [
            [
                'login' => 'root', 
                'password' => 'root', 
                'email' => 'root@example.com'
            ],
            [
                'login' => 'michal', 
                'password' => 'kokoszka', 
                'email' => 'michal@example.com'
            ],
        ];

        foreach ($credentials as $credential) {
            $user = new User();
            $user->setUsername($credential['login']);
            $user->setEmail($credential['email']);
            $user->setPlainPassword($credential['password']);
            $user->setEnabled(true);
            $userManager->updateUser($user, true);
        }
    }
}