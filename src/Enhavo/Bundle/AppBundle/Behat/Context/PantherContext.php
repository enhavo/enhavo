<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverSelect;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use Symfony\Component\Panther\DomCrawler\Crawler;

class PantherContext implements Context, ClientAwareContext
{
    use ClientAwareTrait;

    /**
     * Opens homepage
     * Example: Given I am on "/"
     * Example: When I go to "/"
     * Example: And I go to "/"
     */
    #[Given('/^(?:|I )am on (?:|the )homepage$/')]
    #[When('/^(?:|I )go to (?:|the )homepage$/')]
    public function iAmOnHomepage()
    {
        $this->getClient()->request('GET', '/');
    }

    #[Given('I wait for :selector')]
    public function waitFor($selector)
    {
        $this->getClient()->waitFor($selector);
    }

    #[Given('I wait for :seconds seconds')]
    public function waitForSeconds($seconds)
    {
        sleep((int) $seconds);
    }

    /**
     * Checks, that page contains specified text
     * Example: Then I should see "Who is the Batman?"
     * Example: And I should see "Who is the Batman?"
     */
    #[Then('/^(?:|I )should see "(?P<text>(?:[^"]|\\\\")*)"$/')]
    public function assertPageContainsText($text)
    {
        $text = $this->fixStepArgument($text);
        Assert::assertStringContainsString($text, $this->getPageText(), sprintf('Text "%s" not found on page', $text));
    }

    /**
     * Checks, that page contains specified text
     * Example: Then I should see "Who is the Batman?" in ".test"
     * Example: And I should see "Who is the Batman?" in ".test"
     */
    #[Then('I should see :text in :selector')]
    public function assertPageContainsTextInElement($text, $selector)
    {
        $found = false;
        $elements = $this->getClient()->getCrawler()->filter($selector);
        if (0 === $elements->count()) {
            throw new AssertionFailedError(sprintf('No element found with selector %s', $selector));
        }

        $elements->each(function ($element) use ($text, &$found) {
            if (false !== strpos($element->text(), $text)) {
                $found = true;

                return false;
            }

            return true;
        });

        if (!$found) {
            throw new AssertionFailedError(sprintf('Text %s not found in elements %s', $text, $selector));
        }
    }

    /**
     * Opens specified page
     * Example: Given I am on "http://batman.com"
     * Example: And I am on "/articles/isBatmanBruceWayne"
     * Example: When I go to "/articles/isBatmanBruceWayne"
     */
    #[Given('/^(?:|I )am on "(?P<page>[^"]+)"$/')]
    #[When('/^(?:|I )go to "(?P<page>[^"]+)"$/')]
    public function visit($page)
    {
        $this->getClient()->request('GET', $page);
    }

    /**
     * Reloads current page
     * Example: When I reload the page
     * Example: And I reload the page
     */
    #[When('/^(?:|I )reload the page$/')]
    public function reload()
    {
        $this->getClient()->reload();
    }

    /**
     * Moves backward one page in history
     * Example: When I move backward one page
     */
    #[When('/^(?:|I )move backward one page$/')]
    public function back()
    {
        $this->getClient()->back();
    }

    /**
     * Moves forward one page in history
     * Example: And I move forward one page
     */
    #[When('/^(?:|I )move forward one page$/')]
    public function forward()
    {
        $this->getClient()->forward();
    }

    /**
     * Presses button with specified id|name|title|alt|value
     * Example: When I press "Log In"
     * Example: And I press "Log In"
     */
    #[When('/^(?:|I )press "(?P<button>(?:[^"]|\\\\")*)"$/')]
    public function pressButton($button)
    {
        $button = $this->fixStepArgument($button);
        $crawler = $this->spin(fn () => $this->getClient()->getCrawler()->selectButton($button));
        if (0 === $crawler->count()) {
            throw new AssertionFailedError(sprintf('Button "%s" not found', $button));
        }
        $crawler->click();
    }

    /**
     * Clicks link with specified id|title|alt|text
     * Example: When I follow "Log In"
     * Example: And I follow "Log In"
     */
    #[When('/^(?:|I )follow "(?P<link>(?:[^"]|\\\\")*)"$/')]
    public function clickLink($link)
    {
        $link = $this->fixStepArgument($link);
        $crawler = $this->spin(fn () => $this->getClient()->getCrawler()->selectLink($link));
        if (0 === $crawler->count()) {
            throw new AssertionFailedError(sprintf('Link "%s" not found', $link));
        }
        $crawler->click();
    }

