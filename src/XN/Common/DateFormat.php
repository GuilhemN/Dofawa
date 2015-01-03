<?php

namespace XN\Common;

class DateFormat
{
    public static function formatDate($translator, \Datetime $datetime, $type = 'medium', $textual = true, $locale = null){
		if($type == ('short' or 'medium'))
			$format = self::dateParams('formats.' . (int) $textual . '.' . $type, $locale, $translator);
		else
			$format = $type;

		$infos = getdate($datetime->getTimestamp());

		if($textual) {
            $now = new \Datetime;
			$diff = $datetime->diff($now);

			if ($type == 'medium' && ($diff->y && $diff->m) == 0 ){
                if($diff->d == 0 && $diff->h == 0 && $diff->i == 0)
                    return $translator->trans('formats.1.justNow', [], 'date', $locale);
                elseif($now->getTimestamp() > time()){
    				if($diff->d == 1 or (date('G') < $diff->h && $diff->d == 0))
    					$format = self::dateParams('formats.1.yesterdayAt', $locale, $translator);
    				elseif($diff->d == 0 && $diff->h <= 2){
    					if($diff->h == 0)
    						return $translator->trans('formats.1.xMinutesAgo', ['%m%' => $diff->i], 'date', $locale);
    					else
    						return $translator->trans('formats.1.xHoursAgo', ['%h%' => $diff->h, '%m%' => $diff->i], 'date', $locale);
    				}
                    elseif($diff->d == 0)
                        $format = self::dateParams('formats.1.todayAt', $locale, $translator);
                }
                else {
                    if($diff->d == 1 or (date('G') < $diff->h && $diff->d == 0))
                        $format = self::dateParams('formats.1.tomorrowAt', $locale, $translator);
                    elseif($diff->d == 0 && $diff->h <= 2){
                        if($diff->h == 0)
                            return $translator->trans('formats.1.inXMinutes', ['%m%' => $diff->i], 'date', $locale);
                        else
                            return $translator->trans('formats.1.inXHours', ['%h%' => $diff->h, '%m%' => $diff->i], 'date', $locale);
                    }
                    elseif($diff->d == 0)
                        $format = self::dateParams('formats.1.todayAt', $locale, $translator);
                }

            }
			elseif($type == 'short'){
				if($diff->y == 0)
					if($diff->m == (1 || 2))
						return $translator->trans('formats.1.xMonthsAgo', ['%m%' => $diff->m, '%d%' => $diff->d], 'date', $locale);
					elseif($diff->m == 0 && $diff->d > 0)
						return $translator->trans('formats.1.xDaysAgo', ['%d%' => $diff->d], 'date', $locale);
            }

		}

		if($infos['hours'] > 12)
			$infos['hours-12'] = $infos['hours'] - 12;
		else
			$infos['hours-12'] = $infos['hours'];

		$fields = [
			// Years
			'%Y' => $infos['year'],

			// Months
			'%B' => self::dateParams('months.' . $infos['mon'], $locale, $translator),
			'%m' => sprintf("%02s", $infos['mon']),

			// Days
			'%A' => self::dateParams('days.' . $infos['wday'], $locale, $translator),
			'%d' => sprintf("%02s", $infos['mday']),
			'%e' => $infos['mday'],

			// Hours
			'%H' => sprintf("%02s", $infos['hours']),
			'%l' => $infos['hours-12'],
			'%k' => $infos['hours'],

			// Minutes
			'%M' => sprintf("%02s", $infos['minutes']),

			// Seconds
			'%S' => sprintf("%02s", $infos['seconds']),
		];

		return str_replace(array_keys($fields), array_values($fields), $format);
	}

	protected static function dateParams($string, $locale = null, $translator){
		return $translator->trans($string, [], 'date', $locale);
	}
}
