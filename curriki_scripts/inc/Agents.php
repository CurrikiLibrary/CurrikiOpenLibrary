<?php

class Agents {

    public static $score = array(
        'A+' => 10,
        'A' => 9,
        'A-' => 8,
        'B+' => 7,
        'B' => 6,
        'B-' => 5,
        'C+' => 4,
        'C' => 3,
        'D' => 2,
        'E' => 1,
        'F' => 0
    );

    public static function bbbSync($company) {
        $name = str_replace($company->companyName, ' ', '+');
        $city = str_replace($company->city, ' ', '+');

        $url = 'http://www.bbb.org/search/?type=name&input=' . $name . '&location=' . $city . ',+' . $company->state . '&tobid=&filter=business&radius=&country=USA%2CCAN&language=en&codeType=YPPA';

        $content = Network::torGetUrl($url);

        $k01 = "search-results-table";

        $k01Pos = strpos($content, $k01);

        $filter = Agents::getCompanyFilter($company);

        if ($k01Pos !== false) {
            $str01 = substr($content, $k01Pos);

            $k02 = '<a href="';

            $k02Pos = strpos($str01, $k02);

            $str02 = substr($str01, $k02Pos + strlen($k02));

            $k03 = '"';

            $k03Pos = strpos($str02, $k03);

            $url02 = substr($str02, 0, $k03Pos);

            $content02 = Network::torGetUrl($url02);

            $k04 = 'accedited-rating';

            $k04Pos = strpos($content02, $k04);

            $str04 = substr($content02, $k04Pos);

            $k05 = 'BBB&reg;';
            $k05_del = array('Non-Accredited', 'Accredited');

            $k05Pos = strpos($str04, $k05);

            $str05 = substr($str04, $k05Pos + strlen($k05));

            for ($i = 0; $i < count($k05_del); $i++) {
                $str05 = str_replace($k05_del[$i], '', $str05);
            }

            $str05 = ltrim($str05);

            $k06 = ' ';

            $k06Pos = strpos($str05, $k06);

            $rating = trim(substr($str05, 0, $k06Pos));

            $number = 0;

            if (array_key_exists($rating, Agents::$score) === true) {
                $number = Agents::$score[$rating];
            }

            Sync::$bulk->update(
                    $filter, ['$set' => [
                    'confidence.bbbExists' => true,
                    'confidence.bbbNumber' => $number
                ]], ['multi' => true, 'upsert' => true]
            );
        } else {
            Sync::$bulk->update(
                    $filter, ['$set' => ['confidence.bbbExists' => false]], ['multi' => true, 'upsert' => true]
            );
        }
    }

    public static function priceToFloat($company) {
        $filter = Agents::getCompanyFilter($company);

        foreach ($company->categories as $category) {
            if (property_exists($category, 'priceUSD') && !is_null($category->priceUSD)) {
                $categoryFilter = $filter;

                $categoryFilter['categories.categoryName'] = $category->categoryName;
                $categoryFilter['categories.categoryID'] = $category->categoryID;

                Sync::$bulk->update(
                        $categoryFilter, ['$set' => ['categories.$.priceUSD' => floatval($category->priceUSD)]], ['multi' => false, 'upsert' => false]
                );

                Sync::$bulkSize++;
            }
        }

        Sync::$bulkSize--;
    }

    public static function findCompany($company, $collection = 'company') {
        $filter = Agents::getCompanyFilter($company);

        $options = array();

        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = Sync::$manager->executeQuery('citytwig.' . $collection, $query);

        foreach ($cursor as $resCompany) {
            return $resCompany;
        }

        return null;
    }

    public static function findCategory($category, $collection = 'company') {
        $filter = array(
            "categories.categoryName" => $category->categoryName
        );

        $options = array(
            "limit" => 1
        );

        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = Sync::$manager->executeQuery('citytwig.' . $collection, $query);

        foreach ($cursor as $resCompany) {
            foreach ($resCompany->categories as $resCategory) {
                if ($resCategory->categoryName === $category->categoryName) {
                    return $resCategory;
                }
            }
        }

        return null;
    }

