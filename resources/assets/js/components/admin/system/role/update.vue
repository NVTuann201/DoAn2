<template>
    <div class="white-box clearfix">
        <components-notifications :typeInput="typeNotification" :textInput="textNotification">
        </components-notifications>
        <div class="loading-roles" v-if="loading">
            <components-loading-page></components-loading-page>
        </div>
        <div class="col-md-5 col-xs-12 col-sm-8 col-lg-4">
            <div class="form-material">
                <div class="form-group col-md-6" >
                    <label class="col-md-12">{{ $t("admin.label.name") }}<span style="margin-left: 10px;color: red">*</span></label>
                    <div class="col-md-12">
                        <input type="text" v-model="name" v-validate="'required|max:255'" name="name" class="form-control form-control-line" placeholder="Mời bạn nhập tên">
                        <span v-show="errors.has('name')" class="help is-danger" style="color: red">{{ $t("admin.error.name_2") }}</span>                    
                    </div>
                </div>
                <div class="form-group col-md-6" >
                    <label for="example-code" class="col-md-12">{{ $t("admin.label.code") }}<span style="margin-left: 10px;color: red">*</span></label>
                    <div class="col-md-12">
                        <input type="text" v-model="code" v-validate="'required'" class="form-control form-control-line" placeholder="Mời bạn nhập mã" name="code" id="example-code"> 
                        <span v-show="errors.has('code')" class="help is-danger" style="color: red">{{ $t("admin.error.code") }}</span>                    
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label class="col-md-12">{{ $t("admin.label.description") }}</label>
                    <div class="col-md-12">
                        <input type="text" v-model="description" class="form-control form-control-line" placeholder="Mời bạn nhập mô tả"> 
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label class="control-label col-md-12" style="text-align: left;">{{ $t("admin.label.system") }}</label>
                    <div class="radio-list col-md-12">
                        <label class="radio-inline p-0 ">
                            <div class="radio radio-info ">
                                <input type="radio" v-model="system" name="radio" id="radio1" value="true">
                                <label for="radio1">Hệ thống</label>
                            </div>
                        </label>
                        <label class="radio-inline">
                            <div class="radio radio-info col-md-12" >
                                <input type="radio" v-model="system" name="radio" id="radio2" value="false">
                                <label for="radio2">Thường</label>
                            </div>
                        </label>
                    </div>
                </div>                 
                <div class="form-group col-md-12">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-inverse m-r-20"  type="button" @click="setCancel()">{{ $t("admin.label.cancel") }}</button>
                        <button class="btn btn-success" @click="updateRole()" type="button">{{ $t("admin.label.update") }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-5 col-sm-12 col-xs-12" v-if="this.getmenu.length != 0">
            <div class="col-md-8" style="margin-bottom: 20px;padding-left: 30px; padding-right: 30px">
                <label class="typo__label">{{ $t("admin.label.menu") }}</label>
                <multiselect @input="changeMenu" v-model="menu" :options="menus" :searchable="true" track-by="id" label="name"
                                name="menu"
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
                <span v-if="this.check" class="help is-danger" style="color: red">{{ $t("admin.error.menu") }}</span>
                <span v-if="this.check_role" class="help is-danger" style="color: red">{{ $t("admin.error.menu_unique") }}</span>
            </div>
            <div class="col-md-4" style="margin-top: 29px;padding-left: 30px; padding-right: 30px">
                <button class="btn btn-success" @click="addMenu()" type="button">{{ $t("admin.label.add") }}</button>
            </div>
            <div class="col-md-12" style="padding-left: 30px; padding-right: 30px">
                <label class="typo__label">{{ $t("admin.role.list_menu") }}</label>
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
                                <tr v-for="(item, index) in getmenu" style="cursor: context-menu;">
                                    <td class="position-relative"><span
                                            class="position-absolute text-center-td">{{(page - 1)*3 + index + 1}}</span></td>
                                    <td class="position-relative">
                                        <div class="row position-absolute center-vertical">
                                            <div class="col-md-9">
                                                {{item.menu?item.menu.name:item.name}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
                                            @click="deleteMenu(item.id)"><i class="ti-trash"></i></button>
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
    import { ValidationProvider } from 'vee-validate'
    import Multiselect from 'vue-multiselect'

  export default {
    props: {
      value: {type: String},
    },
    components: {
      ValidationProvider,
      Multiselect
    },
    data: function() {
      return {
        id: null,
        code: null,
        name: null,
        system: null,
        description: null,
        colorRole: null,
        loading: false,
        action: true,
        typeNotification: null,
        textNotification: null,
        getmenu: [],
        menu: null,
        check: false,
        check_role: false,
        menus: [],
        page: 1,
        count: 0
      }
    },
    mounted() {
      axios.get(env.endpointhttp + "menus/info?perpage=9999")
        .then(response => {
          this.menus = response.data.data.data;
        })
      this.getDefaultValue();
      this.getRoleMenu();   
    },
    methods: {
      getDefaultValue(){
        let data = JSON.parse(this.value);
        if (this.value){
          this.id = data[0].id;
          this.name = data[0].name;
          this.code = data[0].code;
          this.description = data[0].description;
          this.system = data[0].system;
          this.colorRole = data[0].color;
        }
      },
      updateRole(){
        this.$validator.validateAll();
        if(this.action == true){
          this.loading = true;
          this.action = false;
          axios
            .post(endpointhttp + 'roles/update/' + this.id, {
              name: this.name,
              code: this.code,
              color: this.colorRole,
              description: this.description,
              system: ((this.system == "true"||this.system==true) ? true : false),
            })
            .then(
              response => {
                if (response.data.message == "message.success") {
                    this.typeNotification = 2;
                    this.textNotification = "Cập nhật thành công.";
                    window.location.href = "/roles";
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
            .finally(() => {this.loading = false; this.typeNotification = null; this.textNotification = null;});
        }
      },
      setCancel(){
        window.location.href = "/roles";
      },
      addMenu() {
          if(!this.menu) {
              this.check = true
          }
          else {
              this.loading = true
              axios.post(endpointhttp + 'rolesmenu/add/' + this.id, {
                  menu_id: this.menu.id,
              })     
              .then(
                  response => {
                      if(response.data.message == "message.success") {
                          this.getRoleMenu();
                          this.loading = false;
                          this.menu = null
                      }
                      else{
                          this.loading = false
                          this.check_role = true;
                          this.menu = null
                      }
                  }
              )
              .catch(error => {
                console.log(error)
              }) 
          }
      },
      deleteMenu(value) {
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
      changeMenu: function() {
          this.check_role = false;
          this.check = false
      },
      getRoleMenu() {
          axios
          .get(env.endpointhttp + "rolesmenu/info/" + this.id)
          .then(response => {
              this.getmenu = response.data.getmenu.data;
              this.count = response.data.getmenu.last_page;
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
              this.getmenu = response.data.getmenu.data;
              this.count = response.data.getmenu.last_page;
          })
          .catch(error => {
              console.log(error);
          })
          .finally();
      }
    }
  }
</script>

