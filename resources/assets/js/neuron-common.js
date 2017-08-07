/**
/**
 * Created by kgp on 2016/12/28.
 */

(function ($) {
    var reqs = {
        "json": true,
        'get': function (url, data, func) {
            return this._ajax('GET', url, data, func);
        },
        'post': function (url, data, func) {
            return this._ajax('POST', url, data, func);
        },
        'put': function (url, data, func) {
            return this._ajax('PUT', url, data, func);
        },
        'delete': function (url, data, func) {
            return this._ajax('DELETE', url, data, func);
        },
        'abort': function (request) {
            request.abort();
        },
        _ajax: function (type, url, data, func, async) {
            var me = this;
            if (!data || ($.isEmptyObject(data) && data.constructor != Array)) {
                data = undefined;
            }
            var _default = {
                type: type || 'POST',
                url: url || $.href(),
                // dataType: 'json',
                // contentType: "application/json;charset=UTF-8",
                data: data || void 0,
                /*traditional: true,*/
                context: document.body,
                async: void 0 === async
            };
            if (me.json) {
                _default = $.extend({}, _default, {
                    dataType: 'json',
                    contentType: "application/json;charset=UTF-8"
                });
                if (_default.data) {
                    _default.data = JSON.stringify(_default.data);
                }
            }
            $.console(type, url, data, func, async);
            return $.ajax(_default).done(function (r) {
                return me._callback(r, func);
            }).fail(function (r) {
                return me._callback(r, func);
            });
        },
        _callback: function (r, func) {

            $.console(r);
            function megi(msg) {
                selectTheme(function () {
                    $.alert(msg);
                }, 200);

                return false;
            }

            if (func != undefined && $.isFunction(func)) {
                func.call(this, r);
            }
            if (r.status == undefined || r.status == 200) {
                return false;
            }
            if (r.status != undefined) {
                switch (r.status) {
                    case 400:
                        megi(LANG_COMMON.ERROR_400_TEXT);
                        break;
                    case 401:
                        if (r.getResponseHeader("login") != undefined && r.getResponseHeader("login") == 1) {
                            $.goto(window.location.origin + "/account/login", {go: encodeURIComponent(window.location.href)});
                        } else {
                            megi(LANG_COMMON.ERROR_401_TEXT);
                        }
                        break;
                    case 403:
                        megi(LANG_COMMON.ERROR_403_TEXT);
                        break;

                    case 404:
                        megi(LANG_COMMON.ERROR_404_TEXT);
                        break;
                    case 405:
                        megi(LANG_COMMON.ERROR_405_TEXT);
                        break;
                    case 500:
                        megi(LANG_COMMON.ERROR_500_TEXT);
                        break;
                    default:
                        megi(LANG_COMMON.ERROR_DEFAULT_TEXT);
                        break;
                }
            }

        }
    };

    var basepath = function () {
        return (window.location.origin + window.location.pathname);
    };

    /**
     * 统一ajax请求函数
     */
    $.request = $.extend({}, reqs, {"json": false});
    $.requestJson = $.extend({}, reqs, {"json": true});
    /**
     * 控制台输出
     * 建议开发模式使用
     */
    $.console = function () {
        if (window.console != undefined) {
            var data = [];
            $.each(arguments, function (i, o) {
                data.push(o);
            });
            return window.console.log(data);
        }
    };

    /**
     * 获取URL
     * @param args
     * @returns {string}
     */
    $.href = function (args) {
        var _href = window.location.href.replace(/#/g, '');
        if (args != undefined) {
            if ($.isArray(args)) {
                _href += '/' + args.join('/');
            } else {
                _href += '/' + args;
            }
        }

        return _href;
    };

    /**
     * 获取拼接后的URL
     * @returns {string}
     */
    $.getHref = function () {
        if (arguments.length == 0) {
            return BASEPATH;
        }
        var args = [];
        $.each(arguments, function (i, o) {
            args.push(o);
        });

        return BASEPATH + "/" + (args.length > 0 ? args.join('/') : '');
    };

    /**
     * 获取url参数
     * @param name
     * @returns {*}
     */
    $.urlParam = function (name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results == null) {
            return null;
        }
        else {
            return results[1] || 0;
        }
    };

    /**
     * 替换url参数
     * @param url
     * @param params
     * @returns {*}
     */
    $.urlParamReplace = function (url, params) {
        if (url && typeof params == 'object') {
            url = url.replace(/#$/, '').replace(/\?$/, '').replace(/\&$/, '');
            $.each(params, function (k, v) {
                var pattern = '(\\?|&)' + k + '=([^&]*)';
                var rp_text = v ? k + '=' + v : '';
                if (url.match(pattern)) {
                    var reg = undefined;
                    if (rp_text) {
                        reg = new RegExp('&' + k + '=([^&]*)');
                        if (reg.test(url)) {
                            rp_text = '&' + rp_text;
                        } else {
                            rp_text = '?' + rp_text;
                        }
                    }
                    url = url.replace(eval('/(\\?|&)(' + k + '=)([^&]*)/gi'), rp_text);
                    if (url.indexOf('?') == -1 && url.indexOf('&') > -1) {
                        url = url.replace(/&/, '?');
                    }
                } else {
                    if (rp_text) {
                        if (/[\?]/.test(url)) {
                            url += '&' + rp_text;
                        } else {
                            url += '?' + rp_text;
                        }
                    }
                }
            });
            if (url.indexOf('?') < 0 && url.indexOf('&') > -1) {
                url = url.substr(0, url.indexOf('&')) + '?' + url.substr(url.indexOf('&') + 1);
            }
        }
        return url;
    };

    /**
     * 修改单个参数
     * @param url
     * @param ref
     * @param value
     * @returns {string}
     */
    $.changeURLPar = function (url, ref, value) {
        if (value == undefined || value == "") {
            return url;
        }
        var str = "";
        if (url.indexOf('?') != -1)
            str = url.substr(url.indexOf('?') + 1);
        else
            return url + "?" + ref + "=" + value;
        var returnurl = "";
        var setparam = "";
        var arr;
        var modify = "0";
        if (str.indexOf('&') != -1) {
            arr = str.split('&');
            for (i in arr) {
                if (arr[i].split('=')[0] == ref) {
                    setparam = value;
                    modify = "1";
                }
                else {
                    setparam = arr[i].split('=')[1];
                }
                returnurl = returnurl + arr[i].split('=')[0] + "=" + setparam + "&";
            }
            returnurl = returnurl.substr(0, returnurl.length - 1);
            if (modify == "0")
                if (returnurl == str)
                    returnurl = returnurl + "&" + ref + "=" + value;
        }
        else {
            if (str.indexOf('=') != -1) {
                arr = str.split('=');
                if (arr[0] == ref) {
                    setparam = value;
                    modify = "1";
                }
                else {
                    setparam = arr[1];
                }
                returnurl = arr[0] + "=" + setparam;
                if (modify == "0")
                    if (returnurl == str)
                        returnurl = returnurl + "&" + ref + "=" + value;
            }
            else
                returnurl = ref + "=" + value;
        }
        return url.substr(0, url.indexOf('?')) + "?" + returnurl;
    };

    /**
     * 替换当前url参数
     * @param params
     */
    $.hrefReplace = function (params) {
        return $.urlParamReplace(window.location.href, params);
    };

    /**
     * 跳转
     * @param parms
     */
    $.go = function (parms) {
        window.location.href = $.hrefReplace(parms);
    };

    /**
     * 跳转至指定页面
     * @param url
     * @param parms
     */
    $.goto = function (url, parms) {
        window.location.href = $.urlParamReplace(url || (basepath()), parms);
    };

    /**
     * 获取url参数
     * @param parm
     * @returns {{}}
     */
    $.getParm = function (parm) {
        return $.getUrlParm($.href(), parm);
    };
    $.getUrlParm = function (url, parm) {
        var ret = {};
        url = url || $.href();
        if (parm === undefined) {
            var search = url;
            if (/\?/.test(search)) {
                var str = search.substr(search.indexOf('?') + 1);
                var params = str.split("&");
                $.each(params, function (i, o) {
                    var param = o.split('=');
                    if (param.length > 0) {
                        ret[param[0]] = decodeURI($.trim(param[1] == undefined ? '' : param[1]));
                    }
                });
            }
        } else if (typeof parm == 'string') {
            var req = new RegExp($.format('(%0=)([^(&|#)]*)', parm), 'g');
            var results = req.exec(url);
            if (results != undefined && results.length == 3) {
                return $.trim(results[2]);
            }
            return undefined;
        } else if (typeof parm === 'object' && parm instanceof Array) {
            $.each(parm, function (i, k) {
                var req = new RegExp($.format('(%0=)([^(&|#)]*)', k), 'g');
                var results = req.exec(url);
                if (results != undefined && results.length == 3) {
                    ret[k] = $.trim(results[2]);
                }
            });
        }
        return ret;
    };
    /**
     * 格式化字符串
     * @param t 字符串
     * @param ... 替换参数
     * @returns {*}
     */
    $.format = function (t) {
        if (t) {
            for (var e = 0; !(-1 == t.indexOf("%" + e) || e > 10);) {
                var a = new RegExp("%" + e, "gi"), n = arguments[e + 1];
                t = t.replace(a, void 0 != n ? n : ""), e++
            }
            return t
        }
        return t
    };

    $.timerFormat = function (s, e) {

        function frmat(r) {
            if (parseInt(r, 10) < 10) {
                return '0' + parseInt(r, 10);
            }
            return parseInt(r, 10);
        }

        var ts = (s) - (e);//计算剩余的毫秒数
        var dd = parseInt(ts / 1000 / 60 / 60 / 24, 10);//计算剩余的天数
        var hh = parseInt(ts / 1000 / 60 / 60 % 24, 10);//计算剩余的小时数
        var mm = parseInt(ts / 1000 / 60 % 60, 10);//计算剩余的分钟数
        var ss = parseInt(ts / 1000 % 60, 10);//计算剩余的秒数
        if (ss < 0 || mm < 0) {
            return '';
        }
        dd = frmat(dd);
        hh = frmat(hh);
        mm = frmat(mm);
        ss = frmat(ss);

        if (dd > 0) {
            return dd + LANG_PRODUCT.JS.RULE.TEXT23 + hh + LANG_TEXTS.TEXT56 + mm + LANG_TEXTS.TEXT57 + ss + LANG_TEXTS.TEXT58;
        }
        if (hh > 0) {
            return hh + LANG_TEXTS.TEXT56 + mm + LANG_TEXTS.TEXT57 + ss + LANG_TEXTS.TEXT58;
        }

        return mm + LANG_TEXTS.TEXT57 + ss + LANG_TEXTS.TEXT58;

    };

    /**
     * 向前补零
     * @param num
     * @param n
     * @returns {*}
     */
    $.pad = function (num, n) {
        return Array(n > ('' + num).length ? (n - ('' + num).length + 1) : 0).join(0) + num;
    };

    var customSwal = function (message, func, btnTitle, showCancelButton) {
        swal({
            title: message || '',
            cancelButtonText: LANG.CANCEL_TEXT,
            showCancelButton: showCancelButton === true,
            confirmButtonColor: "#2196F3",
            confirmButtonText: btnTitle != undefined ? btnTitle : LANG.CONFIRM_TEXT,
            html: true
        }, function (value) {
            if (func && $.isFunction(func)) {
                func(value);
            }
        });
    };
    /**
     * alert
     * @param message
     * @param func
     * @param btnTitle
     */
    $.alert = function (message, func, btnTitle) {
        customSwal(message, func, btnTitle);
    };
    /**
     * confirm
     * @param message
     * @param func
     * @param btnTitle
     */
    $.confirm = function (message, func, btnTitle) {
        customSwal(message, func, btnTitle, true);
    };

    /**
     * 获取元素
     * @param element
     * @returns {*}
     */
    $.getElement = function (element) {
        if (!element) return undefined;

        if ($("#" + element).length > 0) {
            return $("#" + element);
        } else if ($(element).length > 0) {
            return $(element);
        } else if ($('[name=' + element + ']').length > 0) {
            return $('[name=' + element + ']');
        } else if ($('.' + element).length > 0) {
            return $('.' + element);
        }
        return undefined;
    };

    /**
     * 滚动条滚动至指定位置
     * @param top
     * @param speed
     */
    $.goTop = function (top, speed) {
        $('html,body').animate({scrollTop: top || 0}, speed || 500);

    };

    /**
     * 判断是否为Integer
     * @param num
     * @returns {boolean}
     */
    $.isInteger = function (num) {
        var f = num != undefined && /^\d+$/.test(num);
        if (f) {
            num = parseInt(num, 10);
            f = num >= -2147483648 && num <= 2147483647;
        }
        return f;
    };
    $.isMFloat = function (num) {
        var re = /^\d+(\.\d{1,2})?$/;
        return re.test(num);
    };

    /**
     * 判断是否为double
     * @param num
     * @returns {boolean}
     */
    $.isDouble = function (num) {
        return num != undefined && /^\d+(.[0-9])?$/.test(num);
    };

    /**
     * 格式化金额
     * @param $cell
     * @param num
     * @returns {*}
     */
    $.formatMoney = function ($cell, num) {
        var reg = /[\$,%]/g;
        if (num == undefined) {
            num = 2;
        }
        // toFixed小数点后两位
        var key = parseFloat(String($cell).replace(reg, '')).toFixed(num);
        return isNaN(key) ? 0.00 : key;
    };

    /**
     * 格式化金额 分转元
     * @param value
     * @returns {*}
     */
    $.formatMoneyPenny = function (value) {
        return $.formatMoney(MathEx.Division(value || 0, 100));
    };

    $.isNumeric = function (obj) {
        return !isNaN(parseFloat(obj)) && isFinite(obj);
    };


    $.number = function ($cell) {
        var re = /([0-9]+\.[0-9]{2})[0-9]*/;
        var value = parseFloat(String($cell).replace(re, "$1") || 0, 10);
        return isNaN(value) ? '' : value;
    };

    $.moneyNumber = function ($cell) {
        var re = /([0-9]+\.[0-9]{2})[0-9]*/;
        var value = parseFloat(String($cell).replace(re, "$1") || 0, 10);
        return isNaN(value) ? '' : (value);
    };

    var MEMBERAGEDEFAULT = 999;
    $.formatAgeLabel = function (minAge, maxAge, lb) {
        if (minAge == undefined || maxAge == undefined) {
            return '';
        }

        var txt = minAge == MEMBERAGEDEFAULT && maxAge == MEMBERAGEDEFAULT ? '' : '';
        if (!txt) {
            txt = minAge == MEMBERAGEDEFAULT && maxAge != MEMBERAGEDEFAULT ? maxAge + LANG_TEXTS.TEXT655 : '';
        }
        if (!txt) {
            txt = minAge != MEMBERAGEDEFAULT && maxAge == MEMBERAGEDEFAULT ? minAge + LANG_TEXTS.TEXT656 : '';
        }
        if (!txt && LANGUAGE != 'en') {
            txt = minAge != MEMBERAGEDEFAULT && maxAge != MEMBERAGEDEFAULT ? minAge + '-' + maxAge + LANG_TEXTS.TEXT657 : '';
        }
        if (LANGUAGE == 'en') {
            txt = minAge != MEMBERAGEDEFAULT && maxAge != MEMBERAGEDEFAULT ? "age " + minAge + '-' + maxAge : '';
        }

        if (txt) {
            return $.format(lb || '(%0)', txt);
        }
        return '';
    }


})(jQuery);

(function ($) {

    /**
     * 表单验证
     * @param options 属性JSON
     * @returns {jQuery}
     */
    $.fn.validation = function (options) {
        var _t = this;
        var _default = {
            ignore: 'input[type=hidden], .select2-search__field, :hidden:not(#summernote),.note-editable.panel-body',
            errorClass: 'validation-error-label',
            successClass: 'validation-valid-label',
            highlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },
            errorPlacement: function (error, element) {
                element.addClass('')

                if (element.attr('show-error')) {
                    $('.' + element.attr('show-error')).html('')
                    error.appendTo('.' + element.attr('show-error'));
                } else if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container')) {
                    if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                        error.appendTo(element.parent().parent().parent().parent());
                    }
                    else {
                        error.appendTo(element.parent().parent().parent().parent().parent());
                    }
                }
                else if (element.parent('font').hasClass('font_words')) {
                    error.appendTo(element.parent().parent());
                } else if (element.parent().hasClass('wrap_ref_content')) {
                    error.appendTo(element.parent().parent());
                }
                else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
                    error.appendTo(element.parent().parent().parent());
                }

                else if (element.parents('div.bootstrap-select').length > 0) {
                    error.appendTo(element.parent().parent().parent());
                }

                else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
                    error.appendTo(element.parent());
                }

                else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                    error.appendTo(element.parent().parent());
                }

                else if (element.parent().hasClass('uploader')) {
                    error.appendTo(element.parent().parent());
                }


                else if (element.parents('.input-group').length > 0) {
                    error.appendTo(element.parents('.input-group').parent());
                }
                else if (element.parents('.btn-group').length > 0) {
                    error.appendTo(element.parents('.btn-group').parent());
                }

                else {
                    error.insertAfter(element);
                }
            },
            success: function (label) {
                label.remove();
            },
            validClass: "validation-valid-label",
            rules: {
                password: {
                    minlength: 6
                },
                repeat_password: {
                    equalTo: "#password"
                },
                email: {
                    email: true
                },
                repeat_email: {
                    equalTo: "#email"
                },
                minimum_characters: {
                    minlength: 10
                },
                maximum_characters: {
                    maxlength: 10
                },
                minimum_number: {
                    min: 10
                },
                maximum_number: {
                    max: 10
                },
                number_range: {
                    range: [10, 20]
                },
                url: {
                    url: true
                },
                date: {
                    date: true
                },
                date_iso: {
                    dateISO: true
                },
                numbers: {
                    number: true
                },
                digits: {
                    digits: true
                },
                creditcard: {
                    creditcard: true
                },
                basic_checkbox: {
                    minlength: 2
                },
                styled_checkbox: {
                    minlength: 2
                },
                switchery_group: {
                    minlength: 2
                },
                switch_group: {
                    minlength: 2
                }
            },
            messages: {}
        };
        _default = $.extend({}, _default, options || {});
        var validation = _t.validate(_default);
        _t.data('validation', validation);
        return _t;
    };

    /**
     * 重置表单
     */
    $.fn.formReset = function () {
        var _t = this;
        _t.each(function () {
            if ($(this).data('validation')) {
                $(this).data('validation').resetForm();
            }
        });
        return _t;

    };

    /**
     * 重置表单
     */
    $.fn.restForms = function () {
        var _t = this;
        _t[0].reset();
        $('select.select2', _t).trigger('change.select2');
        $('.bootstrap-select', _t).selectpicker('refresh');
        return _t;
    };

    /**
     * 获取表单JSON对象
     */
    $.fn.serializeObject = function () {
        var e = {}, r = this.serializeArray();
        return $.each(r, function () {
            e[this.name] ? (e[this.name].push || (e[this.name] = [e[this.name]]), e[this.name].push(this.value || "")) : e[this.name] = this.value || ""
        }), e
    };

    /**
     * 弹框大小
     * @type {{sm: string, s: string, d: string, l: string, f: string}}
     * @private
     */
    var _windSize = {
        sm: 'modal-xs',
        s: 'modal-sm',
        d: '',
        l: 'modal-lg',
        f: 'modal-full'
    };

    /**
     * 基础弹框
     * @param size 大小 sm|s|m|l|f
     * @returns {*|HTMLElement}
     */
    $.fn.pop = function (size) {
        var modal_size = (_windSize[size] != undefined ? _windSize[size] : '');
        var _t = $(this);
        var modal = '<div class="modal fade">';
        modal += '<div class="modal-dialog ' + modal_size + '">';
        modal += '<div class="modal-content">';
        modal += '</div>';
        modal += '</div>';
        modal += '</div>';
        var _modal = $(modal), content = $('.modal-content', _modal);
        _t.appendTo(content);
        // $('<div class="overlay"><i class="icon-spinner2 spinner position-left"></i></div>').appendTo(content);
        return _modal;
    };

    /**
     * 业务弹框
     * @param title 提示信息
     * @param footer 底部位置
     * @param func 回调函数
     * @param size sm|s|d|l|f
     * @returns {string}
     */
    $.fn.windPop = function (title, footer, func, size, hideHeader) {
        var wind = '<div>';
        wind += '<div class="modal-header ' + (hideHeader == undefined ? '' : 'hidden') + '"><button type="button" class="close" data-dismiss="modal">×</button>';
        if (title && typeof title == 'string') wind += '<h6 class="modal-title text-bold">' + (title || '') + '</h6>';
        wind += '</div>';

        wind += '<div class="modal-body"></div>';

        if (footer != undefined && typeof footer == 'string') {
            if (!/modal-footer/.test(footer)) {
                footer = '<div class="modal-footer">' + footer + '</div>';
            }
            footer = $(footer);
        }
        wind += '</div></div>';

        var _t = this;
        wind = $(wind);
        if (title && typeof title == 'object' && title.length > 0) {
            $('.modal-header', wind).append(title);
        }

        if (footer != undefined && footer.length > 0) {
            wind.append(footer);

        }

        _t.appendTo($('.modal-body', wind));

        wind = wind.pop(size).modal({
            backdrop: 'static',
            show: true,
            keyboard: false
        }).on('shown.bs.modal', function (e) {
            $('.bootstrap-select', wind).selectpicker().on('hidden.bs.select', function (e) {
                $(e.currentTarget).trigger('keyup');
            });
        });
        $('.modal-content', wind).loading();
        $('.bootstrap-select', wind).selectpicker().on('hidden.bs.select', function (e) {
            // do something...
            $(e.currentTarget).trigger('keyup');
        });
        wind.on('hidden.bs.modal', function () {
            wind.remove();
            $('body').css('overflow', 'auto');
            if (func != undefined && $.isFunction(func)) {
                func.call(this, false);
            }
        }).on('shown.bs.modal', function () {
            if (func != undefined && $.isFunction(func)) {
                func.call(this, true);
            }
            $('body').css('overflow', 'hidden');
        });
        window.setTimeout(function () {
            $('.modal-content', wind).loading(1);
        }, 100);
        return wind;
    };

    /**
     * 关闭弹框
     */
    $.fn.closeWind = function () {
        $('.close', this).trigger('click');
        return this;
    };

    /**
     * 加载动画
     * @param f 结束动画
     */
    $.fn.loading = function (f) {
        if (f != undefined) {
            this.unblock();
        } else {
            this.block({
                message: '<i class="icon-spinner spinner"></i>',
                overlayCSS: {
                    backgroundColor: '#fff',
                    opacity: 0.8,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'none'
                }
            });
        }
        return this;
    };

    /**
     * 滚动到元素上的位置
     * @param speed 执行时间
     * @returns {jQuery}
     */
    $.fn.goTop = function (speed) {
        if (this && this.length > 0) {
            var _offset = this.offset(), _top = _offset.top;
            $.goTop(_top, speed);
        }
        return this;

    };

    /**
     * jquery click事件
     * @param func
     * @param time
     */
    $.fn.cvent = function (func, time) {
        var _t = this, TIMER = undefined;
        _t.on('click', function (e, args) {
            e.preventDefault() || e.stopPropagation();
            if (_t.hasClass('isCvent'))return false;
            _t.addClass('isCvent');
            func.call(_t, e, args);
            clearTimeout(TIMER);
            TIMER = setTimeout(function () {
                _t.removeClass('isCvent');
            }, time || 700)
        });
        return _t;
    };

    /**
     * 改变列表页查询条数
     * @param current
     */
    $.fn.changePage = function (args) {
        var _t = this, _option = args;
        if (!_option.method) {
            _option.method = 'get';
        }
        var select = $('.neuron-page-change', _t);
        $($.format('option[value=%0]', (_option.pageSize || 20)), select).prop('selected', true);
        select.selectpicker();
        select.change(function () {
            var option = $('option:selected', this), num = option.val();
            if ($.isInteger(num)) {
                _t.loading();
                var url = window.location.href;
                if (_option.url != undefined && typeof _option.url == 'string') {
                    var parms = $.getParm() || {};
                    var url = _option.url;
                    if (/\?/.test(_option.url)) {
                        parms = $.extend($.getUrlParm(_option.url) || {}, parms);
                        url = $.urlParamReplace(_option.url.substr(0, _option.url.indexOf('?')));
                    }
                    url = $.urlParamReplace(url, parms);
                }
                url = $.urlParamReplace(url, {page: undefined, limit: num});
                var req = _option.method == 'get' ? $.request : $.requestJson;
                var parms = _option.method == 'get' ? {} : $.getUrlParm(url);
                req[_option.method](url, parms, function (data) {
                    History.pushState({
                        url: url,
                        rand: Math.random(),
                        result: data
                    }, window.document.title, $.hrefReplace({page: undefined, limit: num}));
                });

            }
            return true;
        });
        return select;
    };

    $.fn.initPageLimit = function (option) {

        if (!option.method) {
            option.method = 'get';
        }
        var _t = this;
        var limits = [10, 20, 50];
        if ($('.neuron-smart-page-content').length == 0) {
            return _t;

        }
        if ($('.neuron-smart-page-content .smart-page-limiter').length > 0) {
            var _limit = $.getParm('limit') || option.pageSize;
            if (_limit) {
                $('option[value="' + _limit + '"]', '.neuron-smart-page-content .smart-page-limiter').prop('selected', true);
                return true;
            }
        }

        var div = $('<div class="pull-right smart-page-limiter" />').appendTo('.neuron-smart-page-content');

        var select = $('<select class="ml-15 pageSlect" />').appendTo(div);
        select.select2({
            minimumResultsForSearch: -1,
            width: 'auto'
        });
        $.each(limits, function (i, v) {
            $('<option value="' + v + '">' + LANG_COMMON.TEXT26 + '' + v + '</option>').appendTo(select);
        });

        var limit = $.getParm('limit') || option.pageSize;
        select.change(function () {
            limit = this.value;
            _t.goTop(300).loading();
            var url = window.location.href;
            if (option.url != undefined && typeof option.url == 'string') {
                var parms = $.getParm() || {};
                var url = option.url;
                if (/\?/.test(option.url)) {
                    parms = $.extend($.getUrlParm(option.url) || {}, parms);
                    url = $.urlParamReplace(option.url.substr(0, option.url.indexOf('?')));
                }
                url = $.urlParamReplace(url, parms);
            }
            var limitData = {page: undefined, limit: limit};
            url = $.urlParamReplace(url, limitData);
            var req = option.method == 'get' ? $.request : $.requestJson;
            var parms = option.method == 'get' ? {} : $.getUrlParm(url);
            req[option.method](url, parms, function (data) {
                History.pushState({
                    url: url,
                    rand: Math.random(),
                    result: data
                }, window.document.title, $.hrefReplace(limitData));
            });
        });

        if (limit) {
            $('option[value="' + limit + '"]', select).prop('selected', true);
        }

        return div;

    };
    /**
     * 初始化分页信
     * @param options
     * @param page
     */
    $.fn.initPageContent = function (options, page) {

        var _t = this;
        var pageContent = $('.neuron-page-content', _t).html('');


        var _options = {
            url: undefined,
            totalCount: 0,
            pageSize: 20,
            method: 'get',
            beforeFunc: undefined,
            maxVisible: undefined,
            totalLabelTmpl: LANG_PRODUCT.JS.DTA.TEXT11+'${totalCount}'+LANG_PRODUCT.JS.DTA.TEXT12
        };

        var option = $.extend({}, _options, options || {});
        _t.initPageLimit(option);
        var pageIndex = $.getParm('page');
        if (!pageIndex || !$.isInteger(pageIndex)) {
            pageIndex = 1;
        }
        var isCustomVisible = !option.maxVisible;
        var maxVisible = 6;

        if ($.isInteger(option.totalCount) && option.totalCount > 0) {
            var flag = false;
            var pages = $('.bootpag-default', pageContent);

            var pageInfo = $('.page-info', pageContent);
            if (pages.length == 0) {
                flag = true;
                pageInfo = $('<div class="col-lg-4 text-left page-info p-5 pl-10" />').appendTo(pageContent);
                pages = $('<div class="col-lg-8 text-right bootpag-default" />').appendTo(pageContent);

            }


            var pageCount = Math.ceil(MathEx.Division(option.totalCount, option.pageSize));
            if (!option.pageSize || !$.isInteger(option.pageSize)) {
                option.pageSize = 20;
            }

            if (pageIndex > pageCount) {
                pageIndex = pageCount;
            }
            if (!option.maxVisible || !$.isInteger(option.maxVisible)) {
                maxVisible = 6;
            } else {
                maxVisible = option.maxVisible;
            }


            if (maxVisible == 6 && pageIndex > 4) {
                maxVisible = 10
            }

            function _initPageInfo(num) {
                var str = LANG_COMMON.PAGE_INFO_TEXT;
                var startIndex = ((num - 1) * option.pageSize);
                var endIndex = num < pageCount ? (startIndex + option.pageSize) : option.totalCount;
                var pageInfoFormat = $.format(str, startIndex + 1, endIndex, option.totalCount);
                pageInfo.html(pageInfoFormat);
                var smartPageContent = $('.neuron-smart-page-content');
                if (smartPageContent.length > 0) {

                    var smartContent = {
                        pageIndex: pageIndex,
                        pageCount: pageCount,
                        totalCount: option.totalCount
                    };
                    $('.smart_total_page', smartPageContent).html($.tmpl(option.totalLabelTmpl, smartContent));
                    $('.smart_page_index', smartPageContent).html(pageIndex);
                    $('.smart_page_count', smartPageContent).html('/' + pageCount);

                }

            }

            _initPageInfo(pageIndex);

            if (pageCount == 1) {
                pages.html('');
                return true;
            }
            var _default = {
                total: pageCount,
                leaps: false,
                next: '>>',
                prev: '<<',
                wrapClass: 'pagination pagination-rounded',
                maxVisible: maxVisible
            };

            var bootpag = pages.bootpag($.extend({}, _default, {
                page: pageIndex
            }));
            if (flag) {
                //var
                var goHtm = '<div id="" class="page-go-content">';
                goHtm += '<div class="input-group">';
                goHtm += '<input type="text" class="form-control input-xs" placeholder="">';
                goHtm += '<span class="input-group-btn">';
                goHtm += '<button class="btn btn-default btn-xs" type="button">Go</button>';
                goHtm += '</span></div>';
                goHtm += '</div>';
                var goContent = $(goHtm).appendTo(pages);
                var input = $('input', goContent);
                var button = $('button', goContent);
                $('input', goContent).bind('keyup change', function (e) {
                    if (e.keyCode == 13) {
                        button.trigger('click');
                        return false;
                    }
                    if (input.val() && parseInt($.number(input.val()) || 0, 10) > 0) {
                        var v = parseInt($.number(input.val()) || 0, 10);
                        if (v > pageCount) {
                            v = pageCount;
                        }
                        input.val(v);
                        return true;
                    }
                    input.val('');
                }).blur(function () {
                    if (!input.val() || parseInt($.number(input.val()) || 0, 10) <= 0) {
                        input.val('');
                    }
                });
                button.cvent(function (e) {
                    if ($.trim(input.val()).length == 0) {
                        input.focus();
                        return false;
                    }
                    bootpag.trigger('page', input.val());
                });

                bootpag.on("page", function (event, num) {
                    _t.goTop(300).loading();
                    var url = window.location.href;
                    if (option.url != undefined && typeof option.url == 'string') {
                        var parms = $.getParm() || {};
                        var url = option.url;
                        if (/\?/.test(option.url)) {
                            parms = $.extend($.getUrlParm(option.url) || {}, parms);
                            url = $.urlParamReplace(option.url.substr(0, option.url.indexOf('?')));
                        }
                        url = $.urlParamReplace(url, parms);
                    }
                    url = $.urlParamReplace(url, {page: num});

                    var limit = $.getUrlParm(url, 'limit');
                    if (limit == undefined) {
                        url = $.urlParamReplace(url, {limit: option.pageSize});
                    }
                    var req = option.method == 'get' ? $.request : $.requestJson;
                    var parms = option.method == 'get' ? {} : $.getUrlParm(url);
                    req[option.method](url, parms, function (data) {
                        History.pushState({
                            url: url,
                            rand: Math.random(),
                            result: data
                        }, window.document.title, $.hrefReplace({page: num}));
                    });

                    if (option.beforeFunc && $.isFunction(option.beforeFunc)) {
                        option.beforeFunc.call(event, num);
                    }
                });


                if ($('.neuron-smart-page-content').length > 0) {
                    //$('.neuron-smart-page-content').html('');
                    $('.smart-pages', '.neuron-smart-page-content').remove();

                    var morEventHtm = '<div class="pull-right smart-pages mr-10"><span class="pr-10 smart_total_page">' + option.totalLabelTmpl + '</span>';
                    morEventHtm += '<a class="ub-btn ub-btn-default neuron-m-page-prev">' + LANG_COMMON.TEXT24 + '</a>';
                    morEventHtm += '<span class="ml-15 text-blue-600 smart_page_index">${pageIndex}</span><span class="mr-15 smart_page_count">/${pageCount}</span>';
                    morEventHtm += '<a class="ub-btn ub-btn-default neuron-m-page-next">' + LANG_COMMON.TEXT25 + '</a></div>';

                    var smartContent = $.tmpl(morEventHtm, {
                        pageIndex: pageIndex,
                        pageCount: pageCount,
                        totalCount: option.totalCount
                    }).appendTo('.neuron-smart-page-content');
                    $('a', smartContent).each(function () {
                        var _a = $(this), isNext = _a.hasClass('neuron-m-page-next');
                        _a.cvent(function () {
                            if (isNext) {
                                $('.pagination li.next a', bootpag).trigger('click');
                            } else {
                                $('.pagination li.prev a', bootpag).trigger('click');
                            }

                        })
                    });
                }


            }


        } else {
            if ($('.neuron-smart-page-content').length > 0) {
                $('.smart-pages', '.neuron-smart-page-content').remove();
                $.tmpl('<div class="pull-right smart-pages" ><span class="pr-10">' + option.totalLabelTmpl + '</span></div>', {
                    totalCount: option.totalCount
                }).appendTo('.neuron-smart-page-content');
            }
            pageContent.html('');
        }

        return _t;
    };

    $.fn.initThSort = function (options, className) {
        var _t = this;
        var _options = {
            url: undefined,
            totalCount: 0,
            pageSize: 20,
            method: 'get',
            beforeFunc: undefined,
            afterFunc: undefined,
            maxVisible: undefined,
            reloadPage: false
        };
        var option = $.extend({}, _options, options || {});
        var urlSort = $.getParm('sort');
        $('[data-sort]', _t).each(function () {
            var _this = $(this), sort = _this.data('sort'), ascSort = sort.replace(',desc', '');
            _this.css({'cursor': 'pointer'});
            var i = $('i', _this);
            if (urlSort) {
                var _sort = urlSort.replace(',desc', ''), isDesc = urlSort.indexOf(',desc') > -1;
                if (_sort == ascSort) {
                    i.removeClass('icon-menu-open');
                    i.addClass(!isDesc ? 'icon-arrow-up5' : 'icon-arrow-down5');
                    sort = isDesc ? ascSort : (ascSort + ',desc');
                    _this.addClass(className)
                }
            }
            var oriIsDesc = sort.indexOf(',desc') > -1;
            _this.cvent(function () {
                if (i.hasClass('icon-menu-open')) {
                    i.removeClass('icon-menu-open');
                }
                var cls = oriIsDesc ? 'icon-arrow-down5' : 'icon-arrow-up5';
                if (i.hasClass('icon-arrow-up5')) {
                    i.removeClass('icon-arrow-up5');
                    cls = 'icon-arrow-down5';
                } else if (i.hasClass('icon-arrow-down5')) {
                    i.removeClass('icon-arrow-down5');
                    cls = 'icon-arrow-up5';
                }

                i.addClass(cls);

                var url = window.location.href;
                if (option.url != undefined && typeof option.url == 'string') {
                    var parms = $.getParm() || {};
                    var url = option.url;
                    if (/\?/.test(option.url)) {
                        parms = $.extend($.getUrlParm(option.url) || {}, parms);
                        url = $.urlParamReplace(option.url.substr(0, option.url.indexOf('?')));
                    }
                    url = $.urlParamReplace(url, parms);
                }
                url = $.urlParamReplace(url, {sort: sort});

                var limit = $.getUrlParm(url, 'limit');
                if (limit == undefined) {
                    url = $.urlParamReplace(url, {limit: option.pageSize});
                }
                var req = option.method == 'get' ? $.request : $.requestJson;
                var parms = option.method == 'get' ? {} : $.getUrlParm(url);
                req[option.method](url, parms, function (data) {
                    //console.log(url)
                    History.pushState({
                        rand: Math.random(),
                        url: url,
                        result: data
                    }, window.document.title, $.hrefReplace({sort: sort}));
                });

            });

        });

        return _t;
    };

    /**
     * 列表页
     * @param totalCount 总数
     * @param pageSize 每页条数
     * @param maxVisible 默认显示页数
     * @param fulimitnc
     */
    $.fn.pages = function (options) {

        var _t = this;
        var dataContent = $('.neuron-data-content', _t);
        var pageContent = $('.neuron-page-content', _t).html('');
        var changeContent = $('.neuron-page-change', _t);
        var _options = {
            url: undefined,
            totalCount: 0,
            pageSize: 20,
            method: 'get',
            beforeFunc: undefined,
            afterFunc: undefined,
            maxVisible: undefined,
            reloadPage: false
        };


        var option = $.extend({}, _options, options || {});
        _t.initThSort(option);
        var pageIndex = $.getParm('page');
        if (!pageIndex || !$.isInteger(pageIndex)) {
            pageIndex = 1;
        }

        if (!option.reloadPage) {
            History.Adapter.bind(window, 'statechange', function (event) {
                var state = History.getState();
                var result = LANG_COMMON.ERROR_TEXT1, data = state.data;
                if (data != undefined && data.result != undefined) {
                    result = data.result;
                }
                var url = data.url;
                option.totalCount = 0;
                //History.log('statechange:', data, State.title, State.url);
                if (result && typeof result == 'string') {
                    dataContent.html(result);
                } else {
                    var resultData = result.data;
                    if (resultData != undefined && resultData.total > 0) {
                        option.totalCount = resultData.total;
                        option.pageSize = resultData.pageSize;

                    }

                }

                if (option.afterFunc && $.isFunction(option.afterFunc)) {
                    option.afterFunc.call(_t, result);
                }

                _t.data('url', url).loading(1).initPageContent(option);
            });

        }
        _t.changePage(option);
        $('.neuron-sort-change', _t).each(function () {
            $('a[sort]', this).each(function () {
                var a = $(this), sort = encodeURI(a.attr('sort'));
                a.removeAttr('sort');
                if (sort == undefined) {
                    return true;
                }
                a.cvent(function () {
                    _t.loading();
                    var url = window.location.href;
                    if (option.url != undefined && typeof option.url == 'string') {
                        var parms = $.getParm() || {};
                        var url = option.url;
                        if (/\?/.test(option.url)) {
                            parms = $.extend($.getUrlParm(option.url) || {}, parms);
                            url = $.urlParamReplace(option.url.substr(0, option.url.indexOf('?')));
                        }
                        url = $.urlParamReplace(url, parms);
                    }
                    url = $.urlParamReplace(url, {sort: sort});
                    var req = option.method == 'get' ? $.request : $.requestJson;
                    var parms = option.method == 'get' ? {} : $.getUrlParm(url);
                    req[option.method](url, parms, function (data) {
                        // Bind to State Change
                        History.pushState({
                            rand: Math.random(),
                            url: url,
                            result: data
                        }, window.document.title, $.hrefReplace({sort: sort}));
                    });
                });
            });
        });
        _t.bind('list.reload', function () {
            var url = $(this).data('url');
            if (!url) {
                return true;
            }


            var req = option.method == 'get' ? $.request : $.requestJson;
            var parms = option.method == 'get' ? {} : $.getUrlParm(url);
            req[option.method](url, parms, function (data) {
                // Bind to State Change
                History.pushState({
                    rand: Math.random(),
                    url: url,
                    result: data
                }, window.document.title, window.location.href);
            });
        });
        var url = window.location.href;
        if (option.url != undefined && typeof option.url == 'string') {
            var parms = $.getParm() || {};
            var url = option.url;
            if (/\?/.test(option.url)) {
                parms = $.extend($.getUrlParm(option.url) || {}, parms);
                url = $.urlParamReplace(option.url.substr(0, option.url.indexOf('?')));
            }
            url = $.urlParamReplace(url, parms);
        }
        url = $.urlParamReplace(url, {page: pageIndex});

        var limit = $.getUrlParm(url, 'limit');
        if (limit == undefined) {
            url = $.urlParamReplace(url, {limit: option.pageSize});
        }
        var req = option.method == 'get' ? $.request : $.requestJson;
        var parms = option.method == 'get' ? {} : $.getUrlParm(url);
        req[option.method](url, parms, function (data) {
            //console.log(url)
            History.pushState({
                rand: Math.random(),
                url: url,
                result: data
            }, window.document.title, window.location.href);
        })
        //pageContent.remove();
        return _t;
    };

    /**
     * 获取标签名称
     * @returns {*}
     */
    $.fn.getTagName = function () {
        var _t = this;
        if (_t.length > 0) {
            var tagName = _t[0].tagName;
            if (tagName) {
                return tagName.toLowerCase();
            }
        }
        return '';
    };

    ///TODO::单个对象赋值
    $.fn.setValue = function (document, value) {

    };

    /**
     * 批量赋值
     * @param values {name:values}
     */
    $.fn.setValues = function (values) {
        var _t = this;
        if (values && !$.isEmptyObject(values)) {
            $.each(values, function (key, value) {
                if (value == undefined || value == null) {
                    return true;
                }
                if (typeof value == 'number') {
                    value = value + '';
                }
                if (value && value.length == 0) {
                    return true;
                } else {
                    if ($.isArray(value) && value.length == 1) {
                        value = $.trim(value[0]);
                    }
                }

                if ($.isArray(value) && value.length == 0) {
                    return true;
                } else if (value.length == 0) {
                    return true;
                }

                var element = $($.format('[name=%0]', key), _t);
                if (element.length == 0) return true;
                var document = element[0], tagName = element.getTagName(), type = document.type;
                switch (tagName) {
                    case 'input':
                        if (type == 'checkbox') {
                            if ($.isArray(value)) {
                                $.each(value, function (i, v) {
                                    $($.format('[name="%0"]', key) + '[value="' + v + '"]', _t).prop('checked', true);
                                });
                            } else if (value) {
                                $($.format('[name="%0"]', key) + '[value="' + value + '"]', _t).prop('checked', true);
                            }
                        } else if (type == 'radio') {

                            $($.format('[name=%0][value=%1]', element.attr('name'), value), _t).prop('checked', true);
                        } else {
                            element.val(value);
                        }
                        break;
                    case 'select':
                        $('option[vlaue="' + value + '"]', element).prop('select', true);
                        element.val(value).trigger('change');
                        if (element.hasClass('bootstrap-select')) {
                            element.val(value).selectpicker('render');
                        }
                        break;
                    case 'textarea':
                        element.val(value);
                        break;
                }
            });
        }
        return _t;
    };
    $.fn.setValuesBySelect2 = function (values) {
        var _t = this;
        if (values && !$.isEmptyObject(values)) {
            $.each(values, function (key, value) {
                if (value == undefined || value == null) {
                    return true;
                }
                if (typeof value == 'number') {
                    value = value + '';
                }
                if (value && value.length == 0) {
                    return true;
                } else {
                    if ($.isArray(value) && value.length == 1) {
                        value = $.trim(value[0]);
                    }
                }

                if ($.isArray(value) && value.length == 0) {
                    return true;
                } else if (value.length == 0) {
                    return true;
                }

                var element = $($.format('[name=%0]', key));
                if (element.length == 0) return true;
                var document = element[0], tagName = element.getTagName(), type = document.type;
                switch (tagName) {
                    case 'input':
                        if (type == 'checkbox') {
                            if ($.isArray(value)) {
                                $.each(value, function (i, v) {
                                    $('[value=' + v + ']', element).prop('checked', true);
                                });
                            }
                        } else if (type == 'radio') {

                            $($.format('[name=%0][value=%1]', element.attr('name'), value)).prop('checked', true);
                        } else {
                            element.val(value);
                        }
                        break;
                    case 'select':
                        $('option[vlaue="' + value + '"]', element).prop('select', true);
                        element.val(value);
                        break;
                    case 'textarea':
                        element.val(value);
                        break;
                }
            });
        }
        return _t;
    };

    /**
     * 关联查询
     * @param request
     * @param func
     * @param tmpleate
     * @param display
     * @param source
     * @param beforeFunc
     * @param otherOption
     */
    $.fn.suggestion = function (request, func, tmpleate, display, source, beforeFunc, otherOption) {

        var _t = this;
        var substringMatcher = function (strs) {
            return function findMatches(q, cb) {
                var matches = [];
                var regExp = new RegExp(q, 'i');
                $.each(strs, function (i, str) {
                    if (regExp.test(str)) {
                        matches.push({value: str});
                    }
                });

                cb(matches);
            };
        };


        var TIMER = undefined;

        _t.data('url', request).data('afterFunc', beforeFunc);

        _t.typeahead(
            {
                hint: false,
                highlight: false,
                minLength: 1,
            }, {
                limit: 20,
                delay: 500,//延迟时间
                source: source || function (query, processSync, processAsync) {
                    var parameter = {query: query};
                    clearTimeout(TIMER);
                    var uri = _t.data('url');
                    TIMER = setTimeout(function () {
                        $.request.get(uri, parameter, function (data) {
                            var result = [];
                            if (data && data.code === 200 && data.data && $.isArray(data.data)) {
                                result = data.data;
                            }
                            return processAsync(result);
                        });
                    }, 300);
                }, highlighter: function (limit) {
                    return limit;
                }, updater: function (limit) {//选中
                    //console.log("'" + item + "' selected.");
                    $(':submit').focus();
                    return limit;
                }, display: display || ['value', 'name'],
                templates: {
                    pending: '<div class="text-center" style="padding: 5px 0"><i class="icon-spinner2 spinner position-left"></i></div>',
                    empty: function () {
                        if (otherOption == undefined) {
                            return ['<div class="col-lg-12 text-danger">' + LANG_COMMON.ERROR_TEXT2 + '</div>'].join('\n')
                        }
                        return otherOption.emptyTemplate || ['<div class="col-lg-12 text-danger">' + LANG_COMMON.ERROR_TEXT2 + '</div>'].join('\n')
                    },
                    suggestion: Handlebars.compile(tmpleate || '<div><strong>{{#if value}}{{value}}{{/if}}</strong></div>')
                }
            }
        ).bind('typeahead:select', function (ev, data) {
            // $.console('typeahead:select', data);
            _t.data('data', data);
            // 保存当前的文本值
            _t.data('data-current-text', _t.val());
            _t.blur(function (event) {
                if (_t.val() != _t.data('data-current-text')) {
                    // 关键字改变 会置空隐藏域的值
                    if (otherOption && !$.isEmptyObject(otherOption.refDocument)) {
                        $.each(otherOption.refDocument, function (key, value) {
                            var element = $($.format('[name=%0]', value));

                            if (element.length == 0) {
                                return true;
                            }
                            element.val("");
                        });
                        _t.val("")
                    }
                    // $.console(_t);

                }
            });

            if (func && $.isFunction(func)) {
                func.call($(this), data);
            }

        }).bind('typeahead:active', function (ev, data) {
            // $.console('typeahead:active');
            if (beforeFunc && $.isFunction(beforeFunc)) {
                beforeFunc.call($(this));
            }
        }).bind('typeahead:change', function (ev, data) {
            // $.console('typeahead:change');
            if (otherOption && otherOption.change && $.isFunction(otherOption.change)) {
                otherOption.change.call($(this), data);
            }
        }).bind('typeahead:open', function (ev, data) {
            // $.console('typeahead:open');
            if (otherOption && otherOption.open && $.isFunction(otherOption.open)) {
                otherOption.open.call($(this), data);
            }
        }).bind('typeahead:close', function (ev, data) {
            // $.console('typeahead:close');
            // 如果 隐藏域的值为空 则搜索框制空
            var thisSelector = $(this);
            if (otherOption && !$.isEmptyObject(otherOption.refDocument)) {
                $.each(otherOption.refDocument, function (key, value) {
                    var element = $($.format('[name=%0]', value));
                    // $.console(element);
                    if (element.length == 0) {
                        return true;
                    }
                    if ($.trim(element.val()) == "") {
                        thisSelector.val("");
                        thisSelector.valid();
                        return false;
                    }
                    //
                });
            }
            if (otherOption && otherOption.close && $.isFunction(otherOption.close)) {
                otherOption.close.call($(this), data);
            }
        }).bind('typeahead:render', function (ev, data) {
            // $.console('typeahead:render');
            if (otherOption && otherOption.render && $.isFunction(otherOption.render)) {
                otherOption.render.call($(this), data);
            }
        }).bind('typeahead:autocomplete', function (ev, data) {
            // $.console('typeahead:autocomplete');
            if (otherOption && otherOption.autocomplete && $.isFunction(otherOption.autocomplete)) {
                otherOption.autocomplete.call($(this), data);
            }
        }).bind('typeahead:cursorchange', function (ev, data) {
            // $.console('typeahead:cursorchange');
            if (otherOption && otherOption.cursorchange && $.isFunction(otherOption.cursorchange)) {
                otherOption.cursorchange.call($(this), data);
            }
        }).bind('typeahead:asyncrequest', function (ev, data) {
            // $.console('typeahead:asyncrequest');
            if (otherOption && otherOption.asyncrequest && $.isFunction(otherOption.asyncrequest)) {
                otherOption.asyncrequest.call($(this), data);
            }
        }).bind('typeahead:asyncreceive', function (ev, data) {
            // $.console('typeahead:asyncreceive');
            // 关键字改变 会置空隐藏域的值
            // if (otherOption && !$.isEmptyObject(otherOption.refDocument)) {
            //     $.each(otherOption.refDocument, function (key, value) {
            //         var element = $($.format('[name=%0]', value));
            //         $.console(element);
            //         if (element.length == 0){
            //             return true;
            //         }
            //         element.val("");
            //     });
            // }
            if (otherOption && otherOption.asyncreceive && $.isFunction(otherOption.asyncreceive)) {
                otherOption.asyncreceive.call($(this), data);
            }
        });
        return _t;
    };

    /**
     * 隐藏或显示
     * @param f boolean true为显示
     * @returns {*} jquery对象
     */
    $.fn.hidden = function (f) {
        if (f) {
            return this.removeClass('hidden').show();
        }
        return this.addClass('hidden');
    };

    /**
     * 禁用或启用
     * @param f boolean true为禁用
     * @returns {jQuery} jquery对象
     */
    $.fn.disabled = function (f) {
        this.each(function () {
            var _t = $(this);
            if (f == undefined) {
                _t.addClass('disabled');
            } else {
                _t.removeClass('disabled');
            }

            _t.prop('disabled', f == undefined);
        });
        return this;
    };

    /**
     * 判断是否为禁用
     * @returns {*} boolean
     */
    $.fn.isDisabled = function () {
        return this.hasClass('disabled') ||
            this.attr('disabled');
    };

    /**
     * option 赋值
     * @param value 值
     */
    $.fn.selectedVal = function (value) {
        return $($.format('option[value=%0]', (value || '')), this).prop('selected', 'selected');
    };

    /**
     * 获取option value
     * @returns {*} jquery option
     */
    $.fn.getSelectedVal = function () {
        return $('option:selected', this).val();
    };

    /**
     * enter方法
     * @param func
     */
    $.fn.enterSubmit = function (func) {
        this.each(function () {
            var _t = $(this);
            _t.keyup(function (e) {

                if (e.keyCode == 13) {

                    if ($.trim(_t.val()).length == 0) {
                        _t.focus();
                        return false;
                    }

                    if (func && $.isFunction(func)) {
                        func.callback(_t);
                    }
                }
            });
        });
        return this;
    };

    /**
     * 时间控件
     * @param options
     */
    $.fn.timePicker = function (options) {
        var picker = this;

        var _div = $('<div style="display: inline-block;position: relative;" />');
        picker.wrap(_div);
        picker.after('<i class="fa fa-clock-o timepicker_icon"/>');

        var _defaults = {
            confirm: undefined
        };
        _defaults = $.extend({}, _defaults, options || {});
        var timPickers = $('.tim_pickers', 'body');

        if (timPickers.length == 0) {

            var selects = '<span class="select2-container select2-container--default select2-container--open tim_pickers hidden" >';
            selects += '<span class="select2-dropdown" dir="ltr" style="width: 157px;">';
            selects += '<span class="select2-search select2-search--dropdown select2-search--hide"></span>';
            selects += '<span class="select2-results"><div class="select2-results__options" style="overflow: hidden;padding: 0px;"><div class="text-center text-bold p-5 show_select_timer" style="font-size:16px;"><span class="m">--</span>:<span class="s">--</span></div><div class="timer-contents pb-5 clearfix"></div><div><button style="width: 100%;border-radius: 0;" class="btn btn-primary timer_select btn-xs">'+LANG_TEXTS.TEXTALL.RMIND+'</button></div></div></span></span></span>';
            timPickers = $(selects).appendTo('body');
            var timerGroups = $('.timer-contents', timPickers);
            var labelTimer = $('.show_select_timer', timPickers);

            var _ulContent = $('<ul class="ub-time-picker"></ul>').appendTo(timerGroups);
            $('<div style="width:1px;border-right:1px solid #e5e5e5;float:left;height:179px;display: inline-block;padding-left: 4px;" />').appendTo(timerGroups);
            var interval = 15 || 1;
            for (var i = 0; i < 24; i++) {
                var j = 0;
                var hour = $.pad(i, 2);
                var li = $($.format('<li class="" key="%0"  style="cursor:pointer;text-align: center;padding: 2px 4px;" >%1</li>', hour, hour)).appendTo(_ulContent);

            }
            _ulContent = $('<ul class="ub-time-picker is_seccend pull-right"></ul>').appendTo(timerGroups);
            while (j < 60) {
                var v = $.pad(j, 2);
                $($.format('<li class="" key="%0" style="cursor:pointer;text-align: center;padding: 2px 4px;" >%1</li>', v, v)).appendTo(_ulContent);
                j++;
            }

            $('ul', timerGroups).each(function () {
                var _ul = $(this), isS = _ul.hasClass('is_seccend');
                $('li', _ul).each(function () {
                    var _li = $(this), key = _li.attr('key');
                    li.hover(function () {
                        li.addClass('highlighted');
                    }, function () {
                        li.removeClass('highlighted');
                    });
                    _li.click(function () {
                        $('li.active', _ul).removeClass('active');
                        _li.addClass('active');
                        if (isS) {
                            $('.s', labelTimer).text(key).data('key', key);
                        } else {
                            $('.m', labelTimer).text(key).data('key', key);
                        }
                    });
                });
            });


            $('.timer_select', timPickers).click(function (key) {
                var time = '';
                var _m = $('.m', timPickers).data('key');
                var _s = $('.s', timPickers).data('key');
                if (_m && _s) {
                    time = _m + ':' + _s;
                }
                if (!time) {
                    return true;
                }

                timPickers.addClass('hidden');
                var _picker = timPickers.data('ref');
                var _options = timPickers.data('options');
                var _ul = timPickers.data('ul');

                if (_options.confirm && $.isFunction(_options.confirm)) {
                    _options.confirm.call(timPickers, time);
                }
                _picker.data({'time': time});
                //if ($('li', _ul).length >= TIMERMAXLENGTH) {
                //_picker.addClass('hidden');
                //}
            });


            $('ul', timerGroups).scrollTop(100);


            $(document).mousedown(function (e) {
                var _target = $(e.target);
                if (_target.hasClass('tim_pickers') || _target.hasClass('edit_times') || _target.parents('.tim_pickers').length > 0) {
                    return true;
                }
                $('.tim_pickers').addClass('hidden');
            });

        }


        function setDefaults(time) {
            if (!time) {
                time = '08:00';
            }
            var timers = time.split(':');
            var m = $.pad(timers[0] || '08', 2);
            var s = $.pad(timers[1] || '0', 2);
            $('.ub-time-picker:first', timPickers).scrollTop(parseInt(m) * 24);
            $('.ub-time-picker:last', timPickers).scrollTop(parseInt(s) * 24);
            $('.ub-time-picker:first li[key=' + m + ']', timPickers).trigger('click');
            $('.ub-time-picker:last li[key=' + s + ']', timPickers).trigger('click');
        }


        picker.click(function (e) {
            e.preventDefault();
            var _this = $(this), time = _this.data('time');
            var _h = picker.outerHeight(), _width = picker.width(), _left = picker.offset().left,
                _top = picker.offset().top;
            var dh = $(window).scrollTop(), wh = $(window).height(), bh = dh + wh;
            if (bh < 0) {
                bh = 0;
            }
            if (bh < _top + 300) {
                _top = _top - 250 - _h - 6;
            }
            timPickers.data({'ref': _this, 'options': _defaults});
            timPickers.css({
                'position': 'absolute',
                left: _left,
                top: _top + _h + 3
            }).removeClass('hidden');
            setDefaults(time);
        });


        // if (datas && $.isArray(datas)) {
        //     $.each(datas, function (i, v) {
        //         _createTimeLi(picker, ul, v);
        //     })
        //     if (datas.length >= TIMERMAXLENGTH) {
        //         picker.addClass('hidden');
        //     }
        // }
    };

    $.fn.rangeDate = function (func) {
        var _group = this;
        $('.datepicker', _group).each(function () {
            var input = $(this), ref = input.data('picker-ref'),
                isEnd = input.data('picker-end'), max = input.data('picker-max'),
                min = input.data('picker-min');
            input.prop("readonly", true);
            input.datepicker({
                language: LANGUAGE,
                format: DATEFORMAT.toLowerCase(),
                autoclose: true,
                todayHighlight: true,
                orientation: 'auto bottom',
                immediateUpdates: false,
                keepEmptyValues: false,
                weekStart: 0
            }).on('changeDate', function () {
                input.trigger('neuron.picker', $(this).val()).trigger('keyup');
            });


            var minDate = undefined, maxDate = undefined;
            if (min) {
                if (min === "now") {
                    minDate = new Date();
                } else {
                    if (moment(min, DATEFORMAT).isValid()) {
                        minDate = new Date(moment(min, DATEFORMAT).valueOf());
                    }
                }
                input.datepicker('setStartDate', minDate);
            }

            if (max) {
                if (max === "now") {
                    maxDate = new Date();
                } else {
                    if (moment(max, DATEFORMAT).isValid()) {
                        maxDate = new Date(moment(max, DATEFORMAT).valueOf());
                    }
                }
                input.datepicker('setEndDate', maxDate);
            }

            if (ref && _group.getElement(ref)) {
                ref = _group.getElement(ref);
                input.datepicker().on('changeDate', function (ev) {
                    var value = ref.val();
                    var now = new Date();
                    if (value && moment(value, DATEFORMAT).isValid()) {
                        now = new Date(moment(value, DATEFORMAT).valueOf());
                    }
                    //if (isEnd == undefined) {
                    if (ev.date && ev.date.valueOf() > now.valueOf()) {
                        ref.val('');
                    }
                    ref.datepicker('setStartDate', ev.date);
                    // } else {
                    //     if (ev.date.valueOf() < now.valueOf()) {
                    //         ref.val('');
                    //     }
                    //     ref.datepicker('setEndDate', now);
                    // }
                });
            }
        });
    };

    var LANGUAGES = [];

    $.fn.languageInputs = function (data, zIndex) {
        var _t = this;

        if (LANGUAGES.length == 0) {
            $('.neuron_langs li').each(function () {
                var a = $('a', this), span = $('span', a);
                var tmp = {};
                tmp = {
                    lang: a.data('lang'),
                    text: $.trim(span.text()),
                    isDefault: LANGUAGE == a.data('lang')
                };

                var f = true;
                $.each(LANGUAGES, function (i, o) {
                    if (o.lang == tmp.lang) {
                        f = false;
                        return false;
                    }
                });
                if (f) {
                    LANGUAGES.push(tmp);
                }
            });
        }

        if (LANGUAGES.length > 0) {
            var groups = $('<div class="lang_name_groups" />');

            var a = $('<a href="#" style="display: inline-block;" class="form-control-static ub-link ub-link-primary insert_other_lang"><i class="icon-plus22"></i> ' + LANG_TEXTS.TEXT624 + '</a>').appendTo(groups);
            a.click(function (e, lang, value) {
                e.preventDefault();
                var element = creaetElement(lang, value);
                a.before(element);
                initBtns();
                element.iputlimit();
            });

            groups.append('<label class="validation-error-label custom_err hidden" ></label>');


            var defaultHtm = '<div class="input-group lang-groups mb-10">';
            defaultHtm += '<div class="input-group-btn">';
            defaultHtm += '<button type="button" name="nameLang" class="ub-btn ub-btn-default" style="border-radius:3px 0px 0px 3px;" data-toggle="dropdown" aria-haspopup="true" data-lang="${info.lang}" aria-expanded="false">${info.text} <span class="caret"></span></button>';
            defaultHtm += '<ul class="dropdown-menu dta_lang">';
            defaultHtm += '{{each(j,l) languages}}<li><a href="#" class="lang-event" data-lang="${l.lang}">${l.text}</a></li>{{/each}}';
            defaultHtm += '</ul>';
            defaultHtm += '</div>';
            defaultHtm += '<input type="text" class="form-control input-xs" value="${input}" data-iput_len="60" z-index="' + (zIndex || 10) + '"  show-error="langName" name="langName" placeholder="' + LANG_TEXTS.TEXT658 + '">';
            defaultHtm += '<a href="#" class="input-group-addon ub-link ub-link-warning txt-warning hidden" >' + LANG_TEXTS.TEXT173 + '</a>';
            defaultHtm += '</div>';

            function initBtns() {
                if ($('.lang-groups', groups).length >= LANGUAGES.length) {
                    a.addClass('hidden');
                } else {
                    a.removeClass('hidden');
                }
                $('.lang-groups:gt(0)', groups).each(function () {
                    $('.ub-link-warning.hidden', this).removeClass('hidden');
                });


                $('[name="nameLang"]', groups).css({width: 'auto'});
                var w = 0;
                $('[name="nameLang"]', groups).each(function () {
                    if ($(this).outerWidth() > w) {
                        w = $(this).outerWidth();
                    }
                });
                $('[name="nameLang"]', groups).css({width: w + 'px'});
            }

            function setLang(l) {

            }

            function creaetElement(l, v) {
                var info = {};


                $.each(LANGUAGES, function (i, o) {
                    if (o.isDefault) {
                        info = $.extend({}, o, {});
                    }
                });

                var isCheckeds = [];
                $('[name="nameLang"]', groups).each(function () {
                    isCheckeds.push($(this).data('lang'));
                });

                if ($.inArray(info.lang, isCheckeds) > -1) {
                    $.each(LANGUAGES, function (i, o) {
                        if ($.inArray(o.lang, isCheckeds) == -1) {
                            info = $.extend({}, o, {});
                            return false;
                        }
                    });
                }

                if (l) {
                    $.each(LANGUAGES, function (i, o) {
                        if (o.lang == l) {
                            info = $.extend({}, o, {});
                        }
                    });
                }


                var _defaultContent = $.tmpl(defaultHtm, {
                    info: info,
                    languages: LANGUAGES,
                    input: v || ''
                });

                $('.ub-link-warning', _defaultContent).click(function (e) {
                    e.preventDefault();
                    var _k = $('[input_limit_key]', _defaultContent).attr('input_limit_key');
                    _defaultContent.remove();
                    $('.' + _k).remove();
                    initBtns();
                    $(window).resize();
                });
                $('[name="langName"]', _defaultContent).keyup(function () {
                    $('.custom_err', groups).addClass('hidden');
                });
                var btn = $('button', _defaultContent)
                $('a.lang-event', _defaultContent).each(function () {
                    var _t = $(this);
                    _t.click(function (e) {
                        e.preventDefault();
                        btn.html(_t.text() + ' <span class="caret"></span>');
                        btn.data('lang', _t.data('lang'));
                        $('.custom_err', groups).addClass('hidden');

                        initBtns();
                    });
                });
                return _defaultContent;
            }

            groups.appendTo(_t);

            if (data && !$.isEmptyObject(data)) {
                $.each(data, function (l, v) {
                    a.trigger('click', [l, v]);
                });
            } else {
                a.trigger('click');
            }

        }
    };

    $.fn.languageNameValues = function () {
        var o = $(this);
        var obj = {};
        $('.lang_name_groups', this).each(function () {
            $('.lang-groups', this).each(function () {
                var l = $('[name="nameLang"]', this).data('lang');
                var v = $.trim($('[name="langName"]', this).val());
                if ((obj[l] == undefined || obj[l].length == 0) && v) {
                    obj[l] = v;
                }


            });
        });
        return obj;
    };


    $.fn.validLanguageInput = function (txt) {
        if ($('.lang_name_groups', this).length == 0) {
            return true;
        }
        $('.custom_err', this).addClass('hidden');
        var f = true;
        var langs = [], values = [];
        $('[name="nameLang"]', this).each(function () {
            var t = $(this), l = t.data('lang'), v = $.trim($('input', t.parent().parent()).val());
            if ($.inArray(l, langs) > -1) {
                f = false;
            }
            langs.push(l);
            if (v) {
                values.push(l);
            }
            //return f;
        });
        if (!f) {
            $('.custom_err', this).removeClass('hidden').text(LANG_TEXTS.TEXT659).show();
        }

        if (f && values.length == 0) {
            $('.custom_err', this).removeClass('hidden').text(txt || LANG_TEXTS.TEXT660).show();
            f = false;
        }

        return f;
    };
    var IPUTRULES = {
        'integer': $.isInteger,
        'float': $.isMFloat,
        'rate': $.isMFloat
    };

    var IPUTRULESTYPE = {
        'integer': /^\d+$/,
        'float': /^\d+(\.\d{1,2})?$/,
        'rate': /^\d+(\.\d{1,2})?$/
    };
    $.fn.validInputType = function () {
        this.each(function () {
            var iput = $(this), type = iput.data('iput'), ref = iput.attr('ref');

            var func = IPUTRULES[type] || undefined;
            if (!func) {
                return true;
            }
            iput.focus(function () {
                var v = $.trim(iput.val());
                if (!func.call(iput, v)) {
                    iput.val('');
                }
            }).blur(function () {
                var v = $.trim(iput.val());

                if (!func.call(iput, v)) {
                    var vs = v.match(/\d+(\.\d{1,2})?/);
                    var ret = v;
                    if (vs && $.isArray(vs)) {
                        ret = vs[0];
                    }
                    iput.val(ret);
                }
                if (type == 'rate' && ref) {
                    var rateType = $('option:selected', '.' + ref).val();
                    if (rateType == 2 && v >= 100) {
                        iput.val('');
                    }
                }
            });
        });
        return this;
    }

    $.fn.getElement = function (element) {
        var me = this;
        if (!element) return undefined;

        if ($("#" + element, me).length > 0) {
            return $("#" + element, me);
        } else if ($(element, me).length > 0) {
            return $(element, me);
        } else if ($('[name=' + element + ']', me).length > 0) {
            return $('[name=' + element + ']', me);
        } else if ($('.' + element, me).length > 0) {
            return $('.' + element, me);
        }
        return undefined;
    };

    $.fn.iputlimit = function (zIndex) {

        var me = this;
        var time = new Date().valueOf();
        $('[data-iput_len]', me).each(function (i) {
            var iput = $(this), modal = iput.parents('.modal-content'), isModal = modal.length > 0;
            if (iput.attr('input_limit_key')) {
                return true;
            }

            zIndex = iput.attr('z-index') || zIndex;
            var _key = $.format('%0_%1_%2', time++, parseInt(Math.random(1000000) * 100000), i);

            var len = parseInt(iput.data('iput_len') || 60, 10);

            var isInput = iput[0].tagName.toLowerCase() !== 'textarea';

            iput.attr('input_limit_key', _key);

            var offset = iput.offset(), _top = offset.top, _left = offset.left + iput.width() - 24;


            var group = '<font class="font_words" />';
            iput.wrap(group);
            group = iput.parent();
            var spanWord = $('<span style="position: absolute;right:9px;top:6px;z-index: 50;"><word>0</word>/' + len + '</span>');
            if (isInput) {
                iput.css({'padding-right': '54px'});
            } else {
                iput.css({'resize': 'vertical'});
                // var wrapDiv = $('<div  style="border:1px solid #ddd;border-radius: 3px;" class="font-limit-groups" />').css({'padding-bottom': '20px'});
                // iput.wrap(wrapDiv);
                group.css({
                    'padding-bottom': '20px',
                    'border-radius': '3px'
                });
                spanWord = $('<span style="position: absolute;right:6px;bottom:0;z-index: 50;"><word>0</word>/' + len + '</span>');
            }


            var h = iput.outerHeight(), w = iput.outerWidth();
            group.css({'width': w + 'px', 'display': 'table', 'position': 'relative'});
            spanWord.appendTo(group);


            // var div = $('<div class="font-word" style="position: absolute;z-index: ' + (zIndex || 10) + ';"/>').html('<word>0</word>/' + len);
            // div.appendTo('body');

            var word = $('word', spanWord);
            iput.bind('search.limit', function () {
                return lengthLimit();
            }).keyup(function () {
                iput.trigger('search.limit');
            }).focus(function () {
                return lengthLimit();
            }).blur(function () {
                return lengthLimit();
            }).bind('propertychange change keyup', function () {
                return lengthLimit();
            });

            iput.trigger('search.limit');

            function lengthLimit() {
                var v = $.trim(iput.val());
                var l = v.length;
                if (l >= len) {
                    iput.val(v.substr(0, len));
                    v = $.trim(iput.val());
                    l = v.length;
                    word.addClass('txt-warning');
                } else {
                    word.removeClass('txt-warning');
                }
                word.text(l);
                return !(l >= len);
            }

            // div.addClass(_key);
            //
            // iput.bind('reload.left', function () {
            //     var offset = iput.offset(), _left = offset.left + iput.outerWidth() - div.outerWidth() - 5;
            //     var left = (_left) + 'px';
            //     div.css({
            //         left: left
            //     });
            // });
            //
            // iput.bind('reload', function () {
            //     if (!isModal) {
            //         div.removeClass('hidden');
            //     }
            //     var offset = iput.offset(), _top = offset.top,
            //         _left = offset.left + iput.outerWidth() - div.outerWidth() - 5;
            //     var top = (_top + (iput.outerHeight() / 4) - 2) + 'px';
            //     var left = (_left) + 'px';
            //     if (!isInput) {
            //         top = ( _top + iput.outerHeight()) + 'px';
            //     }
            //     div.css({
            //         top: top,
            //         left: left,
            //
            //     });
            //
            //
            // }).trigger('reload');


        });


    }

})(jQuery);

