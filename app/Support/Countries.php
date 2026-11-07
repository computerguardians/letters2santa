<?php

namespace App\Support;

use Locale;

class Countries
{
    /** Countries shown first in the select list. */
    public const PRIORITY = ['AU', 'NZ', 'GB', 'US', 'CA', 'IE', 'SG', 'IN', 'ZA'];

    public const CODES = [
        'AD','AE','AF','AG','AI','AL','AM','AO','AR','AS','AT','AU','AW','AX','AZ','BA','BB','BD','BE','BF','BG','BH','BI','BJ','BL','BM','BN','BO',
        'BQ','BR','BS','BT','BW','BY','BZ','CA','CC','CD','CF','CG','CH','CI','CK','CL','CM','CN','CO','CR','CU','CV','CW','CX','CY','CZ','DE','DJ',
        'DK','DM','DO','DZ','EC','EE','EG','ER','ES','ET','FI','FJ','FK','FM','FO','FR','GA','GB','GD','GE','GF','GG','GH','GI','GL','GM','GN','GP',
        'GQ','GR','GT','GU','GW','GY','HK','HN','HR','HT','HU','ID','IE','IL','IM','IN','IQ','IS','IT','JE','JM','JO','JP','KE','KG','KH','KI',
        'KM','KN','KR','KW','KY','KZ','LA','LB','LC','LI','LK','LR','LS','LT','LU','LV','LY','MA','MC','MD','ME','MF','MG','MH','MK','ML','MM',
        'MN','MO','MP','MQ','MR','MS','MT','MU','MV','MW','MX','MY','MZ','NA','NC','NE','NF','NG','NI','NL','NO','NP','NR','NU','NZ','OM','PA',
        'PE','PF','PG','PH','PK','PL','PM','PR','PS','PT','PW','PY','QA','RE','RO','RS','RU','RW','SA','SB','SC','SE','SG','SH','SI','SK','SL',
        'SM','SN','SO','SR','ST','SV','SX','SZ','TC','TD','TG','TH','TJ','TK','TL','TM','TN','TO','TR','TT','TV','TW','TZ','UA','UG','US','UY',
        'UZ','VA','VC','VE','VG','VI','VN','VU','WF','WS','YE','YT','ZA','ZM','ZW',
    ];

    public static function name(?string $code): string
    {
        if (! $code) {
            return '';
        }
        $name = Locale::getDisplayRegion('-'.strtoupper($code), 'en');

        return $name === strtoupper($code) ? $code : $name;
    }

    /** Regional-indicator emoji flag, e.g. AU -> 🇦🇺 */
    public static function flag(?string $code): string
    {
        $code = strtoupper((string) $code);
        if (! preg_match('/^[A-Z]{2}$/', $code)) {
            return '';
        }

        return mb_chr(0x1F1E6 + ord($code[0]) - 65).mb_chr(0x1F1E6 + ord($code[1]) - 65);
    }

    /** [code => name], priority countries first then alphabetical. */
    public static function options(): array
    {
        $rest = array_diff(self::CODES, self::PRIORITY);
        $named = [];
        foreach ($rest as $c) {
            $named[$c] = self::name($c);
        }
        asort($named);

        $out = [];
        foreach (self::PRIORITY as $c) {
            $out[$c] = self::name($c);
        }

        return $out + $named;
    }

    public static function isValid(?string $code): bool
    {
        return in_array(strtoupper((string) $code), self::CODES, true);
    }
}
