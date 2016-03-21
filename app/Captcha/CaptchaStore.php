<?php

namespace App\Captcha;

class CaptchaStore implements \Serializable
{
    /**
     * @var array
     */
    protected $store = [];

    public function serialize()
    {
        return serialize($this->store);
    }

    public function unserialize($serialized)
    {
        $rawStore = unserialize($serialized);

        foreach ($rawStore as $key => $value) {
            if ($value['expiresAt']->getTimestamp() < time()) {
                unset($rawStore[$key]);
            }
        }

        $this->store = $rawStore;
    }

    /**
     * @param boolean $sensitive
     * @param string $bag
     * @param \DateTime $expiresAt
     */
    public function add($sensitive, $bag, \DateTime $expiresAt)
    {
        $this->store[] = compact('sensitive', 'bag', 'expiresAt');
    }

    /**
     * @param string $captcha
     * @return bool
     */
    public function check($captcha)
    {
        $result = false;

        foreach ($this->store as $key => $value) {
            $result = ($value['bag'] === ($value['sensitive'] ? $captcha : mb_strtolower($captcha, 'UTF-8')));

            if ($result) {
                unset($this->store[$key]);
                break;
            }
        }

        return $result;
    }
}