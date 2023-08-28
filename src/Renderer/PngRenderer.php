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

class PngRenderer extends GdRenderer {

    /**
     * MIME type
     */
    protected string $mime = 'image/png';

    /**
     * @inheritdoc
     */
    protected function encode(?string $file = null): void {
        if ($this->image === null) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Can not encode NULL image');
            // @codeCoverageIgnoreEnd
        }
        imagepng($this->image, $file);
    }
}
