<template>
    <div class="white-box clearfix">
        <components-notifications :typeInput="typeNotification" :textInput="textNotification">
        </components-notifications>
        <div class="loading-roles" v-if="loading">
            <components-loading-page></components-loading-page>
        </div>
        <div class="col-md-6 col-lg-4 col-sm-12 col-xs-12">
            <div class="form-material">
                <div class="form-group col-md-12" >
                    <label class="col-md-12">{{ $t("admin.label.name") }}<span style="color: red; margin-left: 10px">*</span></label>
                    <div class="col-md-12">
                        <input type="text" v-model="name" class="form-control form-control-line" v-validate="'required|max:255'" placeholder="Mời bạn nhập tên" name="name">
                        <span v-show="errors.has('name')" class="help is-danger" style="color: red">{{ $t("admin.error.name_2") }}</span>
                    </div>
                </div>
                <div class="form-group col-md-12" >
                    <label for="example-code" class="col-md-12">{{ $t("admin.label.router_link") }}<span style="color: red;margin-left: 10px">*</span></label>
                    <div class="col-md-12">
                        <input type="text" v-model="router_link" class="form-control form-control-line" v-validate="'required'" placeholder="Mời bạn nhập url" name="router_link" id="example-code"> 
                        <span v-show="errors.has('router_link')" class="help is-danger" style="color: red">{{ $t("admin.error.router_link") }}</span>
                    </div>
                </div>
                <div class="col-md-12" style="margin-bottom: 20px;padding-left: 30px; padding-right: 30px">
                    <label class="typo__label">{{ $t("admin.label.parent_menu") }}</label>
                    <multiselect v-model="parent" :options="menus" :searchable="true" track-by="id" label="name"
                                  name="parent"
                                 :show-labels="false" :placeholder="$t('admin.user.input_menu')"
                    >
                     <template slot-scope="props" slot="option">
                <div>
                  <span>{{ props.option.name }}</span>
                  <span
                    style="font-size: 14px; float: right; color: gray"
                    >{{ props.option.parent ? props.option.parent.name : "" }}</span
                  >
                </div>
              </template>
                    </multiselect>
                </div>
                <div class="form-group col-md-12">
                    <label class="col-md-12">{{ $t("admin.label.icon") }}<span style="color: red;margin-left: 10px">*</span><span style="margin-left: 10px"><i :class="fa_icon"></i></span></label>
                    <div class="col-md-12">
                        <input type="text" v-model="fa_icon" class="form-control form-control-line" v-validate="'required'" name="fa_icon" placeholder="Mời bạn nhập icon">
                        <span v-show="errors.has('fa_icon')" class="help is-danger" style="color: red">{{ $t("admin.error.fa_icon") }}</span>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label class="col-md-12">{{ $t("admin.label.group") }} (Dùng để gộp nhóm mega menu)</label>
                    <div class="col-md-12">
                        <input type="text" v-model="group" class="form-control form-control-line" placeholder="Mời bạn nhập nhóm">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label class="col-md-12">{{ $t("admin.label.order") }}<span style="margin-left: 10px;color: red">*</span></label>
                    <div class="col-md-12">
                        <input type="number" min="1" step="1" v-model="order" class="form-control form-control-line" v-validate="'required'" name="order" placeholder="Mời bạn nhập thứ tự">
                        <span v-show="errors.has('order')" class="help is-danger" style="color: red">{{ $t("admin.error.order") }}</span>                    
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="radio-list col-md-12">
                        <label class="radio-inline p-0 ">
                            <div class="radio radio-info ">
                                <input type="radio" v-model="inactive" name="radio" id="radio1" value="false">
                                <label for="radio1">Đang sử dụng</label>
                            </div>
                        </label>
                        <label class="radio-inline">
                            <div class="radio radio-info col-md-12" >
                                <input type="radio" v-model="inactive" name="radio" id="radio2" value="true">
                                <label for="radio2">Không còn sử dụng</label>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-inverse m-r-20"  @click="cancelUpdateMenu()" type="button">{{ $t("admin.label.cancel") }}</button>
                        <button class="btn btn-success" @click="updateMenu()" type="button">{{ $t("admin.label.update") }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-5 col-sm-12 col-xs-12" v-if="this.getrole.length != 0">
            <div class="col-md-8" style="margin-bottom: 20px;padding-left: 30px; padding-right: 30px">
                <label class="typo__label">{{ $t("admin.label.role") }}</label>
                <multiselect @input="changeRole" v-model="role" :options="roles" :searchable="false" track-by="id" label="name"
                                name="role"
                                :show-labels="false" :placeholder="$t('admin.user.input_role')"
                ></multiselect>
                <span v-if="this.check" class="help is-danger" style="color: red">{{ $t("admin.error.role") }}</span>
                <span v-if="this.check_role" class="help is-danger" style="color: red">{{ $t("admin.error.role_unique") }}</span>
            </div>
            <div class="col-md-4" style="margin-top: 29px;padding-left: 30px; padding-right: 30px">
                <button class="btn btn-success" @click="addRole()" type="button">{{ $t("admin.label.add") }}</button>
            </div>
            <div class="col-md-12" style="padding-left: 30px; padding-right: 30px">
                <label class="typo__label">{{ $t("admin.menu.list_role") }}</label>
                <div class="scrollable">
                    <div class="table-responsive">
                        <table class="table table-hover contact-list footable footable-1 footable-paging footable-paging-center breakpoint-lg">
                            <thead>
                                <tr class="footable-header">
                                    <th class="text-center" style="width: 50px">{{ $t("admin.label.no") }}</th>
                                    <th style="display: table-cell;">{{ $t("admin.label.role")                                    }}
                                    </th>
                                    <th width="150" style="text-align: center">{{ $t("admin.label.manage") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in getrole" style="cursor: context-menu;" :key="item.id">
                                    <td class="position-relative"><span
                                            class="position-absolute text-center-td">{{(page - 1)*3 + index + 1}}</span></td>
                                    <td class="position-relative">
                                        <div class="row position-absolute center-vertical">
                                            <div class="col-md-9">
                                                {{item.role?item.role.name:item.name}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
                                            @click="deleteRole(item.id)"><i class="ti-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot v-if="count > 1">
                                <tr class="footable-paging">
                                    <td colspan="6">
                                        <paginate v-model="page" :page-count="this.count" :page-range="3"
                                            :margin-pages="1" :click-handler="clickCallback" :prev-text="'‹‹'"
                                            :next-text="'››'" :container-class="'pagination'"
                                            :page-class="'page-item'"></paginate>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-5 col-sm-12 col-xs-12" v-else>
            <components-loading-mini></components-loading-mini>
        </div>
    </div>
</template>
<script>
    import * as env from '../../../../env.js'
    import {endpoint, endpointhttp} from '../../../../env.js'
    import Multiselect from 'vue-multiselect'
    import {ValidationProvider} from 'vee-validate'

    export default {
        props: {
            value: {type: String},
        },
        components: {
            Multiselect,
            ValidationProvider
        },
        data: function() {
            return {
                parent: null,
                menus: [],
                id: null,
                name: null,
                parent_id: null,
                inactive: false,
                group: null,
                order: null,
                router_link: null,
                fa_icon: null,
                loading: false,
                action: true,
                role: null,
                role_id: null,
                roles: [],
                getrole: [],
                check_role: false,
                typeNotification: null,
                textNotification: null,
                check: false,
                page: 1,
                count: 0
            }
        },
        mounted() {
            this.getDefaultValue();
            this.getMenus();
            axios
                .get(env.endpointhttp + "roles/info")
                .then(response => {
                    this.roles = response.data.result.data;
                });  
            this.getRoleMenu();      
        },
        methods: {
            cancelUpdateMenu(){
                window.location.href = "/menus";
            },
            updateMenu() {
                this.$validator.validate().then(valid => {
                    if (this.action == true) {
                        this.loading = true;
                        this.action = false;
                        axios
                            .post(endpointhttp + 'menus/update/' + this.id, {
                                name: this.name,
                                parent_id: (this.parent ? this.parent.id : ''),
                                inactive: this.inactive,
                                group: this.group,
                                order: this.order,
                                router_link: this.router_link,
                                fa_icon: this.fa_icon,
                            })
                            .then(
                                response => {
                                    if (response.data.message == "message.success") {
                                        this.typeNotification = 2;
                                        this.textNotification = "Cập nhật thành công.";
                                        window.location.href = "/menus";
                                        this.action = true;
                                    } else {
                                        this.action = true;
                                        this.typeNotification = 1;
                                        this.textNotification = "Cập nhật không thành công.";
                                    }
                                }
                            )
                            .catch(error => {
                                this.action = true;
                                this.typeNotification = 1;
                                this.textNotification = "Cập nhật không thành công.";
                            })
                            .finally(() => {
                                this.loading = false;
                                this.typeNotification = null;
                                this.textNotification = null;
                            });
                    }
                });
            },

            addRole() {
                if(!this.role) {
                    this.check = true
                }
                else {
                    this.loading = true
                    axios.post(endpointhttp + 'rolesmenu/add/' + this.id, {
                        role_id: this.role.id,
                    })     
                    .then(
                        response => {
                            if(response.data.message == "message.success") {
/*                                 if(this.rolesmenu.findIndex(item=>item.id == this.role.id) == -1){
                                    this.rolesmenu.push(this.role);
                                } */
                                this.getRoleMenu();
                                this.loading = false;
                                this.role = null
                            }
                            else{
                                this.loading = false
                                this.check_role = true;
                                this.role = null
                            }
                        }
                    ) 
                }
            },

            deleteRole(value) {
                this.loading = true;
                axios
                    .delete(endpointhttp + 'rolesmenu/delete/' + value, {
                    })
                    .then(
                        response => {
                            if (response.data.message == "message.success") {
                                this.loading = false;
                                this.action = true;
                                this.getRoleMenu();
                                this.typeNotification = 2;
                                this.textNotification = "Bạn đã xoá thành công.";
                            } else {
                                this.loading = false;
                                this.action = true;
                                this.typeNotification = 1;
                                this.textNotification = "Đã có lỗi khi thực hiện tác vụ này.";
                            }
                        }
                    )
                    .catch(error => {
                        this.loading = false;
                        this.action = true;
                        this.typeNotification = 1;
                        this.textNotification = "Đã có lỗi khi thực hiện tác vụ này.";
                    })
                    .finally(() => {
                        this.loading = false;
                        this.typeNotification = null;
                        this.textNotification = null;
                    });
            },

            getDefaultValue(){
                let data = JSON.parse(this.value);
                console.log("this.value", this.value);
                if (this.value){
                    this.id = data[0].id;
                    this.name = data[0].name;
                    this.parent_id = data[0].parent_id;
                    this.inactive = data[0].inactive;
                    this.group = data[0].group;
                    this.order = data[0].order;
                    this.router_link = data[0].router_link;
                    this.fa_icon = data[0].fa_icon;
                }
            },
            getMenus() {
                axios.get(env.endpointhttp + "menus/info?perpage=9999")
                    .then(response => {
                        this.menus = response.data.data.data;
                        for(let i in this.menus){
                            if(this.parent_id == this.menus[i].id){
                                this.parent = this.menus[i];
                            }
                        }
                    });
            },
            changeRole: function() {
                this.check_role = false;
                this.check = false
            },
            getRoleMenu() {
                axios
                .get(env.endpointhttp + "rolesmenu/info/" + this.id)
                .then(response => {
                    this.getrole = response.data.getrole.data;
                    this.count = response.data.getrole.last_page;
                });
            },
            clickCallback(pageNum) {
                this.page = pageNum
                axios
                .get(env.endpointhttp + "rolesmenu/info/" + this.id, {
                    params: {
                        page: this.page,
                    }
                })
                .then(response => {
                    this.getrole = response.data.getrole.data;
                    this.count = response.data.getrole.last_page;
                })
                .catch(error => {
                    console.log(error);
                })
                .finally();
            }
        }
    }
</script>

