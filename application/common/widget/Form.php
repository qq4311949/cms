<?php

namespace app\common\widget;

class Form {

    public function treeview($id, $name, $rules = '') {
        $trees = action('admin/auth/Rule/tree');
        if ($rules) {
            foreach ($trees as &$item) {
                if (in_array($item['id'], explode(',', $rules))) {
                    $item['checked'] = true;
                    $item['open'] = true;
                }
            }
        }
        $trees = json_encode($trees, JSON_UNESCAPED_UNICODE);
        $html = <<<EOT
			<ul id="{$id}" class="ztree"></ul>
			<input type="hidden" name="{$name}" />
			<script type="text/javascript">
				var setting = {
				    check: {
				        enable: true
				    },
				    data: {
				        simpleData: {
				            enable: true
				        }
				    },
				    callback: {
                        onCheck: onCheck
                    }
				};
				$(function(){
					$.fn.zTree.init($("#{$id}"), setting, {$trees});
				});
				function onCheck(e, treeId, treeNode) {
                    var treeObj = $.fn.zTree.getZTreeObj("{$id}"),
                        nodes = treeObj.getCheckedNodes(true),
                        ids = '';
                    for (var i = 0; i < nodes.length; i++) {
                        ids += nodes[i].id + ",";
                    }
                    if(ids){
                        ids.substring(0, -2);
                    }
                    $("[name={$name}]").val(ids);
                }
			</script>
EOT;
        echo $html;
    }

    public function editor($id, $name = '', $value = '', $is_required = true) {
        if ($name == ''){
            $name = $id;
        }
        $required = $is_required ? 'required' : '';
        $html = '<textarea id="'.$id.'" name="'.$name.'" '.$required.' style="height:150px">'.$value.'</textarea>';
        if(!defined('editor')){
            $html .= '<script type="text/javascript" charset="utf-8" src="/static/admin/libs/ueditor/ueditor.config.js"></script>
                    <script type="text/javascript" charset="utf-8" src="/static/admin/libs/ueditor/ueditor.all.min.js"> </script>
                    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
                    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
                    <script type="text/javascript" charset="utf-8" src="/static/admin/libs/ueditor/lang/zh-cn/zh-cn.js"></script>
                    <style>
                        .edui-default .edui-button-body, .edui-splitbutton-body, .edui-menubutton-body, .edui-combox-body {
                            height: 20px;
                        }
                        .edui-default .edui-toolbar .edui-combox .edui-combox-body {
                            height: 20px;
                        }
                        
                        .edui-default .edui-toolbar .edui-splitbutton .edui-splitbutton-body,
                        .edui-default .edui-toolbar .edui-menubutton .edui-menubutton-body {
                            height: 20px;
                        }
                        .edui-editor-iframeholder.edui-default {
                            width: 100%!important;
                        }
                    </style>';
            define('editor', 1);
        };
        $html .= '<script type="text/javascript">
                    var ue = UE.getEditor("'.$id.'");
                    ue.ready(function() { 
                        $("#'.$id.'>div:first").css("width", "");
                    })
                </script>';
        echo $html;
    }