    public static function correctCanadaCategories($company) {
        $tmpBulkSize = Sync::$bulkSize;

        foreach ($company->categories as $category) {
            $syncCategory = self::findCategory($category);

            if ($syncCategory != null && property_exists($syncCategory, 'categoryID')) {
                $filter = Agents::getCompanyFilter($company);

                $filter['categories.categoryName'] = $category->categoryName;

                Sync::$bulk->update(
                        $filter, ['$set' => ['categories.$.categoryID' => $syncCategory->categoryID]], ['multi' => false, 'upsert' => false]
                );

                Sync::$bulkSize++;
            }
        }

        if (Sync::$bulkSize > $tmpBulkSize) {
            Sync::$bulkSize--;
        }
    }

    public static function dnbAuth() {
        $headers = array(
            'x-dnb-user: ' . Credentials::DNB_USER,
            'x-dnb-pwd: ' . Credentials::DNB_PWD
        );

        $response = Network::torGetUrl(BaseURL::dnbAuth(), $headers, 'POST');

        $valid = false;

        if ($response != null && $response != '') {
            $jsoResponse = json_decode($response);

            if (property_exists($jsoResponse, 'AuthenticationDetail')) {
                Sync::$headers = array(
                    'Authorization: ' . $jsoResponse->AuthenticationDetail->Token
                );

                $valid = true;
            }
        }

        if ($valid === false) {
            throw new Exception("Unable to authenticate.");
        }
    }

    public static function dnbSync($company) {
        $urlDUNS = BaseURL::dnbDUNS($company->companyName, $company->state);
        $response = json_decode(Network::torGetUrl($urlDUNS, Sync::$headers, 'GET', array(), 'Agents::dnbAuth'));

        $dunsNumber = null;
        $taxId = null;

        if (property_exists($response, 'MatchResponse') && property_exists($response->MatchResponse, 'MatchResponseDetail') && property_exists($response->MatchResponse->MatchResponseDetail, 'MatchCandidate') && count($response->MatchResponse->MatchResponseDetail->MatchCandidate) > 0 && property_exists($response->MatchResponse->MatchResponseDetail->MatchCandidate[0], 'DUNSNumber')) {
            $matchCandidate = $response->MatchResponse->MatchResponseDetail->MatchCandidate[0];
            $dunsNumber = $matchCandidate->DUNSNumber;

            if (property_exists($matchCandidate, 'OrganizationIdentificationNumberDetail') && property_exists($matchCandidate->OrganizationIdentificationNumberDetail, 'OrganizationIdentificationNumber')) {
                $taxId = $matchCandidate->OrganizationIdentificationNumberDetail->OrganizationIdentificationNumber;
            }
        }

        $filter = Agents::getCompanyFilter($company);

        if ($dunsNumber != null) {
            Sync::$bulk->update(
                    $filter, ['$set' => [
                    'confidence.dnbExists' => true,
                    'confidence.dnbDUNSNumber' => $dunsNumber
                ]], ['multi' => false, 'upsert' => true]
            );

            if ($taxId != null) {
                Sync::$bulk->update(
                        $filter, ['$set' => [
                        'confidence.taxExists' => true,
                        'confidence.taxIdNumber' => $taxId
                    ]], ['multi' => false, 'upsert' => true]
                );

                Sync::$bulkSize++;
            }
        } else {
            Sync::$bulk->update(
                    $filter, ['$set' => [
                    'confidence.dnbExists' => false,
                    'confidence.taxExists' => false
                ]], ['multi' => false, 'upsert' => true]
            );
        }

        Sync::$bulkSize++;
    }

