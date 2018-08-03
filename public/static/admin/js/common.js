$(function() {
    var $fullText = $('.admin-fullText');
    $('#admin-fullscreen').on('click', function() {
        $.AMUI.fullscreen.toggle();
    });

    $(document).on($.AMUI.fullscreen.raw.fullscreenchange, function() {
        $fullText.text($.AMUI.fullscreen.isFullscreen ? '退出全屏' : '开启全屏');
    });

    $('.tpl-switch').find('.tpl-switch-btn-view').on('click', function() {
        $(this).prev('.tpl-switch-btn').prop("checked", function() {
            if ($(this).is(':checked')) {
                return false
            } else {
                return true
            }
        })
        // console.log('123123123')

    })

    $('.am-channel-type').each(function() {
        if ($(this).attr('checked')) {
            $(this).trigger('click');
        }
    });
})
// ==========================
// 侧边导航下拉列表
// ==========================

$('.tpl-left-nav-link-list').on('click', function() {
    $(this).siblings('.tpl-left-nav-sub-menu').slideToggle(80)
        .end()
        .find('.tpl-left-nav-more-ico').toggleClass('tpl-left-nav-more-ico-rotate');
})
// ==========================
// 头部导航隐藏菜单
// ==========================

$('.tpl-header-nav-hover-ico').on('click', function() {
    $('.tpl-left-nav').toggle();
    $('.tpl-content-wrapper').toggleClass('tpl-content-wrapper-hover');
})
// ==========================
// 头部导航切换语言
// ==========================
$('.am-switch-lang').on('click', function() {
     var lang = $(this).data('id');
     if (lang) {
        var _this = $(this);
        $.ajax({
            type: 'POST',
            url: '/' + Config.modulename + '/Ajax/setLang',
            data: {'id':lang},
            success: function(response) {
                if (response.code == 1) {
                    $('.' + _this.prop('class')).each(function(){
                        if($(this).find('span').hasClass('am-icon-check-square-o')){
                            $(this).find('span').removeClass('am-icon-check-square-o').addClass('am-icon-square-o');
                        }
                    })
                    _this.find('span').removeClass('am-icon-square-o').addClass('am-icon-check-square-o');
                    window.location.href = '/' + Config.modulename + '/' + Config.controllername + '/' + Config.actionname;
                } else {
                    alert(response.msg);
                }
            }
        })
     }
})

$(document).on('click', '.choose-icon > ul > li', function () {
    $('[name=icon]').val($(this).find('i').attr('class'));
    $('.am-close').trigger('click');
});

if ($('.am-toggle').length) {
    var toggle = $('.am-toggle').data('toggle');
    var obj = $('.' + $('.am-toggle').data('class'));
    if (toggle) {
        obj.show();
    } else {
        obj.hide();
    }
}

$(document).on('click', '.am-toggle', function () {
    var toggle = $(this).data('toggle');
    var obj = $('.' + $(this).data('class'));
    if (toggle) {
        $(this).data('toggle', 0);
        obj.hide();
    } else {
        $(this).data('toggle', 1);
        obj.show();
    }
});

$(document).on('change', '.am-rule', function () {
    var rule = $(this).find('option:selected').data('name');
    $('[name=name]').focus().val(rule ? rule : '');
});

$(document).on('click', '.am-config-status', function () {
    var status = $('.am-config-status').filter(':checked').val();
    if (status == 0) {
        $('#am-config-status').show();
    } else {
        $('#am-config-status').hide();
    }
});

$(document).on('click', '.am-nav-lang li', function() {
    if (!$(this).hasClass('am-active')) {
        $(this).addClass('am-active').siblings().removeClass('am-active');
    }
});
/* 栏目管理 */
$(document).on('change', '.am-lang', function() {
    var lang = $(this).find('option:selected').val();
    // $(this).find('option:selected').siblings().attr('selected', '');
    if (lang && $('.am-parent-channel').size() > 0) {
        $('.am-parent-channel option').each(function(i, val) {
            if ($(this).data('lang_id') == lang) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        var _lang = $('.am-parent-channel option:selected').data('lang_id');
        if (_lang && _lang != lang) {
            $('.am-parent-channel option').eq(0).attr('selected', 'selected');
        }
    }
});

$(document).on('change', '.am-parent-channel', function() {
    var lang = $(this).find('option:selected').data('lang_id');
    if (lang && $('.am-lang').size(0) > 0) {
        $('.am-lang option').each(function(i, val) {
            if ($(this).val() == lang) {
               if (!$(this).attr('selected')) {
                   $(this).attr('selected', 'selected');
               }
            } else {
                if ($(this).attr('selected')) {
                    $(this).attr('selected', '');
                }
            }
            $('.am-lang').trigger('changed.selected.amui');
        })
    }
});

$(document).on('click', '.am-channel-type', function() {
    $(this).attr('checked', true);
    var sel_id = $(this).prop('id');
    var sel_class = 'type-' + sel_id;
    $('.am-channel-type').each(function() {
        var id = $(this).prop('id');
        if (sel_id == id && $(this).attr('checked')) {
            $('.' + sel_class).show();
        } else {
            $(this).removeAttr('checked');
            $('.type-' + id).each(function(){
                if(!$(this).hasClass(sel_class)){
                    $(this).hide();
                }
            });
        }
    });
});
