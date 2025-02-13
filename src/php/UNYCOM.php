<?php
/*
* @package Match
* @author Carsten Wallenhauer <admin@datapool.info>
* @copyright 2024 to today Carsten Wallenhauer
* @license https://opensource.org/license/mit MIT
*/

declare(strict_types=1);

namespace SourcePot\Match;

final class UNYCOM{

    private const REGIONAL_CODES=['OA'=>'AFRICAN INTELLECTUAL PROPERTY ORGANIZATION (OAPI)',
                                  'AP'=>'AFRICAN REGIONAL INTELLECTUAL PROPERTY ORGANIZATION (ARIPO)',
                                  'BX'=>'BENELUX OFFICE FOR INTELLECTUAL PROPERTY (BOIP)',
                                  'QZ'=>'COMMUNITY PLANT VARIETY OFFICE (EUROPEAN UNION) (CPVO)',
                                  'EA'=>'EURASIAN PATENT ORGANIZATION (EAPO)',
                                  'EM'=>'EUROPEAN UNION INTELLECTUAL PROPERTY OFFICE (EUIPO)',
                                  'EP'=>'EUROPEAN PATENT OFFICE (EPO)',
                                  'WE'=>'EURO PCT',
                                  'WO'=>'INTERNATIONAL BUREAU OF THE WORLD INTELLECTUAL PROPERTY ORGANIZATION (WIPO)',
                                  'GC'=>'PATENT OFFICE OF THE COOPERATION COUNCIL FOR THE ARAB STATES OF THE GULF (GCC PATENT OFFICE)',
                                  'IB'=>'WORLD INTELLECTUAL PROPERTY ORGANIZATION (WIPO) (INTERNATIONAL BUREAU OF)',
                                  'XN'=>'NORDIC PATENT INSTITUTE (NPI)',
                                  'XU'=>'INTERNATIONAL UNION FOR THE PROTECTION OF NEW VARIETIES OF PLANTS (UPOV)',
                                  'XV'=>'VISEGRAD PATENT INSTITUTE (VPI)',];

