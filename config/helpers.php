<?php

function tanggalIndonesia($tanggal = 'now', $format = 'EEEE, dd MMMM yyyy')
{
    $formatter = new IntlDateFormatter(
        'id_ID',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        TIMEZONE,
        IntlDateFormatter::GREGORIAN,
        $format
    );

    return $formatter->format(is_numeric($tanggal) ? $tanggal : strtotime($tanggal));
}