    /**
     * Fills in form field with specified id|name|label|value
     * Example: When I fill in "username" with: "bwayne"
     * Example: And I fill in "bwayne" for "username"
     */
    #[When('/^(?:|I )fill in "(?P<field>(?:[^"]|\\\\")*)" with "(?P<value>(?:[^"]|\\\\")*)"$/')]
    #[When('/^(?:|I )fill in "(?P<field>(?:[^"]|\\\\")*)" with:$/')]
    #[When('/^(?:|I )fill in "(?P<value>(?:[^"]|\\\\")*)" for "(?P<field>(?:[^"]|\\\\")*)"$/')]
    public function fillField($field, $value)
    {
        $element = $this->findField($this->fixStepArgument($field));
        $element->clear();
        $element->sendKeys($this->fixStepArgument((string) $value));
    }

    #[Given('I clear cookies')]
    public function clearCookies()
    {
        $this->getClient()->getCookieJar()->clear();
    }

    #[Given('I submit form')]
    public function submitForm()
    {
        $this->getClient()->getCrawler()->filter('form')->getElement(0)->submit();
    }

    /**
     * Fills in form fields with provided table
     * Example: When I fill in the following"
     *              | username | bruceWayne |
     *              | password | iLoveBats123 |
     * Example: And I fill in the following"
     *              | username | bruceWayne |
     *              | password | iLoveBats123 |
     */
    #[When('/^(?:|I )fill in the following:$/')]
    public function fillFields(TableNode $fields)
    {
        foreach ($fields->getRowsHash() as $field => $value) {
            $this->fillField($field, $value);
        }
    }

    /**
     * Selects option in select field with specified id|name|label|value
     * Example: When I select "Bats" from "user_fears"
     * Example: And I select "Bats" from "user_fears"
     */
    #[When('/^(?:|I )select "(?P<option>(?:[^"]|\\\\")*)" from "(?P<select>(?:[^"]|\\\\")*)"$/')]
    public function selectOption($select, $option)
    {
        $select = new WebDriverSelect($this->findField($this->fixStepArgument($select)));
        $select->selectByVisibleText($this->fixStepArgument($option));
    }

    /**
     * Selects additional option in select field with specified id|name|label|value
     * Example: When I additionally select "Deceased" from "parents_alive_status"
     * Example: And I additionally select "Deceased" from "parents_alive_status"
     */
    #[When('/^(?:|I )additionally select "(?P<option>(?:[^"]|\\\\")*)" from "(?P<select>(?:[^"]|\\\\")*)"$/')]
    public function additionallySelectOption($select, $option)
    {
        $select = new WebDriverSelect($this->findField($this->fixStepArgument($select)));
        $select->selectByVisibleText($this->fixStepArgument($option));
    }

    /**
     * Checks checkbox with specified id|name|label|value
     * Example: When I check "Pearl Necklace"
     * Example: And I check "Pearl Necklace"
     */
    #[When('/^(?:|I )check "(?P<option>(?:[^"]|\\\\")*)"$/')]
    public function checkOption($option)
    {
        $element = $this->findField($this->fixStepArgument($option));
        if (!$element->isSelected()) {
            $element->click();
        }
    }

    /**
     * Unchecks checkbox with specified id|name|label|value
     * Example: When I uncheck "Broadway Plays"
     * Example: And I uncheck "Broadway Plays"
     */
    #[When('/^(?:|I )uncheck "(?P<option>(?:[^"]|\\\\")*)"$/')]
    public function uncheckOption($option)
    {
        $element = $this->findField($this->fixStepArgument($option));
        if ($element->isSelected()) {
            $element->click();
        }
    }

    /**
     * Attaches file to field with specified id|name|label|value
     * Example: When I attach "bwayne_profile.png" to "profileImageUpload"
     * Example: And I attach "bwayne_profile.png" to "profileImageUpload"
     */
    #[When('/^(?:|I )attach the file "(?P<path>[^"]*)" to "(?P<field>(?:[^"]|\\\\")*)"$/')]
    public function attachFileToField($field, $path)
    {
        $fullPath = realpath($path);
        if (false === $fullPath) {
            throw new AssertionFailedError(sprintf('File "%s" not found', $path));
        }
        $this->findField($this->fixStepArgument($field))->sendKeys($fullPath);
    }

