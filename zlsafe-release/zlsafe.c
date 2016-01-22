/*
  +----------------------------------------------------------------------+
  | PHP Version 7                                                        |
  +----------------------------------------------------------------------+
  | Copyright (c) 1997-2015 The PHP Group                                |
  +----------------------------------------------------------------------+
  | This source file is subject to version 3.01 of the PHP license,      |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | http://www.php.net/license/3_01.txt                                  |
  | If you did not receive a copy of the PHP license and are unable to   |
  | obtain it through the world-wide-web, please send a note to          |
  | license@php.net so we can mail you a copy immediately.               |
  +----------------------------------------------------------------------+
  | Author:liang.zhang ÀÏÍçÍ¯ (QQ:974005652)                                                              |
  +----------------------------------------------------------------------+
*/

/* $Id$ */

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "php_ini.h"
#include "ext/standard/php_rand.h"
#include "ext/standard/info.h"
#include "ext/standard/md5.h"
#include "ext/standard/sha1.h"
#include "ext/standard/php_var.h"
#include "ext/session/php_session.h"
#include "ext/standard/php_array.h"
#include "ext/standard/base64.h"

#include "php_zlsafe.h"

/* If you declare any globals in php_zlsafe.h uncomment this:
ZEND_DECLARE_MODULE_GLOBALS(zlsafe)
*/

/* True global resources - no need for thread safety here */
static int le_zlsafe;

/* {{{ PHP_INI
 */
/* Remove comments and fill if you need to have entries in php.ini
PHP_INI_BEGIN()
    STD_PHP_INI_ENTRY("zlsafe.global_value",      "42", PHP_INI_ALL, OnUpdateLong, global_value, zend_zlsafe_globals, zlsafe_globals)
    STD_PHP_INI_ENTRY("zlsafe.global_string", "foobar", PHP_INI_ALL, OnUpdateString, global_string, zend_zlsafe_globals, zlsafe_globals)
PHP_INI_END()
*/
/* }}} */
//  | Author:liang.zhang ÀÏÍçÍ¯ (QQ:974005652)                                                          |

/* Remove the following function when you have successfully modified config.m4
   so that your module can be compiled into PHP, it exists only for testing
   purposes. */

/* Every user-visible function in PHP should document itself in the source */
/* {{{ proto string confirm_zlsafe_compiled(string arg)
   Return a string to confirm that the module is compiled in */
