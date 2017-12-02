;(function($, window, document, undefined) {

    $(document).ready(function() {

        search_tag = {};

        $('#zhishidian-termtree').on('nodeSelected', function(event, data) {
            //console.log(data);
            if ( !data.hasOwnProperty("nodes") ) {
                search_tag.zhishidian_tag = data.tags[0];   //zhishidian tag id
            }
        });

        $('.term-child').on('click', function(e) {
            //console.log($(this).attr('tid'));

            if ( $(this).hasClass("dropdown-toggle") ) {
                if ( $(this).hasClass("tag-clicked") ) {
                    $(this).removeClass("tag-clicked");
                    remove_tag_from_search($(this).attr("search-tag"));
                } else {
                    $(this).addClass("tag-clicked");
                    set_tag_for_search($(this).attr("search-tag"), $(this).attr('tid'));
                }

            } else {
                var button = $(this).closest("div.dropdown").find("button.dropdown-toggle");
                console.log($(this).html());
                button.html($(this).html());
                button.attr('tid', $(this).attr('tid'));

                if ( button.hasClass("tag-clicked") ) {
                    button.removeClass("tag-clicked");
                    remove_tag_from_search(button.attr("search-tag"));
                } else {
                    button.addClass("tag-clicked");
                    set_tag_for_search(button.attr("search-tag"), button.attr('tid'));
                }



            }

        });

        //search
        $('.search-submit').on('click', function(e) {

            console.log(search_tag);

            ajax_url = document.location.origin + "/yc/ajax-api";

            json_data = {
                search_tag: search_tag
            }
            $.ajax({
                method: "POST",
                url: ajax_url,
                data: JSON.stringify(json_data),  //for POST
            }).done(function( msg ) {

                show_search_content(msg.data);

                //console.log( "Data Saved: " + msg );

            }).fail(function( jqXHR, textStatus ) {

                alert( "Request failed: " + textStatus );

            });

        });

        function show_search_content(data) {

            $show_block = $('.search-content');
            $show_block.empty();

            console.log(data);
            if (data.length == 0) {
                $panel = $('<div>').attr({ 'class': "panel panel-warning" }).appendTo($show_block);
                $panel_body = $('<div>').attr({'class': "panel-body"}).html("没有搜索结果，请重新选择标签").appendTo($panel);
            } else {
                $.each(data, function(i, node) {

                    $panel = $('<div>').attr({ 'class': "panel panel-success" }).appendTo($show_block);
                    $panel_head = $('<div>').attr({ 'class': "panel-heading"}).html("试题 节点:" + i).appendTo($panel);
                    
                    $panel_body = $('<div>').attr({'class': "panel-body"}).html(node.neirong).appendTo($panel);
                    $panel_footer = $('<div>').attr({'class': "panel-footer"}).html(
                        '<a href="/node/' + i + '" class="btn btn-primary">查看</a>'
                    ).appendTo($panel);

                });
            }
        }

        function remove_tag_from_search(tag) {
            switch(tag) {
                case 'shiti-laiyuan-zhenti':
                    delete search_tag.shiti_laiyuan_zhenti_tag;
                    break;
                case 'shiti-laiyuan-moni':
                    delete search_tag.shiti_laiyuan_moni_tag;
                    break;
                case 'xiaowen-tixing':
                    delete search_tag.xiaowen_tixing_tag;
                    break;
                case 'xiaowen-nandu':
                    delete search_tag.xiaowen_nandu_tag;
                    break;
                case 'timianfenxi-biaoqian':
                    delete search_tag.timianfenxi_tag;
                    break;
                case 'jietifenxi-biaoqian':
                    delete search_tag.jietifenxi_tag;
                    break;
                case 'ganrao-xuanxiang':
                    delete search_tag.ganrao_xuanxiang_tag;
                    break;
                default:
                    console.log('error');
            }
        }

        function set_tag_for_search(tag, tag_id) {
                switch(tag) {
                    case 'shiti-laiyuan-zhenti':
                        search_tag.shiti_laiyuan_zhenti_tag = tag_id;
                        break;
                    case 'shiti-laiyuan-moni':
                        search_tag.shiti_laiyuan_moni_tag = tag_id;
                        break;
                    case 'xiaowen-tixing':
                        search_tag.xiaowen_tixing_tag = tag_id;
                        break;
                    case 'xiaowen-nandu':
                        search_tag.xiaowen_nandu_tag = tag_id;
                        break;
                    case 'timianfenxi-biaoqian':
                        search_tag.timianfenxi_tag = tag_id;
                        break;
                    case 'jietifenxi-biaoqian':
                        search_tag.jietifenxi_tag = tag_id;
                        break;
                    case 'ganrao-xuanxiang':
                        search_tag.ganrao_xuanxiang_tag = tag_id;
                        break;
                    
                    default:
                        console.log('error');
                }
        }

    });
}(jQuery, window, document));