    /**
     * Checks, that current page PATH is equal to specified
     * Example: Then I should be on "/"
     * Example: And I should be on "/bats"
     * Example: And I should be on "http://google.com"
     */
    #[Then('/^(?:|I )should be on "(?P<page>[^"]+)"$/')]
    public function assertPageAddress($page)
    {
        Assert::assertEquals($page, $this->getCurrentPath());
    }

    /**
     * Checks, that current page is the homepage
     * Example: Then I should be on the homepage
     * Example: And I should be on the homepage
     */
    #[Then('/^(?:|I )should be on (?:|the )homepage$/')]
    public function assertHomepage()
    {
        Assert::assertEquals('/', $this->getCurrentPath());
    }

    /**
     * Checks, that current page PATH matches regular expression
     * Example: Then the url should match "superman is dead"
     * Example: Then the uri should match "log in"
     * Example: And the url should match "log in"
     */
    #[Then('/^the (?i)url(?-i) should match (?P<pattern>"(?:[^"]|\\\\")*")$/')]
    public function assertUrlRegExp($pattern)
    {
        Assert::assertMatchesRegularExpression($this->fixStepArgument($pattern), $this->getCurrentPath());
    }

    /**
     * Checks, that current page response status is equal to specified
     * Example: Then the response status code should be 200
     * Example: And the response status code should be 400
     */
    #[Then('/^the response status code should be (?P<code>\\d+)$/')]
    public function assertResponseStatus($code)
    {
        Assert::assertEquals((int) $code, $this->getStatusCode());
    }

    /**
     * Checks, that current page response status is not equal to specified
     * Example: Then the response status code should not be 501
     * Example: And the response status code should not be 404
     */
    #[Then('/^the response status code should not be (?P<code>\\d+)$/')]
    public function assertResponseStatusIsNot($code)
    {
        Assert::assertNotEquals((int) $code, $this->getStatusCode());
    }

    /**
     * Checks, that page doesn't contain specified text
     * Example: Then I should not see "Batman is Bruce Wayne"
     * Example: And I should not see "Batman is Bruce Wayne"
     */
    #[Then('/^(?:|I )should not see "(?P<text>(?:[^"]|\\\\")*)"$/')]
    public function assertPageNotContainsText($text)
    {
        Assert::assertStringNotContainsString($this->fixStepArgument($text), $this->getPageText());
    }

    /**
     * Checks, that page contains text matching specified pattern
     * Example: Then I should see text matching "Batman, the vigilante"
     * Example: And I should not see "Batman, the vigilante"
     */
    #[Then('/^(?:|I )should see text matching (?P<pattern>"(?:[^"]|\\\\")*")$/')]
    public function assertPageMatchesText($pattern)
    {
        Assert::assertMatchesRegularExpression($this->fixStepArgument($pattern), $this->getPageText());
    }

    /**
     * Checks, that page doesn't contain text matching specified pattern
     * Example: Then I should see text matching "Bruce Wayne, the vigilante"
     * Example: And I should not see "Bruce Wayne, the vigilante"
     */
    #[Then('/^(?:|I )should not see text matching (?P<pattern>"(?:[^"]|\\\\")*")$/')]
    public function assertPageNotMatchesText($pattern)
    {
        Assert::assertDoesNotMatchRegularExpression($this->fixStepArgument($pattern), $this->getPageText());
    }

    /**
     * Checks, that HTML response contains specified string
     * Example: Then the response should contain "Batman is the hero Gotham deserves."
     * Example: And the response should contain "Batman is the hero Gotham deserves."
     */
    #[Then('/^the response should contain "(?P<text>(?:[^"]|\\\\")*)"$/')]
    public function assertResponseContains($text)
    {
        Assert::assertStringContainsString($this->fixStepArgument($text), $this->getClient()->getPageSource());
    }

    /**
     * Checks, that HTML response doesn't contain specified string
     * Example: Then the response should not contain "Bruce Wayne is a billionaire, play-boy, vigilante."
     * Example: And the response should not contain "Bruce Wayne is a billionaire, play-boy, vigilante."
     */
    #[Then('/^the response should not contain "(?P<text>(?:[^"]|\\\\")*)"$/')]
    public function assertResponseNotContains($text)
    {
        Assert::assertStringNotContainsString($this->fixStepArgument($text), $this->getClient()->getPageSource());
    }

