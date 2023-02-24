<template>
    <div class="white-box clearfix">
        <components-notifications :typeInput="typeNotification" :textInput="textNotification">
        </components-notifications>
        <div class="loading-roles" v-if="loading">
            <components-loading-page></components-loading-page>
        </div>
        <div class="col-md-12">
            <div class="form-material">
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.name") }}<span
                            style="color: red; margin-left: 5px">*</span></label>
                    <div class="col-md-12">
                        <input type="text"
                               v-model="name"
                               name="name"
                               class="form-control form-control-line"
                               v-validate="'required'"
                               :placeholder="$t('admin.organization.input.name')">
                    <span v-show="errors.has('name')" class="help is-danger" style="color: red">{{ $t("admin.organization.error.name") }}</span>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.name_vietnamese") }}<span
                            style="color: red; margin-left: 5px">*</span></label>
                    <div class="col-md-12">
                        <input type="text"
                               v-model="name_vietnamese"
                               class="form-control form-control-line"
                               v-validate="'required'"
                               :placeholder="$t('admin.organization.input.name_vietnamese')"
                               name="name_vietnamese">
                        <span v-show="errors.has('name_vietnamese')" class="help is-danger" style="color: red">{{ $t("admin.organization.error.name_vietnamese") }}</span>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.acronym") }}<span
                            style="color: red; margin-left: 5px">*</span></label>
                    <div class="col-md-12">
                        <input type="text"
                               v-model="acronym"
                               class="form-control form-control-line"
                               v-validate="'required'"
                               :placeholder="$t('admin.organization.input.acronym')" name="acronym">
                        <span v-show="errors.has('acronym')" class="help is-danger" style="color: red">{{ $t("admin.organization.error.acronym") }}</span>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.acronym_vietnamese") }}</label>
                    <div class="col-md-12">
                        <input type="text" v-model="acronym_vietnamese" class="form-control form-control-line"
                               :placeholder="$t('admin.organization.input.acronym_vietnamese')"
                               name="acronym_vietnamese">
                    </div>
                </div>
                <div class="col-md-12" style="padding: 0 15px">
                    <div class="col-md-6" style="padding: 0px">
                        <label class="col-md-12">{{ $t("admin.organization.organization_type") }}<span
                                style="color: red; margin-left: 5px">*</span></label>
                        <div class="col-md-12">
                            <input type="text" v-model="organization_type" class="form-control form-control-line"
                                   v-validate="'required'"
                                   :placeholder="$t('admin.organization.organization_type')" name="organization_type">
                            <span v-show="errors.has('organization_type')" class="help is-danger" style="color: red">{{ $t("admin.organization.error.organization_type") }}</span>
                        </div>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 20px;padding-left: 30px; padding-right: 30px">
                        <label class="typo__label">{{ $t("admin.organization.parent_organization_id") }}</label>
                        <multiselect v-model="parent_organization" :options="organization" :searchable="true"
                                     track-by="id" label="name"
                                     name="parent_organization_id"
                                     :show-labels="false"
                                     :placeholder="$t('admin.organization.input.parent_organization_id')"
                        ></multiselect>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.description") }}</label>
                    <div class="col-md-12">
                        <input type="text" v-model="description" class="form-control form-control-line"
                               :placeholder="$t('admin.organization.input.description')" name="description">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.url") }}</label>
                    <div class="col-md-12">
                        <input type="text" v-model="url" class="form-control form-control-line"
                               :placeholder="$t('admin.organization.url')" name="url">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.address") }}</label>
                    <div class="col-md-12">
                        <input type="text" v-model="address" class="form-control form-control-line"
                               :placeholder="$t('admin.organization.input.address')" name="address">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.zipcode") }}</label>
                    <div class="col-md-12">
                        <input type="email" v-model="zipcode" class="form-control form-control-line"
                               :placeholder="$t('admin.organization.input.zipcode')" name="zipcode">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.tel") }}</label>
                    <div class="col-md-12">
                        <input v-model="tel" type="tel" pattern="[0-9,-]{10}"
                            class="form-control form-control-line" :placeholder="$t('admin.organization.input.tel')"
                            name="phone" v-validate="'max:20'">
                        <span v-show="errors.has('phone')" class="help is-danger" style="color: red">{{
                        $t("admin.organization.error.phone") }}</span>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.email") }}</label>
                    <div class="col-md-12">
                        <input type="email" v-model="email" v-validate="'email'" class="form-control form-control-line"
                               :placeholder="$t('admin.organization.input.email')" name="email">
                        <span v-show="errors.has('email')" class="help is-danger" style="color: red">{{ $t("admin.organization.error.email") }}</span>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.contacts") }}</label>
                    <div class="col-md-12">
                        <input type="text" v-model="contacts" class="form-control form-control-line"
                               :placeholder="$t('admin.organization.input.contacts')" name="contacts">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.remarks") }}</label>
                    <div class="col-md-12">
                        <input type="text" v-model="remarks" class="form-control form-control-line"
                               :placeholder="$t('admin.organization.input.remarks')" name="remarks">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.portal_url_element") }}</label>
                    <div class="col-md-12">
                        <input type="text" v-model="portal_url_element" class="form-control form-control-line"
                               :placeholder="$t('admin.organization.input.portal_url_element')"
                               name="portal_url_element">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.portal_contents") }}</label>
                    <div class="col-md-12">
                        <input type="text" v-model="portal_contents" class="form-control form-control-line"
                               :placeholder="$t('admin.organization.input.portal_contents')" name="portal_contents">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.portal_css") }}</label>
                    <div class="col-md-12">
                        <input type="text" v-model="portal_css" class="form-control form-control-line"
                               :placeholder="$t('admin.organization.input.portal_css')" name="portal_css">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6" style="margin: 10px 0 10px 0">
                        <label for="files" class="btn btn-success">{{ $t("admin.profile.upload_img") }}</label><span style="margin-left: 10px">{{ $t("admin.profile.upload_img_note") }}</span>
                        <input type="file" id="files" v-validate="'size:32768'" name="image" style="visibility:hidden;" v-on:change="onImageChange" accept="image/x-png,image/gif,image/jpeg" class="form-control">
                        <span v-show="errors.has('image')" class="help is-danger" style="color: red">{{ $t("admin.organization.error.image") }}</span>
                    </div> 
                      <div class="col-md-3" v-if="image">
                        <img :src="image" class="img-responsive" height="200" width="240">
                    </div>
                    <div class="col-md-3" v-if="!image">
                        <img :src="logo_url" class="img-responsive" height="100" width="120">
                    </div>                               
                </div>
                <div class="form-group col-md-12">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-inverse m-r-20" type="button" @click="cancelOrganization()">{{ $t("admin.label.cancel") }}</button>
                        <button class="btn btn-success" @click="addOrganization()" type="button">{{
                            $t("admin.label.create_new") }}
                        </button>
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
        data: function () {
            return {
                newUsers: null,
                organization: [],
                users: [],
                list_user : [],
                name: null,
                name_vietnamese: null,
                acronym: null,
                acronym_vietnamese: null,
                organization_type: null,
                parent_organization: null,
                description: null,
                url: null,
                address: null,
                zipcode: null,
                tel: null,
                email: null,
                contacts: null,
                remarks: null,
                portal_url_element: null,
                portal_contents: null,
                portal_css: null,
                loading: false,
                action: true,
                typeNotification: null,
                textNotification: null,
                role: null,
                options: ['list', 'of', 'options'],
                birthday: new Date(),
                optionsTime: {
                    format: 'DD/MM/YYYY',
                    useCurrent: false,
                }, 
                update: false,
                logo_url: '',
                file: [],
                image: '',
                media: []
            }
        },
        mounted() {
            this.getOrganization();
        },
        methods: {
            getOrganization() {
                axios.get(env.endpointhttp + "admin/organizations/info")
                    .then(response => {
                        this.organization = response.data.result.data;
                    });
            },
            cancelOrganization(){
                window.location.href = "/admin/organizations";
            },
            addOrganization() {
                this.$validator.validate().then(valid => {
                    if(valid) {
                        if (this.action == true) {
                            this.loading = true;
                            this.action = false;
                            
                            let formData = new FormData();
                            formData.append('images', this.file);
                            axios
                                .post(endpointhttp + 'uploadimage', formData, {
                                    header: {
                                        'Content-Type': 'multipart/form-data'
                                    }
                                })
                                .then(response => {
                                    this.media = response.data.result;
                                    axios
                                        .post(endpointhttp + 'admin/organizations/add', {
                                            name: this.name,
                                            name_vietnamese: this.name_vietnamese,
                                            acronym: this.acronym,
                                            acronym_vietnamese: this.acronym_vietnamese,
                                            organization_type: this.organization_type,
                                            description: this.description,
                                            url: this.url,
                                            address: this.address,
                                            zipcode: this.zipcode,
                                            tel: this.tel,
                                            email: this.email,
                                            contacts: this.contacts,
                                            remarks: this.remarks,
                                            portal_url_element: this.portal_url_element,
                                            portal_contents: this.portal_contents,
                                            portal_css: this.portal_css,
                                            parent_organization_id: (this.parent_organization ? this.parent_organization.id : ''),
                                            media: this.media
                                    })
                                        .then(
                                            (response) => {
                                                if (response.data.message == "message.success") {
                                                    this.action = true;
                                                    this.typeNotification = 2;
                                                    this.textNotification = "Thêm mới thành công.";
                                                    window.location.href = "/admin/organizations";                                    
                                                } else {
                                                    this.action = true;
                                                    this.typeNotification = 1;
                                                    this.textNotification = response.data.data[Object.keys(response.data.data)[0]];
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
                                    })
                        }
                    }
                });
            },

            onImageChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length) return;
                this.file = files[0];
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = e => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
        }
    }
</script>
