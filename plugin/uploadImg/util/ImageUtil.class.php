<?php

/**
 * 
 * @author yanshuai
 * 图片处理类 (包含图片大小转换)
 */
class ImageUtil{
	
	private $local_convert_original_imgs = array();
	//定义一个可以临时存放的目录
	private $tmp_cache_img_dir = "/tmp/";
	private $tmp_i = 0;
	
	/**
	 * 1 . array(array("w"=>480,"h"=>240,"cut"=>1), array("w"=>640,"h"=>360,"cut"=>0))
	 * or
	 * 2. array(array("w"=>480,"h"=>0), array("w"=>640,"h"=>))
	 * or
	 * 3. array(480, 640, 750, 1080, 1242) 
	 * 2,3效果一样 不指定高，就根据宽做等比例缩放
	 * 不至此
	 * @var array
	 */
	private $convert_width_list;
	
	private $locat_convert_original_imgs = array();
	
	public function __construct($convert_width_list = array(480, 640, 750, 1080, 1242)){
		$this->convert_width_list = $convert_width_list;
	}
	
	public function setConvertWidth($width){
		$this->convert_width_list = array();
		if (is_array($width)){
			$this->convert_width_list = $width;
		}else {
			$this->convert_width_list[] = $width;
		}
	}
	
	public function getConvertWidth(){
		return $this->convert_width_list;
	}
	
	/**
	 * 把corvert_widths 转换为指定高度的
	 * @param float $scale 根据宽高比，算出需要转换的高度
	 * @param bool $cat 根据宽高比压缩时，是否需要剪切
	 */
	public function setConvertHeightByScale($scale = 0.5, $is_cat = 1){
		if (empty($scale) || empty($this->convert_width_list)){
			return ;
		}
		$old_width_list = $this->convert_width_list;
		$new_width_list = array();
		if (is_array($old_width_list[0]) && $old_width_list[0]['w']){
			foreach ($old_width_list as $width_info) {
				$corvert_heigth = $width_info['w'] * $scale;
				$new_width_list[] = array(
					"w"		=>	$width_info['w'],
					"h"		=>	$corvert_heigth,
					"cat"	=>	$is_cat
				);
			}
		}else{
			foreach ($old_width_list as $width_info) {
				$corvert_heigth = $width_info * $scale;
				$new_width_list[] = array(
					"w"		=>	$width_info,
					"h"		=>	$corvert_heigth,
					"cat"	=>	$is_cat
				);
			}
		}
		$this->convert_width_list = $new_width_list;
	}
	
	/**
	 * 清除corvert_widths 所指定的高度
	 * 清除高度以后，就是根据图片的宽高比 进行等比压缩 
	 * 无剪切压缩
	 */
	public function clearConvertHeight(){
		if (empty($this->convert_width_list)){
			return ;
		}
		$convert_width_list = $this->convert_width_list;
		$deal_list = array();
		if (is_array($this->convert_width_list[0]) || $this->convert_width_list[0]['h']){
			foreach ($convert_width_list as $convert_width_info) {
				$deal_list[] = array(
					"w"	=>	$convert_width_info['w'],
					"h"	=>	0,
					"cat"	=>	0
				);
			}
		}
		$this->convert_width_list = $deal_list;
	}
	
	/**
	 * 生成缩略图
	 * @param string     源图绝对完整地址{带文件名及后缀名}
	 * @param string     目标图绝对完整地址{带文件名及后缀名}
	 * @param int        缩略图宽{0:此时目标高度不能为0，目标宽度为源图宽*(目标高度/源图高)}
	 * @param int        缩略图高{0:此时目标宽度不能为0，目标高度为源图高*(目标宽度/源图宽)}
	 * @param int        是否裁切{宽,高必须非0}
	 * @param int/float  缩放{0:不缩放, 0<this<1:缩放到相应比例(此时宽高限制和裁切均失效)}
	 * @return boolean
	 */
	public function localImg2Thumb($src_img, $img_type, $width = 75, $height = 75, $cut = 0, $proportion = 0){
		if(!is_file($src_img)){
			return false;
		}
		$time = time();
		$tmp_dst_name = "tmp_dst{$time}_{$width}_".rand(10, 99);
		$dst_img = $this->tmp_cache_img_dir."{$tmp_dst_name}.".$img_type;
	    $ot = $this->fileext($dst_img);
	    $otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
	    $srcinfo = getimagesize($src_img);
	    $src_w = $srcinfo[0];
	    $src_h = $srcinfo[1];
	    $type  = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
	    $createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);
	 
