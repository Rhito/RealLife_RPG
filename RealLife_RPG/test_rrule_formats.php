<?php

require 'vendor/autoload.php';

use RRule\RRule;
use Carbon\Carbon;

// Test different DTSTART formats
$formats = [
    'FREQ=DAILY;INTERVAL=1;DTSTART=20250101T000000',
    'DTSTART=20250101T000000Z;FREQ=DAILY;INTERVAL=1',
    'FREQ=DAILY;INTERVAL=1;DTSTART:20250101T000000',
    'FREQ=DAILY;INTERVAL=1',
];

foreach ($formats as $rule) {
    echo "\nTesting: $rule\n";
    try {
        $rrule = new RRule($rule);
        echo "  ✓ Valid\n";
        $today = Carbon::create(2025, 1, 1, 0, 0, 0);
        $occurrences = $rrule->getOccurrencesBetween($today->copy()->startOfDay(), $today->copy()->endOfDay());
        echo "  Occurrences: " . count($occurrences) . "\n";
    } catch (\Exception $e) {
        echo "  ✗ Error: " .  $e->getMessage() . "\n";
    }
}
