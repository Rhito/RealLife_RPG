<?php

require 'vendor/autoload.php';

use RRule\RRule;
use Carbon\Carbon;

echo "Method 1: Using array with dtstart\n";
try {
    $rrule = new RRule([
        'FREQ' => 'DAILY',
        'INTERVAL' => 1,
        'DTSTART' => '2025-01-01 00:00:00',
    ]);
    echo "  ✓ Valid\n";
    echo "  RFC String: " . $rrule->rfcString() . "\n";
    $today = Carbon::create(2025, 1, 1, 0, 0, 0);
    $occurrences = $rrule->getOccurrencesBetween($today->copy()->startOfDay(), $today->copy()->endOfDay());
    echo "  Occurrences: " . count($occurrences) . "\n";
} catch (\Exception $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n";
}

echo "\nMethod 2: Just FREQ without DTSTART\n";
try {
    $rrule = new RRule('FREQ=DAILY;INTERVAL=1');
    echo "  ✓ Valid\n";
    $today = Carbon::create(2025, 1, 1, 0, 0, 0);
    $occurrences = $rrule->getOccurrencesBetween($today->copy()->startOfDay(), $today->copy()->endOfDay());
    echo "  Occurrences: " . count($occurrences) . "\n"; 
    if (count($occurrences) > 0) {
        foreach ($occurrences as $occ) {
            echo "    - " . $occ->format('Y-m-d H:i:s') . "\n";
        }
    }
} catch (\Exception $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n";
}

echo "\nMethod 3: Parse the rule, ignoring DTSTART\n";
try {
    $ruleString = 'FREQ=DAILY;INTERVAL=1;DTSTART=20250101T000000';
    // Remove DTSTART from the string
    $cleanRule = preg_replace('/;?DTSTART=[^;]+/', '', $ruleString);
    echo "  Clean rule: $cleanRule\n";
    $rrule = new RRule($cleanRule);
    echo "  ✓ Valid\n";
    $today = Carbon::create(2025, 1, 1, 0, 0, 0);
    $occurrences = $rrule->getOccurrencesBetween($today->copy()->startOfDay(), $today->copy()->endOfDay());
    echo "  Occurrences: " . count( $occurrences) . "\n";
} catch(\Exception $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n";
}