    private const COUNTRY_CODES=['AF'=>'AFGHANISTAN','AL'=>'ALBANIA','DZ'=>'ALGERIA','AD'=>'ANDORRA','AO'=>'ANGOLA','AI'=>'ANGUILLA','AG'=>'ANTIGUA AND BARBUDA',
                                 'AR'=>'ARGENTINA','AM'=>'ARMENIA','AW'=>'ARUBA','AU'=>'AUSTRALIA','AT'=>'AUSTRIA','AZ'=>'AZERBAIJAN','BS'=>'BAHAMAS','BH'=>'BAHRAIN',
                                 'BD'=>'BANGLADESH','BB'=>'BARBADOS','BY'=>'BELARUS','BE'=>'BELGIUM','BZ'=>'BELIZE','BJ'=>'BENIN','BM'=>'BERMUDA','BT'=>'BHUTAN',
                                 'BO'=>'BOLIVIA (PLURINATIONAL STATE OF)','BQ'=>'BONAIRE, SINT EUSTATIUS AND SABA','BA'=>'BOSNIA AND HERZEGOVINA','BW'=>'BOTSWANA',
                                 'BV'=>'BOUVET ISLAND','BR'=>'BRAZIL','VG'=>'BRITISH VIRGIN ISLANDS','BN'=>'BRUNEI DARUSSALAM','BG'=>'BULGARIA','BF'=>'BURKINA FASO',
                                 'BI'=>'BURUNDI','KH'=>'CAMBODIA','CM'=>'CAMEROON','CA'=>'CANADA','CV'=>'CABO VERDE','KY'=>'CAYMAN ISLANDS','CF'=>'CENTRAL AFRICAN REPUBLIC',
                                 'TD'=>'CHAD','CL'=>'CHILE','CN'=>'CHINA','CO'=>'COLOMBIA','KM'=>'COMOROS','CG'=>'CONGO','CK'=>'COOK ISLANDS','CR'=>'COSTA RICA',
                                 'CI'=>'CÔTE D\'IVOIRE','HR'=>'CROATIA','CU'=>'CUBA','CW'=>'CURAÇAO','CY'=>'CYPRUS','CZ'=>'CZECHIA','KP'=>'DEMOCRATIC PEOPLE\'S REPUBLIC OF KOREA',
                                 'CD'=>'DEMOCRATIC REPUBLIC OF THE CONGO','DK'=>'DENMARK','DJ'=>'DJIBOUTI','DM'=>'DOMINICA','DO'=>'DOMINICAN REPUBLIC','EC'=>'ECUADOR',
                                 'EG'=>'EGYPT','EU'=>'EUROPEAN UNION','SV'=>'EL SALVADOR','GQ'=>'EQUATORIAL GUINEA','ER'=>'ERITREA','EE'=>'ESTONIA','SZ'=>'ESWATINI','ET'=>'ETHIOPIA',
                                 'FK'=>'FALKLAND ISLANDS (MALVINAS)','FO'=>'FAROE ISLANDS','FJ'=>'FIJI','FI'=>'FINLAND','FR'=>'FRANCE','GA'=>'GABON','GM'=>'GAMBIA',
                                 'GE'=>'GEORGIA','DE'=>'GERMANY','GH'=>'GHANA','GI'=>'GIBRALTAR','GR'=>'GREECE','GL'=>'GREENLAND','GD'=>'GRENADA','GT'=>'GUATEMALA',
                                 'GG'=>'GUERNSEY','GN'=>'GUINEA','GW'=>'GUINEA-BISSAU','GY'=>'GUYANA','HK'=>'HONGKONG','HT'=>'HAITI','VA'=>'HOLY SEE',''=>'HONDURAS',''=>'HONG KONG, CHINA',
                                 'HU'=>'HUNGARY','IS'=>'ICELAND','IN'=>'INDIA','ID'=>'INDONESIA','IR'=>'IRAN (ISLAMIC REPUBLIC OF)','IQ'=>'IRAQ','IE'=>'IRELAND',
                                 'IM'=>'ISLE OF MAN','IL'=>'ISRAEL','IT'=>'ITALY','JM'=>'JAMAICA','JP'=>'JAPAN','JE'=>'JERSEY','JO'=>'JORDAN','KZ'=>'KAZAKHSTAN',
                                 'KE'=>'KENYA','KI'=>'KIRIBATI','KW'=>'KUWAIT','KG'=>'KYRGYZSTAN','LA'=>'LAO PEOPLE\'S DEMOCRATIC REPUBLIC','LV'=>'LATVIA','LB'=>'LEBANON',
                                 'LS'=>'LESOTHO','LR'=>'LIBERIA','LY'=>'LIBYA','LI'=>'LIECHTENSTEIN','LT'=>'LITHUANIA','LU'=>'LUXEMBOURG','MO'=>'MACAO, CHINA',
                                 'MG'=>'MADAGASCAR','MW'=>'MALAWI','MY'=>'MALAYSIA','MV'=>'MALDIVES','ML'=>'MALI','MT'=>'MALTA','MH'=>'MARSHALL ISLANDS','MR'=>'MAURITANIA',
                                 'MU'=>'MAURITIUS','MX'=>'MEXICO','MC'=>'MONACO','MN'=>'MONGOLIA','ME'=>'MONTENEGRO','MS'=>'MONTSERRAT','MA'=>'MOROCCO','MZ'=>'MOZAMBIQUE',
                                 'MM'=>'MYANMAR','NA'=>'NAMIBIA','NR'=>'NAURU','NP'=>'NEPAL','NL'=>'NETHERLANDS (KINGDOM OF THE)','NZ'=>'NEW ZEALAND','NI'=>'NICARAGUA',
                                 'NE'=>'NIGER','NG'=>'NIGERIA','NU'=>'NIUE','MK'=>'NORTH MACEDONIA','MP'=>'NORTHERN MARIANA ISLANDS','NO'=>'NORWAY','OM'=>'OMAN',
                                 'PK'=>'PAKISTAN','PW'=>'PALAU','PA'=>'PANAMA','PG'=>'PAPUA NEW GUINEA','PY'=>'PARAGUAY','PE'=>'PERU','PH'=>'PHILIPPINES',
                                 'PL'=>'POLAND','PT'=>'PORTUGAL','QA'=>'QATAR','KR'=>'REPUBLIC OF KOREA','MD'=>'REPUBLIC OF MOLDOVA','RO'=>'ROMANIA','RU'=>'RUSSIAN FEDERATION',
                                 'RW'=>'RWANDA','SH'=>'SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA','KN'=>'SAINT KITTS AND NEVIS','LC'=>'SAINT LUCIA',
                                 'VC'=>'SAINT VINCENT AND THE GRENADINES','WS'=>'SAMOA','SM'=>'SAN MARINO','ST'=>'SAO TOME AND PRINCIPE','SA'=>'SAUDI ARABIA',
                                 'SN'=>'SENEGAL','RS'=>'SERBIA','SC'=>'SEYCHELLES','SL'=>'SIERRA LEONE','SG'=>'SINGAPORE','SX'=>'SINT MAARTEN (DUTCH PART)',
                                 'SK'=>'SLOVAKIA','SI'=>'SLOVENIA','SB'=>'SOLOMON ISLANDS','SO'=>'SOMALIA','ZA'=>'SOUTH AFRICA','GS'=>'SANDWICH ISLANDS','SS'=>'SOUTH SUDAN',
                                 'ES'=>'SPAIN','LK'=>'SRI LANKA','SD'=>'SUDAN','SR'=>'SURINAME','SE'=>'SWEDEN','CH'=>'SWITZERLAND','SY'=>'SYRIAN ARAB REPUBLIC',
                                 'TW'=>'TAIWAN PROVINCE OF CHINA','TJ'=>'TAJIKISTAN','TH'=>'THAILAND','TL'=>'TIMOR-LESTE','TG'=>'TOGO','TO'=>'TONGA','TT'=>'TRINIDAD AND TOBAGO',
                                 'TN'=>'TUNISIA','TR'=>'TÜRKIYE','TM'=>'TURKMENISTAN','TC'=>'TURKS AND CAICOS ISLANDS','TV'=>'TUVALU','UG'=>'UGANDA','UA'=>'UKRAINE',
                                 'AE'=>'UNITED ARAB EMIRATES','GB'=>'UNITED KINGDOM','TZ'=>'UNITED REPUBLIC OF TANZANIA','US'=>'UNITED STATES OF AMERICA','UY'=>'URUGUAY',
                                 'UZ'=>'UZBEKISTAN','VU'=>'VANUATU','VE'=>'VENEZUELA (BOLIVARIAN REPUBLIC OF)','VN'=>'VIET NAM','EH'=>'WESTERN SAHARA','YE'=>'YEMEN',
                                 'ZM'=>'ZAMBIA','ZW'=>'ZIMBABWE',];
    
