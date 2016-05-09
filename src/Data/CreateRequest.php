<?php
namespace Tael\Nosp\Data;

class CreateRequest
{
    public $adMngStep;
    /**
     * @var AdInput[]
     */
    public $listAdInput = [];
    public $campId = "";

    /**
     * CreateRequest constructor.
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