<?php
// +----------------------------------------------------------------------
// 最便捷的php框架
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.ky53.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.ky53.net )
// +----------------------------------------------------------------------

/**
 
 * @author   qq:974005652(zhx626@126.com) by老顽童 karmi
 * @version  3.0
 +------------------------------------------------------------------------------
 */

define("BCCOMP_LARGER", 1);
class zlrsa{
	function rsa_encrypt($message, $public_key, $modulus, $keylength=1024)
	{
		$padded =$this->add_PKCS1_padding($message, true, $keylength / 8);
		$number =$this->binary_to_number($padded);
		$encrypted = $this->pow_mod($number, $public_key, $modulus);
		$result =$this->number_to_binary($encrypted, $keylength / 8);
		
		return strrev($result);
	}
	function decrypt($message, $data, $modulus='', $keylength=1024)
	{
		if(is_array($data)){

			$private_key=$data['private_key'];
			$modulus=$data['modulus'];
		}else{
			$private_key=$data;
		}
		$message=$this->convert($message); 
		
		return  $this->rsa_decrypt($message, $private_key, $modulus, $keylength);
	}

	function rsa_decrypt($message, $private_key, $modulus, $keylength=1024)
	{
		$number =$this->binary_to_number($message);
		$decrypted =$this->pow_mod($number, $private_key, $modulus);
		$result =$this->number_to_binary($decrypted, $keylength / 8);
		$string= strrev($this->remove_PKCS1_padding($result, $keylength / 8));
		return $string;
	}

	function rsa_sign($message, $private_key, $modulus, $keylength=1024)
	{
		$padded = $this->add_PKCS1_padding($message, false, $keylength / 8);
		$number = $this->binary_to_number($padded);
		$signed = $this->pow_mod($number, $private_key, $modulus);
		$result = $this->number_to_binary($signed, $keylength / 8);

		return $result;
	}

	

	function rsa_kyp_verify($message, $public_key, $modulus, $keylength=1024)
	{
		$number = $this->binary_to_number($message);
		$decrypted = $this->pow_mod($number, $public_key, $modulus);
		$result = $this->number_to_binary($decrypted, $keylength / 8);

		return $this->remove_KYP_padding($result, $keylength / 8);
	}

		
	function pow_mod($p, $q, $r)
	{
		// Extract powers of 2 from $q
		$factors = array();
		$div = $q;
		$power_of_two = 0;
		while(bccomp($div, "0") == BCCOMP_LARGER)
		{
			$rem = bcmod($div, 2);
			$div = bcdiv($div, 2);
		
			if($rem) array_push($factors, $power_of_two);
			$power_of_two++;
		}

		// Calculate partial results for each factor, using each partial result as a
		// starting point for the next. This depends of the factors of two being
		// generated in increasing order.
		$partial_results = array();
		$part_res = $p;
		$idx = 0;
		foreach($factors as $factor)
		{
			while($idx < $factor)
			{
				$part_res = bcpow($part_res, "2");
				$part_res = bcmod($part_res, $r);

				$idx++;
			}
			
			array_push($partial_results, $part_res);
		}

		// Calculate final result
		$result = "1";
		foreach($partial_results as $part_res)
		{
			$result = bcmul($result, $part_res);
			$result = bcmod($result, $r);
		}

		return $result;
	}

	//--
	// Function to add padding to a decrypted string
	// We need to know if this is a private or a public key operation [4]
	//--
	function add_PKCS1_padding($data, $isPublicKey, $blocksize)
	{
		$pad_length = $blocksize - 3 - strlen($data);

		if($isPublicKey)
		{
			$block_type = "\x02";
		
			$padding = "";
			for($i = 0; $i < $pad_length; $i++)
			{
				$rnd = mt_rand(1, 255);
				$padding .= chr($rnd);
			}
		}
		else
		{
			$block_type = "\x01";
			$padding = str_repeat("\xFF", $pad_length);
		}
		
		return "\x00" . $block_type . $padding . "\x00" . $data;
	}

