<?php

declare(strict_types = 1);

/**
 * Qrc
 * Lightweight QR code generator
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Qrc\Tests;

use RuntimeException;

use PHPUnit\Framework\TestCase;

use Qrc\ErrorCorrection;
use Qrc\QrCode;
use Qrc\Renderer\AbstractRenderer;

class QrCodeTest extends TestCase {

    public function testSettersGetters() {
        $renderer = new NullRenderer();
        $qr = QrCode::newInstance($renderer)
            ->setData('0123456789')
            ->setRenderer($renderer)
            ->setErrorCorrection(ErrorCorrection::Medium)
            ->render();
        $this->assertInstanceOf(ErrorCorrection::class, $qr->getErrorCorrection());
        $this->assertInstanceOf(NullRenderer::class, $qr->getRenderer());
        $this->assertEquals('0123456789', $qr->getData());
        $this->assertEquals('getHtml', $qr->toHtml());
        $this->assertEquals('getDataUrl', $qr->toDataUrl());
    }

    public function testEncodeNumeric() {
        $renderer = new NullRenderer();
        $qr = QrCode::newInstance($renderer)
            ->setData('0123456789')
            ->render();
        $mode = $renderer->getMode();
        $this->assertEquals(0, $mode);
    }

    public function testEncodeAlphanumeric() {
        $renderer = new NullRenderer();
        $qr = QrCode::newInstance($renderer)
            ->setData('LOREM 012345')
            ->render();
        $mode = $renderer->getMode();
        $this->assertEquals(1, $mode);
    }

    public function testEncodeBinary() {
        $renderer = new NullRenderer();
        $qr = QrCode::newInstance($renderer)
            ->setData('Lorem ipsum dolor sit amet 🍔🍟')
            ->render();
        $mode = $renderer->getMode();
        $this->assertEquals(2, $mode);
    }

    public function testEncodeKanji() {
        $renderer = new NullRenderer();
        $qr = QrCode::newInstance($renderer)
            ->setData('茗荷')
            ->render();
        $mode = $renderer->getMode();
        $this->assertEquals(3, $mode);
    }

    public function testToHtmlEcho() {
        $renderer = new NullRenderer();
        $qr = QrCode::newInstance($renderer)
            ->setData('0123456789')
            ->render();
        #
        $file = dirname(__FILE__) . '/output/qr.txt';
        $qr->toFile($file);
        $this->assertFileExists($file);
        #
        $this->expectOutputString('getHtml');
        $qr->toHtml(true);
        $this->assertTrue(true);
    }

    public function testToDataUrlEcho() {
        $renderer = new NullRenderer();
        $qr = QrCode::newInstance($renderer)
            ->setData('0123456789')
            ->render();
        #
        $this->expectOutputString('getDataUrl');
        $qr->toDataUrl(true);
        $this->assertTrue(true);
    }

    public function testToFile() {
        $renderer = new NullRenderer();
        $qr = QrCode::newInstance($renderer)
            ->setData('0123456789')
            ->render();
        #
        $file = dirname(__FILE__) . '/output/qr.txt';
        $qr->toFile($file);
        $this->assertFileExists($file);
    }
}

class NullRenderer extends AbstractRenderer {

    protected array $code;

    public function render(array $code): void {
        if (! $code ) {
            throw new RuntimeException('Code is not valid');
        }
        $this->code = $code;
    }

    public function getCode(): array {
        return $this->code;
    }

    public function getMode(): int {
        return $this->code['m'] ?? -1;
    }

    public function getHtml(array $attributes = []): string {
        return 'getHtml';
    }

    public function getDataUrl(): string {
        return 'getDataUrl';
    }

    public function save(string $file): void {
        file_put_contents($file, json_encode($this->code));
    }
}
