<?php
namespace Tael\Nosp\Data;

class CreateRequest
{
    /**
     * @var AdInput[]
     */
    public $listAdInput = [];
    public $campId = "";

    /**
     * CreateRequest constructor.
     * @param string $campId
     */
    public function __construct($campId)
    {
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