    public function upload($id, $value, $is_preview = false, $is_required = true) {
        $arr = explode('-', $id);
        $name = $arr[1];
        $previewId = $is_preview ? 'p-'.$arr[1] : '';
        $required = $is_required ? 'required' : '';
        $html = '<div class="am-input-group">
                    <input type="text" id="i-'. $name .'" name="'. $name .'" value="'. $value .'" '.$required.' />
                    <span class="am-input-group-btn">
                        <button type="button" id="'. $id .'" class="am-btn am-btn-default am-btn-sm plupload"
                            data-input-id="i-'.$name.'" data-preview-id="'. $previewId .'">
                            <i class="am-icon-cloud-upload"></i> 上传
                        </button>
                    </span>
                </div>';
        if (!defined('upload')) {
            $html .= '<script type="text/javascript" src="/static/admin/libs/plupload/js/plupload.full.min.js"></script>';
            define('upload', 1);
        }
        $_html = <<<EOT
            <script type="text/javascript">
                $('.plupload').each(function () {
                    if ($(this).attr("initialized")) {
                        return true;
                    }
                    $(this).attr("initialized", true);
                    var that = this;
                    var id = $(this).prop("id");
                    var url = $(this).data("url");
                    var maxsize = $(this).data("maxsize");
                    var mimetype = $(this).data("mimetype");
                    var multipart = $(this).data("multipart");
                    var multiple = $(this).data("multiple");
                    
                    //填充ID
                    var input_id = $(that).data("input-id") ? $(that).data("input-id") : "";
                    //预览ID
                    var preview_id = $(that).data("preview-id") ? $(that).data("preview-id") : "";
            
                    //上传URL
                    url = url ? url : Config.moduleurl + '/' + Config.upload.uploadurl;
                    //最大可上传文件大小
                    maxsize = typeof maxsize !== "undefined" ? maxsize : Config.upload.maxsize;
                    //文件类型
                    mimetype = typeof mimetype !== "undefined" ? mimetype : Config.upload.mimetype;
                    //请求的表单参数
                    multipart = typeof multipart !== "undefined" ? multipart : Config.upload.multipart;
                    //是否支持批量上传
                    multiple = typeof multiple !== "undefined" ? multiple : Config.upload.multiple;
                    var mimetypeArr = new Array();
                    //支持后缀和Mimetype格式,以,分隔
                    if (mimetype && mimetype !== "*" && mimetype.indexOf("/") === -1) {
                        var tempArr = mimetype.split(',');
                        for (var i = 0; i < tempArr.length; i++) {
                            mimetypeArr.push({title: '文件', extensions: tempArr[i]});
                        }
                        mimetype = mimetypeArr;
                    }
                    //生成Plupload实例
                    var Upload = {list: {}};
                    Upload.list[id] = new plupload.Uploader({
                        runtimes: 'html5,flash,silverlight,html4',
                        multi_selection: multiple, //是否允许多选批量上传
                        browse_button: id, // 浏览按钮的ID
                        container: $(this).parent().get(0), //取按钮的上级元素
                        flash_swf_url: '/static/admin/libs/plupload/js/Moxie.swf',
                        silverlight_xap_url: '/static/admin/libs/plupload/js/Moxie.xap',
                        filters: {
                            max_file_size: maxsize,
                            mime_types: mimetype,
                        },
                        url: url,
                        multipart_params: $.isArray(multipart) ? {} : multipart,
                        init: {
                            FilesAdded: function (up, files) {
                                var button = up.settings.button;
                                $(button).data("bakup-html", $(button).html());
                                var maxcount = $(button).data("maxcount");
                                var input_id = $(button).data("input-id") ? $(button).data("input-id") : "";
                                maxcount = typeof maxcount !== "undefined" ? maxcount : 0;
                                if (maxcount > 0 && input_id) {
                                    var inputObj = $("#" + input_id);
                                    if (inputObj.size() > 0) {
                                        var value = $.trim(inputObj.val());
                                        var nums = value === '' ? 0 : value.split(/\,/).length;
                                        var remains = maxcount - nums;
                                        if (files.length > remains) {
                                            for (var i = 0; i < files.length; i++) {
                                                up.removeFile(files[i]);
                                            }
                                            alert('你最多还可以上传' + remains + '个文件');
                                            return false;
                                        }
                                    }
                                }
                                //添加后立即上传
                                setTimeout(function () {
                                    up.start();
                                }, 1);
                            },
                            UploadProgress: function (up, file) {
                                var button = up.settings.button;
                                $(button).prop("disabled", true).html("<i class='am-icon-cloud-upload'></i> 上传" + file.percent + "%");
                            },
                            FileUploaded: function (up, file, info) {
                                var button = up.settings.button;
                                //还原按钮文字及状态
                                $(button).prop("disabled", false).html($(button).data("bakup-html"));
                              
                                try {
                                    var ret = typeof info.response === 'object' ? info.response : JSON.parse(info.response);
                                    if (!ret.hasOwnProperty('code')) {
                                        $.extend(ret, {code: -2, msg: response, data: null});
                                    }
                                } catch (e) {
                                    var ret = {code: -1, msg: e.message, data: null};
                                }
                                
                                file.ret = ret;
                                if (ret.code === 1) {
                                    var button = up.settings.button;
                                    var onUploadSuccess = up.settings.onUploadSuccess;
                                    var data = typeof ret.data !== 'undefined' ? ret.data : null;
                                    //上传成功后回调
                                    if (button) {
                                        //如果有文本框则填充
                                        var input_id = $(button).data("input-id") ? $(button).data("input-id") : "";
                                        if (input_id) {
                                            var urlArr = [];
                                            var inputObj = $("#" + input_id);
                                            if ($(button).data("multiple") && inputObj.val() !== "") {
                                                urlArr.push(inputObj.val());
                                            }
                                            urlArr.push(data.url);
                                            inputObj.val(urlArr.join(",")).trigger("change");
                                        }
                                        //如果有回调函数
                                        var onDomUploadSuccess = $(button).data("upload-success");
                                        if (onDomUploadSuccess) {
                                            if (typeof onDomUploadSuccess !== 'function' && typeof Upload.api.custom[onDomUploadSuccess] === 'function') {
                                                onDomUploadSuccess = Upload.api.custom[onDomUploadSuccess];
                                            }
                                            if (typeof onDomUploadSuccess === 'function') {
                                                var result = onDomUploadSuccess.call(button, data, ret);
                                                if (result === false)
                                                    return;
                                            }
                                        }
                                    }
                            
                                    if (typeof onUploadSuccess === 'function') {
                                        var result = onUploadSuccess.call(button, data, ret);
                                        if (result === false)
                                            return;
                                    }
                                } else {
                                    var button = up.settings.button;
                                    var onUploadError = up.settings.onUploadError;
                                    var data = typeof ret.data !== 'undefined' ? ret.data : null;
                                    if (button) {
                                        var onDomUploadError = $(button).data("upload-error");
                                        if (onDomUploadError) {
                                            if (typeof onDomUploadError !== 'function' && typeof Upload.api.custom[onDomUploadError] === 'function') {
                                                onDomUploadError = Upload.api.custom[onDomUploadError];
                                            }
                                            if (typeof onDomUploadError === 'function') {
                                                var result = onDomUploadError.call(button, data, ret);
                                                if (result === false)
                                                    return;
                                            }
                                        }
                                    }

                                    if (typeof onUploadError === 'function') {
                                        var result = onUploadError.call(button, data, ret);
                                        if (result === false) {
                                            return;
                                        }
                                    }
                                    alert(ret.msg + "(code:" + ret.code + ")");
                                }
                            },
                            UploadComplete: function (up, files) {
                                var button = up.settings.button;
                                var onUploadComplete = up.settings.onUploadComplete;
                                if (button) {
                                    var onDomUploadComplete = $(button).data("upload-complete");
                                    if (onDomUploadComplete) {
                                        if (typeof onDomUploadComplete !== 'function' && typeof Upload.api.custom[onDomUploadComplete] === 'function') {
                                            onDomUploadComplete = Upload.api.custom[onDomUploadComplete];
                                        }
                                        if (typeof onDomUploadComplete === 'function') {
                                            var result = onDomUploadComplete.call(button, files);
                                            if (result === false)
                                                return;
                                        }
                                    }
                                }
                    
                                if (typeof onUploadComplete === 'function') {
                                    var result = onUploadComplete.call(button, files);
                                    if (result === false) {
                                        return;
                                    }
                                }
                            },
                            Error: function (up, err) {
                                var button = up.settings.button;
                                $(button).prop("disabled", false).html($(button).data("bakup-html"));
                                var ret = {code: err.code, msg: err.message, data: null};
                                var button = up.settings.button;
                                
                                var onUploadError = up.settings.onUploadError;
                                var data = typeof ret.data !== 'undefined' ? ret.data : null;
                                if (button) {
                                    var onDomUploadError = $(button).data("upload-error");
                                    if (onDomUploadError) {
                                        if (typeof onDomUploadError !== 'function' && typeof Upload.api.custom[onDomUploadError] === 'function') {
                                            onDomUploadError = Upload.api.custom[onDomUploadError];
                                        }
                                        if (typeof onDomUploadError === 'function') {
                                            var result = onDomUploadError.call(button, data, ret);
                                            if (result === false)
                                                return;
                                        }
                                    }
                                }
                    
                                if (typeof onUploadError === 'function') {
                                    var result = onUploadError.call(button, data, ret);
                                    if (result === false) {
                                        return;
                                    }
                                }
                                alert(ret.msg + "(code:" + ret.code + ")");
                            }
                        },
                        button: that
                    });
                    if (preview_id && input_id) {
                        $(document.body).on("keyup change", "#" + input_id, function () {
                            var inputStr = $("#" + input_id).val();
                            var inputArr = inputStr.split(/\,/);
                            $("#" + preview_id).empty();
                            $.each(inputArr, function (i, j) {
                                if (!j) {
                                    return true;
                                }
                                var html = '<li class="am-u-xs-3"><a href="'+j+'" target="_blank"><img src="'+j+'" class="am-img-thumbnail am-img-responsive"></a><a href="javascript:;" class="am-btn am-btn-danger btn-xs btn-trash"><i class="am-icon-trash am-font-white"></i></a></li>';
                                $("#" + preview_id).append(html);
                            });
                        });
                        $("#" + input_id).trigger("change");
                    }
                    if (preview_id) {
                        // 监听事件
                        $(document.body).on("fa.preview.change", "#" + preview_id, function () {
                            var urlArr = new Array();
                            $("#" + preview_id + " [data-url]").each(function (i, j) {
                                urlArr.push($(this).data("url"));
                            });
                            if (input_id) {
                                $("#" + input_id).val(urlArr.join(","));
                            }
                        });
                        // 移除按钮事件
                        $(document.body).on("click", "#" + preview_id + " .btn-trash", function () {
                            $(this).closest("li").remove();
                            $("#" + preview_id).trigger("fa.preview.change");
                        });
                    }
                    Upload.list[id].init();
                });
            </script>
EOT;
        echo $html . $_html;
    }
}