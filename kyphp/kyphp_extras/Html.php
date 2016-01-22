<?php
// +----------------------------------------------------------------------
// 最便捷的php框架
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.ky53.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.ky53.net )
// +----------------------------------------------------------------------

/**
 
 * @author   qq:974005652(zhx626@126.com) by老顽童
 * @version  2.0
 +------------------------------------------------------------------------------
 */
class Html{
	public $data;
	public $config;
	public function make($str){
		if(isset($this->data['kysmarty']))$this->data=array_merge($this->data['kysmarty'],$this->data);
		$labelfrom=array();
		$labelto=array();
		if($this->data)foreach($this->data as $key=>$value)
		{
			if(is_string($value)){
				$labelfrom[]='{$'.$key.';}';
				$labelto[]=$value;
			}
		}
		$str=preg_replace_callback('/\{literal\;\}(.*?)\{\/literal\;\}/s',function($s){return str_replace('$','{#kyphp#}',$s[1]);},$str);
		$str=str_replace($labelfrom,$labelto,$str);
		
		$str=preg_replace_callback('/\{foreach([^\{\}\<\>]+)\;\}(.*?)\{\/foreach\;\}/si',function ($s){return $this->zlforeach($s);},$str);

		
		$content=preg_replace_callback("/<include\s+file=\"(\w+):(\w+)\">/is",'self::_include',$str);
		 
		$content=str_replace('__URL__',__URL__,$content);
		$content=str_replace('__ROOT__',__ROOT__,$content);
		$content=str_replace('__APP__',__APP__,$content);
		if(defined('__WEBURL__'))$content=str_replace('__WEBURL__',__WEBURL__,$content);

	
		$content=preg_replace_callback("/<lable[ \f\r\t\n]+name=(\"|\'|)(\w+)(\"|\'|)>/is",function($s){return $this->label($s[2]);},$content);
		$content=preg_replace_callback("/<label[ \f\r\t\n]+name=(\"|\'|)(\w+)(\"|\'|)>/is",function($s){return $this->label($s[2]);},$content);
		
		$content=preg_replace_callback('/\$lable\[(\"|\'|)(\w+)(\"|\'|)\]\[(\"|\'|)(\w+)(\"|\'|)\]/is',function($s){return $this->label($s[2],$s[5]);},$content);
		$content=preg_replace_callback('/\$lable\[(\"|\'|)(\w+)(\"|\'|)\]/is',function($s){return $this->label($s[2]);},$content);
		$content=preg_replace_callback('/\$label\[(\"|\'|)(\w+)(\"|\'|)\]\[(\"|\'|)(\w+)(\"|\'|)\]/is',function($s){return $this->label($s[2],$s[5]);},$content);
		$content=preg_replace_callback('/\$label\[(\"|\'|)(\w+)(\"|\'|)\]/is',function($s){return $this->label($s[2]);},$content);	

		$content=preg_replace_callback('/\$volist\[(\"|\'|)(\w+)(\"|\'|)\]/is',function($s){return $this->_volist($s);},$content);
		$content=preg_replace_callback("/<volist\s+name=(\"|\'|)(\w+)(\"|\'|)\s+id=(\"|\'|)(\w+)(\"|\'|)>(.*?)<\/volist>/is",function($s){return $this->_volist($s);},$content);
		$content=preg_replace_callback("/<if\s+condition=[(](\w+)(==|<|>|>=|<=)(\w+)[)]>(\w+)<\/if>/is",function($s){return $this->_if($s);},$content);
		$content=preg_replace_callback("/<datalist\s+name=(\"|\'|)(\w+|)(\"|\'|)\s+id=(\"|\'|)(\w+|)(\"|\'|)\s+row=(\"|\'|)(\w+|)(\"|\'|)\s+field=(\"|\')(.*?)(\"|\')\s+manage=(\"|\')(.*?)(\"|\')\s+edit=(\"|\'|)(\w+|)(\"|\'|)\s+title=(\"|\'|)(\w+|)(\"|\'|)\s+page=(\"|\'|)(\w+|)(\"|\'|)\s+table=(\"|\')(.*?)(\"|\')\s+list=(\"|\')(\w+|)(\"|\')(\s+|)>/is",function($s){return $this->_datalist($s[2],$s[5],$s[8],$s[11],$s[14],$s[17],$s[20],$s[23],$s[26],$s[29]);},$content);//_datalist('\\2','\\5','\\8','\\11','\\14','\\17','\\20','\\23','\\26','\\29')

		if(strstr($content,'row=')&&strstr($content,'field=')&&strstr($content,'manage=')&&strstr($content,'edit=')&&strstr($content,'title=')&&strstr($content,'page=')&&strstr($content,'table=')&&!strstr($content,'list='))$content=preg_replace_callback("/<datalist\s+name=(\"|\'|)(\w+|)(\"|\'|)\s+id=(\"|\'|)(\w+|)(\"|\'|)\s+row=(\"|\'|)(\w+|)(\"|\'|)\s+field=(\"|\'|)(.*?)(\"|\'|)\s+manage=(\"|\'|)(.*?)(\"|\'|)\s+edit=(\"|\'|)(\w+|)(\"|\'|)\s+title=(\"|\'|)(\w+|)(\"|\'|)\s+page=(\"|\'|)(\w+|)(\"|\'|)\s+table=(\"|\')(.*?)(\"|\')(\s+|)>/is",function($s){return $this->_datalist($s[2],$s[5],$s[8],$s[11],$s[14],$s[17],$s[20],$s[23],$s[26]);},$content);
		  if(strstr($content,'row=')&&strstr($content,'field=')&&strstr($content,'manage=')&&strstr($content,'edit=')&&strstr($content,'title=')&&strstr($content,'page=')&&!strstr($content,'list=')&&!strstr($content,'table='))$content=preg_replace_callback("/<datalist\s+name=(\"|\'|)(\w+|)(\"|\'|)\s+id=(\"|\'|)(\w+|)(\"|\'|)\s+row=(\"|\'|)(\w+|)(\"|\'|)\s+field=(\"|\'|)(.*?)(\"|\'|)\s+manage=(\"|\'|)(.*?)(\"|\'|)\s+edit=(\"|\'|)(\w+|)(\"|\'|)\s+title=(\"|\'|)(\w+|)(\"|\'|)\s+page=(\"|\'|)(\w+|)(\"|\'|)(\s+|)>/is",function($s){return $this->_datalist($s[2],$s[5],$s[8],$s[11],$s[14],$s[17],$s[20],$s[23]);},$content);
		if(strstr($content,'row=')&&strstr($content,'field=')&&strstr($content,'manage=')&&strstr($content,'edit=')&&strstr($content,'title=')&&!strstr($content,'list=')&&!strstr($content,'page=')&&!strstr($content,'table=')) $content=preg_replace_callback("/<datalist\s+name=(\"|\'|)(\w+|)(\"|\'|)\s+id=(\"|\'|)(\w+|)(\"|\'|)\s+row=(\"|\'|)(\w+|)(\"|\'|)\s+field=(\"|\'|)(.*?)(\"|\'|)\s+manage=(\"|\'|)(.*?)(\"|\'|)\s+edit=(\"|\'|)(\w+|)(\"|\'|)\s+title=(\"|\'|)(\w+|)(\"|\'|)(\s+|)>/is",function($s){return $this->_datalist($s[2],$s[5],$s[8],$s[11],$s[14],$s[17],$s[20]);},$content);
		 
		 if(strstr($content,'row=')&&strstr($content,'field=')&&strstr($content,'manage=')&&strstr($content,'edit=')&&!strstr($content,'list=')&&!strstr($content,'page=')&&!strstr($content,'table=')&&!strstr($content,'title=')){$content=preg_replace_callback("/<datalist\s+name=(\"|\'|)(\w+|)(\"|\'|)\s+id=(\"|\'|)(\w+|)(\"|\'|)\s+row=(\"|\'|)(\w+|)(\"|\'|)\s+field=(\"|\'|)(.*?)(\"|\'|)\s+manage=(\"|\'|)(.*?)(\"|\'|)\s+edit=(\"|\'|)(\w+|)(\"|\'|)(\s+|)>/is",function($s){return $this->_datalist($s[2],$s[5],$s[8],$s[11],$s[14],$s[17]);},$content);}
		 if(strstr($content,'row=')&&strstr($content,'field=')&&strstr($content,'manage=')&&strstr($content,'list=')&&!strstr($content,'edit=')&&!strstr($content,'page=')&&!strstr($content,'table=')&&!strstr($content,'title=')){$content=preg_replace_callback("/<datalist\s+name=(\"|\'|)(\w+|)(\"|\'|)\s+id=(\"|\'|)(\w+|)(\"|\'|)\s+row=(\"|\'|)(\w+|)(\"|\'|)\s+field=(\"|\'|)(.*?)(\"|\'|)\s+manage=(\"|\'|)(.*?)(\"|\'|)\s+list=(\"|\'|)(\w+|)(\"|\'|)(\s+|)>/is",function($s){return $this->_datalist($s[2],$s[5],$s[8],$s[11],$s[14],'false','true','false','',$s[17]);},$content);}
		 if(strstr($content,'row=')&&strstr($content,'field=')&&strstr($content,'manage=')&&strstr($content,'table=')&&!strstr($content,'edit=')&&!strstr($content,'page=')&&!strstr($content,'list=')&&!strstr($content,'title=')){$content=preg_replace_callback("/<datalist\s+name=(\"|\'|)(\w+|)(\"|\'|)\s+id=(\"|\'|)(\w+|)(\"|\'|)\s+row=(\"|\'|)(\w+|)(\"|\'|)\s+field=(\"|\'|)(.*?)(\"|\'|)\s+manage=(\"|\'|)(.*?)(\"|\'|)\s+table=(\"|\'|)(.*?|)(\"|\'|)(\s+|)>/is",function($s){return $this->_datalist($s[2],$s[5],$s[8],$s[11],$s[14],false,true,false,$s[17],'table');},$content);}
		if(strstr($content,'row=')&&strstr($content,'field=')&&strstr($content,'manage=')&&strstr($content,'title=')&&!strstr($content,'edit=')&&!strstr($content,'page=')&&!strstr($content,'table=')&&!strstr($content,'list=')){$content=preg_replace_callback("/<datalist\s+name=(\"|\'|)(\w+|)(\"|\'|)\s+id=(\"|\'|)(\w+|)(\"|\'|)\s+row=(\"|\'|)(\w+|)(\"|\'|)\s+field=(\"|\'|)(.*?)(\"|\'|)\s+manage=(\"|\'|)(.*?)(\"|\'|)\s+title=(\"|\'|)(\w+|)(\"|\'|)(\s+|)>/is",function($s){return $this->_datalist($s[2],$s[5],$s[8],$s[11],$s[14],false,$s[17],false,'','table');},$content);}
		if(strstr($content,'row=')&&strstr($content,'field=')&&strstr($content,'manage=')&&strstr($content,'page=')&&!strstr($content,'edit=')&&!strstr($content,'title=')&&!strstr($content,'table=')&&!strstr($content,'list=')){$content=preg_replace_callback("/<datalist\s+name=(\"|\'|)(\w+|)(\"|\'|)\s+id=(\"|\'|)(\w+|)(\"|\'|)\s+row=(\"|\'|)(\w+|)(\"|\'|)\s+field=(\"|\'|)(.*?)(\"|\'|)\s+manage=(\"|\'|)(.*?)(\"|\'|)\s+page=(\"|\'|)(\w+|)(\"|\'|)(\s+|)>/is",function($s){return $this->_datalist($s[2],$s[5],$s[8],$s[11],$s[14],false,true,$s[17],'','table');},$content);}
		
		 if(strstr($content,'row=')&&strstr($content,'field=')&&strstr($content,'manage=')&&!strstr($content,'list=')&&!strstr($content,'page=')&&!strstr($content,'table=')&&!strstr($content,'title=')&&!strstr($content,'edit='))$content=preg_replace_callback("/<datalist\s+name=(\"|\'|)(\w+|)(\"|\'|)\s+id=(\"|\'|)(\w+|)(\"|\'|)\s+row=(\"|\'|)(\w+|)(\"|\'|)\s+field=(\"|\'|)(.*?)(\"|\'|)\s+manage=(\"|\'|)(.*?)(\"|\'|)(\s+|)>/is",function($s){return $this->_datalist($s[2],$s[5],$s[8],$s[11],$s[14]);},$content);
		 if(strstr($content,'row=')&&strstr($content,'field=')&&!strstr($content,'list=')&&!strstr($content,'page=')&&!strstr($content,'table=')&&!strstr($content,'title=')&&!strstr($content,'edit=')&&!strstr($content,'manage=')) $content=preg_replace_callback("/<datalist\s+name=(\"|\'|)(\w+|)(\"|\'|)\s+id=(\"|\'|)(\w+|)(\"|\'|)\s+row=(\"|\'|)(\w+|)(\"|\'|)\s+field=(\"|\'|)(.*?)(\"|\'|)(\s+|)>/is",function($s){return $this->_datalist($s[2],$s[5],$s[8],$s[11]);},$content);
		 
		 if(preg_replace('/<datalist(.*?)>/is','',$content)!=$content){
		 global $datafieldname;
		 if(strstr($content,'name='))preg_replace_callback('/name=(\"|\'|)(\w+|)(\"|\'|)/is',function($s){$name=$s[2];},$content);
		 if(strstr($content,'id='))preg_replace_callback('/id=(\"|\'|)(\w+|)(\"|\'|)/is',function($s){$id=$s[2];},$content);
		 if(strstr($content,'row='))preg_replace_callback('/row=(\"|\'|)(\w+|)(\"|\'|)/is',function($s){$row=$s[2];},$content);
		  if(strstr($content,'field=')){
			  //preg_replace('/field=(\"|\')(.*?|)(\"|\')/is','$field="\\2"',$content);
		  preg_replace_callback('/field=(\"|\')(.*?|)(\"|\')/is',function($s){return $this->getfieldname('field',$s[2]);},$content);
		$field=$datafieldname['field'];
		
		 }
		
		 if(strstr($content,'manage=')){preg_replace_callback('/manage=(\"|\')(.*?|)(\"|\')/is',function($s){return $this->getfieldname('manage',$s[2]);},$content);
		 $manage=$datafieldname['manage'];
		 }
		 if(strstr($content,'edit='))preg_replace_callback('/edit=(\"|\'|)(\w+|)(\"|\'|)/is',function($s){$edit=$s[2];},$content);
		 if(strstr($content,'title='))preg_replace_callback('/title=(\"|\'|)(\w+|)(\"|\'|)/is',function($s){$title=$s[2];},$content);
		 if(strstr($content,'page='))preg_replace_callback('/page=(\"|\'|)(\w+|)(\"|\'|)/is',function($s){$page=$s[2];},$content);
		 if(strstr($content,'table='))preg_replace_callback('/table=(\"|\')(.*?|)(\"|\')/is',function($s){$table=$s[2];},$content);
		 if(strstr($content,'list='))preg_replace_callback('/list=(\"|\')(\w+|)(\"|\')/is',function($s){$islist=$s[2];},$content);
		$edit=isset($edit)?$edit:'';
		$title=isset($title)?$title:'';
		$page=isset($page)?$page:'';
		$table=isset($table)?$table:'';
		$list=isset($list)?$list:'';
		$name=isset($name)?$name:'';
		$id=isset($id)?$id:'';
		$row=isset($row)?$row:'';
		$field=isset($field)?$field:'';
		$manage=isset($manage)?$manage:'';
		$islist=isset($islist)?$islist:'';
		$table=isset($table)?$table:'';
		  $content=preg_replace('/<datalist(.*?)>/is',$this->_datalist($name,$id,$row,$field,$manage,$edit,$title,$page,$table,$islist),$content);
		  $name='';$id='';$row='';$field='';$manage='';$edit='';$title='';$page='';$table='';$islist='';
		 }
		 $content=preg_replace_callback('/\{\$([^\{\}]+)\;\}/is',function($s){return $this->label2($s);},$str);
		 $content=str_replace('{#kyphp#}','$',$content);
		return $content;
	}
	static function _include($s){
		$templetedir=DEFAULT_TPL_PATH.'/'.DEFAULT_TPL;
		$templetedir=str_replace('\\','/',$templetedir);
		include_once($templetedir."/$s[1]/$s[2].htm");
	
	}
	function zlforeach($s){
		$item=preg_replace('/\s+/','&',$s[1]);
		parse_str($item,$items);
		$sp=str_replace('$','',$items['from']);
		$list=isset($this->data[$sp])?$this->data[$sp]:array();
		$str='';
		
		$key=$items['key'];
		$value=$items['item'];
		if($list)foreach($list as $key=>$value)
		{
			$param=array($items,$key,$value);
			
			$str1=preg_replace_callback('/\{\$([^\{\}\<\>]+)\;\}/is',function($s) use ($param){				
				return $this->label2($s,$param[0],$param[1],$param[2]);
				},$s[2]);
			$str.=preg_replace_callback('/\{\$([^\{\}]+)\;\}/is',function($s) use ($param){				
				return $this->label2($s,$param[0],$param[1],$param[2]);
				},$str1);
			
		}
		return $str;
		
	
	}
	function label2($s,$items='',$keynew='',$valuenew=''){
		if(stristr($s[1],'this')){
			if(preg_match('/this->url->link\(([^\,]+)\,([^\,]+)\)/is',$s[1],$match))
			{
				
				return $this->url->link(trim(trim($match[1],'"'),"'"),trim(trim($match[2],'"'),"'"));
			}
			
			if(preg_match('/this->url->link\(([^\,]+)\)/is',$s[1],$match))
			{
				
				return $this->url->link(trim(trim($match[1],'"'),"'"));
				
			}
		}
		if($items){
			$las=explode('.',$s[1]);
			$key=$items['key'];
			$value=$items['item'];
			$$key=$keynew;
			$$value=$valuenew;		
			$a1=$las[0];
			$a2=$las[1];
			
			return $$a1[$a2];
		}else{
			if(isset($this->data[$s[1]]))
				return $this->data[$s[1]];
			else
				return $s[1];
		}
	
	}
	function _datalist($name,$id,$row='',$field,$manage='',$edit='false',$title='true',$page='false',$table='',$islist='table'){
		global $datalist;
		//_datalist('\\2','\\5','\\8','\\11','\\14','\\17','\\20','\\23','\\26','\\29')

		   $fieldarr=explode(',',$field);
		   $managearr=explode(',',$manage);
		   
		   $wname='';
		   $str='<table '.$table.' class='.$name.' id='.$id.'>';
		   $strli='<ul class='.$name.' id='.$id.'>';
		   if($title=='true'){
		   $str.='<tr class=title>';
		   $strli.='<li class=title>';
		   foreach($fieldarr as $key=>$kt){
		   $vpos=strpos($kt,'|');
		   $vname=substr($kt,0,$vpos);
		   
		   $tpos=strpos($kt,':');
		   $wpos=strpos($kt,'%');
		   if($wpos)$wname=substr($kt,$wpos+1);
			$wname=str_replace('=',':',$wname);
		   if(!$tpos)$tpos=strlen($kt);
		 
		   $tname=substr($kt,$vpos+1,$tpos-$vpos-1);
			
		   if(strstr($tname,'%')) $tname=substr($kt,$vpos+1,$wpos-$vpos-1);
		   
		   $str.='<td style="'.$wname.'">'.$tname.'</td>';
			$strli.='<span style="'.$wname.'">'.$tname.'</span>';
		   $wname='';
		   $vname='';
		   $tname='';
		   }
		   if($manage){
			foreach($managearr as $key=>$kt){
		   $vpos=strpos($kt,'|');
		   $vname=substr($kt,0,$vpos);
		   $tpos=strpos($kt,':');
		   $wpos=strpos($kt,'%');
			if($wpos)$wname=substr($kt,$wpos+1);
			$wname=str_replace('=',':',$wname);
		   if(!$tpos)$tpos=strlen($kt);
		   $tname=substr($kt,$vpos+1,$tpos-$vpos-1);
		  if(strstr($tname,'%')) $tname=substr($kt,$vpos+1,$wpos-$vpos-1);
		   $str.='<td style="'.$wname.'">'.$tname.'</td>';
		   $strli.='<span style="'.$wname.'">'.$tname.'</span>';
		   $wname='';
		   }
		   }
		   $str.='</tr>';
			$strli.='</li>';
		   }
		   $dataarr=isset($datalist[$name])?$datalist[$name]:'';
		   $count=count($dataarr);
		   
		   if($row&&$row<count($dataarr)&&$row>0){
		   if($page=='true'){
			 $pagestr='<div class="page">';
			 $pa=ceil($count/$row);
			 $locpa=!empty($_GET['pa'])?$_GET['pa']:'';
			 if(!$locpa)$locpa=1;
			 
			$return=debug_backtrace();
			$funame=$return[4]['function'];
			$gstr='';
			if(!empty($_GET['key']))$gstr='/key/'.$_GET['key'];
			if(!empty($_POST['key']))$gstr='/key/'.$_POST['key'];
			
			if($locpa>$pa)$locpa=$pa;
			
			global $_charset;
			$pagestr=sprintf('<div class=page>'.$_charset['page_text'].'</div>',$pa,$locpa,__URL__,$funame,$gstr,__URL__,$funame,($locpa-1).$gstr,__URL__,$funame,($locpa+1).$gstr,__URL__,$funame,$pa.$gstr);

			 }else{
			 $locpa=1;
			 } 
		   unset($dataarr);
		  
		   $rowend=$row+($locpa-1)*$row;
		   if($rowend>$count)$rowend=$count;
		   for($i=($locpa-1)*$row;$i<$rowend;$i++){
		  
		   $dataarr[]=$datalist[$name][$i];
		   }
		   
		   }
		   if($dataarr)foreach($dataarr as $key=>$k){
		   $str.='<tr>';
			$strli.='<li>';
			  foreach($fieldarr as $key=>$kt){
			  $vpos=strpos($kt,'|');
			  $vname=substr($kt,0,$vpos);
			  $tpos=strpos($kt,':');
			  $wpos=strpos($kt,'%');

			   if($wpos)$wname=substr($kt,$wpos+1);
			   $wname=str_replace('=',':',$wname);
			$jname='';
			 if($tpos)$jname=substr($kt,$tpos+1);
			  $jname=preg_replace_callback('/[{][$](\w+)[}]/is',function($s) use ($k){return $k[$s[1]];},$jname);
			  if(strstr($jname,'%')) $jname=substr($kt,$tpos+1,$wpos-$tpos-1);
			  $sname=isset($k[$vname])?$k[$vname]:'';
			  if(strstr($vname,'(')&&strstr($vname,')')){$sname=$vname;
			  
			  $sname=preg_replace_callback('/[{][$](\w+)[}]/is',function($s) use ($k){return $k[$s[1]];},$sname);
			  
			  $sname=preg_replace_callback('/(\w+)[(](.*?)[)]/is',function($s){return $s[1]($s[2]);},$sname);
			   
			  }
			 
			 
			 
			 
			 
			  $str.='<td style="'.$wname.'"><a href=# onclick="'.$jname.'">'.$sname.'</a></td>';
			  $strli.='<span style="'.$wname.'"><a href=# onclick="'.$jname.'">'.$sname.'</a></span>';
			   $wname='';
			   $jname='';
			   $sname='';
			  }
			 if($manage){
			   foreach($managearr as $key=>$kt){
			  $vpos=strpos($kt,'|');
			  $vname=substr($kt,0,$vpos);
			  $tpos=strpos($kt,':');
			   $wpos=strpos($kt,'%');
			  if($wpos) $wname=substr($kt,$wpos+1);
			  $wname=str_replace('=',':',$wname);
			  if(!$tpos)$tpos=strlen($kt);
			  $tname=substr($kt,$vpos+1,$tpos-$vpos-1);
			   if(strstr($tname,'%')) $tname=substr($kt,$vpos+1,$wpos-$vpos-1);
			   

			  $vname=preg_replace_callback('/[{][$](\w+)[}]/is',function($s) use($k){return $k[$s[1]];},$vname);
			  if($tpos)$jname=substr($kt,$tpos+1);
				if(strstr($jname,'%')) $jname=substr($kt,$tpos+1,$wpos-$tpos-1);
			  $jname=preg_replace_callback('/[{][$](\w+)[}]/is',function($s) use($k){$k[$s[1]];},$jname);
			  $str.='<td style="'.$wname.'"><a href='.$vname.' onclick="'.$jname.'">'.$tname.'</a></td>';
			   $strli.='<span style="'.$wname.'"><a href='.$vname.' onclick="'.$jname.'">'.$tname.'</a></span>';
			   $wname='';
			   $jname='';
			   $tname='';
			  }
		   }
		   $str.='</tr>';
		   $strli.='</li>';
		   }
		   $strli.='</ul>';
		   $str.='</table>';
		   $pagestr=isset($pagestr)?$pagestr:'';
		   $str.=$pagestr;
		   $strli.=$pagestr;
			
			if($islist=='li')$str=$strli; 
		return $str;
	}
	static function replace($s,$data){
	
	
	}
	function getfieldname($name,$str){
		global $datafieldname;
		$datafieldname[$name]=$str;
		return $str;
	}
	public function label($s,$a=''){
		if($a){
			return isset($this->data[$s][$a])?$this->data[$s][$a]:'';
		}else{
			return isset($this->data[$s])?$this->data[$s]:'';
		}
	}
	function _pvolist($name,$nid,$id,$content){
	global $volist,$lable,$list;
	$content1='';
	if(!empty($list[$name][$nid])){
	 foreach($list[$name][$nid] as $key=>$k){
		
		
		if(!strstr($content,$id.'.')){ 	
		  $content1.=preg_replace("/[{][$](\w+)[}]/is",function ($s){return $k[$s[1]];},$content);
		 }else{
		 //echo $id;
		  $content1.=preg_replace("/[{][$]$id\.(\w+)[}]/is",function($s){return $k[$s[1]];},$content);
		 } 
	   }
	  }
	return $content1;
	}
	function _if($s){
		$value1=isset($s[1])?$s[1]:'';
		$condition=isset($s[2])?$s[2]:'';
		$value2=isset($s[3])?$s[3]:'';
		$result=isset($s[4])?$s[4]:'';
		if($condition=="=="){
		  if($value1==$value2)return $result;
		  }
		  if($condition=="<"){
		  if($value1<$value2)return $result;
		  }
		  if($condition=="<="){
		  if($value1<=$value2)return $result;
		  }
		  if($condition==">"){
		  if($value1>$value2)return $result;
		  }
		  if($condition==">="){
		  if($value1>=$value2)return $result;
		}				
	  
	}
	function checkArrayindex($array,$index){

		return isset($array[$index])?$array[$index]:'';
	}
	function _volist($s){
		global $volist,$lable,$list;
		
		$name=$s[2];
		$id=$s[5];
		$content=$s[7];
		if(!$id)return $volist[$name];
		$content1='';
		  foreach($volist[$name] as $key=>$k){
			$$id=$k;
			
			
			  $content2=preg_replace_callback("/[{][$](\w+)[}]/is",function($s) use($k){return $this->checkArrayindex($k,$s[1]);},$content);
			  
			  $content1.=preg_replace_callback("/[{][$]$id\.(\w+)[}]/is",function($s) use($k){return $k[$s[1]];},$content2);
			
			 
		  }
		  
		  $content1=preg_replace_callback("/<list\s+name=[\\\][\"|\'|](\w+)\[(.*?)\][\\\][\"|\'|]\s+id=[\\\][\"|\'|](\w+)[\\\][\"|\'|]>(.*?)<\/list>/is",function($s){return $this->_pvolist($s[1],$s[2],$s[3],$s[4]);},$content1);
		  $content1=str_replace('\"','"',$content1);
		  
		return $content1;

	}

}
?>