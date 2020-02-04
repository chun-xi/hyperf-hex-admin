layui.define(['treeSelect', 'layer', 'jquery', 'form', 'admin', 'setter', 'table', 'authtree', 'upload', 'laydate'], function (exports) {
    let layer = layui.layer, $ = layui.jquery, form = layui.form, admin = layui.admin, treeSelect = layui.treeSelect,
        setter = layui.setter, table = layui.table, authtree = layui.authtree, upload = layui.upload,
        laydate = layui.laydate;

    let hex = {
        property: {
            mapId: {},
            tableDict: {}
        },
        generateRandStr(len) {
            let chars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            var res = "";
            for (var i = 0; i < len; i++) {
                var id = Math.ceil(Math.random() * 35);
                res += chars[id];
            }
            return res;
        },
        getPopupJQueryObject(name) {
            return $('.hex-modal .' + name);
        },
        popup(url, fields, done, values = {}, area = '660px') {
            let d = ' <div class="layui-card-body"><form class="layui-form layui-form-pane hex-modal">';
            let objectContainer = {}
            //初步渲染界面
            fields.forEach(item => {
                //设置默认值
                if (!values.hasOwnProperty(item.name) && item.hasOwnProperty('default')) {
                    values[item.name] = item.default;
                }


                switch (item.type) {
                    case 'hidden':
                        d += '<input type="hidden" name="' + item.name + '" value="' + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '">';
                        break;
                    case 'input':
                        d += '        <div class="layui-form-item">\n' +
                            '            <label class="layui-form-label">' + item.title + '</label>\n' +
                            '            <div class="layui-input-block">\n' +
                            '                <input name="' + item.name + '" placeholder="' + item.placeholder + '" type="text" class="layui-input ' + item.name + '" value="' + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '"/>' +
                            '            </div>\n' +
                            '        </div>';
                        break;
                    case 'textarea':
                        d += '        <div class="layui-form-item">\n' +
                            '            <label class="layui-form-label">' + item.title + '</label>\n' +
                            '            <div class="layui-input-block">\n' +
                            '                <textarea ' + (item.hasOwnProperty('height') ? 'style="height:' + item.height + 'px"' : '') + ' name="' + item.name + '" placeholder="' + item.placeholder + '" class="layui-input ' + item.name + '">' + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '</textarea>' +
                            '            </div>\n' +
                            '        </div>';
                        break;
                    case 'radio':
                        d += '<div class="layui-form-item" pane="">\n' +
                            '            <label class="layui-form-label">' + item.title + '</label>\n' +
                            '            <div class="layui-input-block ' + item.name + '">\n' +
                            '            </div>\n' +
                            '        </div>';
                        break;
                    case 'treeSelect':
                        d += '<div class="layui-form-item" ><input type="hidden" name="' + item.name + '" value="' + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '">\n' +
                            '            <label class="layui-form-label">' + item.title + '</label>\n' +
                            '            <div class="layui-input-block">' +
                            '<input type="text" lay-filter="tree" class="layui-input ' + item.name + '">' +
                            '            </div>\n' +
                            '        </div>';
                        break;
                    case 'switch':
                        d += '<div class="layui-form-item" pane=""><input type="hidden" name="' + item.name + '" value="' + (values.hasOwnProperty(item.name) ? values[item.name] : 0) + '">\n' +
                            '                <label class="layui-form-label">' + item.title + '</label>\n' +
                            '                <div class="layui-input-block">\n' +
                            '                    <input class="' + item.name + '" type="checkbox" lay-filter="switch-' + item.name + '" value="1" title="' + item.text + '" ' + (values.hasOwnProperty(item.name) ? (values[item.name] === 1 ? 'checked' : '') : '') + '>\n' +
                            '                </div>\n' +
                            '            </div>';
                        break;
                    case 'select':
                        d += '<div class="layui-form-item">\n' +
                            '                <label class="layui-form-label">' + item.title + '</label>\n' +
                            '                <div class="layui-input-block">\n' +
                            '                    <select lay-filter="hex-' + item.name + '"  class="' + item.name + '" name="' + item.name + '"><option value="">' + item.placeholder + '</option></select>\n' +
                            '                </div>\n' +
                            '            </div>';
                        break;
                    case 'icon':
                        d += '<div class="layui-form-item">\n' +
                            '      <label class="layui-form-label">' + item.title + '</label>\n' +
                            '           <div class="layui-input-block">\n' +
                            '               <input type="text" name="' + item.name + '" class="layui-input ' + item.name + '" lay-filter="' + item.name + '">\n' +
                            '           </div>\n' +
                            '  </div>';
                        break;
                    case 'treeCheckbox':
                        d += '<div class="layui-form-item" pane="">\n' +
                            '          <label class="layui-form-label">' + item.title + '</label>\n' +
                            '          <div class="layui-input-block">\n' +
                            '            <div class="' + item.name + '"></div>\n' +
                            '          </div>\n' +
                            '        </div>';
                        break;
                    case 'checkbox':
                        d += '<div class="layui-form-item" pane="">\n' +
                            '    <label class="layui-form-label">' + item.title + '</label>\n' +
                            '    <div class="layui-input-block ' + item.name + '">\n' +
                            '    </div>\n' +
                            '  </div>';
                        break;
                    case 'image':
                        d += '<div class="layui-form-item" pane=""><input type="hidden" name="' + item.name + '" value="' + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '">\n' +
                            '    <label class="layui-form-label">' + item.title + '</label>\n' +
                            '    <div class="layui-input-block ' + item.name + '"><img src="' + (item.hasOwnProperty('viewUrl') ? item.viewUrl : '') + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '" style="margin:3px;border-radius:5px;max-width: ' + (item.hasOwnProperty('width') ? item.width : '300') + 'px;' + (values.hasOwnProperty(item.name) ? '' : 'display:none;') + '">\n' +
                            '    <button type="button" class="layui-btn layui-btn-primary" style="' + (values.hasOwnProperty(item.name) ? 'display:none;' : '') + '"><i class="layui-icon layui-icon-picture"></i>' + item.placeholder + '</button >\n' +
                            '    </div>\n' +
                            '  </div>';
                        break;
                    case 'file':
                        d += '<div class="layui-form-item" pane=""><input type="hidden" name="' + item.name + '" value="' + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '">\n' +
                            '    <label class="layui-form-label">' + item.title + '</label>\n' +
                            '    <div class="layui-input-block ' + item.name + '">\n' +
                            '    <button type="button" class="layui-btn layui-btn-primary"><i class="layui-icon ' + (item.hasOwnProperty('icon') ? item.icon : 'layui-icon-file-b') + '"></i><span>' + item.placeholder + '</span></button >\n' +
                            '    </div>\n' +
                            '  </div>';
                        break;
                    case 'json':
                        d += '<div class="layui-form-item" pane=""><input type="hidden" name="' + item.name + '" value="' + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '">\n' +
                            '    <label class="layui-form-label">' + item.title + '</label>\n' +
                            '    <div class="layui-input-block ' + item.name + '">\n' +
                            '       <div class="' + item.name + '"></div>' +
                            '    </div>\n' +
                            '  </div>';
                        break;
                    case 'date':
                        d += '        <div class="layui-form-item">\n' +
                            '            <label class="layui-form-label">' + item.title + '</label>\n' +
                            '            <div class="layui-input-block">\n' +
                            '                <input name="' + item.name + '" placeholder="' + item.placeholder + '" type="text" class="layui-input ' + item.name + '" value="' + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '"/>' +
                            '            </div>\n' +
                            '        </div>';
                        break;
                }
            });
            if (values.hasOwnProperty('id')) {
                d += '<input type="hidden" name="id" value="' + values.id + '">';
            }
            d += '</form></div>';

            layer.open({
                type: 1,
                shade: 0.3,
                content: d,
                title: values.hasOwnProperty('id') ? '修改' : '添加',
                btn: ['确认', '取消'],
                //  shadeClose: true,
                area: area,
                maxmin: true,
                yes: (index, layero) => {
                    let serialize = decodeURIComponent($('.hex-modal').serialize());

                    let paramsToJSONObject = this.paramsToJSONObject(serialize);
                    fields.forEach(item => {
                        switch (item.type) {
                            case "treeCheckbox":
                                delete paramsToJSONObject['ids[]'];
                                console.log(paramsToJSONObject);
                                let data = authtree.getChecked('.hex-modal .' + item.name);
                                paramsToJSONObject[item.name] = data;
                                break;
                            case "json":
                                paramsToJSONObject[item.name] = encodeURIComponent(JSON.stringify(objectContainer[item.name].get()));
                                break;
                        }
                    });
                    admin.req({
                        url: url,
                        data: paramsToJSONObject,
                        done: ret => {
                            layer.msg(ret.msg);
                            layer.close(index);
                            done(ret);
                        }
                    });
                },
                success: (layero, index) => {
                    fields.forEach(item => {
                        //上传url
                        let uploadUrl = item.hasOwnProperty('uploadUrl') ? item.uploadUrl : '/system/other/upload';
                        //上传的url字段名称
                        let uploadUrlName = item.hasOwnProperty('uploadUrlName') ? item.uploadUrlName : 'path';

                        switch (item.type) {
                            case "radio":
                                if (item.hasOwnProperty('dict')) {
                                    let instance = $('.hex-modal .' + item.name);
                                    this.getDict(item.dict, res => {
                                        res.data.forEach(s => {
                                            instance.append('<input name="' + item.name + '" type="radio" value="' + s.id + '" title="' + s.name + '" ' + (values.hasOwnProperty(item.name) ? (values[item.name] == s.id ? 'checked' : '') : '') + ' />');
                                        });
                                        form.render();
                                    });
                                }
                                break;
                            case "treeSelect":
                                if (item.hasOwnProperty('dict')) {
                                    this.getDict(item.dict, res => {
                                        treeSelect.render({
                                            // 选择器
                                            elem: '.hex-modal .' + item.name,
                                            // 数据
                                            data: '/system/dict/getDict?dict=' + item.dict,
                                            // 异步加载方式：get/post，默认get
                                            type: 'post',
                                            // 占位符
                                            placeholder: item.placeholder,
                                            // 是否开启搜索功能：true/false，默认false
                                            search: true,
                                            // 点击回调
                                            click: function (d) {
                                                $('.hex-modal input[name=' + item.name + ']').val(d.current.id);
                                            },
                                            // 加载完成后的回调函数
                                            success: function (d) {
                                                if (values.hasOwnProperty(item.name) && values[item.name] !== 0) {
                                                    treeSelect.checkNode('tree', values[item.name]);
                                                }
                                            }
                                        });
                                    });
                                }
                                break;
                            case "select":
                                if (item.hasOwnProperty('dict')) {
                                    let instance = $('.hex-modal .' + item.name);
                                    this.getDict(item.dict, res => {
                                        res.data.forEach(s => {
                                            instance.append(' <option value="' + s.id + '"  ' + (values.hasOwnProperty(item.name) ? (values[item.name] == s.id ? 'selected' : '') : '') + '>' + s.name + '</option>');
                                        });
                                        form.render();
                                    });
                                    if (item.hasOwnProperty('change')) {
                                        form.on('select(hex-' + item.name + ')', function (data) {
                                            item.change(data.value, data);
                                        });
                                    }
                                }
                                break;
                            case "icon":
                                layui.use(['iconPicker'], function () {
                                    var iconPicker = layui.iconPicker;
                                    //图标选择器
                                    iconPicker.render({
                                        // 选择器，推荐使用input
                                        elem: '.hex-modal .' + item.name,
                                        // 数据类型：fontClass/unicode，推荐使用fontClass
                                        type: 'fontClass',
                                        // 是否开启搜索：true/false，默认true
                                        search: true,
                                        // 是否开启分页：true/false，默认true
                                        page: true,
                                        // 每页显示数量，默认12
                                        limit: 16,
                                        // 每个图标格子的宽度：'43px'或'20%'
                                        cellWidth: 'calc(25% - 10px)',
                                        // 点击回调
                                        click: function (data) {
                                        },
                                        // 渲染成功后的回调
                                        success: function (d) {
                                        }
                                    });

                                    if (values.hasOwnProperty(item.name)) {
                                        try {
                                            iconPicker.checkIcon(item.name, values[item.name]);
                                        } catch (e) {
                                            iconPicker.checkIcon(item.name, 'layui-icon-water');
                                        }
                                    }
                                });
                                break;
                            case "treeCheckbox":
                                if (item.hasOwnProperty('dict')) {
                                    this.getDict(item.dict, res => {
                                        authtree.render('.hex-modal .' + item.name, res.data, {
                                            inputname: 'ids[]'
                                            , layfilter: 'lay-check-auth'
                                            , themePath: 'module/src/style/authtree/css/'
                                            , childKey: 'children'
                                            , valueKey: 'id'
                                            , 'theme': 'auth-skin-universal'
                                            , autowidth: true
                                            , openchecked: false
                                            , checkedKey: values.hasOwnProperty(item.name) ? values[item.name] : []
                                        });
                                    });
                                }
                                break;
                            case "checkbox":
                                if (item.hasOwnProperty('dict')) {
                                    let instance = $('.hex-modal .' + item.name);
                                    let val = [];
                                    if (values.hasOwnProperty(item.name)) {
                                        val = values[item.name];
                                    }
                                    this.getDict(item.dict, res => {
                                        res.data.forEach(s => {
                                            instance.append('<input type="checkbox" ' + (val.indexOf(s.id) !== -1 ? 'checked' : '') + ' value="' + s.id + '" name="' + item.name + '[]" title="' + s.name + '">\n');
                                        });
                                        form.render();
                                    });
                                }
                                break;
                            case "switch":
                                form.on('checkbox(switch-' + item.name + ')', function (res) {
                                    let value = res.elem.checked === true ? '1' : '0'
                                    $('.hex-modal input[name=' + item.name + ']').val(value);
                                });
                                break;
                            case 'image':
                                let opts = {
                                    elem: '.hex-modal .' + item.name
                                    , url: uploadUrl
                                    , accept: 'images' //只允许上传图片
                                    , acceptMime: 'image/*' //只筛选图片
                                    , done: res => {
                                        if (res.code === 200) {
                                            let imgInstance = $('.hex-modal .' + item.name + ' img');
                                            $('.hex-modal input[name=' + item.name + ']').val(res.data[uploadUrlName]);
                                            $('.hex-modal .' + item.name + ' button').hide();
                                            imgInstance.attr('src', (item.hasOwnProperty('viewUrl') ? item.viewUrl : '') + res.data[uploadUrlName]);
                                            imgInstance.show();
                                        }
                                        layer.msg(res.msg);
                                    }
                                    , progress: function (n) {
                                        var percent = n + '%';
                                        layer.msg(percent);
                                    }
                                };
                                if (item.token !== false) {
                                    opts.headers = {
                                        token: layui.data(setter.tableName)[setter.request.tokenName]
                                    };
                                }
                                upload.render(opts)
                                break;
                            case 'file':
                                let buttonSpanInstance = $('.hex-modal .' + item.name + ' button span');
                                let exts = item.hasOwnProperty('exts') ? item.exts : 'jpg|png|gif|bmp|jpeg|gz|zip|rar|doc|xlsx';
                                let acceptMime = item.hasOwnProperty('acceptMime') ? item.acceptMime : '/*';
                                let opt = {
                                    elem: '.hex-modal .' + item.name
                                    , url: uploadUrl
                                    , exts: exts
                                    , acceptMime: acceptMime
                                    , done: res => {
                                        if (res.code === 200) {
                                            $('.hex-modal input[name=' + item.name + ']').val(res.data[uploadUrlName]);
                                            buttonSpanInstance.html('上传成功');
                                        }
                                        layer.msg(res.msg);
                                    }
                                    , progress: function (n) {
                                        var percent = n + '%';
                                        buttonSpanInstance.html("请稍后,已上传:" + percent);
                                    }
                                };
                                if (item.token !== false) {
                                    opt.headers = {
                                        token: layui.data(setter.tableName)[setter.request.tokenName]
                                    };
                                }
                                upload.render(opt)
                                break;
                            case 'json':
                                objectContainer[item.name] = new JSONEditor(document.getElementsByClassName('hex-modal')[0].getElementsByClassName(item.name)[0], {});
                                if (values.hasOwnProperty(item.name)) {
                                    if (typeof (values[item.name]) === "object") {
                                        objectContainer[item.name].set(values[item.name]);
                                    } else {
                                        objectContainer[item.name].set(JSON.parse(values[item.name]));
                                    }
                                }
                                break;
                            case 'date':
                                laydate.render({
                                    elem: '.hex-modal .' + item.name,
                                    type: 'datetime'
                                });
                                break;
                        }
                    });
                    form.render();
                }
            });
        },
        query(elem, table, fields, done, values = {}) {
            let instance = $(elem);
            instance.append("<div class='hex-query-form' style='display: none;'></div>");
            instance.addClass('layui-form-item layui-form');

            instance.append('<style>\n' +
                '    ' + elem + ' .layui-input, ' + elem + ' .layui-form-select dl dd {\n' +
                '        height: 30px;\n' +
                '    }\n' +
                '</style>');

            let formHtml = $(elem + ' .hex-query-form'), boxesObject = {};


            fields.forEach(item => {
                //设置默认值
                if (!values.hasOwnProperty(item.name) && item.hasOwnProperty('default')) {
                    values[item.name] = item.default;
                }

                let width = item.hasOwnProperty('width') ? 'style="width:' + item.width + 'px;padding-top:10px;"' : 'padding-top:10px;';

                switch (item.type) {
                    case "input":
                        formHtml.append('<div class="layui-input-inline" ' + width + '>\n' +
                            '                            <input type="text" class="layui-input" placeholder="' + item.title + '" name="' + item.name + '" value="' + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '">\n' +
                            '                        </div>');
                        break;
                    case "date":
                        formHtml.append(' <div class="layui-input-inline" ' + width + '>\n' +
                            '        <input type="text" class="layui-input" name="' + item.name + '" placeholder="' + item.title + '"  value="' + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '">\n' +
                            '      </div>');

                        //渲染组件
                        laydate.render({
                            elem: elem + ' input[name=' + item.name + ']',
                            type: 'datetime'
                        });
                        break;
                    case "select":
                        formHtml.append('<div class="layui-input-inline" ' + width + '>\n' +
                            '                    <select name="' + item.name + '"><option value="">' + item.title + '</option></select>\n' +
                            '                        </div>');

                        //渲染组件
                        if (item.hasOwnProperty('dict')) {
                            let selectInstance = $(elem + ' select[name=' + item.name + ']');
                            this.getDict(item.dict, res => {
                                res.data.forEach(s => {
                                    selectInstance.append(' <option value="' + s.id + '"  ' + (values.hasOwnProperty(item.name) ? (values[item.name] === s.id ? 'selected' : '') : '') + '>' + s.name + '</option>');
                                });
                                form.render();
                            });
                        }
                        break;
                    case "boxes":
                        formHtml.append('<div class="layui-input-inline" ' + width + '>\n' +
                            '                        <span class="' + item.name + '"></span>' +
                            '                        </div>');

                        if (item.hasOwnProperty('dict')) {
                            this.getDict(item.dict, res => {
                                var boxesData = [];
                                res.data.forEach(s => {
                                    boxesData.push({name: s.name, value: s.id});
                                });
                                boxesObject[item.name] = xmSelect.render({
                                    name: item.name,
                                    el: elem + ' .' + item.name,
                                    size: 'mini',
                                    style: {
                                        height: '28px'
                                    },
                                    language: 'zn',
                                    data: boxesData
                                });
                            });
                        }
                        break;
                }
            });

            formHtml.append('<div class="layui-inline" style="padding-top:10px;"><button type="button" class="layui-btn layui-btn-primary layui-btn-sm queryBtn">' +
                '<i class="layui-icon layui-icon-search"></i>查询</button></div>');


            instance.append('<div style="text-align: center;width: 100%;margin-top:10px;cursor: pointer;display: none;" class="hide"><i class="layui-icon layui-icon-up" title="关闭查询"></i></div>');
            instance.append('<div style="text-align: center;width: 100%;cursor: pointer;" class="show"><i class="layui-icon layui-icon-down" title="查询"></i></div>');

            form.render();

            //监听查询按钮
            $(elem + ' .queryBtn').click(res => {
                let serialize = this.paramsToJSONObject(instance.serialize());
                /*                fields.forEach(item => {
                                    switch (item.type) {
                                        case "boxes":
                                            let list = [];
                                            let val = boxesObject[item.name].getValue();
                                            val.forEach(x => {
                                                list.push(x.value);
                                            });
                                            if (list.length > 0) {
                                                serialize[item.name] = list;
                                            } else {
                                                serialize[item.name] = "";
                                            }
                                            break;
                                    }
                                });
                                console.log(serialize);*/

                table.reload({
                    where: serialize
                });
            });

            //监听查询按钮
            $(elem + ' .show').click(function () {
                formHtml.slideDown(100);
                $(this).hide();
                $(elem + ' .hide').show();
            });

            $(elem + ' .hide').click(function () {
                formHtml.slideUp(100);
                $(this).hide();
                $(elem + ' .show').show();
            });
        },
        getDict(dict, done) {
            let data = localStorage.getItem(dict);
            if (data) {
                done(JSON.parse(data));
                return;
            }
            admin.req({
                url: '/system/dict/getDict',
                method: "post",
                data: {dict: dict},
                done: res => {
                    localStorage.setItem(dict, JSON.stringify(res));
                    done(res);
                }
            });
        },
        getDictSync(dict) {
            let data = localStorage.getItem(dict);
            if (data) {
                return JSON.parse(data);
            }
            $.ajaxSettings.async = false;
            admin.req({
                url: '/system/dict/getDict',
                method: "post",
                data: {dict: dict},
                done: res => {
                    data = res;
                }
            });
            $.ajaxSettings.async = true;
            localStorage.setItem(dict, JSON.stringify(data));
            return data;
        },
        getDictNameSync(dict, value) {
            let dictSync = this.getDictSync(dict);
            let name = "无";
            dictSync.data.forEach(item => {

                if (item.id == value) {
                    name = item.name;
                }
            });
            return name;
        },
        getConfig(key, done) {
            admin.req({
                url: '/system/config/getPrivatelyConfig',
                method: "post",
                data: {key: key},
                done: done
            });
        },
        getConfigSync(key) {
            let data = [];
            $.ajaxSettings.async = false;
            admin.req({
                url: '/system/config/getPrivatelyConfig',
                method: "post",
                data: {key: key},
                done: res => {
                    data = res.data;
                }
            });
            $.ajaxSettings.async = true;
            return data;
        },
        paramsToJSONObject(url) {
            var hash;
            var myJson = {};
            var hashes = url.slice(url.indexOf('?') + 1).split('&');
            for (var i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                if (hash[0].indexOf("[]") !== -1) {
                    if (!myJson.hasOwnProperty(hash[0])) {
                        myJson[hash[0]] = [];
                    }
                    myJson[hash[0]].push(hash[1]);
                } else {
                    myJson[hash[0]] = hash[1];
                }

            }
            return myJson;
        },
        setIdMap(array) {
            if (array === undefined) {
                return;
            }
            array.forEach(item => {
                this.property.mapId[item.id] = item;
            });
        },
        getIdMap(id) {
            return this.property.mapId[id];
        },
        getMapItem(obj) {
            return this.getIdMap(hex.getObjectId(obj))
        },
        getObjectId(obj) {
            return $(obj).attr('data-id');
        },
        listObjectToArray(list) {
            let ids = [];

            list.forEach(function (item, index) {
                ids.push(item.id);
            });

            return ids;
        },
        deleteBatchEntity(url, list, done = null) {
            if (list.length === 0) {
                layer.msg("你没有选中任何数据（＾▽＾）");
                return;
            }
            layer.confirm('您确认要删除吗，这是不可恢复的哦 (。・・)ノ', {
                btn: ['确认', '取消']
            }, function () {
                admin.req({
                    url: url,
                    method: "POST",
                    data: {
                        list: list
                    },
                    done: res => {
                        layer.msg(res.msg);
                        if (done != null) {
                            done(res);
                        }
                    }
                });

            });
        },
        tableChange(saveUrl, id, field, value) {
            let json = {}
            json[field] = value;
            json.id = id;
            admin.req({
                url: saveUrl,
                data: json,
                method: "post",
                done: res => {
                    layer.msg(res.msg);
                }
            });
        },
        renderTable(filter, url, elem, cols, done, saveUrl = null, height = null) {
            let tableInstance, self = this;

            //注册全局修改事件
            window.tableChange = function (saveUrl, id, field, value) {
                self.tableChange(saveUrl, id, field, value);
            };

            //注册字典
            for (let i = 0; i < cols[0].length; i++) {
                let field = cols[0][i].field, dictType = cols[0][i].dictType, title = cols[0][i].title,
                    url = cols[0][i].hasOwnProperty('url') ? cols[0][i].url : '';

                if (cols[0][i].hasOwnProperty('dict') && dictType !== undefined) {
                    let dict = this.getDictSync(cols[0][i].dict);
                    this.property.tableDict[field] = dict.data;
                    cols[0][i].templet = function (item) {

                        let id = 0, s;

                        self.property.tableDict[field].forEach(dt => {
                            if (item[field] == dt.id) {
                                id = dt.id;
                            }
                        });

                        switch (dictType) {
                            case "select":
                                s = '<select lay-ignore style="border:1px solid rgba(109,165,189,0.2);border-radius: 35px;font-size: 12px;padding-bottom: 2px;padding-left: 2px;color: #58bdb8;appearance:none; outline: none;" onchange="tableChange(\'' + saveUrl + '\' ,\'' + item.id + '\',\'' + field + '\',this.value )">';
                                self.property.tableDict[field].forEach(x => {
                                    s += '<option value="' + x.id + '" ' + (id === x.id ? 'selected' : '') + '>' + x.name + '</option>';
                                });
                                s += '</select>';
                                break;
                            case "boxes":
                                s = '<span class="' + filter + '-' + field + '-' + item.id + '"></span>';
                                break;
                            default:
                                self.property.tableDict[field].forEach(x => {
                                    if (x.id == item[field]) {
                                        s = x.name;
                                    }
                                });

                                if (!s) {
                                    s = '无';
                                }

                            //   s = self.property.tableDict[field][item[field]].name;
                        }
                        return s;
                    }.bind(field);
                } else if (!cols[0][i].hasOwnProperty('dict') && dictType !== undefined) {
                    cols[0][i].templet = function (item) {
                        let s;
                        switch (dictType) {
                            case "switch":
                                s = '<input type="checkbox" lay-filter="' + filter + '-checkbox" title="' + title + '" data-field="' + field + '" data-id="' + item.id + '" ' + (item[field] === 1 ? "checked" : "") + '>';
                                break;
                            case "image":
                                s = '<img class="' + filter + '-image" style="height: 100%;cursor: pointer;" src="' + url + item[field] + '" data-id="' + item.id + '">';
                                break;
                            case "video":
                                s = '<span style="cursor:pointer;" class="' + filter + '-video" data-id="' + item.id + '" data-src="' + url + item[field] + '"><i class="layui-icon layui-icon-video"></i></span>';
                                break;
                        }
                        return s;
                    }.bind(field);
                } else if (cols[0][i].hasOwnProperty('action')) {
                    cols[0][i].templet = function (res) {
                        let s = '';
                        cols[0][i].action.forEach(item => {
                            switch (item.type) {
                                case "button":
                                    s += '<button class="layui-btn layui-btn-xs ' + item.class + '" data-id="' + res.id + '"><i class="layui-icon ' + item.icon + '"></i>' + item.title + '</button>';
                                    break;
                            }
                        });
                        return s;
                    }

                }
            }

            let options = {
                elem: elem
                , autoSort: false
                , method: "post"
                , url: url
                , limit: 15
                , page: true
                , headers: {
                    token: layui.data(setter.tableName)[setter.request.tokenName]
                }
                , cols: cols
                , response: {
                    statusCode: 200
                },
                done: result => {

                    //创建image监听器
                    $('.' + filter + '-image').click(function () {
                        var size = 400;
                        var imageUrl = $(this).attr('src')
                        layer.open({
                            type: 1,
                            title: false,
                            closeBtn: 0, //不显示关闭按钮
                            anim: 5,
                            area: size + 'px',
                            shadeClose: true, //开启遮罩关闭
                            content: '<img  src="' + imageUrl + '" style="width: ' + size + 'px;">'
                        });
                    });

                    //创建video监听器
                    $('.' + filter + '-video').click(function () {
                        var videoUrl = $(this).attr('data-src');
                        layer.open({
                            title: false,
                            type: 1,
                            skin: 'hex-watch-video',
                            closeBtn: 0,
                            anim: 1,
                            area: ['893px', '600px'],
                            shadeClose: true,
                            content: '<div style="width: 100%;height: 600px;padding: 0;margin: 0;border-radius: 20px;" class="' + filter + '-video-play"></div>',
                            success: res => {
                                const dp = new DPlayer({
                                    container: document.getElementsByClassName(filter + '-video-play')[0],
                                    video: {
                                        url: videoUrl,
                                        // type: 'hls'
                                    },
                                    autoplay: true,
                                    screenshot: true
                                });
                            }
                        });
                    });


                    //创建关联渲染监听器
                    let boxesField = [];
                    let cellGen = [];

                    for (let i = 0; i < cols[0].length; i++) {
                        let field = cols[0][i].field, dictType = cols[0][i].dictType;
                        switch (dictType) {
                            case "boxes":
                                boxesField.push(field);
                                cellGen.push(i);
                                break;
                        }
                    }


                    result.data.forEach(item => {

                        boxesField.forEach(field => {

                            let boxesData = [];

                            self.property.tableDict[field].forEach(x => {
                                let obj = {name: x.name, value: x.id};
                                boxesData.push(obj);
                            });

                            let initValue = [];

                            item[field].forEach(j => {
                                initValue.push(j.id);
                            });

                            xmSelect.render({
                                name: item.name,
                                el: "." + filter + "-" + field + "-" + item.id,
                                size: 'mini',
                                initValue: initValue,
                                style: {
                                    height: '28px'
                                },
                                language: 'zn',
                                data: boxesData,
                                on: k => {
                                    let s = [];
                                    k.arr.forEach(item => {
                                        s.push(item.value);
                                    });
                                    tableChange(saveUrl, item.id, field, s);
                                }
                            });

                            $("." + filter + "-" + field + "-" + item.id + ' xm-select').click(function () {
                                cellGen.forEach(i => {
                                    $('.laytable-cell-1-0-' + i).css("overflow", "unset");
                                });
                            });
                        });
                    });

                    this.setIdMap(result.data);
                    if (done) {
                        done(result);
                    }
                }
            };

            if (height != null) {
                options.height = height;
            }

            tableInstance = table.render(options);

            //创建checkbox监听器
            form.on('checkbox(' + filter + '-checkbox)', function (obj) {
                let id = $(this).attr('data-id'), field = $(this).attr('data-field'),
                    value = obj.elem.checked === true ? '1' : '0';
                tableChange(saveUrl, id, field, value);
            });

            //创建edit监听器
            table.on('edit(' + filter + ')', function (obj) {
                let value = obj.value //得到修改后的值
                    , data = obj.data //得到所在行所有键值
                    , field = obj.field; //得到字段
                tableChange(saveUrl, data.id, field, value);
            });

            //监听自动排序
            table.on('sort(' + filter + ')', function (obj) {
                tableInstance.reload({
                    initSort: obj,
                    where: {
                        orderBy: obj.field,
                        orderType: obj.type
                    }
                });
            });

            return {instance: tableInstance, table: table};
        },
        getParam(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]);
            return null;
        },
        remoteViewOpen(url, title = '查看', area = ['700px', '450px']) {
            $.get(url, res => {
                layer.open({
                    title: title,
                    type: 1,
                    area: area,
                    anim: 5,
                    maxmin: true, //开启最大化最小化按钮
                    shadeClose: true,
                    content: res
                })
                ;
            });
        },
        set(key, val) {
            return localStorage.setItem(key, val);
        },
        get(key) {
            return localStorage.getItem(key);
        },
        video(videoPath, autoplay = true) {
            layer.open({
                title: false,
                type: 1,
                skin: 'hex-watch-video',
                closeBtn: 0,
                anim: 1,
                area: ['893px', '600px'],
                shadeClose: true,
                content: '<div style="width: 100%;height: 600px;padding: 0;margin: 0;border-radius: 20px;" class="hex-video-play"></div>',
                success: res => {
                    const dp = new DPlayer({
                        container: document.getElementsByClassName('hex-video-play')[0],
                        video: {
                            url: videoPath,
                            type: 'auto'
                        },
                        autoplay: autoplay,
                        screenshot: true
                    });
                }
            });
        }
    }

    exports('hex', hex);
});