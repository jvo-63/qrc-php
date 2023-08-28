<?php

declare(strict_types = 1);

/**
 * Qrc
 * Lightweight QR code generator
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Qrc\Renderer;

use InvalidArgumentException;

use Qrc\Renderer\RendererInterface;

abstract class AbstractRenderer implements RendererInterface {

    /**
     * Scale factor
     */
    protected int $scale;

    /**
     * Fill color
     */
    protected string $fill;

    /**
     * Back color
     */
    protected string $back;

    /**
     * Generated code width
     */
    protected int $width;

    /**
     * Generated code height
     */
    protected int $height;

    /**
     * Padding
     */
    protected int $padding;

    /**
     * Constructor
     * @param string $fill    Fill color
     * @param string $back    Back color
     * @param int    $scale   Scale factor
     * @param int    $padding Padding
     */
    public function __construct(string $fill = '#000', string $back = '#FFF', int $padding = 15, $scale = 1) {
        if ($padding < 0) {
            throw new InvalidArgumentException('Padding must be greater than or equal to 0');
        }
        if ($scale <= 0) {
            throw new InvalidArgumentException('Scale must be greater than or equal to 1');
        }
        $this->fill = $fill;
        $this->back = $back;
        $this->padding = $padding;
        $this->scale = $scale;
    }

    /**
     * Get fill color
     */
    public function getFillColor(): string {
        return $this->fill;
    }

    /**
     * Get back color
     */
    public function getBackColor(): string {
        return $this->back;
    }

    /**
     * Get padding
     */
    public function getPadding(): int {
        return $this->padding;
    }

    /**
     * Get scale factor
     */
    public function getScale(): int {
        return $this->scale;
    }

    /**
     * Set fill color
     * @param string $fill Fill color
     * @return $this
     */
    public function setFillColor(string $fill) {
        $this->fill = $fill;
        return $this;
    }

    /**
     * Set back color
     * @param string $back Back color
     * @return $this
     */
    public function setBackColor(string $back) {
        $this->back = $back;
        return $this;
    }

    /**
     * Set padding
     * @param int $padding Padding
     * @return $this
     */
    public function setPadding(int $padding) {
        if ($padding < 0) {
            throw new InvalidArgumentException('Padding must be greater than or equal to 0');
        }
        $this->padding = $padding;
        return $this;
    }

    /**
     * Set scale factor
     * @param int $scale Scale factor
     * @return $this
     */
    public function setScale(int $scale) {
        if ($scale <= 0) {
            throw new InvalidArgumentException('Scale must be greater than or equal to 1');
        }
        $this->scale = $scale;
        return $this;
    }
}