	    $dst_h = $height;
	    $dst_w = $width;
	    $x = $y = 0;
	 
	    /**
	     * 缩略图不超过源图尺寸（前提是宽或高只有一个）
	     */
	    if(($width> $src_w && $height> $src_h) || ($height> $src_h && $width == 0) || ($width> $src_w && $height == 0))
	    {
	        $proportion = 1;
	    }
	    if($width> $src_w)
	    {
	        $dst_w = $width = $src_w;
	    }
	    if($height> $src_h)
	    {
	        $dst_h = $height = $src_h;
	    }
	 
	    if(!$width && !$height && !$proportion)
	    {
	        return false;
	    }
	    if(!$proportion)
	    {
	        if($cut == 0)
	        {
	            if($dst_w && $dst_h)
	            {
	                if($dst_w/$src_w> $dst_h/$src_h)
	                {
	                    $dst_w = $src_w * ($dst_h / $src_h);
	                    $x = 0 - ($dst_w - $width) / 2;
	                }
	                else
	                {
	                    $dst_h = $src_h * ($dst_w / $src_w);
	                    $y = 0 - ($dst_h - $height) / 2;
	                }
	            }
	            else if($dst_w xor $dst_h)
	            {
	                if($dst_w && !$dst_h)  //有宽无高
	                {
	                    $propor = $dst_w / $src_w;
	                    $height = $dst_h  = $src_h * $propor;
	                }
	                else if(!$dst_w && $dst_h)  //有高无宽
	                {
	                    $propor = $dst_h / $src_h;
	                    $width  = $dst_w = $src_w * $propor;
	                }
	            }
	        }
	        else
	        {
	            if(!$dst_h)  //裁剪时无高
	            {
	                $height = $dst_h = $dst_w;
	            }
	            if(!$dst_w)  //裁剪时无宽
	            {
	                $width = $dst_w = $dst_h;
	            }
	            $propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
	            $dst_w = (int)round($src_w * $propor);
	            $dst_h = (int)round($src_h * $propor);
	            $x = ($width - $dst_w) / 2;
	            $y = ($height - $dst_h) / 2;
	        }
	    }
	    else
	    {
	        $proportion = min($proportion, 1);
	        $height = $dst_h = $src_h * $proportion;
	        $width  = $dst_w = $src_w * $proportion;
	    }
	 
	    $src = $createfun($src_img);
	    $dst = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
	    $white = imagecolorallocate($dst, 255, 255, 255);
	    imagefill($dst, 0, 0, $white);
	 