	//--
	// Remove padding from a decrypted string
	// See [4] for more details.
	//--
	function remove_PKCS1_padding($data, $blocksize)
	{
		//以下部分于原版的RSA有所不同,修复了原版的一个BUG
		//assert(strlen($data) == $blocksize);
		$data = substr($data, 1);

		// We cannot deal with block type 0
		if($data{0} == '\0')
			die("Block type 0 not implemented.");

		// Then the block type must be 1 or 2 
		//assert(($data{0} == "\x01") || ($data{0} == "\x02"));

	//	echo $data;
		// Remove the padding
		$i=1;
		while (1){
			$offset = strpos($data, "\0", $i);
			if(!$offset){
				$offset=$i;
				break;
			}
			$i=$offset+1;
		}
		//$offset = strpos($data, "\0", 100);
		return substr($data, $offset);
	}

	//--
	// Remove "kyp" padding
	// (Non standard)
	//--
	function remove_KYP_padding($data, $blocksize)
	{
		assert(strlen($data) == $blocksize);
		
		$offset = strpos($data, "\0");
		return substr($data, 0, $offset);
	}

	//--
	// Convert binary data to a decimal number
	//--
	function binary_to_number($data)
	{
		$base = "256";
		$radix = "1";
		$result = "0";

		for($i = strlen($data) - 1; $i >= 0; $i--)
		{
			$digit = ord($data{$i});
			$part_res = bcmul($digit, $radix);
			$result = bcadd($result, $part_res);
			$radix = bcmul($radix, $base);
		}

		return $result;
	}

