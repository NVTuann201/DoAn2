<template>
  <PageLayout>
    <template #left-aside>
      <ul class="list-style-none">
        <li class="box-label">
          <a @click="searchRole()" style="cursor: pointer"
            >{{ $t("admin.label.sum_all_roles")
            }}<span>{{ roles ? roles.length : "" }}</span></a
          >
        </li>
        <li class="divider"></li>
        <li v-if="!roles">
          <components-loading></components-loading>
        </li>
        <li v-for="item in roles" v-if="roles">
          <a
            @click="searchRole(item.id)"
            v-if="checkColor != item.id"
            style="cursor: pointer"
            >{{ item.name }}<span>{{ item.users_count }}</span>
          </a>
          <a
            @click="searchRole(item.id)"
            v-if="checkColor == item.id"
            style="cursor: pointer; color: #408c5b; font-weight: bold"
            >{{ item.name }}<span>{{ item.users_count }}</span>
          </a>
        </li>
        <a
          v-if="usersystem.role_id == 1"
          class="btn btn-info btn-rounded"
          href="/roles/add"
          >{{ $t("admin.label.add_role") }}</a
        >
      </ul>
    </template>

    <template #header-right-aside>
      <div class="pull-right">
        <input
          type="text"
          :placeholder="$t('admin.placeholder.search_user')"
          class="form-control"
          v-model="search"
          v-on:keyup.enter="getUser()"
        />
      </div>
      <h3 class="uppercase" style="font-weight: normal">
        {{ $t("admin.listing.users") }}
      </h3>
      <button
        type="button"
        class="btn btn-info btn-rounded"
        @click="goAddUser()"
      >
        Thêm mới người dùng
      </button>
    </template>
    <template #right-aside>
      <Basetable
        :headers="headers"
        :page="page"
        :total-page="count"
        :items="users"
        @update:page="clickCallback"
        :loading="loading"
      >
        <template #[`item.username`]="{ item }">
          <div class="row center-vertical" style="transform: translateY(0%)">
            <div class="col-md-2">
              <img
                v-if="item.mediable.media_id"
                :src="
                  '/storage/' +
                  item.mediable.media_id +
                  '/' +
                  item.mediable.media.file_name
                "
                alt="user"
                class="img-circle"
              />
              <img
                v-else
                :src="item.avatar_url"
                alt="user"
                class="img-circle"
              />
            </div>
            <div class="col-md-10" style="max-width: 320px; overflow:clip">
              {{ item.name }}
            </div>
          </div>
        </template>
        <template #[`item.info`]="{ item }">
          <span class="center-vertical">
            {{ item.email }}
            <br />
            <span class="text-muted">{{ item.phone }}</span>
          </span>
        </template>
        <template #[`item.role`]="{ item }">
          <span
            class="label label-danger center-vertical"
            v-bind:class="item.role.color"
          >
            {{ item.role.name }}
          </span>
        </template>
        <template #[`item.status`]="{ item }">
          <span class="label label-danger text-center-td" v-if="item.inactive">
            Ngừng hoạt động
          </span>
          <span class="label label-info text-center-td" v-else>
            Đang hoạt động
          </span>
        </template>

        <template #[`item.action`]="{ item }">
          <button
            v-if="item.isApprove"
            type="button"
            @click="editUser(item.id)"
            class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
          >
            <i class="ti-pencil-alt"></i>
          </button>
          <button
            v-else
            type="button"
            @click="approve(item.id, index)"
            class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
            title="Phê duyệt người dùng"
            :disabled="loadingApprove"
          >
            <i class="ti-check"></i>
          </button>
          <button
            type="button"
            class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
            @click="showModal(item.id)"
          >
            <i class="ti-trash"></i>
          </button>
        </template>
      </Basetable>

      <div class="modal" id="myModal">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <div class="col-md-10">
                <label class="modal-title">Xác nhận xóa người dùng</label>
              </div>
              <div class="col-md-2">
                <button type="button" class="close" data-dismiss="modal">
                  &times;
                </button>
              </div>
            </div>
            <div class="modal-footer">
              <div class="col-md-6" style="text-align: left">
                <button
                  type="button"
                  class="btn btn-default"
                  data-dismiss="modal"
                >
                  Hủy
                </button>
              </div>
              <div class="col-md-6">
                <button
                  type="button"
                  class="btn btn-danger"
                  @click="deleteUser(getId)"
                  data-dismiss="modal"
                >
                  Xóa
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
    <components-notifications
      :typeInput="typeNotification"
      :textInput="textNotification"
    >
    </components-notifications>
  </PageLayout>
