<?php
class BaseURL {
    const DNB_PREFIX = 'https://maxcvservices.dnb.com';
    
    public static function dnbAuth ($version = '2.0') {
        return self::DNB_PREFIX
                . '/Authentication/V' . $version . '/';
    }
    
    public static function dnbDUNS ($company, $state, $country = 'US', $version = '5.0') {
        return self::DNB_PREFIX
                . '/V' . $version . '/organizations?'
                . 'CountryISOAlpha2Code=' . $country
                . '&SubjectName=' . urlencode($company)
                . '&match=true&MatchTypeText=Advanced'
                . '&TerritoryName=' . $state;
    }
}