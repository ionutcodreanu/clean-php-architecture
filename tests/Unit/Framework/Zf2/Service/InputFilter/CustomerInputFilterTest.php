<?php
namespace CleanPhp\Invoicer\Tests\Unit\Framework\Zf2\Service\InputFilter;

use Application\Service\InputFilter\CustomerInputFilter;

class CustomerInputFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CustomerInputFilter
     */
    private $customerInputFilter;

    protected function setUp()
    {
        $this->customerInputFilter = new CustomerInputFilter();
    }


    /**
     * Name field is required
     * @dataProvider nameFieldIsRequiredDataProvider
     */
    public function testNameFieldIsRequired($name, $expectedResults)
    {
        $this->customerInputFilter
            ->setData(['name' => $name])
            ->isValid();
        $messages = $this->customerInputFilter->getMessages();
        if (!empty($messages['name']) && count($expectedResults)) {
            foreach ($expectedResults as $expectedResult) {
                static::assertArrayHasKey($expectedResult, $messages['name']);
            }
        } else {
            static::assertArrayNotHasKey('name', $messages);
        }
    }

    /**
     * Email field is required
     * @dataProvider emailFieldIsRequiredDataProvider
     */
    public function testEmailFieldIsRequired($email, $expectedResults)
    {
        $this->customerInputFilter
            ->setData(['email' => $email])
            ->isValid();
        $messages = $this->customerInputFilter->getMessages();
        if (!empty($messages['email']) && count($expectedResults)) {
            foreach ($expectedResults as $expectedResult) {
                static::assertArrayHasKey($expectedResult, $messages['email']);
            }
        } else {
            static::assertArrayNotHasKey('email', $messages);
        }
    }


    /**
     * Email field is valid
     * @dataProvider emailFieldIsValidDataProvider
     */
    public function testEmailFieldIsValid($email, $expectedResults)
    {
        $this->customerInputFilter
            ->setData(['email' => $email])
            ->isValid();
        $messages = $this->customerInputFilter->getMessages();
        if (!empty($messages['email']) && count($expectedResults)) {
            foreach ($expectedResults as $expectedResult) {
                static::assertArrayHasKey($expectedResult, $messages['email']);
            }
        } else {
            static::assertArrayNotHasKey('email', $messages);
        }
    }

    public function emailFieldIsValidDataProvider()
    {
        return [
            ['test', ['emailAddressInvalidFormat']],
            ['test@', ['emailAddressInvalidFormat']],
            ['test@aaa', ['emailAddressInvalidHostname']],
            ['test@aaa.com', []],
        ];
    }

    public function emailFieldIsRequiredDataProvider()
    {
        return [
            [null, ['isEmpty']],
            ['', ['isEmpty']],
            ['fakeEmail@fake.com', []]
        ];
    }

    public function nameFieldIsRequiredDataProvider()
    {
        return [
            [null, ['isEmpty']],
            ['test', []]
        ];
    }
}
