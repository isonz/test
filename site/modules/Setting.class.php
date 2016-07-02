<?php
class Setting
{
	const MAX_PEOPLE_NUM = 15;		//最多人数
	
	//----------------------------------------- Categorys
	static public function categorys($code='')
	{
		$categorys = array(
			'hwyxtj'	=> '海外医学体检',
			'qmkslzl'	=> '全面抗衰老治疗',
			'mswzhflf'	=> 'MesoThérapie美塑微针焕肤疗法',
			'zwzjspdjh'	=> '植物重金属排毒精华疗程',
			'nlpdzhlf'	=> 'ReBalance能量排毒综合疗法',
			'lsly'		=> '瑞士疗养',
		);
		if(!$code) return $categorys;
		return $categorys[$code];
	}
	
	//------------------------------------------ Projects
	static public function projects($code='')
	{
		$projects = array(
			'hwyxtj'	=> array('base'=>'基础体检','all'=>'全面体检'),
			'qmkslzl'	=> array('classic'=>'经典', 'top'=>'顶配', 'all'=>'全面'),
			'nlpdzhlf'	=> array('classic'=>'经典', 'top'=>'顶配', 'all'=>'全面'),
			'lsly'		=> array('standard'=>'标准疗养套餐', 'healthy'=>'健康体检', 'diseaseCare '=>'疾病护理', 'bodyFunction'=>'身体机能恢复及优化', 'detoxification'=>'全身排毒','menopause'=>'更年期护理','spa'=>'温泉疗养','skinCare '=>'美肤护理'),
		);
		if(!$code) return $projects;
		return isset($projects[$code]) ? $projects[$code] : '';
	}
	
	//------------------------------------------ Tickets
	static public function tickets($code='')
	{
		$tickets = array(
			'economy'	=> '经济舱',
			'business'	=> '商务舱',
			'first'		=> '头等舱',
		);
		if(!$code) return $tickets;
		return $tickets[$code];
	}
	
	//------------------------------------------ Hotels
	static public function hotel($code='')
	{
		$hotel = array(
			'three'	=> '三星级',
			'four'	=> '四星级',
			'five'	=> '五星级',
		);
		if(!$code) return $hotel;
		return $hotel[$code];
	}
	
	//------------------------------------------ Car
	static public function car($code='')
	{
		$car = array(
			'comfort'	=> '舒适型',
			'business'	=> '商务型',
			'luxury'	=> '豪华型',
		);
		if(!$code) return $car;
		return $car[$code];
	}
	
	//------------------------------------------ Driver
	static public function driver($code='')
	{
		$driver = array(
			'hotel'		=> '住宿费',
			'meals'		=> '餐费',
			'atip'		=> '小费',
			'service'	=> '服务费',
		);
		if(!$code) return $driver;
		return $driver[$code];
	}
	
	//------------------------------------------ Other
	static public function others($code='')
	{
		$others = array(
			'chinese'		=> '中文服务',
			'visa_insurance'=> '签证及保险',
			'all_dining'	=> '全日用餐',
		);
		if(!$code) return $others;
		return $others[$code];
	}
	