    private const CASE_TYPE=['XF'=>'Third party patent family','MF'=>'Tradmark family','E'=>'Invention','F'=>'Patent family','P'=>'Patent case','M'=>'Trademark','R'=>'Search file'];
    private const WEIGHTS=['Year'=>2,'Type'=>1,'Number'=>4,'Region'=>1,'Country'=>1,'Part'=>1];
    private const UNYCOM_TEMPLATE=['String'=>'','isValid'=>FALSE,'Year'=>'    ','Type'=>' ','Number'=>'     ','Region'=>'  ','Country'=>'  ','Part'=>'  ','Family'=>' ','Reference'=>'','Full'=>''];

    public const UNYCOM_REGEX='/([0-9]{4})([ ]{0,1}[A-Z]{1,2})([0-9]{5})([A-Z ]{0,5})([0-9]{0,2})\s/u';

    private $unycom=self::UNYCOM_TEMPLATE;

    function __construct()
    {
        
    }

    /**
     * Getter methods
     */

    final public function __toString():string
    {
        return $this->unycom['Full'];
    }

    final public function get():array
    {
        return $this->unycom;
    }
    
    final public function isValid():bool
    {
        return $this->unycom['isValid'];
    }

    final public function fetchCase(string $string)
    {
        preg_match_all(self::UNYCOM_REGEX,$string,$matches);
        foreach($matches[0] as $match){
            yield $match;
        }
    }

    /**
     * Setter methods
     */

