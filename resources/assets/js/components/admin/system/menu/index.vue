<template>
    <div class="white-box p-0">
        <!-- .left-right-aside-column-->
        <div class="page-aside">
            <components-loading-page v-if="loading"></components-loading-page>
            <components-notifications :typeInput="typeNotification" :textInput="textNotification">
            </components-notifications>
            <!-- /.left-aside-column-->
            <div class="right-aside" style="margin-left: 0px">
                <div class="right-page-header">
                    <button type="button" class="btn btn-info btn-rounded" @click="goAddMenu()">Thêm mới menu</button>
                    <div class="pull-right">
                        <input type="text" :placeholder="$t('admin.placeholder.search_menu')" class="form-control" v-model="search" v-on:keyup.enter="getMenus()">
                    </div>
                    <div class="clearfix"></div>
                    <div class="scrollable">
                        <div class="table-responsive">
                            <table class="table m-t-30 table-hover contact-list footable footable-1 footable-paging footable-paging-center breakpoint-lg">
                                <thead>
                                <tr class="footable-header">
                                    <th class="text-center" style="display: table-cell; width: 150px">{{ $t("admin.label.no") }}</th>
                                    <th style="display: table-cell;">{{ $t("admin.label.name")}}</th>
                                    <th style="display: table-cell;">{{ $t("admin.label.parent_menu")}}</th>
                                    <th style="display: table-cell; width: 150px">{{ $t("admin.label.icon")}}</th>
                                    <th style="display: table-cell;width: 150px">{{ $t("admin.label.inactive")}}</th>
                                    <th width="150" style="text-align: center">{{ $t("admin.label.manage")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-if="!menus">
                                    <td colspan="6">
                                        <components-loading></components-loading>
                                    </td>
                                </tr>
                                <tr v-if="menus && menus.length == 0">
                                    <td colspan="6" class="emptyInfomation">
                                        <h5>{{ $t("admin.error.no_data") }}</h5>
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in menus" v-if="menus" style="cursor: context-menu;">
                                    <td class="position-relative"><span class="position-absolute text-center-td">{{(page - 1)*10 + index + 1}}</span></td>
                                    <td class="position-relative">
                                        <div class="row position-absolute center-vertical">
                                            <div style="white-space: initial">
                                                <span class="text-muted">{{item.name}}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="position-relative">
                                        <span class="position-absolute center-vertical">
                                            {{item.parent ? item.parent.name : item.parent}}
                                        </span>
                                    </td>
                                    <td class=" position-relative">
                                        <span><i :class="item.fa_icon"></i></span>
                                    </td>
                                    <td class=" position-relative">
                                        <span class="label label-danger position-absolute text-center-td"
                                              v-if="item.inactive">Ngừng
                                            hoạt động</span>
                                        <span class="label label-info position-absolute text-center-td" v-else>Đang
                                            hoạt động</span>
                                    </td>
                                    <td>
                                        <button type="button" @click="editMenu(item.id)" class="btn btn-info btn-outline btn-circle btn-lg m-r-5"><i
                                                class="ti-pencil-alt"></i></button>
                                        <button type="button" class="btn btn-info btn-outline btn-circle btn-lg m-r-5" @click="showModal(item.id)"><i
                                                 class="ti-trash"></i></button>
                                    </td>
                                    <div class="modal" id="myModal">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">                      
                                                <div class="modal-header">
                                                    <div class="col-md-10">
                                                        <label class="modal-title">Xác nhận xóa menu</label>                                                    
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="col-md-6" style="text-align: left;">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="button" class="btn btn-danger" @click="deleteMenu(getId)" data-dismiss="modal">Xóa</button>                                                    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                                </tbody>
                                <tfoot v-if="count>1">
                                <tr class="footable-paging">
                                    <td colspan="6">
                                        <paginate
                                                v-model="page"
                                                :page-count="this.count"
                                                :page-range="3"
                                                :margin-pages="2"
                                                :click-handler="clickCallback"
                                                :prev-text="'‹‹'"
                                                :next-text="'››'"
                                                :container-class="'pagination'"
                                                :page-class="'page-item'"
                                        ></paginate>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- .left-aside-column-->
            </div>
            <!-- /.left-right-aside-column-->
        </div>
    </div>
</template>

<script>
    import * as env from '../../../../env.js'
    import {endpoint, endpointhttp} from '../../../../env.js'

    export default {
        data: function () {
            return {
                menus: [],
                search: null,
                typeNotification: null,
                textNotification: null,
                roles: null,
                organization: null,
                page: 1,
                count: 0,
                search_role: null,
                loading: false,
                getId: null
            }
        },
        mounted() {
            this.getMenus();
        },
        methods: {
            editMenu(value){
                window.location.href = "/menus/update/" + value;
            },
            deleteMenu(value){
                this.loading = true;
                axios
                    .delete(endpointhttp + 'menus/delete/' + value)
                    .then(
                        response => {
                            if (response.data.message == "message.success") {
                                this.typeNotification = 2;
                                this.textNotification = "Xóa thành công.";
                                this.getMenus();
                                this.loading = false;
                            } else {
                                this.typeNotification = 1;
                                this.textNotification = "Xóa không thành công.";
                                this.loading = false;
                            }
                        }
                    )
                    .catch(error => {
                        this.typeNotification = 1;
                        this.textNotification = "Xóa không thành công.";
                        this.loading = false;
                    })
                    .finally(() => {
                        this.loading = false;
                        this.typeNotification = null;
                        this.textNotification = null;
                    });
            },

            getMenus() {
                axios.get(env.endpointhttp + "menus/info", {
                    params: {
                        search: this.search,
                    }
                })
                    .then(response => {
                        this.menus = response.data.data.data;
                        console.log("this.menus::", this.menus);
                        this.count = response.data.data.last_page;
                    });
            },
            clickCallback(pageNum) {
                this.page = pageNum;
                axios
                    .get(env.endpointhttp + "menus/info", {
                        params: {
                            search: this.search,
                            page: this.page,
                        }
                    })
                    .then(response => {
                        this.menus = response.data.data.data;
                        console.log("this.menus::", this.menus);
                        this.count = response.data.data.last_page;
                    })
                    .catch(error => {
                        console.log(error);
                    })
                    .finally();
            },
            goAddMenu() {
                window.location.href = "menus/add";
            },
            showModal(value) {
                this.getId = value;
                $("#myModal").modal('show');
            }                                
        },
    }
</script>