<?php

namespace Matthewbdaly\BehatSteps;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Session;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->driver = new GoutteDriver();
        $this->session = new Session($this->driver);
        $this->session->start();
    }

    /**
     * @Given I am on the page :route
     */
    public function iAmOnThePage($route)
    {
        $this->session->visit('http://localhost:8000/'.$route);
    }

    /**
     * @Given I click the link :link
     */
    public function iClickTheLink($link)
    {
        $page = $this->session->getPage();
        $el = $page->findLink($link);
        $el->click();
    }

    /**
     * @Given I press the button :button
     */
    public function iPressTheButton($button)
    {
        $page = $this->session->getPage();
        $el = $page->findButton($button);
        $el->press();
    }

    /**
     * @Given I fill in the :field field with :value
     */
    public function iFillInTheFieldWith($field, $value)
    {
        $page = $this->session->getPage();
        $el = $page->findField($field);
        $el->setValue($value);
    }

    /**
     * @Then I should be redirected to :route
     */
    public function iShouldBeRedirectedTo($route)
    {
        $url = parse_url($this->session->getCurrentUrl());
        if (array_key_exists('path', $url)) {
            $path = $url['path'];
        } else {
            $path = "/";
        }
        if ($path != $route) {
            throw new \Exception();
        }
    }

    /**
     * @Then I should see the text :text
     */
    public function iShouldSeeTheText($text)
    {
        $content = $this->session->getPage()->getContent();
        if (!strpos($content, $text)) {
            throw new \Exception();
        }
    }
}
