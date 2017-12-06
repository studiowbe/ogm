<?php


namespace Studiow\OGM;

use InvalidArgumentException;

class OGM
{

    private $code;

    /**
     * OGM constructor.
     *
     *
     * @param string|null $code
     */
    public function __construct(string $code = null)
    {
        if (is_null($code)) {
            $base = implode('', array_map(function () {
                return rand(0, 9);
            }, range(0, 10)));

            $control = self::getControlForCode($base);
            $this->code = $base . $control;
        } else {
            if (
                !preg_match('#^[0-9]{3}\/[0-9]{4}\/[0-9]{5}$#', $code) &&
                !preg_match('#^[\*]{3}[0-9]{3}\/[0-9]{4}\/[0-9]{5}[\*]{3}$#', $code) &&
                !preg_match('#^[\+]{3}[0-9]{3}\/[0-9]{4}\/[0-9]{5}[\+]{3}$#', $code)
            ) {
                throw new InvalidArgumentException('Malformed OGM code');
            }
            $this->code = preg_replace('#[^0-9]#', '', $code);
        }
    }

    public function getBase(): string
    {
        return substr($this->code, 0, -2);
    }

    public function getControl(): string
    {
        return substr($this->code, -2);
    }

    /**
     * Output in a human-readable format
     *
     * @return string
     */
    public function output()
    {
        return substr($this->code, 0, 3) . '/' . substr($this->code, 3, 4) . '/' . substr($this->code, 7, 5);
    }

    /**
     * Check the format and control number
     *
     * @param string $code
     * @return bool
     */
    public static function verify(string $code): bool
    {
        $ogm = new self($code);

        $expected = self::getControlForCode($ogm->getBase());
        return $ogm->getControl() === $expected;
    }

    /**
     * Calculate the control number
     *
     * @param string $code
     * @return string
     */
    public static function getControlForCode(string $code): string
    {
        $control = $code % 97;

        if ($control == 0) {
            return '97';
        } else {
            return substr("0{$control}", -2);
        }
    }

}