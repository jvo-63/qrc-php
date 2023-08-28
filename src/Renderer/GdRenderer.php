<?php

declare(strict_types = 1);

/**
 * Qrc
 * Lightweight QR code generator
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Qrc\Renderer;

use GdImage;
use InvalidArgumentException;
use RuntimeException;

use Qrc\Renderer\AbstractRenderer;

abstract class GdRenderer extends AbstractRenderer {

    /**
     * MIME type
     */
    protected string $mime;

    /**
     * Image handle
     */
    protected ?GdImage $image = null;

    /**
     * @inheritdoc
     */
    public function render(array $code): void {
        $size = 4 * $this->scale;
        #
        $width = ($code['s'][0] * $size) + ($this->padding * 2);
        $height = ($code['s'][1] * $size) + ($this->padding * 2);
        #
        $image = imagecreatetruecolor($width, $height);
        if ( $image === false ) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Unable to create image');
            // @codeCoverageIgnoreEnd
        }
        imagesavealpha($image, true);
        #
        $color_back = $this->allocateColor($image, $this->back);
        $color_fill = $this->allocateColor($image, $this->fill);
        #
        imagefill($image, 0, 0, $color_back);
        #
        foreach ($code['b'] as $y => $row) {
            $pos_y = ($y * $size) + $this->padding;
            foreach ($row as $x => $val) {
                $pos_x = ($x * $size) + $this->padding;
                if ($val) {
                    imagefilledrectangle($image, $pos_x, $pos_y, ($pos_x + $size) - 1, ($pos_y + $size) - 1, $color_fill);
                }
            }
        }
        $this->image = $image;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @inheritdoc
     */
    public function getHtml(array $attributes = []): string {
        if (! $this->image ) {
            throw new RuntimeException('The code has not been rendered yet');
        }
        $attributes['width'] = $attributes['width'] ?? $this->width;
        $attributes['height'] = $attributes['height'] ?? $this->height;
        $attributes['alt'] = $attributes['alt'] ?? '';
        $attrs = join(' ', array_map(function($key) use ($attributes) {
            if( is_bool($attributes[$key]) ) {
                return $attributes[$key] ? $key : '';
            }
            return $key . '="' . $attributes[$key] . '"';
        }, array_keys($attributes)));
        $src = $this->getDataUrl();
        $html = sprintf('<img src="%s" %s>', $src, $attrs);
        return $html;
    }

    /**
     * @inheritdoc
     */
    public function getDataUrl(): string {
        ob_start ();
        $this->encode();
        $data = ob_get_clean();
        if ( $data === false ){
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Unable to generate image.');
            // @codeCoverageIgnoreEnd
        }
        $encoded = base64_encode($data);
        return sprintf('data:%s;base64,%s', $this->mime, $encoded);
    }

    /**
     * @inheritdoc
     */
    public function save(string $file): void {
        if (! $this->image ) {
            throw new RuntimeException('The code has not been rendered yet');
        }
        if ( file_exists($file) && (!is_writable($file) || is_dir($file)) ) {
            throw new InvalidArgumentException('The specified file is not writable');
        }
        $this->encode($file);
    }

    /**
     * Allocate a color
     * @param  GdImage $image Image handle
     * @param  string  $color HTML color
     */
    protected function allocateColor(GdImage $image, string $color): int {
        $color = preg_replace('/[^0-9A-Fa-f]/', '', $color);
        if ($color === null) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException("Invalid color code: '{$color}'");
            // @codeCoverageIgnoreEnd
        }
        $components = str_split(ltrim($color, '#'), strlen($color) > 4 ? 2 : 1);
        if ( !is_array($components) || count($components) != 3 ) {
            throw new RuntimeException("Invalid color code: '{$color}'");
        }
        list($r, $g, $b) = array_map(function ($c) {
            return (int)hexdec(str_pad($c, 2, $c));
        }, $components);
        $identifier = imagecolorallocate($image, $r, $g, $b);
        if ($identifier === false) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Unable to allocate color');
            // @codeCoverageIgnoreEnd
        }
        return $identifier;
    }

    /**
     * Encode the image
     * @param  string|null $file File name
     */
    protected abstract function encode(?string $file = null): void;
}
