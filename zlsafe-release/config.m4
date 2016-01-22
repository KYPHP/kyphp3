dnl $Id$
dnl config.m4 for extension zlsafe

dnl Comments in this file start with the string 'dnl'.
dnl Remove where necessary. This file will not work
dnl without editing.

dnl If your extension references something external, use with:

 PHP_ARG_WITH(zlsafe, for zlsafe support,
 Make sure that the comment is aligned:
 [  --with-zlsafe             Include zlsafe support])

dnl Otherwise use enable:

dnl PHP_ARG_ENABLE(zlsafe, whether to enable zlsafe support,
dnl Make sure that the comment is aligned:
dnl [  --enable-zlsafe           Enable zlsafe support])

if test "$PHP_ZLSAFE" != "no"; then
  dnl Write more examples of tests here...

  dnl # --with-zlsafe -> check with-path
  dnl SEARCH_PATH="/usr/local /usr"     # you might want to change this
  dnl SEARCH_FOR="/include/zlsafe.h"  # you most likely want to change this
  dnl if test -r $PHP_ZLSAFE/$SEARCH_FOR; then # path given as parameter
  dnl   ZLSAFE_DIR=$PHP_ZLSAFE
  dnl else # search default path list
  dnl   AC_MSG_CHECKING([for zlsafe files in default path])
  dnl   for i in $SEARCH_PATH ; do
  dnl     if test -r $i/$SEARCH_FOR; then
  dnl       ZLSAFE_DIR=$i
  dnl       AC_MSG_RESULT(found in $i)
  dnl     fi
  dnl   done
  dnl fi
  dnl
  dnl if test -z "$ZLSAFE_DIR"; then
  dnl   AC_MSG_RESULT([not found])
  dnl   AC_MSG_ERROR([Please reinstall the zlsafe distribution])
  dnl fi

  dnl # --with-zlsafe -> add include path
  dnl PHP_ADD_INCLUDE($ZLSAFE_DIR/include)

  dnl # --with-zlsafe -> check for lib and symbol presence
  dnl LIBNAME=zlsafe # you may want to change this
  dnl LIBSYMBOL=zlsafe # you most likely want to change this 

  dnl PHP_CHECK_LIBRARY($LIBNAME,$LIBSYMBOL,
  dnl [
  dnl   PHP_ADD_LIBRARY_WITH_PATH($LIBNAME, $ZLSAFE_DIR/$PHP_LIBDIR, ZLSAFE_SHARED_LIBADD)
  dnl   AC_DEFINE(HAVE_ZLSAFELIB,1,[ ])
  dnl ],[
  dnl   AC_MSG_ERROR([wrong zlsafe lib version or lib not found])
  dnl ],[
  dnl   -L$ZLSAFE_DIR/$PHP_LIBDIR -lm
  dnl ])
  dnl
  dnl PHP_SUBST(ZLSAFE_SHARED_LIBADD)

  PHP_NEW_EXTENSION(zlsafe, zlsafe.c, $ext_shared,, -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1)
fi
