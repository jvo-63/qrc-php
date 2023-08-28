# qrc-php

Lightweight QR code generator

The QR generation logic was sourced from https://github.com/kreativekorp/barcode. It was cleaned-up, type-hinted and tested under PHP 8.2.5.

### Basic usage

First require `biohzrdmx/qrc-php` with Composer.

To create a `QrCode` instance you will need to pass a `RendererInterface` implementation instance, there are five included:

- `GifRenderer` - Renders a GIF image
- `JpegRenderer` - Renders a JPEG image
- `PngRenderer` - Renders a PNG image
- `SvgRenderer` - Renders a SVG image
- `WebpRenderer` - Renders a WEBP image

For this example we'll use the `SvgRenderer`:

```php
use Qrc\QrCode;
use Qrc\Renderer\SvgRenderer;

$renderer = new SvgRenderer();
$qr = QrCode::newInstance($renderer)
	->setData('https://example.com')
	->render()
	->toHtml(true);
```

The above code sets the data to an URL, renders the code and shows it in an HTML `img` tag.

You can also save the resulting image:

```php
use Qrc\QrCode;
use Qrc\Renderer\SvgRenderer;

$renderer = new SvgRenderer();
$qr = QrCode::newInstance($renderer)
	->setData('https://example.com')
	->render()
	->toFile('qr.svg');
```

#### Advanced options

All of the renderers support changing the background and fill colors, as well as the padding and scale.

You can set them up by passing those parameters directly to the renderer constructor o by using the setter functions:

```php
use Qrc\Renderer\SvgRenderer;

$renderer = new SvgRenderer('#222', '#FAFAFA', 30, 2); # Use a 30px padding and 2x scale
```

Is the same as:

```php
use Qrc\Renderer\SvgRenderer;

$renderer = new SvgRenderer();
$renderer->setFillColor('#222');
$renderer->setBackColor('#FAFAFA');
$renderer->setPadding(30);
$renderer->setScale(2);
```

You can also change the error correction level by specifying it when creating the QrCode instance:

```php
use Qrc\QrCode;
use Qrc\ErrorCorrection;
use Qrc\Renderer\SvgRenderer;

$renderer = new SvgRenderer();
$qr = QrCode::newInstance($renderer, ErrorCorrection::Medium);
```

As per-spec, there are four error correction levels (from lowest to highest):

- `ErrorCorrection::Low`
- `ErrorCorrection::Medium`
- `ErrorCorrection::Quartile`
- `ErrorCorrection::High`

### Licensing

This software is released under the MIT license.

Copyright &copy; 2023 biohzrdmx

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

### Credits

**Lead coder:** biohzrdmx [github.com/biohzrdmx](http://github.com/biohzrdmx)

### Trademark Notice

The word "QR Code" is a registered trademark of DENSO WAVE INCORPORATED
https://www.qrcode.com/en/faq.html#patentH2Title
