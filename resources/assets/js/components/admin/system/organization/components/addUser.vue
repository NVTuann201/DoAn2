<template>
    <div class="white-box clearfix no-padding">
        <components-notifications :typeInput="typeNotification" :textInput="textNotification">
        </components-notifications>
        <div class="loading-roles" v-if="loading">
            <components-loading-page></components-loading-page>
        </div>
        <div class="col-md-12 no-padding">
            <div class="form-material">
                <div class="form-group col-md-12 no-padding">
                    <label class="col-md-12">{{ $t("admin.user.name") }}<span style="color: red">*</span></label>
                    <div class="col-md-12">
                        <input type="text" v-model="name" name="name" class="form-control form-control-line" v-validate="'required'"
                               :placeholder="$t('admin.user.input_name')">
                    <span v-show="errors.has('name')" class="help is-danger" style="color: red">{{ $t("admin.error.name") }}</span>
                    </div>
                </div>
                <div class="form-group col-md-12 no-padding">
                    <label for="example-code" class="col-md-12">{{ $t("admin.user.username") }}<span style="color: red; margin-left: 5px">*</span></label>
                    <div class="col-md-12">
                        <input type="text" v-model="username" class="form-control form-control-line" v-validate="'required'"
                               :placeholder="$t('admin.user.input_username')" name="username" id="example-code">
                        <span v-show="errors.has('username')" class="help is-danger" style="color: red">{{ $t("admin.error.username") }}</span>
                    </div>
                </div>
                <div class="col-md-12 no-padding" style="margin-bottom: 25px">
                    <label class="col-md-12">{{ $t("admin.user.email") }}<span style="color: red">*</span></label>
                    <div class="col-md-12">
                        <input type="text" v-model="email" name="email" class="form-control form-control-line" v-validate="'required|email'"
                               :placeholder="$t('admin.user.input_email')">
                        <span v-show="errors.has('email')" class="help is-danger" style="color: red">{{ $t("admin.error.email") }}</span>
                    </div>
                </div>
                <div class="col-md-12 no-padding">
                    <label class="col-md-12">{{ $t("admin.user.birthday") }}</label>
                    <date-picker v-model="birthday" :config="optionsTime"
                                 style="padding-left: 15px; padding-right: 15px"></date-picker>
                </div>
                <div class="form-group col-md-12 no-padding">
                    <label class="col-md-12">{{ $t("admin.user.password") }}<span style="color: red; margin-left: 5px">*</span></label>
                    <div class="col-md-12">
                        <input type="password" v-model="password" class="form-control form-control-line" v-validate="'required'" name="password">
                        <span v-show="errors.has('password')" class="help is-danger" style="color: red">{{ $t("admin.error.password") }}</span>
                    </div>
                </div>
                <div class="form-group col-md-12 no-padding">
                    <label class="col-md-12">{{ $t("admin.user.check_password") }}<span style="color: red; margin-left: 5px">*</span></label>
                    <div class="col-md-12">
                        <input type="password" v-model="checkPassword" class="form-control form-control-line" v-validate="'required'"></div>
                </div>
                <div class="form-group col-md-12 no-padding">
                    <label class="col-md-12 ">{{ $t("admin.label.phone") }}</label>
                    <div class="col-md-12">
                        <input type="text" v-model="phone" class="form-control form-control-line"
                               :placeholder="$t('admin.user.input_phone')">
                    </div>
                </div>
                <div class="col-md-12 no-padding" style="padding: 15px">
                    <label class="typo__label">{{ $t("admin.label.role") }}<span style="color: red; margin-left: 5px">*</span></label>
                    <multiselect v-model="role" :options="roles" :searchable="false" track-by="id" label="name"
                                 v-validate="'required'" name="role"
                                 :show-labels="false" :placeholder="$t('admin.user.input_role')"
                    ></multiselect>
                    <span v-show="errors.has('role')" class="help is-danger" style="color: red">{{ $t("admin.error.role") }}</span>
                </div>
                <div class="form-group col-md-12 no-padding">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-inverse m-r-20" type="button" @click="setCancel()">{{ $t("admin.label.cancel") }}</button>
                        <button class="btn btn-success" @click="addUser()" type="button">{{
                            $t("admin.label.create_new") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import * as env from '../../../../../env.js'
    import {endpoint, endpointhttp} from '../../../../../env.js'
    import Multiselect from 'vue-multiselect'
    import { ValidationProvider } from 'vee-validate'

    export default {
        components: {
            Multiselect,
            ValidationProvider
        },
        props: {
            value: {type: String},
        },
        data: function () {
            return {
                roles: [],
                email: null,
                phone: null,
                name: null,
                username: null,
                system: null,
                description: null,
                loading: false,
                action: true,
                password: null,
                checkPassword: null,
                typeNotification: null,
                textNotification: null,
                role: null,
                options: ['list', 'of', 'options'],
                birthday: new Date(),
                optionsTime: {
                    format: 'DD/MM/YYYY',
                    useCurrent: false,
                }
            }
        },
        mounted() {
            axios.get(env.endpointhttp + "roles/info")
                .then(response => {
                    this.roles = response.data.result.data;
                });
            this.getDefaultValue();
        },
        methods: {
            getDefaultValue() {
                console.log(this.value);
                let data = JSON.parse(this.value);
                if (this.value) {
                    this.organization_id = this.value;

                }
            },
            setCancel(){
                this.$emit('cancelPopUp', false)
            },
            addUser() {
                this.$validator.validateAll();
                if (this.action == true) {
                    if (this.password == this.checkPassword) {
                        this.loading = true;
                        this.action = false;
                        axios
                            .post(endpointhttp + 'users/add', {
                                email: this.email,
                                password: this.password,
                                birthday: this.birthday,
                                role_id: (this.role ? this.role.id : ''),
                                username: this.username,
                                name: this.name,
                                phone: this.phone,
                                organization_id: this.organization_id,
                            })
                            .then(
                                response => {
                                    if (response.data.message == "message.success") {
                                        this.action = true;
                                        this.$emit('readloadTable', true);
                                        this.typeNotification = 2;
                                        this.textNotification = "Bạn thêm mới người dùng thành công";
                                        this.clearData();
                                    } else {
                                        this.action = true;
                                        this.typeNotification = 1;
                                        this.textNotification = "Đã có lỗi khi thực hiện tác vụ này.";
                                    }
                                }
                            )
                            .catch(error => {
                                this.action = true;
                                this.typeNotification = 1;
                                this.textNotification = "Đã có lỗi khi thực hiện tác vụ này.";
                            })
                            .finally(() => {
                                this.loading = false;
                                this.typeNotification = null;
                                this.textNotification = null;
                            });
                    }else {
                        this.typeNotification = 1;
                        this.textNotification = "Nhập lại password (Password và nhập lại password không trùng nhau)";
                    }
                }
            },
            clearData()
            {
                this.email = null;
                this.password = null;
                this.birthday = new Date();
                this.role = null;
                this.username = null;
                this.name = null;
                this.phone = null;
            },
        }
    }
</script>