    public static function getCompanyFilter($company) {
        $filter = array();

        if (property_exists($company, 'companyName')) {
            $filter['companyName'] = $company->companyName;
        }

        if (property_exists($company, 'address1')) {
            $filter['address1'] = $company->address1;
        }

        if (property_exists($company, 'city')) {
            $filter['city'] = $company->city;
        }

        if (property_exists($company, 'state')) {
            $filter['state'] = $company->state;
        }

        if (property_exists($company, 'zip')) {
            $filter['zip'] = $company->zip;
        }

        if (property_exists($company, 'priPhone')) {
            $filter['priPhone'] = $company->priPhone;
        }

        return $filter;
    }

    public static function crimeNYSync($company) {
        $url = 'https://iapps.courts.state.ny.us/webcrim_attorney/DefendantSearch';

        //"No cases found"  false
        //"Case Number"     true
    }

    public static function findPriceReference($category) {
        $filter = array(
            'priceReference.categoryName' => $category->categoryName,
            'priceReference.categoryID' => $category->categoryID
        );

        $options = array(
            "limit" => 1
        );

        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = Sync::$manager->executeQuery('citytwig.priceReference', $query);

        foreach ($cursor as $priceReference) {
            return $priceReference;
        }

        return null;
    }

    public static function priceReference($company) {
        foreach ($company->categories as $category) {
            $priceReference = self::findPriceReference($category);

            $avgPriceUSD = $category->priceUSD;

            echo "----";
            echo "category->categoryName: {$category->categoryName}\n";
            echo "category->categoryID: {$category->categoryID}\n";
            echo "category->priceUSD: $avgPriceUSD\n";

            if ($priceReference != null && property_exists($priceReference, 'priceUSD') && property_exists($priceReference, 'count')) {
                if ($priceReference->count > 0) {
                    echo "priceReference->priceUSD: {$priceReference->priceUSD}\n";

                    $totalPrice = $priceReference->priceUSD * $priceReference->count;

                    $newCount = $priceReference->count + 1;
                    echo "newCount: {$newCount}\n";

                    $avgPriceUSD = round(($totalPrice + $category->priceUSD) / $newCount, 2);
                }
            }

            $filter = array(
                'priceReference.categoryName' => $category->categoryName,
                'priceReference.categoryID' => $category->categoryID
            );

            Sync::$bulk->update(
                    $filter, [
                '$set' => [
                    'priceReference.$.priceUSD' => $avgPriceUSD
                ],
                '$inc' => [
                    'priceReference.$.count' => 1
                ]
                    ], ['multi' => false, 'upsert' => true]
            );

            Sync::$bulkSize++;
        }
        /*
          Sync::$bulk->update(
          array(),
          [
          '$inc' => [
          'priceReference.companyCount' => 1
          ]
          ],
          ['multi' => false, 'upsert' => true]
          );

          Sync::$bulkSize++;
         */
        echo "\n";
    }

    public static function skipPriceReference() {
        $filter = array();

        $options = array(
            "limit" => 1
        );

        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = Sync::$manager->executeQuery('citytwig.priceReference', $query);

        $skip = 0;

        foreach ($cursor as $priceReference) {
            $skip = $priceReference->companyCount;
        }

        Sync::$skip = $skip;
        Conn::$limit = null;
    }

    public static function getSimpleConfidenceScore($company) {

        $confidence['confidence.companyName'] = (isset($company->companyName) && $company->companyName != '') ? true : false;
        $confidence['confidence.priPhone'] = (isset($company->priPhone) && $company->priPhone != '') ? true : false;
        $confidence['confidence.email'] = (isset($company->contacts[0]->email) && $company->contacts[0]->email != '') ? true : false;
        $confidence['confidence.website'] = (isset($company->social->url) && $company->social->url != '') ? true : false;
        $confidence['confidence.facebook'] = (isset($company->social->facebook) && $company->social->facebook != '') ? true : false;
        $confidence['confidence.twitter'] = (isset($company->social->twitter) && $company->social->twitter != '') ? true : false;
        $confidence['confidence.google'] = (isset($company->social->google) && $company->social->google != '') ? true : false;
        $confidence['confidence.pinterest'] = (isset($company->social->pinterest) && $company->social->pinterest != '') ? true : false;
        $confidence['confidence.youtube'] = (isset($company->social->youtube) && $company->social->youtube != '') ? true : false;
        $confidence['confidence.pricing'] = (isset($company->categories[0]->pricing) && $company->categories[0]->pricing != 0) ? true : false;
        $confidence['confidence.ctActive'] = self::isCTActiveExists($company);
        $confidence['confidence.score'] = $confidence_score = self::calculateConfidence($confidence);
        $confidence['confidence.entered'] = new MongoDate(time());
        $confidence['confidence.grade'] = self::calculateConfidenceGrade($confidence_score);

        return $confidence;
    }

