<?php

date_default_timezone_set('Europe/London');

function getMiddleDay($day, $month, $year) {
    $thisMonth = mktime(0, 0, 0, $month, 0, $year);

    $daysInMonth = date('t', $thisMonth);
    $middleTimestamp = mktime(0, 0, 0, $month, floor($daysInMonth/2), $year);

    $potentials = array();
    foreach (array(2,3) as $i) {
        $timestamp = strtotime("{$i} {$day}", $thisMonth);
        $potentials[abs($timestamp - $middleTimestamp)] = $timestamp;
    }

    ksort($potentials);
    return array_shift($potentials);
}


// Google calendar explodes if you indent ical. Seriously.
header('Content-Type: text/calendar');
?>
BEGIN:VCALENDAR
PRODID:-//PUBSTANDARDS//PUBCAL 1.0//EN
CALSCALE:GREGORIAN
VERSION:2.0
METHOD:PUBLISH
X-WR-CALNAME:Pub Standards
BEGIN:VTIMEZONE
TZID:Europe/London
X-LIC-LOCATION:Europe/London
BEGIN:DAYLIGHT
TZOFFSETFROM:+0000
TZOFFSETTO:+0100
TZNAME:BST
DTSTART:19700329T010000
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:+0100
TZOFFSETTO:+0000
TZNAME:GMT
DTSTART:19701025T020000
RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU
END:STANDARD
END:VTIMEZONE
<?

for ($i = 0; $i < 100; $i++) {
    $nextMonth = strtotime("{$i} month");
    $timestamp = getMiddleDay(
        'thursday', 
        date('n', $nextMonth), 
        date('Y', $nextMonth)
    );

?>
BEGIN:VEVENT
LOCATION:The Bricklayers Arms, Gresse Street, London, W1
DTSTART:<?=date('Ymd', $timestamp)?>T183000
SUMMARY:Pub Standards
DTEND:<?=date('Ymd', $timestamp)?>T230000
DESCRIPTION:Beer, lots of beer.
END:VEVENT
<?

}

?>
END:VCALENDAR
