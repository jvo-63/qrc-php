<?php

declare(strict_types = 1);

/**
 * Qrc
 * Lightweight QR code generator
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Qrc;

enum ErrorCorrection: int {
    case Low = 0;
    case Medium = 1;
    case Quartile = 2;
    case High = 3;
}
