<?php

namespace Aonach\X12\Generator;

use Aonach\X12\Generator\SegmentGeneratorInterface;

/**
 * Class CttGenerator
 * @package Aonach\X12\Generator
 */
class CttGenerator implements SegmentGeneratorInterface
{

    /**
     *
     */
    const SEGMENT_CODE = 'CTT';


    /**
     * @var
     */
    private $numberOfLineItems;

    /**
     * @var
     */
    private $hashTotal;

    /**
     * @var null
     */
    private $data = null;


    /**
     * @return mixed|void
     */
    public function build()
    {
        // TODO: Implement build() method.
    }

    /**
     * @return mixed|string
     */
    public function __toString()
    {
        return 'string';
    }

    /**
     * @return mixed
     */
    public function getNumberOfLineItems()
    {
        return $this->numberOfLineItems;
    }

    /**
     * @param mixed $numberOfLineItems
     */
    public function setNumberOfLineItems($numberOfLineItems): void
    {
        $this->numberOfLineItems = $numberOfLineItems;
    }

    /**
     * @return mixed
     */
    public function getHashTotal()
    {
        return $this->hashTotal;
    }

    /**
     * @param mixed $hashTotal
     */
    public function setHashTotal($hashTotal): void
    {
        $this->hashTotal = $hashTotal;
    }

    /**
     * @return null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param null $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

}