<?php
namespace CleanPhp\Invoicer\Tests\Unit\Framework\Zf2\Service\InputFilter;

use Application\Service\InputFilter\OrderInputFilter;

class OrderInputFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OrderInputFilter
     */
    private $orderInputFilter;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->orderInputFilter = new OrderInputFilter();
    }

    /**
     * Should require an order number
     */
    public function testShouldRequireAnOrderNumber()
    {
        $this->orderInputFilter->setData([]);
        $isValid = $this->orderInputFilter->isValid();
        static::assertFalse($isValid);
        $error = [
            'isEmpty' => 'Value is required and can\'t be empty'
        ];

        $orderNo = $this->orderInputFilter
            ->getMessages()['orderNumber'];

        static::assertEquals($error, $orderNo);
    }

    /**
     * Should require a customer id
     */
    public function testShouldRequireACustomerId()
    {
        $this->orderInputFilter->setData([]);
        $isValid = $this->orderInputFilter->isValid();
        $error = [
            'id' => [
                'isEmpty' => 'Value is required and can\'t be empty'
            ]
        ];
        $customer = $this->orderInputFilter
            ->getMessages()['customer'];

        static::assertFalse($isValid);
        static::assertEquals($error, $customer);
    }


    /**
     * Should require order number to be 13 characters long
     * @dataProvider orderNumberDataProvider
     */
    public function testShouldRequireOrderNumberToBe13CharactersLong($orderNumber, $error)
    {
        $this->orderInputFilter->setData(['orderNumber' => $orderNumber]);
        $isValid = $this->orderInputFilter->isValid();
        $messages = $this->orderInputFilter->getMessages();
        $orderNumberError = $messages['orderNumber'] ?? null;
        static::assertEquals($error, $orderNumberError);
    }


    /**
     * Should require a total
     */
    public function testShouldRequireATotal()
    {
        $this->orderInputFilter->setData([]);
        $this->orderInputFilter->isValid();
        $orderTotal = $this->orderInputFilter->getMessages()['total'];
        $error = [
            'isEmpty' => 'Value is required and can\'t be empty'
        ];

        static::assertEquals($error, $orderTotal);
    }

    /**
     * Should require total to be a float value
     * @dataProvider orderTotalDataProvider
     */
    public function testShouldRequireTotalToBeAFloatValue($orderTotal, $errors)
    {
        $this->orderInputFilter->setData(['total' => $orderTotal]);
        $this->orderInputFilter->isValid();
        $messages = $this->orderInputFilter->getMessages();
        $orderTotalMessage = $messages['total']??null;
        static::assertEquals($errors, $orderTotalMessage);
    }


    /**
     * Should require description
     */
    public function testShouldRequireDescription()
    {
        $this->orderInputFilter->setData([]);
        $this->orderInputFilter->isValid();

        $error = [
            'isEmpty' => 'Value is required and can\'t be empty'
        ];

        $description = $this->orderInputFilter->getMessages()['description'];
        static::assertEquals($error, $description);
    }


    public function orderTotalDataProvider()
    {
        return [
            [
                'asdas',
                [
                    'notFloat' => 'The input does not appear to be a float'
                ]
            ],
            [123, null],
            [99.99, null],
            [123456789123456789123456789, null],
            [123456789123456789.123456789123456789, null],
        ];
    }


    public function orderNumberDataProvider()
    {
        return [
            [
                '124',
                [
                    'stringLengthTooShort' => 'The input is less than 13 characters long'
                ],
            ],
            [
                '12345612345612',
                [
                    'stringLengthTooLong' => 'The input is more than 13 characters long'
                ]
            ],
            [
                '1234561234561',
                null
            ]
        ];
    }

}
