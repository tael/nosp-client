<?php
namespace Tael\Nosp;

class CreateRequestData
{
    public $adMngStep;
    /**
     * @var AdInput[]
     */
    public $listAdInput = [];
    public $campId = "";

    /**
     * CreateRequestData constructor.
     * @param string $adMngStep
     * @param string $campId
     */
    public function __construct($adMngStep, $campId)
    {
        $this->adMngStep = $adMngStep;
        $this->listAdInput = [];
        $this->campId = $campId;
    }

    /**
     * @param AdInput $adInput
     */
    public function addItem(AdInput $adInput)
    {
        array_push($this->listAdInput, $adInput);
    }
}