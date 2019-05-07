<?php

namespace Aonach\X12;

use Aonach\X12\Generator\BaselineItemData;
use Aonach\X12\Generator\GsGenerator;
use Aonach\X12\Generator\IsaGenerator;
use Aonach\X12\Generator\BakGenerator;
use Aonach\X12\Generator\AckGenerator;
use Aonach\X12\Generator\StGenerator;
use Aonach\X12\Generator\SegmentGeneratorInterface;
use Aonach\X12\Generator\SeGenerator;
use Aonach\X12\Generator\Name;
use Aonach\X12\Generator\Product;
use Faker\Provider\Base;

/**
 * Class Generator
 * @package Aonach\X12
 */
class Generator
{

    /**
     * @var IsaGenerator $isaSegment
     */
    private $isaSegment;

    /**
     * @var $gsSegment
     */
    private $gsSegment;

    /**
     * @var $stSegment
     */
    private $stSegment;

    /**
     * @var $bakSegment
     */
    private $bakSegment;

    /**
     * @var $po1Segment
     */
    private $po1Segment;

    /**
     * @var
     */
    private $ackSegment;

    /**
     * @var $seSegment
     */
    private $seSegment;

    /**
     * @var
     */
    private $nSegment;

    /**
     * @var $isaData
     */
    private $isaData;

    /**
     * @var $itemData
     */
    private $productsData;

    /**
     * @var
     */
    private $extraInformation;

    /**
     * @return mixed
     */
    public function getExtraInformation()
    {
        return $this->extraInformation;
    }

    /**
     * @param mixed $extraInformation
     */
    public function setExtraInformation($extraInformation): void
    {
        $this->extraInformation = $extraInformation;
    }

    /**
     * Generator constructor.
     */
    public function __construct($isaData, array $products, $extraInformation)
    {
        $this->setIsaData($isaData);
        $this->setProductsData($products);
        $this->setExtraInformation($extraInformation);

    }


    /**
     * @return string
     */
    public function generate()
    {
        $this->isaSegment = new IsaGenerator(
            $this->isaData['amazon/authorization_qualifier'],
            $this->isaData['amazon/authorization_information'],
            $this->isaData['amazon/security_qualifier'],
            $this->isaData['amazon/security_information']
        );

        $this->gsSegment = new GsGenerator();
        $this->stSegment = new StGenerator('855', $this->getExtraInformation()['855_data']->transaction_control_number);
        $this->bakSegment =  new BakGenerator($this->getExtraInformation()['acknowledgment_type'], $this->getExtraInformation()['855_data']->purchase_order_number, $this->getExtraInformation()['855_data']->date_of_issuance);
//        $this->nSegment = new Name($this->getExtraInformation()['855_data']->entity_identifier_code, $this->getExtraInformation()['855_data']->name, $this->getExtraInformation()['855_data']->identification_code_qualifier, $this->getExtraInformation()['855_data']->identification_code);
        $this->nSegment = new Name('SF', $this->getExtraInformation()['855_data']->name, '92', $this->getExtraInformation()['855_data']->identification_code);

        foreach ($this->productsData as $product) {
            $this->po1Segment[] = new BaselineItemData($product);
            $this->ackSegment[] = new AckGenerator($product);
        }

        $this->seSegment = new SeGenerator($this->getNumberOfSegments());

        return $this->__generate();

    }

    /**
     * @return string
     */
    private function __generate(){
        $this->getIsaSegment()->build();
        $this->getGsSegment()->build();
        $this->getStSegment()->build();
        $this->getBakSegment()->build();
        $this->getNSegment()->build();

        foreach ($this->getPo1Segment() as $po1){
            $po1->build();
        }

        foreach ($this->getAckSegment() as $ack) {
            $ack->build();
        }

        $this->getSeSegment()->build();
        
        $fileContent = array();

        $fileContent[] = $this->getIsaSegment()->__toString();
        $fileContent[] = $this->getGsSegment()->__toString();
        $fileContent[] = $this->getStSegment()->__toString();
        $fileContent[] = $this->getBakSegment()->__toString();
        $fileContent[] = $this->getNSegment()->__toString();

        for ($i = 0; $i < count($this->getPo1Segment()); $i++){
            $fileContent[] = $this->getPo1Segment()[$i]->__toString();
            $fileContent[] = $this->getAckSegment()[$i]->__toString();
        }
        $fileContent[] = $this->getSeSegment()->__toString();

        return implode('~', $fileContent);


    }

    /**
     * @return mixed
     */
    public function getNSegment()
    {
        return $this->nSegment;
    }

    /**
     * @param mixed $nSegment
     */
    public function setNSegment($nSegment): void
    {
        $this->nSegment = $nSegment;
    }

    /**
     * @return int
     */
    public function getNumberOfSegments(){
        return count($this->getProductsData()) + 2;
    }

    /**
     * @return mixed
     */
    public function getIsaSegment()
    {
        return $this->isaSegment;
    }

    /**
     * @param mixed $isaSegment
     */
    public function setIsaSegment($isaSegment): void
    {
        $this->isaSegment = $isaSegment;
    }

    /**
     * @return mixed
     */
    public function getGsSegment()
    {
        return $this->gsSegment;
    }

    /**
     * @param mixed $gsSegment
     */
    public function setGsSegment($gsSegment): void
    {
        $this->gsSegment = $gsSegment;
    }

    /**
     * @return mixed
     */
    public function getStSegment()
    {
        return $this->stSegment;
    }

    /**
     * @param mixed $stSegment
     */
    public function setStSegment($stSegment): void
    {
        $this->stSegment = $stSegment;
    }

    /**
     * @return mixed
     */
    public function getBakSegment()
    {
        return $this->bakSegment;
    }

    /**
     * @param mixed $bakSegment
     */
    public function setBakSegment($bakSegment): void
    {
        $this->bakSegment = $bakSegment;
    }

    /**
     * @return mixed
     */
    public function getPo1Segment()
    {
        return $this->po1Segment;
    }

    /**
     * @param mixed $po1Segment
     */
    public function setPo1Segment($po1Segment): void
    {
        $this->po1Segment = $po1Segment;
    }

    /**
     * @return mixed
     */
    public function getSeSegment()
    {
        return $this->seSegment;
    }

    /**
     * @param mixed $seSegment
     */
    public function setSeSegment($seSegment): void
    {
        $this->seSegment = $seSegment;
    }

    /**
     * @return mixed
     */
    public function getIsaData()
    {
        return $this->isaData;
    }

    /**
     * @param mixed $isaData
     */
    public function setIsaData($isaData): void
    {
        $this->isaData = $isaData;
    }

    /**
     * @return mixed
     */
    public function getAckSegment()
    {
        return $this->ackSegment;
    }

    /**
     * @param mixed $ackSegment
     */
    public function setAckSegment($ackSegment): void
    {
        $this->ackSegment = $ackSegment;
    }

    /**
     * @return mixed
     */
    public function getProductsData()
    {
        return $this->productsData;
    }

    /**
     * @param mixed $productsData
     */
    public function setProductsData($productsData): void
    {
        $this->productsData = $productsData;
    }
}