PHP_FUNCTION(confirm_zlsafe_compiled)
{
	char *arg = NULL;
	size_t arg_len, len;
	zend_string *strg;

	if (zend_parse_parameters(ZEND_NUM_ARGS(), "s", &arg, &arg_len) == FAILURE) {
		return;
	}

	strg = strpprintf(0, "Congratulations! You have successfully modified ext/%.78s/config.m4. Module %.78s is now compiled into PHP.", "zlsafe", arg);

	RETURN_STR(strg);
}
zval * zlreadserver(char *zlvalue){
       	 zval *server_vars, *ret,*empty;
         php_stream_filter *zf = NULL;
         zend_string *server = zend_string_init("_SERVER", sizeof("_SERVER") - 1, 0);

         zend_is_auto_global(server);
         if ((server_vars = zend_hash_find(&EG(symbol_table), server)) != NULL &&
                            Z_TYPE_P(server_vars) == IS_ARRAY &&
                            (ret = zend_hash_str_find(Z_ARRVAL_P(server_vars), zlvalue, strlen(zlvalue))) != NULL &&
                            Z_TYPE_P(ret) == IS_STRING) { 
      		return ret; 
	 }
	return NULL;
}
zval * zlreadcookie(char *zlvalue){
       	 zval *server_vars, *ret,*empty;
         php_stream_filter *zf = NULL;
         zend_string *server = zend_string_init("_COOKIE", sizeof("_COOKIE") - 1, 0);
		
         zend_is_auto_global(server);
         if ((server_vars = zend_hash_find(&EG(symbol_table), server)) != NULL &&
                            Z_TYPE_P(server_vars) == IS_ARRAY &&
                            (ret = zend_hash_str_find(Z_ARRVAL_P(server_vars), zlvalue, strlen(zlvalue))) != NULL &&
                            Z_TYPE_P(ret) == IS_STRING) { 
      		return ret; 
		 }
		 
	
		 return NULL;

}
zend_string * zlmd5(char *zl)
{
	zend_string *outid;
	unsigned char *zldigest;
    size_t digest_len;
	char md5str[33];
	
	PHP_MD5_CTX md5_context;
	PHP_MD5Init(&md5_context);
    PHP_MD5Update(&md5_context, (unsigned char *) zl, strlen(zl));
	
	digest_len = 16;
	zldigest = emalloc(digest_len + 1);
	PHP_MD5Final(zldigest, &md5_context);
	md5str[0] = '\0';
	
	make_digest_ex(md5str, zldigest, 16);
	

	outid= strpprintf(0,"%s",md5str);
	
	return outid;
}
zend_string * zlsafemd5(char *zl,int p)
{
	zend_long number;
	zval *ipvalue,*agentvalue;
	zend_string *strg,*strg1,*strg2,*name=NULL;
	zend_string *strgm,*strgm1,*strgm2;
	if (!BG(mt_rand_is_seeded)) {
                php_mt_srand(GENERATE_SEED());
    }
	number = (zend_long) (php_mt_rand() >> 1);
	ipvalue=zlreadserver("REMOTE_ADDR");
	agentvalue=zlreadserver("HTTP_USER_AGENT");
	if(p==1){
		strg=strpprintf(0,"kyphp#zlwrite%.78s",zl);
	}else{
		strg=strpprintf(0,"kyphp#zlwrite%d%.78s",number,zl);
	}
	strg1=strpprintf(0,"%.78s",Z_STRVAL_P(ipvalue));
	strg2=strpprintf(0,"%.78s",Z_STRVAL_P(agentvalue));
	
	
	strgm=zlmd5(strg->val);
	
	strgm1=zlmd5(strg1->val);	
	strgm2=zlmd5(strg2->val);
	strgm->len-16;
	strgm1->len-16;
	strgm2->len-16;
	name=strpprintf(0,"%.78s%.78s%.78s",strgm->val+16,strgm1->val+16,strgm2->val+16);
		
	return name;
}
PHP_FUNCTION(zlsafe_session_check)
{
	zend_string *strgm=NULL,*name;
	zend_string *strg,*strg1,*strg2,*strg3,*strg4,*strg5;
	zval *ipvalue,*agentvalue,*sessionid;
	char *arg = NULL;	
	size_t arg_len, len;
    if (zend_parse_parameters(ZEND_NUM_ARGS(), "|s", &arg, &arg_len) == FAILURE) {
          return;
    }
	ipvalue=zlreadserver("REMOTE_ADDR");
	agentvalue=zlreadserver("HTTP_USER_AGENT");
	strg1=strpprintf(0,"%.78s",Z_STRVAL_P(ipvalue));
	strg2=strpprintf(0,"%.78s",Z_STRVAL_P(agentvalue));
	strgm=zlmd5(strg1->val);
	strg=zlmd5(strg2->val);
	strg3=strpprintf(0,"%.78s",PS(session_name));
	sessionid=zlreadcookie(strg3->val);
	
	
	if(sessionid){
		name=strpprintf(0,"%.78s",Z_STRVAL_P(sessionid)); 
		if(arg){
			name=strpprintf(0,"%.78s",arg);
		}
		strgm->len-16;
		strg->len-16;
		name->len-16;
		strg4=strpprintf(0,"%.78s%.78s",strgm->val+16,strg->val+16);
		strg5=strpprintf(0,"%.78s%",name->val+16);
				
		
		if(strcmp(strg5->val,strg4->val)==0)
		{
			RETVAL_TRUE;
			return;
		}
	}
	RETVAL_FALSE;

}
PHP_FUNCTION(zlsafe_session_checkip)
{
	zend_string *strgm=NULL,*name;
	zend_string *strg1,*strg2,*strg3,*strg4,*strg5;
	zval *ipvalue,*agentvalue,*sessionid,zlstr;
	char *arg = NULL;	
	size_t arg_len, len;
    if (zend_parse_parameters(ZEND_NUM_ARGS(), "|s", &arg, &arg_len) == FAILURE) {
          return;
    }
	
	ipvalue=zlreadserver("REMOTE_ADDR");
	agentvalue=zlreadserver("HTTP_USER_AGENT");
	strg1=strpprintf(0,"%.78s",Z_STRVAL_P(ipvalue));	
	strgm=zlmd5(strg1->val);	
	strg3=strpprintf(0,"%.78s",PS(session_name));
	sessionid=zlreadcookie(strg3->val);
	
	if(sessionid){
		name=strpprintf(0,"%.78s",Z_STRVAL_P(sessionid)); 
		if(arg){
			name=strpprintf(0,"%.78s",arg);
		}
		strgm->len-16;
		name->len-16;
		strg4=strpprintf(0,"%.78s",strgm->val+16);
		strg5=strpprintf(0,"%.78s%",name->val+16);
		strg5->len-16;
		strg2->len+16;
		
		ZVAL_STRINGL(&zlstr,strg5->val,16);
		strg2=strpprintf(0,"%.78s",Z_STRVAL_P(&zlstr));
		
		
		if(strcmp(strg2->val,strg4->val)==0)
		{
			RETVAL_TRUE;
			return;
		}
	}
	RETVAL_FALSE;

}
PHP_FUNCTION(zlsafe_md5)
{
	char *arg = NULL;
	zend_string *name=NULL;
	size_t arg_len, len;
    if (zend_parse_parameters(ZEND_NUM_ARGS(), "s", &arg, &arg_len) == FAILURE) {
          return;
    }
	name=zlsafemd5(arg,0);
	RETURN_STR(name);
}
PHP_FUNCTION(zlsafe_md5_encrypt)
{
	char *arg = NULL;
	zend_string *name=NULL;
	size_t arg_len, len;
    if (zend_parse_parameters(ZEND_NUM_ARGS(), "s", &arg, &arg_len) == FAILURE) {
          return;
    }
	name=zlsafemd5(arg,1);
	RETURN_STR(name);
}
PHP_FUNCTION(zlsafe_md5_real)
{
	char *arg = NULL;
	zend_string *name=NULL;
	size_t arg_len, len;
    if (zend_parse_parameters(ZEND_NUM_ARGS(), "s", &arg, &arg_len) == FAILURE) {
          return;
    }
	name=zlmd5(arg);
	RETURN_STR(name);
}
PHP_FUNCTION(zlsafe_session_id)
{
	char *arg = NULL;
	zend_string *strg ,*name=NULL,*session_cookie,*strg3;
	zend_long number;
	
	zval *sessionid;
	size_t arg_len, len;

        if (zend_parse_parameters(ZEND_NUM_ARGS(), "|s", &arg, &arg_len) == FAILURE) {
                return;
        }
	
	strg3=strpprintf(0,"%.78s",PS(session_name));
	sessionid=zlreadcookie(strg3->val);
	
	if (arg) {
		if(sessionid){
			session_cookie=strpprintf(0,"%.78s",Z_STRVAL_P(sessionid)); 
			RETURN_STR(session_cookie);
		}
		else{
			name=zlsafemd5(arg,0);
			if (PS(id)) {
					zend_string_release(PS(id));
			}
			PS(id) = zend_string_copy(name);
			php_session_reset_id();
		}
    }
	
	RETVAL_STR_COPY(PS(id));
	
}

