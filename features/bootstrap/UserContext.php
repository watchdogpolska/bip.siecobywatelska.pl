<?php

use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Behat\Definition\Call\Given;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;

class UserContext implements Context
{
    use Behat\Symfony2Extension\Context\KernelDictionary;
    use DoctrineDictrionary;

    /** @var Behat\MinkExtension\Context\MinkContext */
    private $minkContext;

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        /** @var Behat\Behat\Context\Environment\InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();

        $this->minkContext = $environment->getContext('Behat\MinkExtension\Context\MinkContext');
    }

    /**
     * @Given /^(\d+) users should exist$/
     */
    public function numUsersShouldExists($num)
    {
        $faker = Faker\Factory::create('pl_PL');
        $roles = array('ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN');
        for ($i = 0; $i < $num; ++$i) {
            $username = $faker->userName;
            $password = 'root';
            $role = $faker->randomElements($roles);
            $this->createUser($username, $password, $role);
        }
    }

    /**
     * @Given /^the following users exist$/
     */
    public function theFollowingUsersExist(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $name = $row['name'];
            $password = isset($row['password']) ? $row['password'] : 'root';
            $role = isset($row['role']) ? $row['role'] : 'ROLE_SUPER_ADMIN';
            $role = array($role);
            $this->createUser($name, $password, $role);
        }
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

    /**
     * @Given /^I am logged in$/
     */
    public function iAmLoggedIn()
    {
        $this->currentUser = $this->createUser('root', 'root', array('ROLE_SUPER_ADMIN'));

        $this->minkContext->visit('/login');
        $this->minkContext->fillField('username', 'root');
        $this->minkContext->fillField('password', 'root');
        $this->minkContext->pressButton('Log in');
    }
}
