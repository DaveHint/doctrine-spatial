<?php

namespace LongitudeOne\Spatial\PHP\Types;

enum Srid: int {
    case WGS84 = 4326;
    case CH1903LV95 = 2056;
    case CH1903LV03 = 21781;
    case WEBMERCATOR = 3857;
}
