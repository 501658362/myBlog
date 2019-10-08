(function ($) {

    /**
     * Ajax请求类 包含get|post|put|delete
     * 示例代码:$.request.put('http://mis.ubtour.com/ajax');
     * @type {{get: jQuery.request.get, post: jQuery.request.post, put: jQuery.request.put, delete: jQuery.request.delete}}
     */
    $.request = {

        get: function (url, param, func, error) {
            return this.__ajax(url, 'GET', param, func, error);
        },
        post: function (url, param, func, error) {
            return this.__ajax(url, 'POST', param, func, error);
        },
        put: function (url, param, func, error) {
            return this.__ajax(url, 'PUT', param, func, error);
        },
        delete: function (url, param, func, error) {
            return this.__ajax(url, 'DELETE', param, func, error);
        },
        __ajax: function (url, type, data, back, error) {
            //debug
            $.console(url, type, data, back);
            if (url == undefined) {
                return false;
            }

            if ($(window).is_disabled()) {
                return false;
            }
            $(window).disabled();

            var me = this;
            $.ajax({
                type: type,
                url: url || window.location.href,
                data: data || undefined,
                context: document.body,
                success: function (ret) {
                    $(window).enabled();
                    return me.__callback(ret, back, error);
                },
                error: function (err) {
                    $(window).enabled();
                    return me.__callback(err, back, error);
                }
            });

        },
        __callback: function (data, func, error) {

            if (error != undefined && $.isFunction(error)) {
                error.call(this, data);
                return true;
            }
            if (undefined != data && data.code == 0) {
                if (func && $.isFunction(func)) {
                    func.call(this, data.data);
                }
            } else {
                if (func && $.isFunction(func)) {
                    func.call(this, undefined);
                }
            }

            if (undefined != data && data.message && error == undefined) {
                $.gritter.add({
                    title: data.code ? '错误提示' : '提示信息',
                    text: data.message,
                    class_name: data.code ? 'gritter-error' : 'gritter-light'
                });
            }

            if (undefined != data && data.readyState) {
                var success = true;
                var message = '请稍后再试 服务器处于异常状态';
                switch (data.status) {
                    case 401:
                        message = '登录信息失效,请重新登录后再试!';
                        break;
                    case 400:
                        break;
                    case 403:
                        message = '禁止执行访问.';
                        break;
                    case 500:
                        break;
                    default:
                        success = false;
                        break;
                }
                if (success) {
                    $.gritter.add({
                        title: '异常信息',
                        text: message,
                        class_name: 'gritter-error'
                    });
                    if (data.status == 401 || data.status == 403) {
                        setTimeout(function () {
                            window.location.href = window.location.href;
                        }, 5000);
                    }
                }
            }
        }

    };

    /**
     * 控制台输出
     * 示例代码 $.console(arguments);
     */
    $.console = function () {
        return console.log(arguments);
    };
    /**
     * 确认提示框
     * 示例代码 $.confirm('确定要执行此操作码?',function(){ alert(arguments[0])});
     * @param msg 提示信息
     * @param func 回调函数
     * @param nofunc
     */
    $.confirm = function (msg, func, nofunc) {
        bootbox.confirm(msg || '未填写任何提示信息...', function (result) {
            if (result) {
                if (func && $.isFunction(func)) {
                    func.call(this, result);
                }
            } else {
                if (nofunc && $.isFunction(nofunc)) {
                    nofunc.call(this, result);
                }
            }
        });
    };

    /**
     * 提示框
     * 示例代码 $.alert('确定要执行此操作码?',function(){ alert(arguments[0])});
     * @param msg 提示信息
     * @param func 回调函数
     */
    $.alert = function (msg, func) {
        bootbox.alert(msg || '未填写任何提示信息...', function () {
            if (func && $.isFunction(func)) {
                func.call(this);
            }
        });
    };

    /**
     * 带输入的确认提示框
     * 示例代码 $.confirm('确定要执行此操作码?',function(){ alert(arguments[0])});
     * @param msg 提示信息
     * @param func 回调函数
     * @param nofunc
     */
    $.prompt = function (msg, func, nofunc) {
        bootbox.prompt(msg || '未填写任何提示信息...', function (result) {
            if (result) {
                if (func && $.isFunction(func)) {
                    func.call(this, result);
                }
            } else {
                if (nofunc && $.isFunction(nofunc)) {
                    nofunc.call(this, result);
                }
            }
        });
    };

    $.moneyFormat = function ($cell, num) {
        var reg = /[\$,%]/g;
        if (num == undefined) {
            num = 2;
        }
        var key = parseFloat(String($cell).replace(reg, '')).toFixed(num); // toFixed小数点后两位
        return isNaN(key) ? 0.00 : key;
    };
})(jQuery);