/**
 * 高精度扩展
 * @type {{}}
 */
var MathEx = {};
/**
 * 除法
 * @param num1
 * @param num2
 * @returns {number}
 * @constructor
 */
MathEx.Division = function (num1, num2) {
    if (num2 == undefined) {
        num2 = 100;
    }
    var t1 = 0, t2 = 0, r1, r2;
    try {
        t1 = num1.toString().split('.')[1].length;
    }
    catch (e) {
    }
    try {
        t2 = num2.toString().split('.')[1].length;
    }
    catch (e) {
    }
    with (Math) {
        r1 = Number(num1.toString().replace('.', ''));
        r2 = Number(num2.toString().replace('.', ''));
        return (r1 / r2) * pow(10, t2 - t1);
    }
};
/**
 * 乘法
 * @param num1
 * @param num2
 * @returns {number}
 * @constructor
 */
MathEx.Multiplication = function (num1, num2) {
    if (num2 == undefined) {
        num2 = 100;
    }
    var m = 0, s1 = num1.toString(), s2 = num2.toString();
    try {
        m += s1.split('.')[1].length;
    } catch (e) {
    }
    try {
        m += s2.split('.')[1].length;
    } catch (e) {
    }
    return Number(s1.replace('.', '')) * Number(s2.replace('.', '')) / Math.pow(10, m);
};
/**
 * 加法
 * @param num1
 * @param num2
 * @returns {number}
 * @constructor
 */
MathEx.Addition = function (num1, num2) {
    var r1 = 0, r2 = 0, m;
    try {
        r1 = num1.toString().split('.')[1].length;
    } catch (e) {
    }
    try {
        r2 = num2.toString().split('.')[1].length;
    } catch (e) {
    }
    m = Math.pow(10, Math.max(r1, r2));
    return (num1 * m + num2 * m) / m;
};
/**
 * 减法
 * @param num1
 * @param num2
 * @returns {string}
 * @constructor
 */
MathEx.Subtraction = function (num1, num2) {
    var r1 = 0, r2 = 0, m, n;
    try {
        r1 = num1.toString().split('.')[1].length;
    } catch (e) {
    }
    try {
        r2 = num2.toString().split('.')[1].length;
    } catch (e) {
    }
    m = Math.pow(10, Math.max(r1, r2));
    n = (r1 >= r2) ? r1 : r2;
    return ((num1 * m - num2 * m) / m).toFixed(n);
};