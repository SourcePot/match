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
                                  'EU'=>'EUROPEAN UNION',
                                  'EM'=>'EUROPEAN UNION INTELLECTUAL PROPERTY OFFICE (EUIPO)',
                                  'EP'=>'EUROPEAN PATENT OFFICE (EPO)',
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
                                 'EG'=>'EGYPT','SV'=>'EL SALVADOR','GQ'=>'EQUATORIAL GUINEA','ER'=>'ERITREA','EE'=>'ESTONIA','SZ'=>'ESWATINI','ET'=>'ETHIOPIA',
                                 'FK'=>'FALKLAND ISLANDS (MALVINAS)','FO'=>'FAROE ISLANDS','FJ'=>'FIJI','FI'=>'FINLAND','FR'=>'FRANCE','GA'=>'GABON','GM'=>'GAMBIA',
                                 'GE'=>'GEORGIA','DE'=>'GERMANY','GH'=>'GHANA','GI'=>'GIBRALTAR','GR'=>'GREECE','GL'=>'GREENLAND','GD'=>'GRENADA','GT'=>'GUATEMALA',
                                 'GG'=>'GUERNSEY','GN'=>'GUINEA','GW'=>'GUINEA-BISSAU','GY'=>'GUYANA','HT'=>'HAITI','VA'=>'HOLY SEE',''=>'HONDURAS',''=>'HONG KONG, CHINA',
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
    
    private $unycom=NULL;

    function __construct()
    {
    }

    /**
     * Getter methods
     */

    final public function __toString():string
    {
        return '';
    }

    final public function isValid():bool
    {
        return FALSE;
    }


    /**
     * Setter methods
     */

     final function setCase()
     {
        
     }

}
?>