zend_string * zlsafe_encrypt(char *zl,char *key){
	zval val;
	zval func;
	zval param[5];
	zend_string *base64_zl,*outbuf;
	ZVAL_STRING(&func,"mcrypt_encrypt"); 
	ZVAL_STRING(&param[0], "rijndael-128");
	ZVAL_STRING(&param[1], key);
	ZVAL_STRING(&param[2], zl);
	ZVAL_STRING(&param[3], "cbc");
	ZVAL_STRING(&param[4], "9740056520123456");
	if (FAILURE ==call_user_function_ex(EG(function_table), NULL, &func, &val, 5, param, 1, NULL)){
		 php_error_docref(NULL TSRMLS_CC, E_WARNING, "not support mcrypt");
		return;
	 
	 }
	outbuf=strpprintf(0,"%.78s",Z_STRVAL_P(&val));
	base64_zl = php_base64_encode(outbuf->val,outbuf->len);	
	return base64_zl;

}
zend_string * zlsafe_decrypt(char *zl,char *key){
        zval val;
        zval func;
        zval param[5];
        zend_string *base64_zl,*outbuf;
        ZVAL_STRING(&func,"mcrypt_decrypt");
     
        outbuf=strpprintf(0,"%.78s",zl);
        base64_zl = php_base64_decode(outbuf->val,outbuf->len);
        ZVAL_STRING(&param[0], "rijndael-128");
        ZVAL_STRING(&param[1], key);
        ZVAL_STRING(&param[2], base64_zl->val);
        ZVAL_STRING(&param[3], "cbc");
        ZVAL_STRING(&param[4], "9740056520123456");
       
        if (FAILURE ==call_user_function_ex(EG(function_table), NULL, &func, &val, 5, param, 1, NULL)){
                 php_error_docref(NULL TSRMLS_CC, E_WARNING, "not support mcrypt");
                return;

         }
        base64_zl=strpprintf(0,"%.78s",Z_STRVAL_P(&val));
       
        return base64_zl;

}
PHP_FUNCTION(zlsafe_array_encrypt)
{
	
	zval *array, *entry;
        zend_string *string_key;
        zend_string *new_key;
        zend_ulong num_key;
	char *zl = NULL,*zlfun=NULL;
	size_t zl_len,zlfun_len;

        if (zend_parse_parameters(ZEND_NUM_ARGS(), "as|s", &array, &zl,&zl_len,&zlfun,&zlfun_len) == FAILURE) {
                return;
        }

        array_init_size(return_value, zend_hash_num_elements(Z_ARRVAL_P(array)));

        ZEND_HASH_FOREACH_KEY_VAL(Z_ARRVAL_P(array), num_key, string_key, entry) {
                if (!string_key) {
                        entry = zend_hash_index_update(Z_ARRVAL_P(return_value), num_key, entry);
                } else {
                        if (zlfun) {
				if(strcmp(zlfun,"encrypt")==0){
                                	new_key =zlsafe_encrypt(string_key->val,zl);
				}else if(strcmp(zlfun,"md5")==0){
                                	new_key =zlmd5(string_key->val);
				}else{
					new_key=string_key;
				}
                        } else {
                                new_key =zlsafe_encrypt(string_key->val,zl);
                        }
                        entry = zend_hash_update(Z_ARRVAL_P(return_value), new_key, entry);
                        zend_string_release(new_key);
                }

                zval_add_ref(entry);
        } ZEND_HASH_FOREACH_END();
}
PHP_FUNCTION(zlsafe_array_decrypt)
{

	zval *array, *entry;
        zend_string *string_key;
        zend_string *new_key;
        zend_ulong num_key;
        char *zl = NULL,*zlfun=NULL;
        size_t zl_len,zlfun_len;

        if (zend_parse_parameters(ZEND_NUM_ARGS(), "as|s", &array, &zl,&zl_len,&zlfun,&zlfun_len) == FAILURE) {
                return;
        }

        array_init_size(return_value, zend_hash_num_elements(Z_ARRVAL_P(array)));

        ZEND_HASH_FOREACH_KEY_VAL(Z_ARRVAL_P(array), num_key, string_key, entry) {
                if (!string_key) {
                        entry = zend_hash_index_update(Z_ARRVAL_P(return_value), num_key, entry);
                } else {
                        if (zlfun) {
                                if(strcmp(zlfun,"decrypt")==0){
                                        new_key =zlsafe_decrypt(string_key->val,zl);
                                }else{
                                        new_key=string_key;
                                }
                        } else {
                                new_key =zlsafe_decrypt(string_key->val,zl);
                        }
                        entry = zend_hash_update(Z_ARRVAL_P(return_value), new_key, entry);
                        zend_string_release(new_key);
                }

                zval_add_ref(entry);
        } ZEND_HASH_FOREACH_END();
}
PHP_FUNCTION(zlsafe_encrypt)
{
	char *zl = NULL,*zlkey;
	zend_string *name=NULL;
	size_t zl_len, zlkey_len;
	zval zlret;
    if (zend_parse_parameters(ZEND_NUM_ARGS(), "ss", &zl, &zl_len,&zlkey,&zlkey_len) == FAILURE) {
          return;
    }
	name=zlsafe_encrypt(zl,zlkey);

	RETURN_STR(name);
	
}
PHP_FUNCTION(zlsafe_decrypt)
{
	char *zl = NULL,*zlkey=NULL;
	zend_string *name=NULL;
	size_t zl_len, zlkey_len;
    if (zend_parse_parameters(ZEND_NUM_ARGS(), "ss", &zl, &zl_len,&zlkey,&zlkey_len) == FAILURE) {
          return;
    }
	name=zlsafe_decrypt(zl,zlkey);
	RETURN_STR(name);
}