	//--
	// Convert a number back into binary form
	//--
	function number_to_binary($number, $blocksize)
	{
		$base = "256";
		$result = "";

		$div = $number;
		while($div > 0)
		{
			$mod = bcmod($div, $base);
			$div = bcdiv($div, $base);
			
			$result = chr($mod) . $result;
		}

		return str_pad($result, $blocksize, "\x00", STR_PAD_LEFT);
	}
	function convert($hexString) 
	{ 
			$hexLenght = strlen($hexString); 
			// only hex numbers is allowed 
			if ($hexLenght % 2 != 0 || preg_match("/[^\da-fA-F]/",$hexString)) return FALSE; 

			$binString=''; 

			for ($x = 1; $x <= $hexLenght/2; $x++) 
			{ 
					$binString .= chr(hexdec(substr($hexString,2 * $x - 2,2))); 
			} 

			return $binString; 
	}
	public static function js(){
		$js=<<<KYPHP
var hexcase=0;var b64pad="";function hex_md5(s){return rstr2hex(rstr_md5(str2rstr_utf8(s)))}function b64_md5(s){return rstr2b64(rstr_md5(str2rstr_utf8(s)))}function any_md5(s,e){return rstr2any(rstr_md5(str2rstr_utf8(s)),e)}function hex_hmac_md5(k,d){return rstr2hex(rstr_hmac_md5(str2rstr_utf8(k),str2rstr_utf8(d)))}function b64_hmac_md5(k,d){return rstr2b64(rstr_hmac_md5(str2rstr_utf8(k),str2rstr_utf8(d)))}function any_hmac_md5(k,d,e){return rstr2any(rstr_hmac_md5(str2rstr_utf8(k),str2rstr_utf8(d)),e)}function md5_vm_test(){return hex_md5("abc").toLowerCase()=="900150983cd24fb0d6963f7d28e17f72"}function rstr_md5(s){return binl2rstr(binl_md5(rstr2binl(s),s.length*8))}function rstr_hmac_md5(key,data){var bkey=rstr2binl(key);if(bkey.length>16)bkey=binl_md5(bkey,key.length*8);var ipad=Array(16),opad=Array(16);for(var i=0;i<16;i++){ipad[i]=bkey[i]^0x36363636;opad[i]=bkey[i]^0x5C5C5C5C}var hash=binl_md5(ipad.concat(rstr2binl(data)),512+data.length*8);return binl2rstr(binl_md5(opad.concat(hash),512+128))}function rstr2hex(input){try{hexcase}catch(e){hexcase=0}var hex_tab=hexcase?"0123456789ABCDEF":"0123456789abcdef";var output="";var x;for(var i=0;i<input.length;i++){x=input.charCodeAt(i);output+=hex_tab.charAt((x>>>4)&0x0F)+hex_tab.charAt(x&0x0F)}return output}function rstr2b64(input){try{b64pad}catch(e){b64pad=''}var tab="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";var output="";var len=input.length;for(var i=0;i<len;i+=3){var triplet=(input.charCodeAt(i)<<16)|(i+1<len?input.charCodeAt(i+1)<<8:0)|(i+2<len?input.charCodeAt(i+2):0);for(var j=0;j<4;j++){if(i*8+j*6>input.length*8)output+=b64pad;else output+=tab.charAt((triplet>>>6*(3-j))&0x3F)}}return output}function rstr2any(input,encoding){var divisor=encoding.length;var i,j,q,x,quotient;var dividend=Array(Math.ceil(input.length/2));for(i=0;i<dividend.length;i++){dividend[i]=(input.charCodeAt(i*2)<<8)|input.charCodeAt(i*2+1)}var full_length=Math.ceil(input.length*8/(Math.log(encoding.length)/Math.log(2)));var remainders=Array(full_length);for(j=0;j<full_length;j++){quotient=Array();x=0;for(i=0;i<dividend.length;i++){x=(x<<16)+dividend[i];q=Math.floor(x/divisor);x-=q*divisor;if(quotient.length>0||q>0)quotient[quotient.length]=q}remainders[j]=x;dividend=quotient}var output="";for(i=remainders.length-1;i>=0;i--)output+=encoding.charAt(remainders[i]);return output}function str2rstr_utf8(input){var output="";var i=-1;var x,y;while(++i<input.length){x=input.charCodeAt(i);y=i+1<input.length?input.charCodeAt(i+1):0;if(0xD800<=x&&x<=0xDBFF&&0xDC00<=y&&y<=0xDFFF){x=0x10000+((x&0x03FF)<<10)+(y&0x03FF);i++}if(x<=0x7F)output+=String.fromCharCode(x);else if(x<=0x7FF)output+=String.fromCharCode(0xC0|((x>>>6)&0x1F),0x80|(x&0x3F));else if(x<=0xFFFF)output+=String.fromCharCode(0xE0|((x>>>12)&0x0F),0x80|((x>>>6)&0x3F),0x80|(x&0x3F));else if(x<=0x1FFFFF)output+=String.fromCharCode(0xF0|((x>>>18)&0x07),0x80|((x>>>12)&0x3F),0x80|((x>>>6)&0x3F),0x80|(x&0x3F))}return output}function str2rstr_utf16le(input){var output="";for(var i=0;i<input.length;i++)output+=String.fromCharCode(input.charCodeAt(i)&0xFF,(input.charCodeAt(i)>>>8)&0xFF);return output}function str2rstr_utf16be(input){var output="";for(var i=0;i<input.length;i++)output+=String.fromCharCode((input.charCodeAt(i)>>>8)&0xFF,input.charCodeAt(i)&0xFF);return output}function rstr2binl(input){var output=Array(input.length>>2);for(var i=0;i<output.length;i++)output[i]=0;for(var i=0;i<input.length*8;i+=8)output[i>>5]|=(input.charCodeAt(i/8)&0xFF)<<(i%32);return output}function binl2rstr(input){var output="";for(var i=0;i<input.length*32;i+=8)output+=String.fromCharCode((input[i>>5]>>>(i%32))&0xFF);return output}function binl_md5(x,len){x[len>>5]|=0x80<<((len)%32);x[(((len+64)>>>9)<<4)+14]=len;var a=1732584193;var b=-271733879;var c=-1732584194;var d=271733878;for(var i=0;i<x.length;i+=16){var olda=a;var oldb=b;var oldc=c;var oldd=d;a=md5_ff(a,b,c,d,x[i+0],7,-680876936);d=md5_ff(d,a,b,c,x[i+1],12,-389564586);c=md5_ff(c,d,a,b,x[i+2],17,606105819);b=md5_ff(b,c,d,a,x[i+3],22,-1044525330);a=md5_ff(a,b,c,d,x[i+4],7,-176418897);d=md5_ff(d,a,b,c,x[i+5],12,1200080426);c=md5_ff(c,d,a,b,x[i+6],17,-1473231341);b=md5_ff(b,c,d,a,x[i+7],22,-45705983);a=md5_ff(a,b,c,d,x[i+8],7,1770035416);d=md5_ff(d,a,b,c,x[i+9],12,-1958414417);c=md5_ff(c,d,a,b,x[i+10],17,-42063);b=md5_ff(b,c,d,a,x[i+11],22,-1990404162);a=md5_ff(a,b,c,d,x[i+12],7,1804603682);d=md5_ff(d,a,b,c,x[i+13],12,-40341101);c=md5_ff(c,d,a,b,x[i+14],17,-1502002290);b=md5_ff(b,c,d,a,x[i+15],22,1236535329);a=md5_gg(a,b,c,d,x[i+1],5,-165796510);d=md5_gg(d,a,b,c,x[i+6],9,-1069501632);c=md5_gg(c,d,a,b,x[i+11],14,643717713);b=md5_gg(b,c,d,a,x[i+0],20,-373897302);a=md5_gg(a,b,c,d,x[i+5],5,-701558691);d=md5_gg(d,a,b,c,x[i+10],9,38016083);c=md5_gg(c,d,a,b,x[i+15],14,-660478335);b=md5_gg(b,c,d,a,x[i+4],20,-405537848);a=md5_gg(a,b,c,d,x[i+9],5,568446438);d=md5_gg(d,a,b,c,x[i+14],9,-1019803690);c=md5_gg(c,d,a,b,x[i+3],14,-187363961);b=md5_gg(b,c,d,a,x[i+8],20,1163531501);a=md5_gg(a,b,c,d,x[i+13],5,-1444681467);d=md5_gg(d,a,b,c,x[i+2],9,-51403784);c=md5_gg(c,d,a,b,x[i+7],14,1735328473);b=md5_gg(b,c,d,a,x[i+12],20,-1926607734);a=md5_hh(a,b,c,d,x[i+5],4,-378558);d=md5_hh(d,a,b,c,x[i+8],11,-2022574463);c=md5_hh(c,d,a,b,x[i+11],16,1839030562);b=md5_hh(b,c,d,a,x[i+14],23,-35309556);a=md5_hh(a,b,c,d,x[i+1],4,-1530992060);d=md5_hh(d,a,b,c,x[i+4],11,1272893353);c=md5_hh(c,d,a,b,x[i+7],16,-155497632);b=md5_hh(b,c,d,a,x[i+10],23,-1094730640);a=md5_hh(a,b,c,d,x[i+13],4,681279174);d=md5_hh(d,a,b,c,x[i+0],11,-358537222);c=md5_hh(c,d,a,b,x[i+3],16,-722521979);b=md5_hh(b,c,d,a,x[i+6],23,76029189);a=md5_hh(a,b,c,d,x[i+9],4,-640364487);d=md5_hh(d,a,b,c,x[i+12],11,-421815835);c=md5_hh(c,d,a,b,x[i+15],16,530742520);b=md5_hh(b,c,d,a,x[i+2],23,-995338651);a=md5_ii(a,b,c,d,x[i+0],6,-198630844);d=md5_ii(d,a,b,c,x[i+7],10,1126891415);c=md5_ii(c,d,a,b,x[i+14],15,-1416354905);b=md5_ii(b,c,d,a,x[i+5],21,-57434055);a=md5_ii(a,b,c,d,x[i+12],6,1700485571);d=md5_ii(d,a,b,c,x[i+3],10,-1894986606);c=md5_ii(c,d,a,b,x[i+10],15,-1051523);b=md5_ii(b,c,d,a,x[i+1],21,-2054922799);a=md5_ii(a,b,c,d,x[i+8],6,1873313359);d=md5_ii(d,a,b,c,x[i+15],10,-30611744);c=md5_ii(c,d,a,b,x[i+6],15,-1560198380);b=md5_ii(b,c,d,a,x[i+13],21,1309151649);a=md5_ii(a,b,c,d,x[i+4],6,-145523070);d=md5_ii(d,a,b,c,x[i+11],10,-1120210379);c=md5_ii(c,d,a,b,x[i+2],15,718787259);b=md5_ii(b,c,d,a,x[i+9],21,-343485551);a=safe_add(a,olda);b=safe_add(b,oldb);c=safe_add(c,oldc);d=safe_add(d,oldd)}return Array(a,b,c,d)}function md5_cmn(q,a,b,x,s,t){return safe_add(bit_rol(safe_add(safe_add(a,q),safe_add(x,t)),s),b)}function md5_ff(a,b,c,d,x,s,t){return md5_cmn((b&c)|((~b)&d),a,b,x,s,t)}function md5_gg(a,b,c,d,x,s,t){return md5_cmn((b&d)|(c&(~d)),a,b,x,s,t)}function md5_hh(a,b,c,d,x,s,t){return md5_cmn(b^c^d,a,b,x,s,t)}function md5_ii(a,b,c,d,x,s,t){return md5_cmn(c^(b|(~d)),a,b,x,s,t)}function safe_add(x,y){var lsw=(x&0xFFFF)+(y&0xFFFF);var msw=(x>>16)+(y>>16)+(lsw>>16);return(msw<<16)|(lsw&0xFFFF)}function bit_rol(num,cnt){return(num<<cnt)|(num>>>(32-cnt))}function zlrsa(ptoken,b,d){ptoken=hex_md5(ptoken);BigTools&&BigTools.setMaxDigits(130);var c=new RSAKeyPair(b,"",d);ptoken=RSAKeyPair.encryptedString(c,ptoken);return ptoken}
(function(ab){var ad=2;var I=16;var o=I;var Q=1<<16;var e=Q>>>1;var M=Q*Q;var T=Q-1;var Z=9999999999999998;var U;var aa;var n,c;function u(af){U=af;aa=new Array(U);for(var a=0;a<aa.length;a++){aa[a]=0}n=new b();c=new b();c.digits[0]=1}u(20);var J=15;var L=q(1000000000000000);function b(a){if(typeof a=="boolean"&&a==true){this.digits=null}else{this.digits=aa.slice(0)}this.isNeg=false}function r(ai){var ah=ai.charAt(0)=="-";var ag=ah?1:0;var a;while(ag<ai.length&&ai.charAt(ag)=="0"){++ag}if(ag==ai.length){a=new b()}else{var af=ai.length-ag;var aj=af%J;if(aj==0){aj=J}a=q(Number(ai.substr(ag,aj)));ag+=aj;while(ag<ai.length){a=g(ae(a,L),q(Number(ai.substr(ag,J))));ag+=J}a.isNeg=ah}return a}function P(af){var a=new b(true);a.digits=af.digits.slice(0);a.isNeg=af.isNeg;return a}function q(ag){var a=new b();a.isNeg=ag<0;ag=Math.abs(ag);var af=0;while(ag>0){a.digits[af++]=ag&T;ag=Math.floor(ag/Q)}return a}function x(ag){var a="";for(var af=ag.length-1;af>-1;--af){a+=ag.charAt(af)}return a}var d=new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");function O(ag,ai){var af=new b();af.digits[0]=ai;var ah=w(ag,af);var a=d[ah[1].digits[0]];while(f(ah[0],n)==1){ah=w(ah[0],af);digit=ah[1].digits[0];a+=d[ah[1].digits[0]]}return(ag.isNeg?"-":"")+x(a)}function ac(ag){var af=new b();af.digits[0]=10;var ah=w(ag,af);var a=String(ah[1].digits[0]);while(f(ah[0],n)==1){ah=w(ah[0],af);a+=String(ah[1].digits[0])}return(ag.isNeg?"-":"")+x(a)}var m=new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");function R(ag){var af=15;var a="";for(i=0;i<4;++i){a+=m[ag&af];ag>>>=4}return x(a)}function B(af){var a="";var ah=V(af);for(var ag=V(af);ag>-1;--ag){a+=R(af.digits[ag])}return a}function A(al){var ag=48;var af=ag+9;var ah=97;var ak=ah+25;var aj=65;var ai=65+25;var a;if(al>=ag&&al<=af){a=al-ag}else{if(al>=aj&&al<=ai){a=10+al-aj}else{if(al>=ah&&al<=ak){a=10+al-ah}else{a=0}}}return a}function K(ah){var af=0;var a=Math.min(ah.length,4);for(var ag=0;ag<a;++ag){af<<=4;af|=A(ah.charCodeAt(ag))}return af}function W(ai){var af=new b();var a=ai.length;for(var ah=a,ag=0;ah>0;ah-=4,++ag){af.digits[ag]=K(ai.substr(Math.max(ah-4,0),Math.min(ah,4)))}return af}function C(am,al){var a=am.charAt(0)=="-";var ah=a?1:0;var an=new b();var af=new b();af.digits[0]=1;for(var ag=am.length-1;ag>=ah;ag--){var ai=am.charCodeAt(ag);var aj=A(ai);var ak=k(af,aj);an=g(an,ak);af=k(af,al)}an.isNeg=a;return an}function D(a){return(a.isNeg?"-":"")+a.digits.join(" ")}function g(af,aj){var a;if(af.isNeg!=aj.isNeg){aj.isNeg=!aj.isNeg;a=S(af,aj);aj.isNeg=!aj.isNeg}else{a=new b();var ai=0;var ah;for(var ag=0;ag<af.digits.length;++ag){ah=af.digits[ag]+aj.digits[ag]+ai;a.digits[ag]=ah%Q;ai=Number(ah>=Q)}a.isNeg=af.isNeg}return a}function S(af,aj){var a;if(af.isNeg!=aj.isNeg){aj.isNeg=!aj.isNeg;a=g(af,aj);aj.isNeg=!aj.isNeg}else{a=new b();var ai,ah;ah=0;for(var ag=0;ag<af.digits.length;++ag){ai=af.digits[ag]-aj.digits[ag]+ah;a.digits[ag]=ai%Q;if(a.digits[ag]<0){a.digits[ag]+=Q}ah=0-Number(ai<0)}if(ah==-1){ah=0;for(var ag=0;ag<af.digits.length;++ag){ai=0-a.digits[ag]+ah;a.digits[ag]=ai%Q;if(a.digits[ag]<0){a.digits[ag]+=Q}ah=0-Number(ai<0)}a.isNeg=!af.isNeg}else{a.isNeg=af.isNeg}}return a}function V(af){var a=af.digits.length-1;while(a>0&&af.digits[a]==0){--a}return a}function H(ag){var ai=V(ag);var ah=ag.digits[ai];var af=(ai+1)*o;var a;for(a=af;a>af-o;--a){if((ah&32768)!=0){break}ah<<=1}return a}function ae(ak,aj){var an=new b();var ai;var af=V(ak);var am=V(aj);var al,a,ag;for(var ah=0;ah<=am;++ah){ai=0;ag=ah;for(j=0;j<=af;++j,++ag){a=an.digits[ag]+ak.digits[j]*aj.digits[ah]+ai;an.digits[ag]=a&T;ai=a>>>I}an.digits[ah+af+1]=ai}an.isNeg=ak.isNeg!=aj.isNeg;return an}function k(a,aj){var ai,ah,ag;result=new b();ai=V(a);ah=0;for(var af=0;af<=ai;++af){ag=result.digits[af]+a.digits[af]*aj+ah;result.digits[af]=ag&T;ah=ag>>>I}result.digits[1+ai]=ah;return result}function v(ai,al,ag,ak,aj){var a=Math.min(al+aj,ai.length);for(var ah=al,af=ak;ah<a;++ah,++af){ag[af]=ai[ah]}}var p=new Array(0,32768,49152,57344,61440,63488,64512,65024,65280,65408,65472,65504,65520,65528,65532,65534,65535);function t(af,al){var ah=Math.floor(al/o);var a=new b();v(af.digits,0,a.digits,ah,a.digits.length-ah);var ak=al%o;var ag=o-ak;for(var ai=a.digits.length-1,aj=ai-1;ai>0;--ai,--aj){a.digits[ai]=((a.digits[ai]<<ak)&T)|((a.digits[aj]&p[ak])>>>(ag))}a.digits[0]=((a.digits[ai]<<ak)&T);a.isNeg=af.isNeg;return a}var E=new Array(0,1,3,7,15,31,63,127,255,511,1023,2047,4095,8191,16383,32767,65535);function l(af,al){var ag=Math.floor(al/o);var a=new b();v(af.digits,ag,a.digits,0,af.digits.length-ag);var aj=al%o;var ak=o-aj;for(var ah=0,ai=ah+1;ah<a.digits.length-1;++ah,++ai){a.digits[ah]=(a.digits[ah]>>>aj)|((a.digits[ai]&E[aj])<<ak)}a.digits[a.digits.length-1]>>>=aj;a.isNeg=af.isNeg;return a}function y(af,ag){var a=new b();v(af.digits,0,a.digits,ag,a.digits.length-ag);return a}function h(af,ag){var a=new b();v(af.digits,ag,a.digits,0,a.digits.length-ag);return a}function N(af,ag){var a=new b();v(af.digits,0,a.digits,0,ag);return a}function f(a,ag){if(a.isNeg!=ag.isNeg){return 1-2*Number(a.isNeg)}for(var af=a.digits.length-1;af>=0;--af){if(a.digits[af]!=ag.digits[af]){if(a.isNeg){return 1-2*Number(a.digits[af]>ag.digits[af])}else{return 1-2*Number(a.digits[af]<ag.digits[af])}}}return 0}function w(aj,ai){var a=H(aj);var ah=H(ai);var ag=ai.isNeg;var ao,an;if(a<ah){if(aj.isNeg){ao=P(c);ao.isNeg=!ai.isNeg;aj.isNeg=false;ai.isNeg=false;an=S(ai,aj);aj.isNeg=true;ai.isNeg=ag}else{ao=new b();an=P(aj)}return new Array(ao,an)}ao=new b();an=aj;var al=Math.ceil(ah/o)-1;var ak=0;while(ai.digits[al]<e){ai=t(ai,1);++ak;++ah;al=Math.ceil(ah/o)-1}an=t(an,ak);a+=ak;var ar=Math.ceil(a/o)-1;var ax=y(ai,ar-al);while(f(an,ax)!=-1){++ao.digits[ar-al];an=S(an,ax)}for(var av=ar;av>al;--av){var am=(av>=an.digits.length)?0:an.digits[av];var aw=(av-1>=an.digits.length)?0:an.digits[av-1];var au=(av-2>=an.digits.length)?0:an.digits[av-2];var at=(al>=ai.digits.length)?0:ai.digits[al];var af=(al-1>=ai.digits.length)?0:ai.digits[al-1];if(am==at){ao.digits[av-al-1]=T}else{ao.digits[av-al-1]=Math.floor((am*Q+aw)/at)}var aq=ao.digits[av-al-1]*((at*Q)+af);var ap=(am*M)+((aw*Q)+au);while(aq>ap){--ao.digits[av-al-1];aq=ao.digits[av-al-1]*((at*Q)|af);ap=(am*Q*Q)+((aw*Q)+au)}ax=y(ai,av-al-1);an=S(an,k(ax,ao.digits[av-al-1]));if(an.isNeg){an=g(an,ax);--ao.digits[av-al-1]}}an=l(an,ak);ao.isNeg=aj.isNeg!=ag;if(aj.isNeg){if(ag){ao=g(ao,c)}else{ao=S(ao,c)}ai=l(ai,ak);an=S(ai,an)}if(an.digits[0]==0&&V(an)==0){an.isNeg=false}return new Array(ao,an)}function Y(a,af){return w(a,af)[0]}function z(a,af){return w(a,af)[1]}function s(af,ag,a){return z(ae(af,ag),a)}function G(ag,ai){var af=c;var ah=ag;while(true){if((ai&1)!=0){af=ae(af,ah)}ai>>=1;if(ai==0){break}ah=ae(ah,ah)}return af}function F(ah,ak,ag){var af=c;var ai=ah;var aj=ak;while(true){if((aj.digits[0]&1)!=0){af=s(af,ai,ag)}aj=l(aj,1);if(aj.digits[0]==0&&V(aj)==0){break}ai=s(ai,ai,ag)}return af}var X={setMaxDigits:u,biCopy:P,biHighIndex:V,BigInt:b,biDivide:Y,biDivideByRadixPower:h,biMultiply:ae,biModuloByRadixPower:N,biSubtract:S,biAdd:g,biCompare:f,biShiftRight:l,biFromHex:W,biToHex:B,biToString:O,biFromString:C};ab.BigTools=ab.BigTools||X})(window);(function(c){var h=c.BigTools,a=h.BigInt,e=h.biFromHex,f=h.biHighIndex;function d(k){this.modulus=h.biCopy(k);this.k=f(this.modulus)+1;var l=new a();l.digits[2*this.k]=1;this.mu=h.biDivide(l,this.modulus);this.bkplus1=new a();this.bkplus1.digits[this.k+1]=1;this.modulo=function(u){var t=h.biDivideByRadixPower(u,this.k-1);var q=h.biMultiply(t,this.mu);var p=h.biDivideByRadixPower(q,this.k+1);var o=h.biModuloByRadixPower(u,this.k+1);var v=h.biMultiply(p,this.modulus);var n=h.biModuloByRadixPower(v,this.k+1);var m=h.biSubtract(o,n);if(m.isNeg){m=h.biAdd(m,this.bkplus1)}var s=h.biCompare(m,this.modulus)>=0;while(s){m=h.biSubtract(m,this.modulus);s=h.biCompare(m,this.modulus)>=0}return m};this.multiplyMod=function(m,o){var n=h.biMultiply(m,o);return this.modulo(n)};this.powMod=function(n,q){var m=new a();m.digits[0]=1;var o=n;var p=q;while(true){if((p.digits[0]&1)!=0){m=this.multiplyMod(m,o)}p=h.biShiftRight(p,1);if(p.digits[0]==0&&f(p)==0){break}o=this.multiplyMod(o,o)}return m}}function g(l,m,k){this.e=e(l);this.d=e(m);this.m=e(k);this.chunkSize=2*f(this.m);this.radix=16;this.barrett=new d(this.m)}function b(k){return(k<10?"0":"")+String(k)}g.encryptedString=function(t,w){var r=new Array();var l=w.length;var p=0;while(p<l){r[p]=w.charCodeAt(p);p++}while(r.length%t.chunkSize!=0){r[p++]=0}var q=r.length;var x="";var o,n,m;for(p=0;p<q;p+=t.chunkSize){m=new a();o=0;for(n=p;n<p+t.chunkSize;++o){m.digits[o]=r[n++];m.digits[o]+=r[n++]<<8}var v=t.barrett.powMod(m,t.e);var u=t.radix==16?h.biToHex(v):h.biToString(v,t.radix);x+=u+" "}return x.substring(0,x.length-1)};g.decryptedString=function(o,p){var r=p.split(" ");var k="";var n,m,q;for(n=0;n<r.length;++n){var l;if(o.radix==16){l=e(r[n])}else{l=h.biFromString(r[n],o.radix)}q=o.barrett.powMod(l,o.d);for(m=0;m<=f(q);++m){k+=String.fromCharCode(q.digits[m]&255,q.digits[m]>>8)}}if(k.charCodeAt(k.length-1)==0){k=k.substring(0,k.length-1)}return k};c.RSAKeyPair=c.RSAKeyPair||g})(window);


KYPHP;
	
	return $js;
	
	}
	
}
?>
