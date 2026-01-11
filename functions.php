<?php
function weatherCodeToText($code)
{
    switch ($code) {
        case 0:
            return "clear sky";
        case 1:
            return "mainly clear";
        case 2:
            return "partly cloudy";
        case 3:
            return "overcast";
        default:
            return "unknown";
    }
}

?>