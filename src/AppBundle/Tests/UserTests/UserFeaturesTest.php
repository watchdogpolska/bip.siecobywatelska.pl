<?php
namespace AppBundle\Tests\UserTests;

use AppBundle\DataFixtures\ORM\LoadUserData;
use AppBundle\Tests\ApiUtils\ApiTestCase;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Response;

class UserFeaturesTest extends ApiTestCase
{
    /**
     * Run user data fixtures as needed for testing FosUserBundle features
     */
    public function setUp()
    {
        parent::setUp();
        $purger = new ORMPurger();
        $oe = new ORMExecutor($this->em, $purger);
        $fl = new ContainerAwareLoader($this->container);
        $fl->addFixture(new LoadUserData());
        $oe->execute($fl->getFixtures());
    }

    /**
     * Go to login, submit form and check if logout, profile link exist
     * also check html for username we attempted to login
     */
    public function testUserLogin()
    {
        $link = $this
            ->generateUrl('fos_user_security_login');

        $client = static::createClient();
        $crawler = $client->request('GET', $link);

        $this->assertEquals(
            Response::HTTP_OK,
            self::httpCode($client),
            "Can't request login form"
        );

        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = 'root';
        $form['_password'] = 'root';

        $client->submit($form);

        $this->assertEquals(
            Response::HTTP_FOUND,
            self::httpCode($client),
            "Can't send login form"
        );

        $redirection = $client->getResponse()->headers->get('Location', false);

        $this->assertNotFalse($redirection, "Ther is no 'Location' header after submit");
        $client->followRedirect();
        $this->assertEquals(
            Response::HTTP_OK,
            self::httpCode($client),
            'Can\'t follow location after log in'
        );

        $html = $client->getResponse()->getContent();

        $this->assertTrue($this->apiStringContains('Profile', $html), "No 'Profile' text.");
        $this->assertTrue($this->apiStringContains('Logout', $html), "No 'Logout' text.");
        $this->assertTrue($this->apiStringContains("You are logged as root", $html), "No seeked text.");
    }

    /**
     * Check if register user link is disabled
     */
    public function testRegisterUserLinkDisabled()
    {
        try {
            $link = $this->generateUrl('fos_user_registration_register');
        } catch (\Exception $e) {}

        $this->assertFalse(isset($link), "Route for register user account should be disabled");
    }

    /**
     * Test if user password reset link works
     */
    public function testPasswordResetLink()
    {
        $passwordRecLink = $this
            ->generateUrl('fos_user_resetting_request');

        $client = static::createClient();

        $client->request('GET', $passwordRecLink);
        $this->assertEquals(
            Response::HTTP_OK,
            self::httpCode($client),
            'Invalid password reset link'
        );
    }

    /**
     * Fill in reset password form and submit,
     * check html for proper message about sending email
     */
    public function testPasswordResetForm()
    {
        $client = static::createClient();

        $this->submitPasswordResetForm($client);
        $this->assertEquals(
            Response::HTTP_FOUND,
            self::httpCode($client),
            'Expecting 302 code after submit reset password form'
        );

        $client->followRedirect();
        $this->assertEquals(
            Response::HTTP_OK,
            self::httpCode($client),
            'Expecting 200 status code after redirect from password reset form');

        $this->apiStringContains(
            'An email has been sent. It contains a link you must click to reset your password.',
            $client->getResponse()->getContent()
        );
    }

    public function testPasswordResetEmailContent()
    {
        $client = static::createClient();

        $client->enableProfiler();
        $this->submitPasswordResetForm($client);

        if ($profile = $client->getProfile()) {
            $mailCollector = $profile->getCollector('swiftmailer');
            $messages = $mailCollector->getMessages();
            /** @var  $message \Swift_Message */
            $message = $messages[0];
            $this->assertTrue(
                $this->apiStringContains('To reset your password', $message->getBody()),
                "Email Message do not contain seeked text"
            );
        } else {
            print "Password Reset Email was not tested - enable profiler in config.yml";
        }
    }

    private function submitPasswordResetForm(Client $client)
    {
        $passwordRecLink = $this
            ->generateUrl('fos_user_resetting_request');
        $crawler = $client->request('GET', $passwordRecLink);
        $form = $crawler->selectButton('Reset password')->form();
        $form['username'] = 'root@example.org';
        $client->submit($form);
    }
}