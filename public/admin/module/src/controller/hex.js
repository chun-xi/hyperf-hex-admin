layui.define(['treeSelect', 'layer', 'jquery', 'form', 'admin', 'setter', 'table', 'authtree', 'upload', 'laydate'], function (exports) {
    let layer = layui.layer, $ = layui.jquery, form = layui.form, admin = layui.admin, treeSelect = layui.treeSelect,
        setter = layui.setter, table = layui.table, authtree = layui.authtree, upload = layui.upload,
        laydate = layui.laydate;

    let hex = {
        property: {
            mapId: {}
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
                            '                <input name="' + item.name + '" placeholder="' + item.placeholder + '" type="text" class="layui-input" value="' + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '"/>' +
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
                            '                    <input type="checkbox" lay-filter="switch-' + item.name + '" value="1" title="' + item.text + '" ' + (values.hasOwnProperty(item.name) ? (values[item.name] === 1 ? 'checked' : '') : '') + '>\n' +
                            '                </div>\n' +
                            '            </div>';
                        break;
                    case 'select':
                        d += '<div class="layui-form-item">\n' +
                            '                <label class="layui-form-label">' + item.title + '</label>\n' +
                            '                <div class="layui-input-block">\n' +
                            '                    <select class="' + item.name + '" name="' + item.name + '"><option value="">' + item.placeholder + '</option></select>\n' +
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
                            '    <div class="layui-input-block ' + item.name + '"><img src="' + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '" style="margin:3px;border-radius:5px;width: 100px;height: 100px;' + (values.hasOwnProperty(item.name) ? '' : 'display:none;') + '">\n' +
                            '    <button type="button" class="layui-btn layui-btn-primary" style="' + (values.hasOwnProperty(item.name) ? 'display:none;' : '') + '"><i class="layui-icon layui-icon-picture"></i>' + item.placeholder + '</button >\n' +
                            '    </div>\n' +
                            '  </div>';
                        break;
                    case 'file':
                        d += '<div class="layui-form-item" pane=""><input type="hidden" name="' + item.name + '" value="' + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '">\n' +
                            '    <label class="layui-form-label">' + item.title + '</label>\n' +
                            '    <div class="layui-input-block ' + item.name + '">\n' +
                            '    <button type="button" class="layui-btn layui-btn-primary" style="' + (values.hasOwnProperty(item.name) ? 'display:none;' : '') + '"><i class="layui-icon layui-icon-file-b"></i><span>' + item.placeholder + '</span></button >\n' +
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
                    console.log(paramsToJSONObject);
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
                        switch (item.type) {
                            case "radio":
                                if (item.hasOwnProperty('dict')) {
                                    let instance = $('.hex-modal .' + item.name);
                                    this.getDict(item.dict, res => {
                                        res.data.forEach(s => {
                                            instance.append('<input name="' + item.name + '" type="radio" value="' + s.id + '" title="' + s.name + '" ' + (values.hasOwnProperty(item.name) ? (values[item.name] === s.id ? 'checked' : '') : '') + ' />');
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
                                            instance.append(' <option value="' + s.id + '"  ' + (values.hasOwnProperty(item.name) ? (values[item.name] === s.id ? 'selected' : '') : '') + '>' + s.name + '</option>');
                                        });
                                        form.render();
                                    });
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
                                upload.render({
                                    elem: '.hex-modal .' + item.name
                                    , url: '/system/other/upload'
                                    , headers: {
                                        token: layui.data(setter.tableName)[setter.request.tokenName]
                                    }
                                    , accept: 'images' //只允许上传图片
                                    , acceptMime: 'image/*' //只筛选图片
                                    , done: res => {
                                        if (res.code === 200) {
                                            let imgInstance = $('.hex-modal .' + item.name + ' img');
                                            $('.hex-modal input[name=' + item.name + ']').val(res.data.path);
                                            $('.hex-modal .' + item.name + ' button').hide();
                                            imgInstance.attr('src', res.data.path);
                                            imgInstance.show();
                                        }
                                        layer.msg(res.msg);
                                    }
                                    , progress: function (n) {
                                        var percent = n + '%';
                                        layer.msg(percent);
                                    }
                                })
                                break;
                            case 'file':
                                let buttonSpanInstance = $('.hex-modal .' + item.name + ' button span');
                                upload.render({
                                    elem: '.hex-modal .' + item.name
                                    , url: '/system/other/upload'
                                    , exts: 'jpg|png|gif|bmp|jpeg|gz|zip|rar|doc|xlsx'
                                    , headers: {
                                        token: layui.data(setter.tableName)[setter.request.tokenName]
                                    }
                                    , done: res => {
                                        if (res.code === 200) {
                                            $('.hex-modal input[name=' + item.name + ']').val(res.data.path);
                                            buttonSpanInstance.html('上传成功');
                                        }
                                        layer.msg(res.msg);
                                    }
                                    , progress: function (n) {
                                        var percent = n + '%';
                                        buttonSpanInstance.html("请稍后,已上传:" + percent);
                                    }
                                })
                                break;
                            case 'json':
                                objectContainer[item.name] = new JSONEditor(document.getElementsByClassName('hex-modal')[0].getElementsByClassName(item.name)[0], {});
                                if (values.hasOwnProperty(item.name)) {
                                    objectContainer[item.name].set(JSON.parse(values[item.name]));
                                }
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

            let formHtml = $(elem + ' .hex-query-form');


            fields.forEach(item => {
                //设置默认值
                if (!values.hasOwnProperty(item.name) && item.hasOwnProperty('default')) {
                    values[item.name] = item.default;
                }

                let width = item.hasOwnProperty('width') ? 'style="width:' + item.width + 'px"' : '';

                switch (item.type) {
                    case "input":
                        formHtml.append('<div class="layui-input-inline" ' + width + '>\n' +
                            '                            <input type="text" class="layui-input" placeholder="' + item.title + '" name="' + item.name + '" value="' + (values.hasOwnProperty(item.name) ? values[item.name] : '') + '">\n' +
                            '                        </div>');
                        break;
                    case "date":
                        formHtml.append(' <div class="layui-input-inline">\n' +
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
                }
            });

            formHtml.append('<button type="button" class="layui-btn layui-btn-primary layui-btn-sm queryBtn">' +
                '<i class="layui-icon layui-icon-search"></i>查询</button>');


            instance.append('<div style="text-align: center;width: 100%;margin-top:10px;cursor: pointer;display: none;" class="hide"><i class="layui-icon layui-icon-up" title="关闭查询"></i></div>');
            instance.append('<div style="text-align: center;width: 100%;cursor: pointer;" class="show"><i class="layui-icon layui-icon-down" title="查询"></i></div>');

            form.render();

            //监听查询按钮
            $(elem + ' .queryBtn').click(res => {
                let serialize = this.paramsToJSONObject(instance.serialize());
                table.reload({
                    where: serialize
                });
                console.log(serialize);
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
            admin.req({
                url: '/system/dict/getDict',
                method: "post",
                data: {dict: dict},
                done: done
            });
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
        renderTable(filter, url, elem, cols, done, saveUrl = null, enhanceListener = null) {
            let tableInstance;

            tableInstance = table.render({
                elem: elem
                , method: "post"
                , url: url
                , page: true
                , headers: {
                    token: layui.data(setter.tableName)[setter.request.tokenName]
                }
                , cols: cols
                , response: {
                    statusCode: 200
                },
                done: done
            });

            if (saveUrl !== null) {
                table.on('edit(' + filter + ')', function (obj) {
                    let value = obj.value //得到修改后的值
                        , data = obj.data //得到所在行所有键值
                        , field = obj.field; //得到字段

                    let json = {}
                    json[field] = value;
                    json.id = data.id;

                    admin.req({
                        url: saveUrl,
                        data: json,
                        method: "post",
                        done: res => {
                            layer.msg(res.msg);
                        }
                    });
                });
            }

            if (enhanceListener != null) {
                //增强监听器
                enhanceListener.forEach(item => {
                    switch (item.type) {
                        case "checkbox":
                            form.on('checkbox(' + item.filter + ')', function (obj) {
                                let id = hex.getObjectId(this), val = obj.elem.checked === true ? '1' : '0', json = {}
                                json[item.name] = val;
                                json.id = id;
                                admin.req({
                                    url: saveUrl,
                                    data: json,
                                    method: "post",
                                    done: res => {
                                        layer.msg(res.msg);
                                    }
                                });
                            });
                            break;
                    }
                });
            }

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
        }
    }

    exports('hex', hex);
});