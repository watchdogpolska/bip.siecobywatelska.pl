<?php

use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

class UserContext implements Context
{
    use Behat\Symfony2Extension\Context\KernelDictionary;
    use DoctrineDictrionary;

    /** @var Behat\MinkExtension\Context\MinkContext */
    private $minkContext;

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        $this->minkContext = $environment->getContext('Behat\MinkExtension\Context\MinkContext');
    }

    /**
     * @Given /^the user "([^"]*)" exists$/
     */
    public function theUserExists($username)
    {
        $this->thereIsAUserWithPasswordAndRole($username, 'foo');
    }

    /**
     * @Given /^there is a user "([^"]*)" with password "([^"]*)"$/
     */
    public function thereIsAUserWithPassword($username, $password)
    {
        $this->createUser($username, $password, array('ROLE_SUPER_ADMIN'));
    }

    /**
     * @Given /^there is a user "([^"]*)" with password "([^"]*)" and role "([^"]*)"$/
     */
    public function thereIsAUserWithPasswordAndRole($username, $password)
    {
        $this->createUser($username, $password, array('ROLE_SUPER_ADMIN'));
    }

    public function createUser($username, $plainPassword, $role)
    {
        $em = $this->getManager();
        $user = new User();
        $user->setUsername($username);
        $user->setPlainPassword($plainPassword);
        $user->setEmail($username.'@example.org');
        $user->setEnabled(true);
        $user->setRoles($role);

        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * @Then I open the confirmation link for the user :username
     */
    public function iOpenTheConfirmationLinkForTheUser($username)
    {
        $repo = $this->getManager()->getRepository(User::class);

        $user = $repo->findOneBy(['username' => $username]);
        $token = $user->getConfirmationToken();

        $this->minkContext->visit("resetting/reset/{$token}");
    }
}
