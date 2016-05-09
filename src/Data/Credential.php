<?php
namespace Tael\Nosp\Data;


class Credential
{
    /**
     * @var string
     */
    private $id;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPw()
    {
        return $this->pw;
    }

    /**
     * @return string
     */
    public function getEncPw()
    {
        return $this->encPw;
    }
    /**
     * @var string
     */
    private $pw;
    /**
     * @var string
     */
    private $encPw;

    /**
     * Credential constructor.
     * @param string $id
     * @param string $pw
     * @param string $encPw
     */
    public function __construct($id, $pw, $encPw)
    {
        $this->id = $id;
        $this->pw = $pw;
        $this->encPw = $encPw;
    }
}