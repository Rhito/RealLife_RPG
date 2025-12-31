<?php

require 'vendor/autoload.php';

use RRule\RRule;
use Carbon\Carbon;

Carbon::setTestNow(Carbon::create(2025, 1, 1, 0, 0, 0));
$today = Carbon::today();

echo "Today (start of day): " . $today->copy()->startOfDay()->toDateTimeString() . "\n";
echo "Today (end of day): " . $today->copy()->endOfDay()->toDateTimeString() . "\n\n";

$rule = 'FREQ=DAILY;INTERVAL=1;DTSTART=20250101T000000';
echo "Rule: $rule\n";

try {
    $rrule = new RRule($rule);
    echo "RRule created successfully\n";
    
    $occurrences = $rrule->getOccurrencesBetween($today->copy()->startOfDay(), $today->copy()->endOfDay());
    
    echo "Occurrences count: " . count($occurrences) . "\n";
    
    if (count($occurrences) > 0) {
        echo "Occurrences:\n";
        foreach ($occurrences as $occurrence) {
            echo "  - " . $occurrence->format('Y-m-d H:i:s') . "\n";
        }
    }
    
    // Try alternative approach
    echo "\nTrying alternative: occursAt\n";
    $occursAt = $rrule->occursAt($today->copy()->startOfDay());
    echo "occursAt (startOfDay): " . ($occursAt ? 'true' : 'false') . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