    /**
     * Checks, that element with specified CSS contains specified text
     * Example: Then I should see "Batman" in the "heroes_list" element
     * Example: And I should see "Batman" in the "heroes_list" element
     */
    #[Then('/^(?:|I )should see "(?P<text>(?:[^"]|\\\\")*)" in the "(?P<element>[^"]*)" element$/')]
    public function assertElementContainsText($element, $text)
    {
        Assert::assertStringContainsString($this->fixStepArgument($text), $this->findElements($element)->text());
    }

    /**
     * Checks, that element with specified CSS doesn't contain specified text
     * Example: Then I should not see "Bruce Wayne" in the "heroes_alter_egos" element
     * Example: And I should not see "Bruce Wayne" in the "heroes_alter_egos" element
     */
    #[Then('/^(?:|I )should not see "(?P<text>(?:[^"]|\\\\")*)" in the "(?P<element>[^"]*)" element$/')]
    public function assertElementNotContainsText($element, $text)
    {
        Assert::assertStringNotContainsString($this->fixStepArgument($text), $this->findElements($element)->text());
    }

    /**
     * Checks, that element with specified CSS contains specified HTML
     * Example: Then the "body" element should contain "style=\"color:black;\""
     * Example: And the "body" element should contain "style=\"color:black;\""
     */
    #[Then('/^the "(?P<element>[^"]*)" element should contain "(?P<value>(?:[^"]|\\\\")*)"$/')]
    public function assertElementContains($element, $value)
    {
        Assert::assertStringContainsString($this->fixStepArgument($value), $this->findElements($element)->html());
    }

    /**
     * Checks, that element with specified CSS doesn't contain specified HTML
     * Example: Then the "body" element should not contain "style=\"color:black;\""
     * Example: And the "body" element should not contain "style=\"color:black;\""
     */
    #[Then('/^the "(?P<element>[^"]*)" element should not contain "(?P<value>(?:[^"]|\\\\")*)"$/')]
    public function assertElementNotContains($element, $value)
    {
        Assert::assertStringNotContainsString($this->fixStepArgument($value), $this->findElements($element)->html());
    }

    /**
     * Checks, that element with specified CSS exists on page
     * Example: Then I should see a "body" element
     * Example: And I should see a "body" element
     */
    #[Then('/^(?:|I )should see an? "(?P<element>[^"]*)" element$/')]
    public function assertElementOnPage($element)
    {
        $this->findElements($element);
    }

    /**
     * Checks, that element with specified CSS doesn't exist on page
     * Example: Then I should not see a "canvas" element
     * Example: And I should not see a "canvas" element
     */
    #[Then('/^(?:|I )should not see an? "(?P<element>[^"]*)" element$/')]
    public function assertElementNotOnPage($element)
    {
        Assert::assertCount(0, $this->getClient()->getCrawler()->filter($element), sprintf('Element "%s" found on page', $element));
    }

    /**
     * Checks, that form field with specified id|name|label|value has specified value
     * Example: Then the "username" field should contain "bwayne"
     * Example: And the "username" field should contain "bwayne"
     */
    #[Then('/^the "(?P<field>(?:[^"]|\\\\")*)" field should contain "(?P<value>(?:[^"]|\\\\")*)"$/')]
    public function assertFieldContains($field, $value)
    {
        Assert::assertEquals($this->fixStepArgument($value), $this->findField($this->fixStepArgument($field))->getAttribute('value'));
    }

    /**
     * Checks, that form field with specified id|name|label|value doesn't have specified value
     * Example: Then the "username" field should not contain "batman"
     * Example: And the "username" field should not contain "batman"
     */
    #[Then('/^the "(?P<field>(?:[^"]|\\\\")*)" field should not contain "(?P<value>(?:[^"]|\\\\")*)"$/')]
    public function assertFieldNotContains($field, $value)
    {
        Assert::assertNotEquals($this->fixStepArgument($value), $this->findField($this->fixStepArgument($field))->getAttribute('value'));
    }

    /**
     * Checks, that (?P<num>\d+) CSS elements exist on the page
     * Example: Then I should see 5 "div" elements
     * Example: And I should see 5 "div" elements
     */
    #[Then('/^(?:|I )should see (?P<num>\\d+) "(?P<element>[^"]*)" elements?$/')]
    public function assertNumElements($num, $element)
    {
        Assert::assertCount((int) $num, $this->getClient()->getCrawler()->filter($element));
    }

