<?php

/**
 * php-gedcom.
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @license         MIT
 *
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace Gedcom\Parser\SourRef;

class Data extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser): mixed
    {
        $data = new \Gedcom\Record\SourRef\Data();
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];

        $parser->forward();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtoupper(trim((string) $record[1]));
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            match ($recordType) {
                'DATE' => $data->setDate(trim((string) $record[2])),
                'TEXT' => $data->setText($parser->parseMultiLineRecord()),
                default => $parser->logUnhandledRecord(self::class.' @ '.__LINE__),
            };

            $parser->forward();
        }

        return $data;
    }
}