	    if(function_exists('imagecopyresampled'))
	    {
	        imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
	    }
	    else
	    {
	        imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
	    }
	    $otfunc($dst, $dst_img);
	    imagedestroy($dst);
	    imagedestroy($src);
	    $thumbnailUrl = "";
		try {
			$thumbnailUrl = _save_image($dst_img, null, $img_type, 0, 90, false);
		} catch (Exception $e) {
		}
		@unlink($dst_img);
		return $thumbnailUrl;
	}
	
	/**
	 * 定制宽，原图批量转换
	 * @param string $img_url
	 * @return array array("$width"=>$src)
	 */
	public function urlImg2ThumbCustomize($img_url){
		if (empty($this->convert_width_list)){
			return false;
		}
		$rt = array();
		foreach ($this->convert_width_list as $convert_width) {
			if (is_array($convert_width)){
				$convert_src = $this->urlImg2Thumb($img_url, $convert_width['w'], $convert_width['h'], $convert_width['cat']);
				if ($convert_src){
					$rt[$convert_width['w']] = $convert_src;
				}
			}else {
				$convert_src = $this->urlImg2Thumb($img_url, $convert_width, 0);
				if ($convert_src){
					$rt[$convert_width] = $convert_src;
				}
			}
		}
		return $rt;
	}
	
	/**
	 * 定制宽，原图批量转换
	 * $with_thumb_scale 在定制的宽高的基础上，按比例再次转换双份
	 * @param string $img_url
	 * @return array array(
	 * 		"base"=>array("$width"=>$src),
	 * 		"thumb"=>array("$width"=>$src)
	 * )
	 */
	public function urlImg2ThumbCustomizeWithScale($img_url, $with_thumb_scale){
		if (empty($this->convert_width_list)){
			return false;
		}
		$rt = array();
		$thumb_list = $base_list = array();
		foreach ($this->convert_width_list as $convert_width) {
			$convert_src = $this->urlImg2Thumb($img_url, $convert_width, 0);
			if ($convert_src){
				$base_list[$convert_width] = $convert_src;
			}
		}
		if ($with_thumb_scale && $with_thumb_scale > 0 && $with_thumb_scale < 1){
			foreach ($this->convert_width_list as $convert_width) {
				$thumb_convert_width = $convert_width * $with_thumb_scale;
				$convert_src = $this->urlImg2Thumb($img_url, $thumb_convert_width, 0);
				if ($convert_src){
					$thumb_list[$convert_width] = $convert_src;
				}
			}
		}
		$rt = array(
			"base"	=>	$base_list,
			"thumb"		=>	$thumb_list
		);
		return $rt;
	}
	public function clearRubbishInfo(){
		if($this->locat_convert_original_imgs){
			foreach ($this->locat_convert_original_imgs as $img_url => $img_info) {
				unlink($img_info['src']);
			}
			$this->locat_convert_original_imgs = array();
		}
	}
	
	/**
	 * 生成缩略图 根据图片url
	 * @param string     源图绝对完整地址{带文件名及后缀名}
	 * @param string     目标图绝对完整地址{带文件名及后缀名}
	 * @param int        缩略图宽{0:此时目标高度不能为0，目标宽度为源图宽*(目标高度/源图高)}
	 * @param int        缩略图高{0:此时目标宽度不能为0，目标高度为源图高*(目标宽度/源图宽)}
	 * @param int        是否裁切{宽,高必须非0}
	 * @param int/float  缩放{0:不缩放, 0<this<1:缩放到相应比例(此时宽高限制和裁切均失效)}
	 * @return boolean
	 */
	public function urlImg2Thumb($img_url, $width = 75, $height = 75, $cut = 0, $proportion = 0)
	{
		$this->tmp_i++;
		if (file_exists($img_url)){
			$img_type = end(explode(".", $img_url));
			$img_type = $img_type ? strtolower($img_type) : "";
			if (!in_array($img_type, array("jpg","jpeg","gif","png"))){
				return false;
			}
			$this->locat_convert_original_imgs[$img_url] = array(
				"src"	=>	$img_url,
				"type"	=>	$img_type
			);
			return $this->localImg2Thumb($img_url, $img_type, $width, $height, $cut, $proportion);
		}
		
		$time = time();
		$src_img = $img_type = "";
		if (!empty($this->locat_convert_original_imgs[$img_url])){
			$tmp_info = $this->locat_convert_original_imgs[$img_url];
			$src_img = $tmp_info['src'];
			$img_type = $tmp_info['type'];
		}else {
			//如果是个url 下载下来做转换
			$img_type = end(explode(".", $img_url));
			$img_type = $img_type ? strtolower($img_type) : "";
			if (!in_array($img_type, array("jpg","jpeg","gif","png"))){
				return false;
			}
			$tmp_load_file = "loadtmp_{$time}_".rand(1000, 9999).$this->tmp_i;
			$src_img_tmp = $this->tmp_cache_img_dir.$tmp_load_file.".".$img_type;
			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch,CURLOPT_URL, $img_url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
			$img_source = curl_exec($ch);
			$info = curl_getinfo($ch);
			if($info['http_code'] != 200){
				return false;
			}
			curl_close($ch);
			//文件大小
			$fp2 = @fopen($src_img_tmp, 'a');
			fwrite($fp2, $img_source);
			fclose($fp2);
			$this->locat_convert_original_imgs[$img_url] = array(
				"src"	=>	$src_img_tmp,
				"type"	=>	$img_type
			);
			$src_img = $src_img_tmp;
			unset($img_source, $src_img_tmp);
		}
		return $this->localImg2Thumb($src_img, $img_type, $width, $height, $cut, $proportion);
	}
	
	protected function fileext($file)
	{
		return pathinfo($file, PATHINFO_EXTENSION);
	}
	
}

?>