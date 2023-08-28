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
use RuntimeException;

use PHPUnit\Framework\TestCase;

use Qrc\QrCode;
use Qrc\Renderer\GifRenderer;
use Qrc\Renderer\JpegRenderer;
use Qrc\Renderer\PngRenderer;
use Qrc\Renderer\WebpRenderer;

class GdRendererTest extends TestCase {

    public function testConstructor() {
        $renderer = new PngRenderer();
        $renderer->setBackColor('#C00')
            ->setFillColor('#00C')
            ->setPadding(20)
            ->setScale(2);
        $qr = QrCode::newInstance($renderer)
            ->setData('0123456789')
            ->render();
        #
        $this->assertEquals('#C00', $renderer->getBackColor());
        $this->assertEquals('#00C', $renderer->getFillColor());
        $this->assertEquals(20, $renderer->getPadding());
        $this->assertEquals(2, $renderer->getScale());
        #
        $this->assertStringContainsString('<img src="data:image/', $qr->toHtml(attributes: ['class' => 'img-fluid', 'lazy' => true]));
        $this->assertStringContainsString('image/png', $qr->toDataUrl());
    }

    public function testInvalidColor() {
        $renderer = new PngRenderer('red');
        $this->expectException(RuntimeException::class);
        $qr = QrCode::newInstance($renderer)
            ->setData('0123456789')
            ->render();
        $this->assertTrue(true);
    }

    public function testHtmlNotRendererYet() {
        $renderer = new PngRenderer();
        $qr = QrCode::newInstance($renderer);
        $this->expectException(RuntimeException::class);
        $qr->toHtml();
        $this->assertTrue(true);
    }

    public function testSave() {
        $renderer = new PngRenderer();
        $qr = QrCode::newInstance($renderer)
            ->setData('0123456789')
            ->render();
        #
        $file = dirname(__FILE__) . '/output/qr.png';
        $qr->toFile($file);
        $this->assertFileExists($file);
    }

    public function testSaveNotRendererYet() {
        $renderer = new PngRenderer();
        $qr = QrCode::newInstance($renderer);
        $this->expectException(RuntimeException::class);
        $qr->toFile('dummy.png');
        $this->assertTrue(true);
    }

    public function testSaveNotWriteable() {
        $renderer = new PngRenderer();
        $qr = QrCode::newInstance($renderer)
            ->setData('0123456789')
            ->render();
        $this->expectException(InvalidArgumentException::class);
        $file = dirname(__FILE__) . '/output';
        $qr->toFile($file);
        $this->assertTrue(true);
    }

    public function testJpegRenderer() {
        $renderer = new JpegRenderer();
        $renderer->setQuality(85);
        $qr = QrCode::newInstance($renderer)
            ->setData('0123456789')
            ->render();
        #
        $this->assertEquals(85, $renderer->getQuality());
        #
        $this->assertStringContainsString('<img src="data:image/', $qr->toHtml());
        $this->assertStringContainsString('image/jpeg', $qr->toDataUrl());
    }

    public function testGifRenderer() {
        $renderer = new GifRenderer();
        $qr = QrCode::newInstance($renderer)
            ->setData('0123456789')
            ->render();
        #
        $this->assertStringContainsString('<img src="data:image/', $qr->toHtml());
        $this->assertStringContainsString('image/gif', $qr->toDataUrl());
    }

    public function testWebpRenderer() {
        $renderer = new WebpRenderer();
        $qr = QrCode::newInstance($renderer)
            ->setData('0123456789')
            ->render();
        #
        $this->assertStringContainsString('<img src="data:image/', $qr->toHtml());
        $this->assertStringContainsString('image/webp', $qr->toDataUrl());
    }
}