(function ($) {

    /**
     * 是否为禁用状态
     * 示例代码 $('#password').is_disabled();
     * @returns Boolean
     */
    $.fn.is_disabled = function () {
        return $(this).hasClass('disabled');
    };

    /**
     * 禁用
     * 示例代码 $('#password').disabled();
     */
    $.fn.disabled = function () {
        return $(this).removeAttr('enabled').addClass('disabled').attr('disabled', 'disabled');

    };

    /**
     * 启用
     * 示例代码 $('#password').enabled();
     */
    $.fn.enabled = function () {
        return $(this).removeClass('disabled').removeAttr('disabled').attr('enabled', 'enabled');
    };

    /**
     * 错误提示信息
     * 示例代码 $('#password').input_error('请输入账号密码!');
     * @param msg 文本(默认值为 请输入)
     */
    $.fn.input_error = function (msg) {

        var form_group = $(this).parents('.form-group');
        var err_class = 'has-error';
        var err_icon = '<i class="icon-remove-sign"></i>';
        var err_text = '<div class="help-block col-xs-12 col-sm-reset inline">' + (msg || '请输入...') + '</div>';

        var _t = $(this), timer_key = undefined;
        $.fn.add_error = function () {
            if (form_group.hasClass(err_class)) {
                return true
            }
            $(this).remove_error();
            form_group.addClass(err_class);
            var _err_icon = $(err_icon);
            $(this).after(_err_icon);
            form_group.append(err_text);

            _err_icon.click(function () {
                $(this).remove_error();
            });

        }
        $.fn.remove_error = function () {
            if (form_group.hasClass(err_class)) {
                form_group.removeClass(err_class);
                $('i.icon-remove-sign', form_group).remove();
                $('.help-block', form_group).remove();

            }
        };

        _t.bind('keyup keydown blur', function () {
            if ($.trim(_t.val())) {
                _t.remove_error();
            } else {
                _t.add_error();
            }
        });

        return _t;


    };

    /**
     * 警告提示信息
     * 示例代码 $('#password').input_warning('太棒了!');
     * @param msg 文本
     */
    $.fn.input_warning = function (msg) {
        var form_group = $(this).parents('.form-group');
        var err_class = 'has-warning';
        var err_icon = '<i class="icon-info-sign"></i>';
        var err_text = '<div class="help-block col-xs-12 col-sm-reset inline">' + (msg || '') + '</div>';

        var _t = $(this), timer_key = undefined;
        $.fn.add_warning = function () {
            if (form_group.hasClass(err_class)) {
                return true
            }
            $(this).remove_warning();
            form_group.addClass(err_class);
            var _err_icon = $(err_icon);
            $(this).after(_err_icon);
            form_group.append(err_text);

            _err_icon.click(function () {
                $(this).remove_warning();
            });

        }
        $.fn.remove_warning = function () {
            if (form_group.hasClass(err_class)) {
                form_group.removeClass(err_class);
                $('i.icon-info-sign', form_group).remove();
                $('.help-block', form_group).remove();

            }
        };

        _t.bind('keyup blur', function () {
            if ($.trim(_t.val())) {
                _t.remove_warning();
            } else {
                _t.add_warning();
            }
        });
        return _t;
    };

    $.fn.my_pickadate = function () {
        var _t = $(this);
        _t.pickadate({
            format: "yyyy-m-d",
            labelMonthNext: "下一个月",
            labelMonthPrev: "前一个月",
            labelMonthSelect: "选择月",
            labelYearSelect: "选择年",
            monthsFull: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            monthsShort: ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二"],
            weekdaysFull: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
            weekdaysShort: ["日", "一", "二", "三", "四", "五", "六"],
            today: "今天",
            clear: "清空",
            close: "关闭",
            closeOnSelect: !0,
            closeOnClear: !0,
        });
    };

    $.fn.my_pickatime = function () {
        var _t = $(this);
        _t.pickatime({
            format: "H:i",
            clear: "清空",
            interval: 15
        });
    };

    $.fn.form_validate = function (rules, messages) {
        var _t = $(this);
        var default_rules = $.extend({
            name: {
                required: true,
            },
            email: {
                email: true,
                required: true,
            },
            password: {
                required: true,
            },
            password_confirmation: {
                required: true,
                equalTo: "#password",
            },
        }, rules || {});
        var default_messages = $.extend({
            name: {
                required: "请输入姓名",
            },
            password_confirmation: {
                required: "请输入确认密码",
                equalTo: "密码输入不一致,请确认."
            },
            email: {
                email: "邮箱格式不对",
                required: "请输入邮箱"
            },
            code: {
                required: "请输入验证码"
            },
            password: {
                required: "请输入密码"
            },
            phone: {
                required: "请输入手机号码"
            },
            message: {
                required: "请输入信息内容"
            },
        }, messages || {});
        _t.validate({
            onkeyup: false,
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: default_rules,
            messages: default_messages,
            invalidHandler: function (event, validator) { //display error alert on form submit
                $('.alert-danger', $('.login-form')).show();
            },
            errorPlacement: function (error, element) {
                if (element.is(':checkbox') || element.is(':radio')) {
                    var controls = element.closest('div[class*="col-"]');
                    if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                }
                else if (element.is('.select2')) {
                    error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                }
                else if (element.is('.chosen-select')) {
                    error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                    //error.insertAfter(element.parent());
                }
                else error.insertAfter(element.parent());
                //console.log(error,element);
                var offset = $('.has-error:first').offset();
                if (offset) {
                    $('html,body').scrollTop(offset.top - 50);
                }
            },
            highlight: function (e) {
                //if(e.parent().parent())
                if ($(e).parent().parent().is('label')) {
                    $(e).parent().parent().removeClass('has-info').addClass('has-error');
                }
                //console.log($(e).parent().parent().is('label'));
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },

            success: function (e) {
                if ($(e).parent().is('label')) {
                    $(e).parent().removeClass('has-error').addClass('has-info');
                }
                $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                $(e).remove();
            },
            submitHandler: function (form) {
                $("div.error").hide();
                //return true;
                form.submit();
                $('button[type=submit]').button('loading');
            }
        });

    };
})(jQuery);