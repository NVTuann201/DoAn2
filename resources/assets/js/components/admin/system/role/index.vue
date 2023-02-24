<template>
    <div class="white-box">
        <components-notifications :typeInput="typeNotification" :textInput="textNotification">
        </components-notifications>
        <div class="card-body">
            <a type="button" href="/roles/add" class="btn btn-info btn-rounded m-t-10 float-right">{{
                $t("admin.buttons.add") }}</a>
            <div class="table-responsive">
                <table class="table table-hover manage-u-table">
                    <thead>
                    <tr>
                        <th width="70" class="text-center">#</th>
                        <th>{{ $t("admin.label.code") }}</th>
                        <th>{{ $t("admin.label.name") }}</th>
                        <th>{{ $t("admin.label.description") }}</th>
                        <th class="text-center">{{ $t("admin.label.system") }}</th>
                        <th class="text-center">{{ $t("admin.label.inactive") }}</th>
                        <th width="150" style="text-align: center">{{ $t("admin.label.manage") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="!roles">
                        <td colspan="7">
                            <components-loading></components-loading>
                        </td>
                    </tr>
                    <tr v-if="roles && roles.length == 0">
                        <td colspan="7" class="emptyInfomation">
                            <h5>{{ $t("admin.error.no_data") }}</h5>
                        </td>
                    </tr>
                    <tr v-for="(item, index) in roles" v-if="roles" style="cursor: context-menu;">
                        <td class="text-center"><span style="line-height: 50px">{{(page - 1)*10 + index + 1}}</span></td>
                        <td><span style="line-height: 50px">{{item.code}}</span></td>
                        <td><span style="line-height: 50px">{{item.name}}</span></td>
                        <td><span style="line-height: 50px">{{item.description}}</span></td>
                        <td class=" text-center">
                            <span class="label label-danger " style="line-height: 50px"
                                  v-if="item.system">Hệ thống</span>
                            <span class="label label-info " style="line-height: 50px" v-else>Thường</span>
                        </td>
                        <td class="  text-center">
                            <span class="label label-danger " style="line-height: 50px" v-if="item.inactive">Không sử dụng</span>
                            <span class="label label-info  " style="line-height: 50px" v-else>Đang sử dụng</span>
                        </td>
                        <td>
                            <button type="button" style="display: none" class="btn btn-info btn-outline btn-circle btn-lg m-r-5"><i
                                    class="ti-key"></i></button>
                            <button type="button" class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
                                    @click="editRoles(item.id)"><i class="ti-pencil-alt"></i></button>
                            <button type="button" class="btn btn-info btn-outline btn-circle btn-lg m-r-5" @click="showModal(item.id)"><i
                                    class="ti-trash"></i></button>
                        </td>
                        <div class="modal" id="myModal">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">                      
                                    <div class="modal-header">
                                        <div class="col-md-10">
                                            <label class="modal-title">Xác nhận xóa role</label>                                                    
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
                                            <button type="button" class="btn btn-danger" @click="deleteRoles(getId)" data-dismiss="modal">Xóa</button>                                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                    </tbody>
                </table>
            </div>
            <footer v-if="count>1">
                <paginate v-model="page" :page-count="this.count" :page-range="3"
                    :margin-pages="2" :click-handler="clickCallback" :prev-text="'‹‹'"
                    :next-text="'››'" :container-class="'pagination'" :page-class="'page-item'">
                </paginate>
            </footer>
        </div>
    </div>
</template>

<script>
    import * as env from '../../../../env.js'

    export default {
        data: function () {
            return {
                typeNotification: null,
                textNotification: null,
                page: 1,
                count: 0,
                roles: null,
                loading: false,
                getId: null
            }
        },
        mounted() {
            this.getRoles();
        },
        methods: {
            getRoles() {
                axios.get(env.endpointhttp + "roles/info")
                    .then(response => {
                        this.roles = response.data.result.data;
                    });
            },
            editRoles(value) {
                window.location.href = "/roles/update/" + value;
            },
            deleteRoles(value) {
                this.loading = true;
                axios
                    .delete(env.endpointhttp + "roles/delete/" + value)
                    .then(
                        response => {
                            if(response.data.message = "message.success"){
                                this.action = true;
                                this.getRoles();
                                this.typeNotification = 2;
                                this.textNotification = "Xóa role thành công"
                            }
                            else{
                                this.action = true;
                                this.typeNotification = 1;
                                this.textNotification = "Xóa role không thành công"
                            }
                    })
                    .catch(error => {
                        this.action = true;
                        this.typeNotification = 1;
                        this.textNotification = "Xóa role không thành công.";
                    })
                    .finally(() => {
                        this.loading = false;
                        this.typeNotification = null;
                        this.textNotification = null;
                    });
                },
            showModal(value) {
                this.getId = value;
                $("#myModal").modal('show');
            },
            clickCallback(pageNum) {
                this.page = pageNum;
                axios
                    .get(env.endpointhttp + "role/info", {
                        params: {
                            page: this.page,
                        }
                    })
                    .then(response => {
                        console.log(response.data)
                        this.roles = response.data.result.data;
                        this.count = response.data.result.last_page;
                    })
                    .catch(error => {
                        console.log(error);
                    })
                    .finally();
            },                 
        }
    }
</script>