    /**
     * Checks, that checkbox with specified id|name|label|value is checked
     * Example: Then the "remember_me" checkbox should be checked
     * Example: And the "remember_me" checkbox is checked
     */
    #[Then('/^the "(?P<checkbox>(?:[^"]|\\\\")*)" checkbox should be checked$/')]
    #[Then('/^the "(?P<checkbox>(?:[^"]|\\\\")*)" checkbox is checked$/')]
    #[Then('/^the checkbox "(?P<checkbox>(?:[^"]|\\\\")*)" (?:is|should be) checked$/')]
    public function assertCheckboxChecked($checkbox)
    {
        Assert::assertTrue($this->findField($this->fixStepArgument($checkbox))->isSelected());
    }

    /**
     * Checks, that checkbox with specified id|name|label|value is unchecked
     * Example: Then the "newsletter" checkbox should be unchecked
     * Example: Then the "newsletter" checkbox should not be checked
     * Example: And the "newsletter" checkbox is unchecked
     */
    #[Then('/^the "(?P<checkbox>(?:[^"]|\\\\")*)" checkbox should (?:be unchecked|not be checked)$/')]
    #[Then('/^the "(?P<checkbox>(?:[^"]|\\\\")*)" checkbox is (?:unchecked|not checked)$/')]
    #[Then('/^the checkbox "(?P<checkbox>(?:[^"]|\\\\")*)" should (?:be unchecked|not be checked)$/')]
    #[Then('/^the checkbox "(?P<checkbox>(?:[^"]|\\\\")*)" is (?:unchecked|not checked)$/')]
    public function assertCheckboxNotChecked($checkbox)
    {
        Assert::assertFalse($this->findField($this->fixStepArgument($checkbox))->isSelected());
    }

    /**
     * Returns the HTTP status code of the current page. WebDriver does not expose it,
     * so it is read from the browser's Navigation Timing API.
     */
    private function getStatusCode(): ?int
    {
        return $this->getClient()->executeScript("const entry = performance.getEntriesByType('navigation')[0]; return entry ? entry.responseStatus : null;");
    }

    private function getCurrentPath(): string
    {
        return parse_url($this->getClient()->getCurrentURL(), PHP_URL_PATH) ?? '/';
    }

    private function getPageText(): string
    {
        return $this->getClient()->getCrawler()->filter('body')->text();
    }

    private function findElements(string $selector): Crawler
    {
        $crawler = $this->spin(fn () => $this->getClient()->getCrawler()->filter($selector));
        if (0 === $crawler->count()) {
            throw new AssertionFailedError(sprintf('No element found with selector "%s"', $selector));
        }

        return $crawler;
    }

    /**
     * Finds a form field by id, name, label or placeholder
     */
    private function findField(string $locator): WebDriverElement
    {
        $literal = $this->xpathLiteral($locator);
        $fields = '(self::input or self::textarea or self::select)';
        $xpath = implode(' | ', [
            sprintf('//*[%s][@id=%s or @name=%s or @placeholder=%s]', $fields, $literal, $literal, $literal),
            sprintf('//*[%s][@id=//label[normalize-space()=%s]/@for]', $fields, $literal),
            sprintf('//label[normalize-space()=%s]//*[%s]', $literal, $fields),
        ]);

        $crawler = $this->spin(fn () => $this->getClient()->getCrawler()->filterXPath($xpath));
        if (0 === $crawler->count()) {
            throw new AssertionFailedError(sprintf('Form field "%s" not found', $locator));
        }

        return $crawler->getElement(0);
    }

    /**
     * Retries the lookup until it finds something, as content may be rendered asynchronously by javascript
     */
    private function spin(callable $lookup, int $timeout = 5): Crawler
    {
        $end = microtime(true) + $timeout;
        do {
            $crawler = $lookup();
            if ($crawler->count() > 0) {
                return $crawler;
            }
            usleep(100000);
        } while (microtime(true) < $end);

        return $crawler;
    }

    private function xpathLiteral(string $value): string
    {
        if (!str_contains($value, "'")) {
            return sprintf("'%s'", $value);
        }

        return sprintf('concat(\'%s\')', str_replace("'", "', \"'\", '", $value));
    }

    private function fixStepArgument(string $argument): string
    {
        return str_replace('\\"', '"', $argument);
    }
}
