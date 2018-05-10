<?php

namespace AbuseIO\Parsers;

use AbuseIO\Models\Incident;
use Log;

/**
 * Class Comeso
 * @package AbuseIO\Parsers
 */
class Comeso extends Parser
{
    /**
     * Create a new Comeso instance
     *
     * @param \PhpMimeMailParser\Parser $parsedMail phpMimeParser object
     * @param array $arfMail array with ARF detected results
     */
    public function __construct($parsedMail, $arfMail)
    {
        parent::__construct($parsedMail, $arfMail, $this);
    }

    /**
     * Parse attachments
     * @return array    Returns array with failed or success data
     *                  (See parser-common/src/Parser.php) for more info.
     */
    public function parse()
    {
        if ($this->arfMail !== true) {
            $this->feedName = 'default';

            // If feed is known and enabled, validate data and save report
            if ($this->isKnownFeed() && $this->isEnabledFeed()) {

                // To get some more consitency, remove "\r" from the report.
                $this->arfMail['report'] = str_replace("\r", "", $this->arfMail['report']);

                // Build up the report
                preg_match_all(
                    '/([\w\-]+): (.*)[ ]*\r?\n/',
                    $this->arfMail['report'],
                    $matches
                );

                $report = array_combine($matches[1], $matches[2]);

                $report['timestamp'] = time();

                // Sanity check
                if ($this->hasRequiredFields($report) === true) {

                    ksort($report);

                    // incident has all requirements met, filter and add!
                    $report = $this->applyFilters($report);

                    $incident = new Incident();
                    $incident->source      = config("{$this->configBase}.parser.name");
                    $incident->source_id   = false;
                    $incident->ip          = $report['IP'];
                    $incident->domain      = false;
                    $incident->class       = config("{$this->configBase}.feeds.{$this->feedName}.class");
                    $incident->type        = config("{$this->configBase}.feeds.{$this->feedName}.type");
                    $incident->timestamp   = $report['timestamp']);
                    $incident->information = json_encode($report);

                    $this->incidents[] = $incident;

                }
            }
        }
        Log::info('japje : ' . json_encode($this->incidents);
        return $this->success();
    }
}