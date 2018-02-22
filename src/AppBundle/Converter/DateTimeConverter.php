<?php

namespace AppBundle\Converter;

class DateTimeConverter
{
    const DISCONTINUED_MARK = 'yes';
    const FIELDNAME_DISCONTINUED = 'dtmDiscontinued';
    const FIELDNAME_DTM_ADDED = 'dtmAdded';

    public function __invoke($input)
    {
        if ($input[self::FIELDNAME_DISCONTINUED] == self::DISCONTINUED_MARK) {
            $input[self::FIELDNAME_DISCONTINUED] = new \DateTime();
        } else {
            $input[self::FIELDNAME_DISCONTINUED] = null;
        }

        $input[self::FIELDNAME_DTM_ADDED] = new \DateTime();

        return $input;
    }
}