</template>

<script>
import * as env from "@/env.js";
import { endpointhttp } from "@/env.js";
import PageLayout from "@/modules/layout/page-layout.vue";
import Basetable from "@/modules/base/table.vue";
export default {
  props: {
    usersystem: { type: Object },
  },
  components: { PageLayout, Basetable },
  data: function () {
    return {
      typeNotification: null,
      textNotification: null,
      roles: null,
      users: null,
      page: 1,
      count: 0,
      search_role: null,
      search: null,
      getId: null,
      checkColor: 0,
      loadingApprove: false,
      loading: false,
      headers: [
        {
          text:
            this.$t("admin.label.name") + "/" + this.$t("admin.label.username"),
          value: "username",
        },
        {
          text:
            this.$t("admin.label.email") + "/" + this.$t("admin.label.phone"),
          value: "info",
        },
        {
          text: "Vai trò",
          value: "role",
        },
        {
          text: this.$t("admin.label.inactive"),
          value: "status",
        },
        {
          text: this.$t("admin.label.manage"),
          value: "action",
        },
      ],
    };
  },
  mounted() {
    this.getRoles();
    this.getUser();
  },
  methods: {
    editUser(value) {
      window.location.href = "/users/update/" + value;
    },
    deleteUser(value) {
      axios
        .delete(endpointhttp + "users/delete/" + value)
        .then((response) => {
          if (response.data.message == "message.success") {
            this.action = true;
            this.typeNotification = 2;
            this.textNotification = "Xóa người dùng thành công.";
            this.getRoles();
            if (this.search_role) {
              this.searchRole(this.search_role);
            } else {
              this.getUser();
            }
          } else {
            this.action = true;
            this.typeNotification = 1;
            this.textNotification = "Xóa người dùng không thành công.";
          }
        })
        .catch((error) => {
          this.action = true;
          this.typeNotification = 1;
          this.textNotification = "Xóa người dùng không thành công.";
        })
        .finally(() => {
          this.loading = false;
          this.typeNotification = null;
          this.textNotification = null;
        });
    },
    searchRole(value) {
      this.checkColor = value;
      if (value) {
        this.search_role = value;
      } else {
        this.search_role = null;
      }
      axios
        .get(env.endpointhttp + "users", {
          params: {
            search_role: this.search_role,
            with: ["role"],
          },
        })
        .then((response) => {
          this.users = response.data.result.data;
          this.count = response.data.result.last_page;
        });
    },
    approve(id, index) {
      this.loadingApprove = true;
      axios
        .post(endpointhttp + "users/approve/" + id)
        .then((response) => {
          if (response.data.message == "message.success") {
            this.typeNotification = 2;
            this.textNotification = "Phê duyệt thành công.";
            this.action = true;
            this.users[index].isApprove = true;
          } else {
            this.action = true;
            this.typeNotification = 1;
            this.textNotification = "Phê duyệt không thành công.";
          }
        })
        .catch((error) => {
          this.action = true;
          this.typeNotification = 1;
          this.textNotification = "Phê duyệt không thành công.";
        })
        .finally(() => {
          this.loadingApprove = false;
          this.typeNotification = null;
          this.textNotification = null;
        });
    },
    getRoles() {
      axios
        .get(env.endpointhttp + "roles/info", {
          params: {
            count: ["users"],
          },
        })
        .then((response) => {
          this.roles = response.data.result.data;
        });
    },
    getUser() {
      this.checkColor = 0;
      this.loading = true;
      axios
        .get(env.endpointhttp + "users", {
          params: {
            with: ["role"],
            search: this.search,
            page: this.page,
          },
        })
        .then((response) => {
          this.users = response.data.result.data;
          this.count = response.data.result.last_page;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.getUser();
    },
    goAddUser() {
      window.location.href = "users/add";
    },
    showModal(value) {
      this.getId = value;
      $("#myModal").modal("show");
    },
  },
};
</script>
