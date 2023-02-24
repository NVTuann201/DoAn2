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
                        <input type="text" v-model="name" name="name" v-validate="'required'"
                            class="form-control form-control-line" :placeholder="$t('admin.organization.input.name')">
                        <span v-show="errors.has('name')" class="help is-danger" style="color: red">{{
                        $t("admin.organization.error.name") }}</span>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.name_vietnamese") }}<span
                            style="color: red; margin-left: 5px">*</span></label>
                    <div class="col-md-12">
                        <input type="text" v-model="name_vietnamese" class="form-control form-control-line"
                            v-validate="'required'" :placeholder="$t('admin.organization.input.name_vietnamese')"
                            name="name_vietnamese">
                        <span v-show="errors.has('name_vietnamese')" class="help is-danger" style="color: red">{{
                        $t("admin.organization.error.name_vietnamese") }}</span>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.acronym") }}<span
                            style="color: red; margin-left: 5px">*</span></label>
                    <div class="col-md-12">
                        <input type="text" v-model="acronym" class="form-control form-control-line"
                            v-validate="'required'" :placeholder="$t('admin.organization.input.acronym')"
                            name="acronym">
                        <span v-show="errors.has('acronym')" class="help is-danger" style="color: red">{{
                        $t("admin.organization.error.acronym") }}</span>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12">{{ $t("admin.organization.acronym_vietnamese") }}</label>
                    <div class="col-md-12">
                        <input type="text" v-model="acronym_vietnamese" class="form-control form-control-line"
                            :placeholder="$t('admin.organization.input.acronym_vietnamese')" name="acronym_vietnamese">
                    </div>
                </div>
                <div class="col-md-12" style="padding: 0 15px">
                    <div class="col-md-6" style="padding: 0px">
                        <label class="col-md-12">{{ $t("admin.organization.organization_type") }}<span
                                style="color: red; margin-left: 5px">*</span></label>
                        <div class="col-md-12">
                            <input type="text" v-model="organization_type" class="form-control form-control-line"
                                v-validate="'required'" :placeholder="$t('admin.organization.organization_type')"
                                name="organization_type">
                            <span v-show="errors.has('organization_type')" class="help is-danger" style="color: red">{{
                            $t("admin.organization.error.organization_type") }}</span>
                        </div>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 20px;padding-left: 30px; padding-right: 30px">
                        <label class="typo__label">{{ $t("admin.organization.parent_organization_id") }}</label>
                        <multiselect v-model="parent_organization" :options="organization" :searchable="true"
                            track-by="id" label="name" name="parent_organization_id" :show-labels="false"
                            :placeholder="$t('admin.organization.input.parent_organization_id')">
                        </multiselect>
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
                        <span v-show="errors.has('email')" class="help is-danger" style="color: red">{{
                        $t("admin.organization.error.email") }}</span>
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
                            :placeholder="$t('admin.organization.input.portal_url_element')" name="portal_url_element">
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
                        <label for="files" class="btn btn-success">{{ $t("admin.profile.upload_img") }}</label><span
                            style="margin-left: 10px">{{ $t("admin.profile.upload_img_note") }}</span>
                        <input type="file" id="files" v-validate="'size:32768'" name="image" style="visibility:hidden;"
                            v-on:change="onImageChange" accept="image/x-png,image/gif,image/jpeg" class="form-control">
                        <span v-show="errors.has('image')" class="help is-danger" style="color: red">{{
                        $t("admin.organization.error.image") }}</span>
                    </div>
                    <div class="col-md-3" v-if="image && image != 'null'">
                        <img :src="image" class="img-responsive" height="200" width="240">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-inverse m-r-20" type="button" @click="cancelUpdateOrganization()">{{
                        $t("admin.label.cancel") }}</button>
                        <button class="btn btn-success" @click="updateOrganization()" type="button">{{
                        $t("admin.label.update") }}
                        </button>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <button class="btn btn-success" @click=" popShowAddUser = true" v-if="popShowAddUser == false">{{
                    $t("admin.organization.controller_user") }}</button>
                    <div class="col-md-4 no-padding" v-if="popShowAddUser == true">
                        <label class="typo__label">{{ $t("admin.organization.of_user") }}</label>
                        <UserAdd @cancelPopUp="popShowAddUser = false" @readloadTable="getUser()" :value="id"></UserAdd>
                    </div>
                    <div class="col-md-8" v-if="popShowAddUser == true">
                        <label class="typo__label">{{ $t("admin.organization.list_user") }}</label>
                        <div class="scrollable">
                            <div class="table-responsive">
                                <table
                                    class="table table-hover contact-list footable footable-1 footable-paging footable-paging-center breakpoint-lg">
                                    <thead>
                                        <tr class="footable-header">
                                            <th class="text-center">{{ $t("admin.label.no") }}</th>
                                            <th style="display: table-cell;">{{ $t("admin.label.name") + '/' +
                                            $t("admin.label.username")
                                            }}
                                            </th>
                                            <th style="display: table-cell;">{{$t("admin.label.email") +
                                            '/' + $t("admin.label.phone")}}
                                            </th>
                                            <th width="150" style="text-align: center">{{ $t("admin.label.manage") }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in users" style="cursor: context-menu;">
                                            <td class="position-relative"><span
                                                    class="position-absolute text-center-td">{{index
                                                    + 1}}</span></td>
                                            <td class="position-relative">
                                                <div class="row position-absolute center-vertical">
                                                    <div class="col-md-3">
                                                        <img :src="item.avatar_url" alt="user" class="img-circle">
                                                    </div>
                                                    <div class="col-md-9">
                                                        {{item.name}}
                                                        <br>
                                                        <span class="text-muted">{{item.username}}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="position-relative">
                                                <span class="position-absolute center-vertical">
                                                    {{item.email}}
                                                    <br>
                                                    <span class="text-muted">{{item.phone}}</span>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button"
                                                    class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
                                                    @click="deleteUser(item.id)"><i class="ti-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="footable-paging">
                                            <td colspan="6">
                                                <paginate v-model="page" :page-count="this.count" :page-range="3"
                                                    :margin-pages="2" :click-handler="clickCallback" :prev-text="'‹‹'"
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
            </div>
        </div>
    </div>
</template>
<script>
import * as env from '../../../../env.js'
import { endpoint, endpointhttp } from '../../../../env.js'
import Multiselect from 'vue-multiselect'
import { ValidationProvider } from 'vee-validate'
import UserAdd from "./components/addUser.vue";

export default {
    components: {
        Multiselect,
        ValidationProvider,
        UserAdd
    },
    props: {
        value: { type: String },
        imageurl: { type: String }
    },
    data: function () {
        return {
            id: null,
            popShowAddUser: false,
            users: [],
            organization: [],
            name: null,
            name_vietnamese: null,
            acronym: null,
            acronym_vietnamese: null,
            organization_type: null,
            parent_organization: null,
            parent_organization_id: null,
            description: null,
            url: null,
            address: null,
            zipcode: null,
            tel: null,
            contacts: null,
            remarks: null,
            portal_url_element: null,
            portal_contents: null,
            portal_css: null,
            email: null,
            phone: null,
            system: null,
            loading: false,
            action: true,
            typeNotification: null,
            textNotification: null,
            birthday: new Date(),
            optionsTime: {
                format: 'DD/MM/YYYY',
                useCurrent: false,
            },
            page: null,
            count: null,
            file: [],
            image: '',
            media: [],
        }
    },
    mounted() {
        this.image = this.imageurl
        this.getDefaultValue();
        axios.get(env.endpointhttp + "admin/organizations/info")
            .then(response => {
                this.organization = response.data.result.data;
                for (let i in this.organization) {
                    if (this.parent_organization_id == this.organization[i].id) {
                        this.parent_organization = this.organization[i];
                    }
                }
            });
        this.getUser();
    },
    methods: {
        clickCallback(pageNum) {
            this.page = pageNum;
            axios
                .get(env.endpointhttp + "users" + "&page=" + this.page, {
                    params: {
                        with: ['role']
                    }
                })
                .then(response => {
                    this.users = response.data.result.data;
                    this.count = response.data.result.last_page;
                })
                .catch(error => {
                    console.log(error);
                })
                .finally();
        },
        deleteUser(value) {
            this.loading = true;
            axios
                .delete(endpointhttp + 'users/delete/' + value, {
                })
                .then(
                    response => {
                        if (response.data.message == "message.success") {
                            this.loading = false;
                            this.action = true;
                            this.getUser();
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
        getUser() {
            axios.get(env.endpointhttp + "users", {
                params: {
                    with: ['role'],
                    search_organization: this.id,
                }
            })
                .then(response => {
                    this.users = response.data.result.data;
                    this.count = response.data.result.last_page;
                });
        },
        getDefaultValue() {
            let data = JSON.parse(this.value);
            if (this.value) {
                this.id = data[0].id;
                this.name = data[0].name;
                this.name_vietnamese = data[0].name_vietnamese;
                this.acronym = data[0].acronym;
                this.acronym_vietnamese = data[0].acronym_vietnamese;
                this.organization_type = data[0].organization_type;
                this.parent_organization_id = data[0].parent_organization_id;
                this.description = data[0].description;
                this.url = data[0].url;
                this.address = data[0].address;
                this.zipcode = data[0].zipcode;
                this.tel = data[0].tel;
                this.contacts = data[0].contacts;
                this.remarks = data[0].remarks;
                this.portal_url_element = data[0].portal_url_element;
                this.portal_contents = data[0].portal_contents;
                this.portal_css = data[0].portal_css;
                this.email = data[0].email;
                this.media = data[0].mediable.media_id ? data[0].mediable.media : [];
            }
        },
        updateOrganization() {
            this.$validator.validate().then(valid => {
                if (valid) {
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
                                    .post(endpointhttp + 'admin/organizations/update/' + this.id, {
                                        email: this.email,
                                        name: this.name,
                                        name_vietnamese: this.name_vietnamese,
                                        acronym: this.acronym,
                                        acronym_vietnamese: this.acronym_vietnamese,
                                        organization_type: this.organization_type,
                                        parent_organization_id: (this.parent_organization ? this.parent_organization.id : ''),
                                        description: this.description,
                                        url: this.url,
                                        address: this.address,
                                        zipcode: this.zipcode,
                                        tel: this.tel,
                                        contacts: this.contacts,
                                        remarks: this.remarks,
                                        portal_url_element: this.portal_url_element,
                                        portal_contents: this.portal_contents,
                                        portal_css: this.portal_css,
                                        media: this.media
                                    })
                                    .then(
                                        (response) => {
                                            console.log(response);
                                            if (response.data.message == "message.success") {
                                                this.typeNotification = 2;
                                                this.textNotification = "Cập nhật thành công.";
                                                window.location.href = "/admin/organizations";
                                                this.action = true;
                                            }
                                            else if (response.data.message == "trungTen") {
                                                this.action = true;
                                                this.typeNotification = 1;
                                                this.textNotification = "Tên tổ chức đã tồn tại";
                                            }
                                            else if (response.data.message == "trungTenVNese") {
                                                this.action = true;
                                                this.typeNotification = 1;
                                                this.textNotification = "Tên tổ chức Tiếng Việt đã tồn tại";
                                            }
                                            else {
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
                            })
                    } else {
                        this.typeNotification = 1;
                    }
                }
            });
        },
        cancelUpdateOrganization() {
            window.location.href = "/admin/organizations";
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