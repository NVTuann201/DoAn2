<template>
    <div class="white-box clearfix">
        <components-notifications :typeInput="typeNotification" :textInput="textNotification">
        </components-notifications>
        <div class="loading-roles" v-if="loading">
            <components-loading-page></components-loading-page>
        </div>
        <div class="col-md-6 col-lg-5 col-sm-12 col-xs-12">
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
                                 :show-labels="false" :placeholder="$t('admin.user.input_role')"
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
                        <button class="btn btn-inverse m-r-20"  @click="cancelAddMenu()" type="button">{{ $t("admin.label.cancel") }}</button>
                        <button class="btn btn-success" @click="addMenu()" type="button">{{ $t("admin.label.add") }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import * as env from '../../../../env.js'
    import {endpoint, endpointhttp} from '../../../../env.js'
    import Multiselect from 'vue-multiselect'
    import {ValidationProvider} from 'vee-validate'

    export default {
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
                typeNotification: null,
                textNotification: null
            }
        },

        mounted() {
            this.getMenus();
        },

        methods: {
            cancelAddMenu() {
                window.location.href = '/menus'
            },
            addMenu() {
                this.$validator.validateAll();
                if (this.action == true) {
                    this.loading = true;
                    this.action = false;
                    axios
                        .post(endpointhttp + 'menus/add', {
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
                                    this.textNotification = "Thêm mới thành công.";
                                    window.location.href = "/menus";
                                    this.action = true;
                                } else {
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
                        .finally(() => {
                            this.loading = false;
                            this.typeNotification = null;
                            this.textNotification = null;
                        });
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
        }
    }
</script>