    private static function isCTActiveExists($company) {
        //Finding CTActive - START
        $company_id = $company->_id;
        $company_id = new MongoDB\BSON\ObjectId($company_id);

        $company_filter = ['companies.companyID' => $company_id];
        $company_options = [
            'projection' => ['email' => 1],
            'sort' => ['_id' => -1],
        ];

        $query = new MongoDB\Driver\Query($company_filter, $company_options);

        $cursor1 = Sync::$manager->executeQuery('citytwig.users', $query);

        $ct_active = false;
        if (count($cursor1->toArray())):
            $ct_active = true;
        endif;

        return $ct_active;
        //Finding CTActive - END
    }

    private static function calculateConfidence($confidence){
        // Applying weightage table
        $weightage = [
            'confidence.companyName' => [
                'weight' => 0.10,
                'value' => ($confidence['confidence.companyName']) ? 1 : 0
            ],
            'confidence.priPhone' => [
                'weight' => 0.10,
                'value' => ($confidence['confidence.priPhone']) ? 1 : 0
            ],
            'confidence.email' => [
                'weight' => 0.10,
                'value' => ($confidence['confidence.email']) ? 1 : 0
            ],
            'confidence.website' => [
                'weight' => 0.10,
                'value' => ($confidence['confidence.website']) ? 1 : 0
            ],
            'confidence.facebook' => [
                'weight' => 0.10,
                'value' => ($confidence['confidence.facebook']) ? 1 : 0
            ],
            'confidence.twitter' => [
                'weight' => 0.10,
                'value' => ($confidence['confidence.twitter']) ? 1 : 0
            ],
            'confidence.google' => [
                'weight' => 0.05,
                'value' => ($confidence['confidence.google']) ? 1 : 0
            ],
            'confidence.pinterest' => [
                'weight' => 0.05,
                'value' => ($confidence['confidence.pinterest']) ? 1 : 0
            ],
            'confidence.youtube' => [
                'weight' => 0.10,
                'value' => ($confidence['confidence.youtube']) ? 1 : 0
            ],
            'confidence.pricing' => [
                'weight' => 0.10,
                'value' => ($confidence['confidence.pricing']) ? 1 : 0
            ],
            'confidence.ctActive' => [
                'weight' => 0.10,
                'value' => ($confidence['confidence.ctActive']) ? 1 : 0
            ],
        ];


        $confidence_score = 0;
        foreach ($weightage as $weight):
            $confidence_score += (float) $weight['weight'] * (float) $weight['value'];
        endforeach;
        return (float) $confidence_score;
    }
    private static function calculateConfidenceGrade($confidence_score){
        // Calculating confidence grade
        $grade = 'F';
        $confidence_score = $confidence_score * 10;
        if ($confidence_score >= 0 && $confidence_score <= 2) {
            $grade = 'F';
        } elseif ($confidence_score > 2 && $confidence_score <= 3) {
            $grade = 'D';
        } elseif ($confidence_score > 3 && $confidence_score <= 4) {
            $grade = 'C';
        } elseif ($confidence_score > 4 && $confidence_score <= 6) {
            $grade = 'B';
        } elseif ($confidence_score > 6 && $confidence_score <= 7) {
            $grade = 'A-';
        } elseif ($confidence_score > 7 && $confidence_score <= 8) {
            $grade = 'A';
        } elseif ($confidence_score > 8 && $confidence_score <= 10) {
            $grade = 'A+';
        }
        return $grade;
    }
}
