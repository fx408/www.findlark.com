<?php
/*
 * 大津法，自适应计算单阈值
 */
class Otsu{
	public static function threshold($imageData) {
		$histogram = self::getGrayHistogram($imageData);
		
		$n = 0; $sum = 0;
		for($i = 0; $i < 256; $i++) {
			$sum += $i * $histogram[$i]; // 质量矩阵
			$n += $histogram[$i]; // 质量
		}
		$pow_n = pow($n, 2);
		
		$max = 0; $n1 = 0; $n2 = 0; $csum = 0; $t = 0;
		for($i = 0; $i < 256; $i++) {
			$n1 += $histogram[$i];
			if(!$n1) continue;
			$n2 = $n - $n1;
			if($n2 < 1) break;
			
			$csum += $i * $histogram[$i];
			$m1 = $csum / $n1;
			$m2 = ($sum - $csum) / $n2;
			
			$s = $n1*$n2*pow(($m1-$m2), 2)/$pow_n;
			if($s > $max) {
				$max = $s;
				$t = $i;
			}
		}
		
		return $t;
	}
	
	// 图像的灰度直方图
	protected static function getGrayHistogram($imageData) {
		$histogram = array();
		for($i = 0; $i < 256; $i++) {
			$histogram[$i] = 0;
		}
		foreach($imageData as $item) {
			$histogram[$item]++;
		}
		return $histogram;
	}
}