    final function set($case)
    {

        $this->unycom=$this->var2case($case);
    }

    /**
     * Feature methods
     */

    private function var2case($var):array
    {
        if (empty($var)){
            return self::UNYCOM_TEMPLATE;
        } else if (is_array($var)){
            $relevantCaseString='';
            foreach(self::UNYCOM_TEMPLATE as $key=>$initValue){
                if (isset($var[$key])){
                    if ($key==='Reference' || $key==='Full'){
                        $relevantCaseString=$var[$key];
                    }
                    break;
                }
            }
            if (empty($relevantCaseString)){
                return self::UNYCOM_TEMPLATE;
            } else {
                $case=$this->parse($relevantCaseString);
            }
        } else {
            $case=$this->parse(strval($var));
        }
        return $case;
    }

    private function parse(string $case):array
    {
        $unycom=self::UNYCOM_TEMPLATE;
        $case=' '.strtoupper($case).' ';
        // get case number
        preg_match('/[^0-9]([0-9]{5})[^0-9]/',$case,$match);
        if (!isset($match[1])){return $unycom;}
        $unycom['Number']=$match[1];
        // get year and case type
        $caseComps=explode($match[1],$case);
        preg_match('/[^0-9]([0-9]{2,4})([A-Z]{1,2})/',$caseComps[0],$match);
        if (!isset($match[2])){return $unycom;}
        $unycom['Year']=$match[1];
        $unycom['Type']=$match[2];
        if (strlen($unycom['Year'])===2){$unycom['Year']='19'.$unycom['Year'];}
        // get prefix
        $unycom['Prefix']=str_replace($match[0],'',$caseComps[0]);
        $unycom['Prefix']=trim($unycom['Prefix'],' -');
        // get region, country, part
        $regionCountryPart=$caseComps[1];
        $unycom['Part']=preg_replace('/[^0-9]/','',$regionCountryPart);
        $unycom['Part']=(empty($unycom['Part']))?'  ':str_pad($unycom['Part'],2,"0", STR_PAD_LEFT);
        $unycom['Part']=substr($unycom['Part'],0,2);
        $regionCountry=preg_replace('/[^A-Z]/','',$regionCountryPart);
        if (strlen($regionCountry)>2){
            // region and country
            $needles=[substr($regionCountry,0,2),substr($regionCountry,2)];
        } else if (strlen($regionCountry)<2){
            // no region, no country
            $needles=[];
        } else {
            // region or country
            $needles=[$regionCountry];
        }
        $regionFound=$countryFound=FALSE;
        foreach($needles as $needle){
            if (!$regionFound){
                foreach(self::REGIONAL_CODES as $code=>$codeDescription){
                    if ($code===$needle){
                        $unycom['Region']=$code;
                        $unycom['Region (long)']=$codeDescription;
                        $regionFound=TRUE;
                        break;
                    }
                }
            }
            if (!$countryFound){
                foreach(self::COUNTRY_CODES as $code=>$codeDescription){
                    if ($code===$needle){
                        $unycom['Country']=$code;
                        $unycom['Country (long)']=$codeDescription;
                        $countryFound=TRUE;
                        break;
                    }
                }
            }
        }
        $unycom['Family']=$unycom['Year'].'F'.$unycom['Number'];
        $unycom['Reference']=$unycom['Year'].$unycom['Type'].$unycom['Number'].$unycom['Region'].$unycom['Country'].$unycom['Part'];
        $unycom['Reference without \s']=preg_replace('/\s+/','',$unycom['Reference']);
        if (!empty($unycom['Prefix'])){
            $unycom['Full']=$unycom['Prefix'].' - '.$unycom['Reference'];
        }
        $unycom['isValid']=TRUE;
        return $unycom;
    }

    final public function match($case):float|int
    {
        $case=$this->var2case($case);
        if (!$case['isValid']){return 0;}
        $match=0;
        $maxWeight=0;
        foreach(self::WEIGHTS as $key=>$weight){
            $maxWeight+=$weight;
            if ($case[$key]===$this->unycom[$key]){
                $match+=$weight;
            }
        }
        return $match/$maxWeight;
    }

}
?>