	//------------------------------------------ Citys
	static public function citys($code='')
	{
		$citys = array(
			'hongkong'	=> '香港',
			'beijing'	=> '北京',
			'shanghai'	=> '上海',
			'guangzhou'	=> '广州',
		);
		if(!$code) return $citys;
		return $citys[$code];
	}
	
	
	
	
	
	
	//--------------------------------------- Flys
	static public function flys($code='')
	{
		$flys = array(
			'hongkong-switzerland'	=> array(
				'name'=>'瑞士国际航空', 
				'code'=>'LX139 343', 
				'leavetime'=>'23:59', 
				'leaveairport'=>'香港国际机场', 
				'arrivaltime'=>'06:10', 
				'arrivalairport'=>'苏黎世国际机场', 
				'type'=>0,'typename'=>'直飞', 
				'alltime'=>'13h11m'
			),
			'hongkong2-switzerland'	=> array(
				'name'=>'瑞士国际航空', 
				'code'=>'LX138 343', 
				'leavetime'=>'22:40', 
				'leaveairport'=>'苏黎世国际机场', 
				'arrivaltime'=>'17:30', 
				'arrivalairport'=>'香港国际机场', 
				'type'=>0,'typename'=>'直飞', 
				'alltime'=>'11h50m'
			),
			'beijingswitzerland'	=> array(
				'name'=>'中国国航', 
				'code'=>'CA861 330', 
				'leavetime'=>'13:35', 
				'leaveairport'=>'首都国际机场', 
				'arrivaltime'=>'17:40', 
				'arrivalairport'=>'日内瓦机场', 
				'type'=>0,'typename'=>'直飞', 
				'alltime'=>'11h05m'
			),
			'beijing2-switzerland'	=> array(
				'name'=>'中国国航', 
				'code'=>'CA862 330', 
				'leavetime'=>'19:55', 
				'leaveairport'=>'日内瓦机场', 
				'arrivaltime'=>'13:25', 
				'arrivalairport'=>'首都国际机场', 
				'type'=>0,'typename'=>'直飞', 
				'alltime'=>'10h30m'
			),
			'shanghai-switzerland'	=> array(
				'name'=>'瑞士国际航空', 
				'code'=>'LX189 343', 
				'leavetime'=>'09:50', 
				'leaveairport'=>'浦东国际机场', 
				'arrivaltime'=>'15:40', 
				'arrivalairport'=>'苏黎世国际机场', 
				'type'=>0,'typename'=>'直飞', 
				'alltime'=>'12h50m'
			),
			'shanghai2-switzerland'	=> array(
				'name'=>'瑞士国际航空', 
				'code'=>'LX188 343', 
				'leavetime'=>'13:00', 
				'leaveairport'=>'苏黎世国际机场', 
				'arrivaltime'=>'07:55', 
				'arrivalairport'=>'浦东国际机场', 
				'type'=>0,'typename'=>'直飞', 
				'alltime'=>'11h55m'
			),
			'guangzhou-switzerland'	=> array(
				'type'=>1, 
				'typename'=>'2程航班', 
				'name'=>'中国国航', 
				'leavetime'=>'08:25', 
				'leaveairport'=>'新白云国际机场', 
				'arrivaltime'=>'17:40', 
				'arrivalairport'=>'日内瓦机场', 
				'alltime'=>'16h15m',
				'info'=>array(
					array(
						'name'=>'中国国航', 
						'code'=>'CA1310 772', 
						'leavetime'=>'08:25', 
						'leaveairport'=>'新白云国际机场', 
						'arrivaltime'=>'11:25', 
						'arrivalairport'=>'首都国际机场', 
						'alltime'=>'03h00m', 
						'staytime'=>'02h10m'
					),
					array(
						'name'=>'中国国航', 
						'code'=>'CA861 330', 
						'leavetime'=>'13:35', 
						'leaveairport'=>'首都国际机场', 
						'arrivaltime'=>'17:40', 
						'arrivalairport'=>'日内瓦机场', 
						'alltime'=>'11h05m', 
					)
				)
			),
			'guangzhou2-switzerland'	=> array(
				'type'=>1, 
				'typename'=>'2程航班', 
				'name'=>'中国国航', 
				'leavetime'=>'19:55', 
				'leaveairport'=>'日内瓦机场', 
				'arrivaltime'=>'18:15', 
				'arrivalairport'=>'新白云国际机场', 
				'alltime'=>'15h20m', 
				'info'=>array(
					array(
						'name'=>'中国国航', 
						'code'=>'CA862 330', 
						'leavetime'=>'19:55', 
						'leaveairport'=>'日内瓦机场', 
						'arrivaltime'=>'13:25', 
						'arrivalairport'=>'首都国际机场', 
						'alltime'=>'10h30m', 
						'staytime'=>'01h35m'
					),
					array(
						'name'=>'中国国航', 
						'code'=>'CA1301 330', 
						'leavetime'=>'15:00', 
						'leaveairport'=>'首都国际机场', 
						'arrivaltime'=>'18:15', 
						'arrivalairport'=>'新白云国际机场', 
						'alltime'=>'03h15m'
					)
				)
			),
			'hongkong-germany'  => array(
				'type'=>1,
				'typename'=>'2程航班',
				'name'=>'汉莎航空',
				'leavetime'=>'23:10',
				'leaveairport'=>'香港国际机场',
				'arrivaltime'=>'08:30',
				'arrivalairport'=>'纽伦堡机场',
				'alltime'=>'16h20m',
				'info'=>array(
					array(
						'name'=>'汉莎航空',
						'code'=>'LH797 388',
						'leavetime'=>'23:10',
						'leaveairport'=>'香港国际机场',
						'arrivaltime'=>'05:30',
						'arrivalairport'=>'法兰克福机场',
						'alltime'=>'13h20m',
						'staytime'=>'02h15m'
					),
					array(
						'name'=>'汉莎航空',
						'code'=>'LH140 319',
						'leavetime'=>'07:45',
						'leaveairport'=>'法兰克福机场',
						'arrivaltime'=>'08:30',
						'arrivalairport'=>'纽伦堡机场',
						'alltime'=>'00h45m'
					)
				)
			),
			'hongkong2-germany'	=> array(
				'type'=>1,
				'typename'=>'2程航班',
				'name'=>'汉莎航空',
				'leavetime'=>'19:05',
				'leaveairport'=>'纽伦堡机场',
				'arrivaltime'=>'16:55',
				'arrivalairport'=>'香港国际机场',
				'alltime'=>'14h50m',
				'info'=>array(
					array(
						'name'=>'汉莎航空',
						'code'=>'LH153 733',
						'leavetime'=>'19:05',
						'leaveairport'=>'纽伦堡机场',
						'arrivaltime'=>'19:55',
						'arrivalairport'=>'法兰克福机场',
						'alltime'=>'00h50m',
						'staytime'=>'02h20m'
					),
					array(
						'name'=>'汉莎航空',
						'code'=>'LH796 388',
						'leavetime'=>'22:15',
						'leaveairport'=>'法兰克福机场',
						'arrivaltime'=>'16:55',
						'arrivalairport'=>'香港国际机场',
						'alltime'=>'11h40m'
					)
				)
			),
			'beijing-germany'	=> array(
				'type'=>1,
				'typename'=>'2程航班',
				'name'=>'南方航空',
				'leavetime'=>'00:50',
				'leaveairport'=>'首都国际机场',
				'arrivaltime'=>'11:35',
				'arrivalairport'=>'纽伦堡机场',
				'alltime'=>'17h45m',
				'info'=>array(
					array(
						'name'=>'南方航空',
						'code'=>'CZ345 330',
						'leavetime'=>'00:50',
						'leaveairport'=>'首都国际机场',
						'arrivaltime'=>'04:40',
						'arrivalairport'=>'阿姆斯特丹机场',
						'alltime'=>'10h50m',
						'staytime'=>'05h40m'
					),
					array(
						'name'=>'南方航空',
						'code'=>'CZ7685 E90',
						'leavetime'=>'10:20',
						'leaveairport'=>'阿姆斯特丹机场',
						'arrivaltime'=>'11:35',
						'arrivalairport'=>'纽伦堡机场',
						'alltime'=>'01h15m'
					)
				)
			),
			'beijing2-germany'	=> array(
				'type'=>1,
				'typename'=>'2程航班',
				'name'=>'南方航空',
				'leavetime'=>'06:15',
				'leaveairport'=>'纽伦堡机场',
				'arrivaltime'=>'06:55',
				'arrivalairport'=>'首都国际机场',
				'alltime'=>'17h40m',
				'info'=>array(
					array(
						'name'=>'南方航空',
						'code'=>'CZ345 330',
						'leavetime'=>'00:50',
						'leaveairport'=>'首都国际机场',
						'arrivaltime'=>'04:40',
						'arrivalairport'=>'阿姆斯特丹机场',
						'alltime'=>'01h25m',
						'staytime'=>'07h00m'
					),
					array(
						'name'=>'南方航空',
						'code'=>'CZ346 330',
						'leavetime'=>'14:40',
						'leaveairport'=>'阿姆斯特丹机场',
						'arrivaltime'=>'06:55',
						'arrivalairport'=>'首都国际机场',
						'alltime'=>'09h15m'
					)
				)
			),
			'shanghai-germany'	=> array(
				'type'=>1,
				'typename'=>'2程航班',
				'name'=>'汉莎航空',
				'leavetime'=>'13:50',
				'leaveairport'=>'浦东国际机场',
				'arrivaltime'=>'22:05',
				'arrivalairport'=>'纽伦堡机场',
				'alltime'=>'15h15m',
				'info'=>array(
					array(
						'name'=>'汉莎航空',
						'code'=>'LH729 388',
						'leavetime'=>'13:50',
						'leaveairport'=>'浦东国际机场',
						'arrivaltime'=>'19:30',
						'arrivalairport'=>'法兰克福机场',
						'alltime'=>'12h10m',
						'staytime'=>'02h20m'
					),
					array(
						'name'=>'汉莎航空',
						'code'=>'LH150 319',
						'leavetime'=>'10:20',
						'leaveairport'=>'法兰克福机场',
						'arrivaltime'=>'22:05',
						'arrivalairport'=>'纽伦堡机场',
						'alltime'=>'00h45m'
					)
				)
			),
			'shanghai2-germany'	=> array(
				'type'=>1,
				'typename'=>'2程航班',
				'name'=>'汉莎航空',
				'leavetime'=>'20:15',
				'leaveairport'=>'纽伦堡机场',
				'arrivaltime'=>'15:55',
				'arrivalairport'=>'浦东国际机场',
				'alltime'=>'12h40m',
				'info'=>array(
					array(
						'name'=>'汉莎航空',
						'code'=>'LH2163 CR9',
						'leavetime'=>'20:15',
						'leaveairport'=>'纽伦堡机场',
						'arrivaltime'=>'20:55',
						'arrivalairport'=>'弗朗茨约瑟夫施特劳斯机场',
						'alltime'=>'00h40m',
						'staytime'=>'01h05m'
					),
					array(
						'name'=>'汉莎航空',
						'code'=>'LH726 346',
						'leavetime'=>'22:00',
						'leaveairport'=>'弗朗茨约瑟夫施特劳斯机场',
						'arrivaltime'=>'15:55',
						'arrivalairport'=>'浦东国际机场',
						'alltime'=>'10h55m'
					)
				)
			),
			'guangzhou-germany'	=> array(
				'type'=>1,
				'typename'=>'2程航班',
				'name'=>'法国航空',
				'leavetime'=>'00:20',
				'leaveairport'=>'新白云国际机场',
				'arrivaltime'=>'10:00',
				'arrivalairport'=>'纽伦堡机场',
				'alltime'=>'16h40m',
				'info'=>array(
					array(
						'name'=>'法国航空',
						'code'=>'AF4403 332',
						'leavetime'=>'00:20',
						'leaveairport'=>'新白云国际机场',
						'arrivaltime'=>'06:40',
						'arrivalairport'=>'巴黎戴高乐机场',
						'alltime'=>'13h20m',
						'staytime'=>'01h55m'
					),
					array(
						'name'=>'法国航空',
						'code'=>'AF1010 E90',
						'leavetime'=>'08:35',
						'leaveairport'=>'巴黎戴高乐机场',
						'arrivaltime'=>'10:00',
						'arrivalairport'=>'纽伦堡机场',
						'alltime'=>'01h25m'
					)
				)
			),
			'guangzhou2-germany'	=> array(
				'type'=>1,
				'typename'=>'2程航班',
				'name'=>'法国航空',
				'leavetime'=>'06:30',
				'leaveairport'=>'纽伦堡机场',
				'arrivaltime'=>'06:20',
				'arrivalairport'=>'新白云国际机场',
				'alltime'=>'16h50m',
				'info'=>array(
					array(
						'name'=>'法国航空',
						'code'=>'AF1311 E70',
						'leavetime'=>'06:30',
						'leaveairport'=>'纽伦堡机场',
						'arrivaltime'=>'07:55',
						'arrivalairport'=>'巴黎戴高乐机场',
						'alltime'=>'01h25m',
						'staytime'=>'03h50m'
					),
					array(
						'name'=>'法国航空',
						'code'=>'AF4402 332',
						'leavetime'=>'11:45',
						'leaveairport'=>'巴黎戴高乐机场',
						'arrivaltime'=>'06:20',
						'arrivalairport'=>'新白云国际机场',
						'alltime'=>'11h35m'
					)
				)
			),
		);
		
		if(!$code) return $flys;
		return $flys[$code];
	}
	
	
	
	
	
}