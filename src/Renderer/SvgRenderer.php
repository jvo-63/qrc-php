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
use InvalidArgumentException;

use Qrc\Renderer\AbstractRenderer;

class SvgRenderer extends AbstractRenderer {

    /**
     * SVG code
     */
    protected ?string $svg = null;

    /**
     * @inheritdoc
     */
    public function render(array $code): void {
        $size = 4 * $this->scale;
        $this->width = ($code['s'][0] * $size) + ($this->padding * 2);
        $this->height = ($code['s'][1] * $size) + ($this->padding * 2);
        $this->svg = sprintf('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d %d" {attrs}>', $this->width, $this->height);
        $this->svg .= sprintf('<rect x="0" y="0" width="%d" height="%d" fill="%s" />', $this->width, $this->height, $this->back);
        foreach ($code['b'] as $y => $row) {
            $pos_y = ($y * $size) + $this->padding;
            foreach ($row as $x => $val) {
                $pos_x = ($x * $size) + $this->padding;
                if ($val) {
                    $this->svg .= sprintf('<rect x="%d" y="%d" width="%d" height="%d" fill="%s" />', $pos_x, $pos_y, $size, $size, $this->fill);
                }
            }
        }
        $this->svg .= '</svg>';
    }

    /**
     * @inheritdoc
     */
    public function getHtml(array $attributes = []): string {
        if (! $this->svg ) {
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
        $html = str_replace('{attrs}', $attrs, $this->svg);
        return $html;
    }

    /**
     * @inheritdoc
     */
    public function getDataUrl(): string {
        $html = str_replace(' {attrs}', '', $this->svg);
        $encoded = base64_encode($html);
        return sprintf('data:image/svg+xml;base64,%s', $encoded);
    }

    /**
     * @inheritdoc
     */
    public function save(string $file): void {
        if (! $this->svg ) {
            throw new RuntimeException('The code has not been rendered yet');
        }
        if ( file_exists($file) && (!is_writable($file) || is_dir($file)) ) {
            throw new InvalidArgumentException('The specified file is not writable');
        }
        file_put_contents($file, $this->svg);
    }
}
