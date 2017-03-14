<?php
namespace Application\Persistence\Hydrator;

use Application\Persistence\Hydrator\Strategy\DateStrategy;
use CleanPhp\Invoicer\Domain\Entity\Invoice;
use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;

class InvoiceHydrator implements HydratorInterface
{
    /**
     * @var ClassMethods
     */
    private $wrappedHydrator;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * InvoiceHydrator constructor.
     * @param ClassMethods $wrappedHydrator
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(ClassMethods $wrappedHydrator, OrderRepositoryInterface $orderRepository)
    {
        $this->wrappedHydrator = $wrappedHydrator;
        $this->wrappedHydrator
            ->addStrategy(
                'invoice_date',
                new DateStrategy()
            );
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param array $data
     * @param object $object
     * @return Invoice
     */
    public function hydrate(array $data, $object)
    {
        $order = null;
        if (isset($data['order'])) {
            /** @var Order $order */
            $order = $this->wrappedHydrator->hydrate(
                $data['order'],
                new Order()
            );
            unset($data['order']);
        }
        if (isset($data['order_id'])) {
            $order = $this->orderRepository->getById($data['order_id']);
        }
        $invoice = new Invoice();
        /** @var Invoice $invoice */
        $invoice = $this->wrappedHydrator->hydrate($data, $invoice);
        if ($order) {
            $invoice->setOrder($order);
        }
        return $invoice;
    }

    public function extract($object)
    {
        $data = $this->wrappedHydrator->extract($object);
        if (array_key_exists('order', $data) &&
            !empty($data['order'])
        ) {
            $data['order_id'] = $data['order']->getId();
            unset($data['order']);
        }
        return $data;
    }
}
