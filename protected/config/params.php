<?php

// this contains the application parameters that can be maintained via GUI
return array(
	// this is displayed in the header section
	'title'=>'My Yii Blog',
	// this is used in error pages
	'adminEmail'=>'webmaster@example.com',
	// number of posts displayed per page
	'postsPerPage'=>10,
	// maximum number of comments that can be displayed in recent comments portlet
	'recentCommentCount'=>10,
	// maximum number of tags that can be displayed in tag cloud portlet
	'tagCloudCount'=>20,
	// whether post comments need to be approved before published
	'commentNeedApproval'=>true,
	// the copyright information displayed in the footer section
	// 'baseUrl' => 'http://events.doublemax.net/cloud',
	// 'baseUrl' => 'http://eas.doublemax.net',
	'baseUrl' => 'http://192.168.1.10/eas',

	'noPayAdvertiser' => '100235377', 

	'userGroup' => array(
		'1'=>'管理員','2'=>'域動_PM','3'=>'域動_財務','4'=>'域動_BD','5'=>'域動_業務','6'=>'域動_行銷','7'=>'供應商','8'=>'經銷商','9'=>'廣告主','10'=>'代理商'
	),
	
	'uploadFolder' => dirname(__FILE__)."/../../upload/",

	'cfTel' => '02-27198500',

	'mail' => array(
		"adminEmail" => "web.service@clickforce.com.tw",
		'smtpHost'=>'mail.clickforce.com.tw',
		'smtpAuth'=>true,
		'smtpUsername'=>'web.service@clickforce.com.tw',
		'smtpPassword'=>'clickforce168',
	),

	'invoiceType' => array(
		"0"=> "未確認",
		"1"=> "三聯式電子計算機",
		"2"=> "三聯式收銀機",
		"3"=> "電子發票",
		"4"=> "載有稅額憑證(有字軌)",
		"5"=> "載有稅額其他憑證",
		"6"=> "勞報單",
		"7"=> "Invoice",
		"8"=> "其他",
	),

	'androidSdkVersion' => "1.15",
	'androidSdkUrl' => "http://eas.doublemax.net/sdk/android/MFAD115.zip",
	'androidSdkDoc' => "http://eas.doublemax.net/sdk/android/MFAD115.pdf",
	'androidSdkVersionNowNew' => true,

	'iosSdkVersion' => "1.15",
	'iosSdkUrl' => "http://eas.doublemax.net/sdk/ios/MFAD115.zip",
	'iosSdkDoc' => "http://eas.doublemax.net/sdk/ios/MFAD115.pdf",
	'iosSdkVersionNowNew' => true,

	'buyType' => array(
		"0" => "未選擇",
		"1" => "固定採購",
		"2" => "分成採購",
		"3" => "包斷採購",
		"4" => "CPD採購",
	),

	'chrgeType' => array(
		"0" => "未選擇",
		"1" => "分成",
		"2" => "曝光",
		"3" => "點擊",
	),

	'priceType' => array(
		"0" => 1,
		"1" => 100,
		"2" => 0.01,
		"3" => 0.01,
	),

	'supplierType' => array(
		"1" => "國內個人",
		"2" => "國外個人",
		"3" => "國內公司",
		"4" => "國外公司",
	),

	'taxType' => array(
		"1" => "1",
		"2" => "1",
		"3" => "1.05",
		"4" => "1"
	),

	'taxTypeDeduct' => array(
		"1" => "0.9",
		"2" => "0.8",
		"3" => "1",
		"4" => "0.8"
	),

	'siteType' => array("","PC","Mobile App","Mobile Web"),

	'supplierTypeInList' => array("無資料","台灣個人", "國外個人", "台灣公司", "國外公司"),

	'bankType' => array(
		"1" => "國內銀行",
		"2" => "國外銀行"
	),

	'countryCode' => array(
		"TW" => "台灣(TW)",
		"TT" => "千里達及托巴哥(TT)",
		"TV" => "吐瓦魯(TV)",
		"TR" => "土耳其(TR)",
		"TM" => "土庫曼(TM)",
		"BT" => "不丹(BT)",
		"CF" => "中非共和國(CF)",
		"CN" => "中華人民共和國(CN)",
		"DK" => "丹麥(DK)",
		"EC" => "厄瓜多(EC)",
		"ER" => "厄利垂亞(ER)",
		"PG" => "巴布亞紐幾內亞(PG)",
		"BR" => "巴西(BR)",
		"BB" => "巴貝多(BB)",
		"PY" => "巴拉圭(PY)",
		"BH" => "巴林(BH)",
		"BS" => "巴哈馬(BS)",
		"PA" => "巴拿馬(PA)",
		"PS" => "巴勒斯坦(PS)",
		"PK" => "巴基斯坦(PK)",
		"JP" => "日本(JP)",
		"BE" => "比利時(BE)",
		"JM" => "牙買加(JM)",
		"IL" => "以色列(IL)",
		"CA" => "加拿大(CA)",
		"GA" => "加彭(GA)",
		"MP" => "北馬里亞納群島(MP)",
		"QA" => "卡達(QA)",
		"CC" => "科科斯（基林）群島(CC)",
		"SZ" => "史瓦濟蘭(SZ)",
		"NE" => "尼日(NE)",
		"NI" => "尼加拉瓜(NI)",
		"NP" => "尼泊爾(NP)",
		"BV" => "布威島(BV)",
		"BF" => "布吉納法索(BF)",
		"GP" => "瓜地洛普(GP)",
		"GT" => "瓜地馬拉(GT)",
		"WF" => "瓦利斯和富圖納群島(WF)",
		"GM" => "甘比亞(GM)",
		"BY" => "白俄羅斯(BY)",
		"PN" => "皮特肯群島(PN)",
		"LT" => "立陶宛(LT)",
		"IQ" => "伊拉克(IQ)",
		"IS" => "冰島(IS)",
		"LI" => "列支敦斯登(LI)",
		"HU" => "匈牙利(HU)",
		"ID" => "印尼(ID)",
		"IN" => "印度(IN)",
		"DJ" => "吉布地(DJ)",
		"KI" => "吉里巴斯(KI)",
		"KG" => "吉爾吉斯(KG)",
		"DM" => "多米尼克(DM)",
		"DO" => "多明尼加共和國(DO)",
		"TG" => "多哥(TG)",
		"AG" => "安地卡及巴布達(AG)",
		"AI" => "安圭拉(AI)",
		"AO" => "安哥拉(AO)",
		"AD" => "安道爾(AD)",
		"TK" => "托克勞群島(TK)",
		"BM" => "百慕達(BM)",
		"ET" => "衣索比亞(ET)",
		"ES" => "西班牙(ES)",
		"EH" => "西撒哈拉(EH)",
		"HR" => "克羅埃西亞(HR)",
		"SJ" => "冷岸及央麥恩群島(SJ)",
		"LY" => "利比亞(LY)",
		"HN" => "宏都拉斯(HN)",
		"GR" => "希臘(GR)",
		"SA" => "沙烏地阿拉伯(SA)",
		"BN" => "汶萊(BN)",
		"BZ" => "貝里斯(BZ)",
		"BJ" => "貝南(BJ)",
		"GQ" => "赤道幾內亞(GQ)",
		"ZW" => "辛巴威(ZW)",
		"AM" => "亞美尼亞(AM)",
		"AZ" => "亞塞拜然(AZ)",
		"TZ" => "坦尚尼亞(TZ)",
		"NG" => "奈及利亞(NG)",
		"VE" => "委內瑞拉(VE)",
		"BD" => "孟加拉(BD)",
		"ZM" => "尚比亞(ZM)",
		"PW" => "帛琉(PW)",
		"SB" => "索羅門群島(SB)",
		"LV" => "拉脫維亞(LV)",
		"TO" => "東加(TO)",
		"TL" => "東帝汶(TL)",
		"BA" => "波士尼亞與赫塞哥維納(BA)",
		"BW" => "波札那(BW)",
		"PR" => "波多黎各(PR)",
		"PL" => "波蘭(PL)",
		"FR" => "法國(FR)",
		"FO" => "法羅群島(FO)",
		"GF" => "法屬圭亞那(GF)",
		"PF" => "法屬玻里尼西亞(PF)",
		"TF" => "法屬南方屬地(TF)",
		"GI" => "直布羅陀(GI)",
		"KE" => "肯亞(KE)",
		"FI" => "芬蘭(FI)",
		"AE" => "阿拉伯聯合大公國(AE)",
		"AR" => "阿根廷(AR)",
		"OM" => "阿曼王國(OM)",
		"AF" => "阿富汗(AF)",
		"DZ" => "阿爾及利亞(DZ)",
		"AL" => "阿爾巴尼亞(AL)",
		"AW" => "荷屬阿魯巴(AW)",
		"BG" => "保加利亞(BG)",
		"RU" => "俄羅斯(RU)",
		"ZA" => "南非(ZA)",
		"GS" => "南喬治亞與南三明治群島(GS)",
		"AQ" => "南極洲(AQ)",
		"KR" => "南韓(KR)",
		"KZ" => "哈薩克(KZ)",
		"KH" => "柬埔寨(KH)",
		"TD" => "查德(TD)",
		"BO" => "玻利維亞(BO)",
		"KW" => "科威特(KW)",
		"KM" => "葛摩(KM)",
		"TN" => "突尼西亞(TN)",
		"JO" => "約旦(JO)",
		"US" => "美國(US)",
		"VI" => "美屬維京群島(VI)",
		"AS" => "美屬薩摩亞(AS)",
		"UM" => "美國本土外小島嶼(UM)",
		"MR" => "茅利塔尼亞(MR)",
		"GB" => "英國(GB)",
		"IO" => "英屬印度洋領地(IO)",
		"VG" => "英屬維京群島(VG)",
		"GH" => "迦納(GH)",
		"HK" => "香港(HK)",
		"CG" => "剛果（布拉薩）(CG)",
		"CD" => "剛果（金夏沙）(CD)",
		"CO" => "哥倫比亞(CO)",
		"CR" => "哥斯大黎加(CR)",
		"EG" => "埃及(EG)",
		"CK" => "庫克群島(CK)",
		"NO" => "挪威(NO)",
		"GL" => "格陵蘭(GL)",
		"GD" => "格瑞那達(GD)",
		"TH" => "泰國(TH)",
		"HT" => "海地(HT)",
		"UG" => "烏干達(UG)",
		"UA" => "烏克蘭(UA)",
		"UY" => "烏拉圭(UY)",
		"UZ" => "烏茲別克(UZ)",
		"TC" => "英屬土克凱可群島(TC)",
		"RE" => "留尼旺(RE)",
		"PE" => "秘魯(PE)",
		"NZ" => "紐西蘭(NZ)",
		"NU" => "紐埃島(NU)",
		"NA" => "納米比亞(NA)",
		"MQ" => "馬丁尼克島(MQ)",
		"ML" => "馬利(ML)",
		"MY" => "馬來西亞(MY)",
		"MK" => "馬其頓(MK)",
		"MW" => "馬拉威(MW)",
		"YT" => "馬約特(YT)",
		"MH" => "馬紹爾群島(MH)",
		"MG" => "馬達加斯加(MG)",
		"MT" => "馬爾他(MT)",
		"MV" => "馬爾地夫(MV)",
		"FM" => "密克羅尼西亞群島(FM)",
		"CZ" => "捷克共和國(CZ)",
		"VA" => "梵蒂岡(VA)",
		"MZ" => "莫三比克(MZ)",
		"AN" => "荷屬安地列斯(AN)",
		"NL" => "荷蘭(NL)",
		"CM" => "喀麥隆(CM)",
		"GE" => "喬治亞共和國(GE)",
		"GN" => "幾內亞(GN)",
		"GW" => "幾內亞比索(GW)",
		"FJ" => "斐濟(FJ)",
		"LK" => "斯里蘭卡(LK)",
		"SK" => "斯洛伐克(SK)",
		"SI" => "斯洛維尼亞(SI)",
		"CL" => "智利(CL)",
		"PH" => "菲律賓(PH)",
		"CI" => "象牙海岸(CI)",
		"VN" => "越南(VN)",
		"KY" => "開曼群島(KY)",
		"SN" => "塞內加爾(SN)",
		"SC" => "塞席爾(SC)",
		"RS" => "塞爾維亞(RS)",
		"TJ" => "塔吉克(TJ)",
		"AT" => "奧地利(AT)",
		"EE" => "愛沙尼亞(EE)",
		"IE" => "愛爾蘭(IE)",
		"SG" => "新加坡(SG)",
		"NC" => "新喀里多尼亞(NC)",
		"SL" => "獅子山(SL)",
		"CH" => "瑞士(CH)",
		"SE" => "瑞典(SE)",
		"VU" => "萬那杜(VU)",
		"IT" => "義大利(IT)",
		"PM" => "聖皮埃爾和密克隆群島(PM)",
		"VC" => "聖文森及格瑞那丁(VC)",
		"ST" => "聖多美普林西比(ST)",
		"KN" => "聖克里斯多福及尼維斯(KN)",
		"SM" => "聖馬利諾(SM)",
		"SH" => "聖赫勒拿島(SH)",
		"CX" => "聖誕島(CX)",
		"LC" => "聖露西亞(LC)",
		"YE" => "葉門(YE)",
		"PT" => "葡萄牙(PT)",
		"FK" => "福克蘭群島(FK)",
		"CV" => "維德角(CV)",
		"MN" => "蒙古(MN)",
		"MS" => "蒙哲臘(MS)",
		"ME" => "蒙特內哥羅(ME)",
		"BI" => "蒲隆地(BI)",
		"GY" => "蓋亞那(GY)",
		"HM" => "赫德及麥當勞群島(HM)",
		"LA" => "寮國(LA)",
		"DE" => "德國(DE)",
		"MA" => "摩洛哥(MA)",
		"MC" => "摩納哥(MC)",
		"MD" => "摩爾多瓦(MD)",
		"MU" => "模里西斯(MU)",
		"LB" => "黎巴嫩(LB)",
		"MX" => "墨西哥(MX)",
		"MO" => "澳門(MO)",
		"AU" => "澳洲(AU)",
		"RW" => "盧安達(RW)",
		"LU" => "盧森堡(LU)",
		"NF" => "諾福克島(NF)",
		"NR" => "諾魯(NR)",
		"LR" => "賴比瑞亞(LR)",
		"LS" => "賴索托(LS)",
		"CY" => "賽普勒斯(CY)",
		"SV" => "薩爾瓦多(SV)",
		"WS" => "薩摩亞群島(WS)",
		"RO" => "羅馬尼亞(RO)",
		"GU" => "關島(GU)",
		"SR" => "蘇利南(SR)"
	),
);
