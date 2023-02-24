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
                        <button class="btn btn-success" @click="addRole()" type="button">{{ $t("admin.label.create_new") }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import * as env from '../../../../env.js'
    import {endpoint, endpointhttp} from '../../../../env.js'
    import { ValidationProvider } from 'vee-validate'

  export default {
    components: {
      ValidationProvider
    },
    data: function() {
      return {
        roles: [],
        code: null,
        name: null,
        system: false,
        description: null,
        colorRole: 'danger',
        loading: false,
        action: true,
        typeNotification: null,
        textNotification: null,
      }
    },
    mounted() {
      axios.get(env.endpointhttp + "roles")
        .then(response => {
          this.roles = response.data.result;
        });
    },
    methods: {
      addRole(){
        this.$validator.validateAll();
        if(this.action == true){
          this.loading = true;
          this.action = false;
          axios
            .post(endpointhttp + 'roles/add', {
              name: this.name,
              code: this.code,
              color: this.colorRole,
              description: this.description,
              system: ((this.system == "true") ? true : false),
            })
            .then(
              response => {
                if(response.data.message == "message.success"){
                  window.location.href = "/roles";
                  this.action = true;
                  this.typeNotification = 2;
                  this.textNotification = "Thêm mới thành công.";

                }else{
                  this.action = true;
                  this.typeNotification = 1;
                  this.textNotification = "Thêm mới không thành công.";
                }
              }
            )
            .catch(error => {
              this.action = true;
              this.typeNotification = 1;
              this.textNotification = "Thêm mới không thành công.";
            })
            .finally(() => {this.loading = false; this.typeNotification = null; this.textNotification = null;});
        }
      },
      setCancel(){
        window.location.href = "/roles";
      }
    }
  }
</script>

