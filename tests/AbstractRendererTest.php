<?php

declare(strict_types = 1);

/**
 * Qrc
 * Lightweight QR code generator
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Qrc\Tests;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

use Qrc\Renderer\AbstractRenderer;

class AbstractRendererTest extends TestCase {

    public function testInvalidPaddingOnConstructor() {
        $this->expectException(InvalidArgumentException::class);
        $renderer = new TestRenderer(padding: -1);
    }

    public function testInvalidPaddingSetter() {
        $this->expectException(InvalidArgumentException::class);
        $renderer = new TestRenderer();
        $renderer->setPadding(-1);
    }

    public function testInvalidScaleSetter() {
        $this->expectException(InvalidArgumentException::class);
        $renderer = new TestRenderer();
        $renderer->setScale(0);
    }

    public function testInvalidScaleOnConstructor() {
        $this->expectException(InvalidArgumentException::class);
        $renderer = new TestRenderer(scale: 0);
    }
}

class TestRenderer extends AbstractRenderer {

    public function render(array $code): void {
        #
    }

    public function getHtml(array $attributes = []): string {
        return 'getHtml';
    }

    public function getDataUrl(): string {
        return 'getDataUrl';
    }

    public function save(string $file): void {
        #
    }
}
