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

class WebpRenderer extends GdRenderer {

    /**
     * MIME type
     */
    protected string $mime = 'image/webp';

    /**
     * @inheritdoc
     */
    protected function encode(?string $file = null): void {
        if ($this->image === null) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Can not encode NULL image');
            // @codeCoverageIgnoreEnd
        }
        imagewebp($this->image, $file);
    }
}
