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
use Qrc\Renderer\SvgRenderer;

class SvgRendererTest extends TestCase {

    public function testConstructor() {
        $renderer = new SvgRenderer();
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
        $this->assertStringContainsString('<svg xmlns="http://www.w3.org/2000/svg"', $qr->toHtml(attributes: ['class' => 'img-fluid', 'lazy' => true]));
        $this->assertStringContainsString('image/svg+xml', $qr->toDataUrl());
    }

    public function testHtmlNotRendererYet() {
        $renderer = new SvgRenderer();
        $qr = QrCode::newInstance($renderer);
        $this->expectException(RuntimeException::class);
        $qr->toHtml();
        $this->assertTrue(true);
    }

    public function testSave() {
        $renderer = new SvgRenderer();
        $qr = QrCode::newInstance($renderer)
            ->setData('0123456789')
            ->render();
        #
        $file = dirname(__FILE__) . '/output/qr.svg';
        $qr->toFile($file);
        $this->assertFileExists($file);
    }

    public function testSaveNotRendererYet() {
        $renderer = new SvgRenderer();
        $qr = QrCode::newInstance($renderer);
        $this->expectException(RuntimeException::class);
        $qr->toFile('dummy.svg');
        $this->assertTrue(true);
    }

    public function testSaveNotWriteable() {
        $renderer = new SvgRenderer();
        $qr = QrCode::newInstance($renderer)
            ->setData('0123456789')
            ->render();
        $this->expectException(InvalidArgumentException::class);
        $file = dirname(__FILE__) . '/output';
        $qr->toFile($file);
        $this->assertTrue(true);
    }
}
