<template>
    <div class="white-box p-0">
        <!-- .left-right-aside-column-->
        <div class="page-aside">
            <components-loading-page v-if="loading"></components-loading-page>
            <components-notifications :typeInput="typeNotification" :textInput="textNotification">
            </components-notifications>
            <!-- /.left-aside-column-->
            <div class="left-aside">
                <div class="scrollable">                    
                    <ul style="margin: 0px; padding: 0px; display: block;list-style: none">
                        <li style="list-style: none;margin: 0px;">{{ $t("admin.label.filter")}}</li>                        
                        <li style="margin: 10px 0; height: 1px; background: rgba(120, 130, 140, 0.13)"></li>
                        <li style="list-style: none;" v-if="!userlog">
                            <components-loading></components-loading>
                        </li>
                        <div  @click="isUser = -isUser" data-toggle="collapse" data-target=".filter1"
                            style="cursor: pointer; font-weight: normal;padding: 15px 10px; margin-bottom: -10px;"
                            :style="searchUser ? 'color: #000000; font-weight: bold' : ''">
                            {{ $t("nbds_lang.user_logs.user_id")}}<span
                                :class="(isUser == 1) ? 'fa fa-angle-right' : 'fa fa-angle-down'"
                                style="float:right"></span></div>
                            <multiselect style="padding: 0 !important" v-show="isUser == 1" v-model="username" :options="users" :searchable="true" track-by="name" label="name"
                                name="username" @input="searchWithUser()"
                                :show-labels="false" :placeholder="$t('nbds_lang.search')"
                            ></multiselect>
                        <li v-for="item in users" v-if="users" class="collapse filter1" style="margin-left:15px;list-style: none;">
                            <a @click="searchWithUser(item.user_id)" style="cursor: pointer;padding: 15px 10px; display: block;color: #313131;"
                                :style="(searchUser == item.user_id) ? 'color: #408c5b; font-weight: bold' : ''">
                                {{(item.name.length >= 20)? item.name.substring(0,20) + '...' : item.name}}
                                <span style="float: right;">{{item.count}}</span>
                            </a>
                        </li>
                        <br/>
                        <div @click="isAction = -isAction" data-toggle="collapse" data-target=".filter2"
                            style="cursor: pointer; font-weight: normal;padding: 15px 10px; margin-bottom: -10px;"
                            :style="searchAction ? 'color: #000000; font-weight: bold' : ''">
                            {{ $t("nbds_lang.user_logs.action")}}<span
                                :class="(isAction == 1) ? 'fa fa-angle-right' : 'fa fa-angle-down'"
                                style="float:right"></span></div><br/>
                        <li v-for="item in action" v-if="action" class="collapse filter2" style="margin-left:15px;list-style: none;">
                            <a @click="searchWithAction(item.action)" style="cursor: pointer;padding: 15px 10px; display: block;color: #313131;"
                                :style="(searchAction == item.action) ? 'color: #408c5b; font-weight: bold' : ''">
                                {{item.action.length >= 20 ? item.action.substring(0,20) + '...' : item.action}}
                                <span style="float: right;">{{item.count}}</span>
                            </a>
                        </li>
                        <div @click="isTime = -isTime" data-toggle="collapse" data-target=".filter3"
                            style="cursor: pointer; font-weight: normal;padding: 15px 10px; margin-bottom: -10px;"
                            :style="searchTime ? 'color: #000000; font-weight: bold' : ''">
                            Thời gian<span
                                :class="(isTime == 1) ? 'fa fa-angle-right' : 'fa fa-angle-down'"
                                style="float:right"></span></div><br/>
                        <li class="collapse filter3" style="margin-left:15px;list-style: none; margin: 0px;">
                            <a @click="searchWithTime('onemonth')" style="cursor:pointer;padding: 15px 10px; display: block;color: #313131;"
                                :style="(searchTime == 'onemonth') ? 'color: #408c5b; font-weight: bold' : ''">
                                Một tháng gần nhất
                            </a>
                        </li> 
                        <li class="collapse filter3" style="margin-left:15px;list-style: none; margin: 0px;">
                            <a @click="searchWithTime('threemonths')" style="cursor: pointer;padding: 15px 10px; display: block;color: #313131;"
                                :style="(searchTime == 'threemonths') ? 'color: #408c5b; font-weight: bold' : ''">
                                Ba tháng gần nhất
                            </a>
                        </li>   
                        <li class="collapse filter3" style="margin-left:15px;list-style: none; margin: 0px;">
                            <a @click="searchWithTime('sixmonths')" style="cursor: pointer;padding: 15px 10px; display: block;color: #313131;"
                                :style="(searchTime == 'sixmonths') ? 'color: #408c5b; font-weight: bold' : ''">
                                Sáu tháng gần nhất
                            </a>
                        </li>      
                        <li style="text-align: center; margin-top: 12px;list-style: none;" v-if="searchUser || searchAction ||searchTime">
                            <button type="button" class="btn btn-info btn-rounded" @click="deleteSearchFilter()">
                                <span>Xóa bộ lọc</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="right-aside">
                <div class="right-page-header">
                    <div class="pull-right">
                        <input type="text" placeholder="Tìm kiếm thông tin" class="form-control" v-model="search"
                            v-on:keyup.enter="searchUserLog()" />
                    </div>                    
                </div>
                <div class="clearfix"></div>
                <div class="scrollable">
                    <div class="table-responsive">
                        <table
                            class="table m-t-30 table-hover contact-list footable footable-1 footable-paging footable-paging-center breakpoint-lg">
                            <thead>
                                <tr class="footable-header">
                                    <th class="text-center">{{ $t("admin.label.no") }}</th>
                                    <th style="display: table-cell; min-width: 80px">{{ $t("nbds_lang.user_logs.user_id")}}
                                    </th>
                                    <th style="display: table-cell;">
                                        {{ $t("nbds_lang.user_logs.event_time") }}</th>
                                    <th style="display: table-cell">
                                        {{ $t("nbds_lang.user_logs.action") }}</th>
                                    <th style="display: table-cell">
                                        {{ $t("nbds_lang.user_logs.ip_address") }}</th>
                                    <th style="display: table-cell">
                                        Nội dung
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!userlog">
                                    <td colspan="5">
                                        <components-loading></components-loading>
                                    </td>
                                </tr>
                                <tr v-if="userlog && userlog.length == 0">
                                    <td colspan="5" class="emptyInfomation">
                                        <h5>{{ $t("admin.error.no_data") }}</h5>
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in userlog" v-if="userlog" style="cursor: context-menu;">
                                    <td align="center">
                                        <span class=" text-center-td">{{(page - 1)*10 + index + 1}}</span>
                                    </td>
                                    <td v-if="item.user">{{item.user.name}}</td>
                                    <td v-else>Tài khoản đã bị xóa</td>
                                    <td>{{item.event_time}}</td>
                                    <td>{{item.action}}</td>
                                    <td>{{item.ip_address}}</td>
                                    <td>{{item.noi_dung}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <tr v-if="count>1" class="footable-paging">
                    <td colspan="6">
                        <paginate v-model="page" :page-count="this.count" :page-range="3" :margin-pages="2"
                            :click-handler="clickCallback" :prev-text="'‹‹'" :next-text="'››'"
                            :container-class="'pagination'" :page-class="'page-item'"></paginate>
                    </td>
                </tr>
                <!-- .left-aside-column-->
            </div>
            <!-- /.left-right-aside-column-->
        </div>
    </div>
</template>

<script>
    import * as env from "../../../../env.js";
    import * as routes from "../../../../routes.js";
    import { endpoint, endpointhttp } from "../../../../env.js";
    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            Multiselect
        },
        data: function () {
            return {
                confirmPopUp: false,
                search: null,
                typeNotification: null,
                textNotification: null,
                resetData: false,
                userlog: [],
                page: 1,
                count: 0,
                per_page: 0,
                current_page: 0,
                search_role: null,
                loading: false,
                idDeleteDataset: null,
                users: [],
                action: [],
                searchUser: null,
                searchAction: null,
                searchTime: null,
                isUser: -1,
                isAction: -1,
                isTime: -1,
                username: null,
            };
        },
        mounted() {
            this.getUserLog();
            this.getUser();
            this.getAction();
        },
        methods: {
            getUserLog() {
                this.loading = true;
                this.page = 1;
                this.resetData = false;
                axios
                    .get(env.endpointhttp + "userlog/info", {
                        params: {
                            search: this.search,
                            search_user: this.searchUser,
                            search_action: this.searchAction,
                            search_time: this.searchTime,
                            page: this.page
                        }
                    })
                    .then(response => {
                        this.userlog = response.data.result.data;
                        this.count = response.data.result.last_page;
                        this.per_page = response.data.result.per_page;
                        this.current_page = response.data.result.current_page;
                        this.loading = false;
                    });
            },
            getUser() {
                this.loading = true;
                this.users = [];
                axios
                    .get(env.endpointhttp + "userlog/users", {
                        params: {
                            search: this.search,
                        }
                    })
                    .then(response => {
                        this.users = response.data.result;
                        this.loading = false;
                    })
            },
            getAction() {
                this.action = [];
                this.loading = true;
                axios
                    .get(env.endpointhttp + "userlog/action", {
                        params: {
                            search: this.search,
                        }
                    })
                    .then(response => {
                        this.action = response.data.result;
                        this.loading = false;
                    })
            },
            searchUserLog() {
                this.loading = true;
                this.getUserLog();
                this.getAction();
                this.getUser();
            },
            clickCallback(pageNum) {
                this.page = pageNum;
                axios
                    .get(env.endpointhttp + "userlog/info", {
                        params: {
                            search: this.search,
                            search_user: this.searchUser,
                            search_action: this.searchAction,
                            search_time: this.searchTime,
                            page: this.page
                        }
                    })
                    .then(response => {
                        this.userlog = response.data.result.data;
                        this.count = response.data.result.last_page;
                        this.per_page = response.data.result.per_page;
                        this.current_page = response.data.result.current_page;
                    });
            },
            searchWithUser(value) {
                if(!value){
                    this.searchUser = (this.username)?this.username.user_id : null;
                }
                else{
                    this.searchUser = value;
                }
                this.getUserLog();
            },
            searchWithAction(value) {
                this.searchAction = value;
                this.getUserLog();
            },
            searchWithTime(value) {
                this.searchTime = value;
                this.getUserLog();
            },
            deleteSearchFilter() {
                this.searchUser = null;
                this.searchAction = null;
                this.searchTime = null;
                this.getUserLog();
            },

        },
    };
</script>