/* }}} */
/* The previous line is meant for vim and emacs, so it can correctly fold and
   unfold functions in source code. See the corresponding marks just before
   function definition, where the functions purpose is also documented. Please
   follow this convention for the convenience of others editing your code.
*/


/* {{{ php_zlsafe_init_globals
 */
/* Uncomment this function if you have INI entries
static void php_zlsafe_init_globals(zend_zlsafe_globals *zlsafe_globals)
{
	zlsafe_globals->global_value = 0;
	zlsafe_globals->global_string = NULL;
}
*/
/* }}} */

/* {{{ PHP_MINIT_FUNCTION
 */
PHP_MINIT_FUNCTION(zlsafe)
{
	/* If you have INI entries, uncomment these lines
	REGISTER_INI_ENTRIES();
	*/
	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MSHUTDOWN_FUNCTION
 */
PHP_MSHUTDOWN_FUNCTION(zlsafe)
{
	/* uncomment this line if you have INI entries
	UNREGISTER_INI_ENTRIES();
	*/
	return SUCCESS;
}
/* }}} */

/* Remove if there's nothing to do at request start */
/* {{{ PHP_RINIT_FUNCTION
 */
PHP_RINIT_FUNCTION(zlsafe)
{
#if defined(COMPILE_DL_ZLSAFE) && defined(ZTS)
	ZEND_TSRMLS_CACHE_UPDATE();
#endif
	return SUCCESS;
}
/* }}} */

/* Remove if there's nothing to do at request end */
/* {{{ PHP_RSHUTDOWN_FUNCTION
 */
PHP_RSHUTDOWN_FUNCTION(zlsafe)
{
	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MINFO_FUNCTION
 */
PHP_MINFO_FUNCTION(zlsafe)
{
	php_info_print_table_start();
	php_info_print_table_header(2, "zlsafe support", "enabled");
	php_info_print_table_end();

	/* Remove comments if you have entries in php.ini
	DISPLAY_INI_ENTRIES();
	*/
}
/* }}} */
//  | Author:liang.zhang ÀÏÍçÍ¯ (QQ:974005652)                                                              |

/* {{{ zlsafe_functions[]
 *
 * Every user visible function must have an entry in zlsafe_functions[].
 */
const zend_function_entry zlsafe_functions[] = {
	PHP_FE(confirm_zlsafe_compiled,	NULL)		/* For testing, remove later. */
	PHP_FE(zlsafe_session_id,	NULL)		/* For testing, remove later. */
	PHP_FE(zlsafe_md5,	NULL)
	PHP_FE(zlsafe_md5_encrypt,	NULL)
	PHP_FE(zlsafe_md5_real,	NULL)
	PHP_FE(zlsafe_session_check,	NULL)
	PHP_FE(zlsafe_session_checkip,	NULL)
	PHP_FE(zlsafe_encrypt,	NULL)
	PHP_FE(zlsafe_decrypt,	NULL)
	PHP_FE(zlsafe_array_encrypt,	NULL)
	PHP_FE(zlsafe_array_decrypt,	NULL)
	PHP_FE_END	/* Must be the last line in zlsafe_functions[] */
};
/* }}} */
//  | Author:liang.zhang ÀÏÍçÍ¯ (QQ:974005652)                                                              |

/* {{{ zlsafe_module_entry
 */
zend_module_entry zlsafe_module_entry = {
	STANDARD_MODULE_HEADER,
	"zlsafe",
	zlsafe_functions,
	PHP_MINIT(zlsafe),
	PHP_MSHUTDOWN(zlsafe),
	PHP_RINIT(zlsafe),		/* Replace with NULL if there's nothing to do at request start */
	PHP_RSHUTDOWN(zlsafe),	/* Replace with NULL if there's nothing to do at request end */
	PHP_MINFO(zlsafe),
	PHP_ZLSAFE_VERSION,
	STANDARD_MODULE_PROPERTIES
};
/* }}} */

#ifdef COMPILE_DL_ZLSAFE
#ifdef ZTS
ZEND_TSRMLS_CACHE_DEFINE();
#endif
ZEND_GET_MODULE(zlsafe)
#endif
//  | Author:liang.zhang ÀÏÍçÍ¯ (QQ:974005652)                                                              |

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 fdm=marker
 * vim<600: noet sw=4 ts=4
 */
