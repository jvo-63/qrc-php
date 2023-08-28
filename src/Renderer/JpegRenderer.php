<?php

declare(strict_types = 1);

/**
 * Qrc
 * Lightweight QR code generator
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Qrc\Renderer;

use RuntimeException;

use Qrc\Renderer\GdRenderer;

class JpegRenderer extends GdRenderer {

    /**
     * MIME type
     */
    protected string $mime = 'image/jpeg';

    /**
     * JPEG quality
     */
    protected int $quality = 90;

    /**
     * Get JPEG quality
     */
    public function getQuality(): int {
        return $this->quality;
    }

    /**
     * Set JPEG quality
     * @param int $quality JPEG quality
     * @return $this
     */
    public function setQuality(int $quality) {
        $this->quality = $quality;
        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function encode(?string $file = null): void {
        if ($this->image === null) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Can not encode NULL image');
            // @codeCoverageIgnoreEnd
        }
        imagejpeg($this->image, $file, $this->quality);
    }
}
