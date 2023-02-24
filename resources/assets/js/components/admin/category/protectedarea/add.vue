<template>
  <div class="white-box clearfix" style="min-height: 100vh">
    <components-notifications :typeInput="typeNotification" :textInput="textNotification"></components-notifications>
    <div class="loading-roles" v-if="loading">
      <components-loading-page></components-loading-page>
    </div>
    <div class="col-md-12">
      <label class="col-md-12 uppercase" style="margin-bottom: 25px">Thông tin chung</label>
      <div class="form-material">
        <div class="col-md-6" style="margin: 0 0 8px">
          <label class="col-md-12"> Quận huyện </label>
          <div class="col-md-12">
            <multiselect v-model="quanHuyenSelect" :options="quanHuyens" :searchable="true" :multiple="true"
              track-by="id" label="name_vietnamese" placeholder="Quận huyện" :show-labels="false" />
          </div>
        </div>

        <div class="form-group col-md-6">
          <label class="col-md-12">
            Tên gọi
            <span style="color: red; margin-left: 5px">*</span>
          </label>
          <div class="col-md-12">
            <input type="text" v-model="name" name="name" class="form-control form-control-line" v-validate="'required'"
              :placeholder="$t('nbds_lang.protected_areas.name_vn')" onpaste="return false" oncopy="return false" />
            <span v-show="errors.has('name')" class="help is-danger" style="color: red">{{
                $t("nbds_lang.protected_areas.error.name_vn")
            }}</span>
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12">
            {{ $t("nbds_lang.protected_areas.name_en") }}
            <span style="color: red; margin-left: 5px"></span>
          </label>
          <div class="col-md-12">
            <input type="text" v-model="orig_name" name="orig_name" class="form-control form-control-line"
              :placeholder="$t('nbds_lang.protected_areas.name_en')" onpaste="return false" oncopy="return false" />
          </div>
        </div>
        <div class="col-md-6" style="margin: 0 0 8px">
          <label class="col-md-12"> Trạng thái </label>
          <div class="col-md-12">
            <multiselect v-model="trangThai" :options="selectTrangThai"/>
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12"> Địa chỉ </label>
          <div class="col-md-12">
            <input type="text" v-model="dia_chi" name="orig_name" class="form-control form-control-line"
              placeholder="Mô tả địa chỉ" onpaste="return false" oncopy="return false" />
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12">Số hiệu/Văn bản
            <span data-toggle="tooltip" data-placement="top"
              title="Văn bản phê duyệt (số hiệu, ngày tháng, cơ quan ban hành)">
              <i class="fas fa-info-circle"></i>
            </span>
          </label>
          <div class="col-md-12">
            <input type="text" v-model="status_no" name="description" class="form-control"
              placeholder="Số quyết định thành lập" onpaste="return false" oncopy="return false" />
          </div>
        </div>
        <div class="col-md-6" style="margin: 0 0 8px">
          <label class="col-md-12">{{
              $t("nbds_lang.protected_areas.gov_type")
          }}</label>
          <div class="col-md-12">
            <multiselect v-model="gov_type" :options="gov_types" :searchable="true" track-by="gov_type" label="gov_type"
              name="gov_type" :show-labels="false" :placeholder="$t('nbds_lang.protected_areas.gov_type')">
            </multiselect>
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12"> Cơ quan quản lý</label>
          <div class="col-md-12">
            <input type="text" v-model="co_quan_quan_ly" name="orig_name" class="form-control form-control-line"
              placeholder="Cơ quan quản lý" onpaste="return false" oncopy="return false" />
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12">
            {{ $t("nbds_lang.protected_areas.rep_area") }}
            <span style="color: red; margin-left: 5px">*</span>
          </label>
          <div class="col-md-12">
            <input type="number" v-model="rep_area" v-validate="'required'" name="rep_area"
              class="form-control form-control-line" :placeholder="$t('nbds_lang.protected_areas.rep_area')"
              onpaste="return false" oncopy="return false" />
            <span v-show="errors.has('rep_area')" class="help is-danger" style="color: red">{{
                $t("nbds_lang.protected_areas.error.rep_area")
            }}</span>
          </div>
        </div>

        <div class="form-group col-md-6">
          <label class="col-md-12">
            Diện tích vùng lõi
            <span style="color: red; margin-left: 5px"></span>
          </label>
          <div class="col-md-12">
            <input type="number" v-model="dien_tich_vung_loi" class="form-control form-control-line"
              placeholder="Diện tích vùng lõi" onpaste="return false" oncopy="return false" />
          </div>
        </div>

        <div class="form-group col-md-6">
          <label class="col-md-12">
            Diện tích vùng đệm
            <span style="color: red; margin-left: 5px"></span>
          </label>
          <div class="col-md-12">
            <input type="number" v-model="dien_tich_vung_dem" class="form-control form-control-line"
              placeholder="Diện tích vùng đệm" onpaste="return false" oncopy="return false" />
          </div>
        </div>

        <div class="col-md-6" style="margin: 0 0 8px">
          <label class="col-md-12">Loại hình</label>
          <div class="col-md-12">
            <multiselect v-model="desig" :options="select_desig" :searchable="true" :show-labels="false"
              :placeholder="$t('nbds_lang.protected_areas.desig')"></multiselect>
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12">Quốc tế công nhận</label>
          <div class="radio-list col-md-12">
            <label class="radio-inline p-0">
              <div class="radio radio-info">
                <input type="radio" v-model="isInternational" name="radiointernational" id="radio3" value="1"
                  onpaste="return false" oncopy="return false" />
                <label for="radio3">Có</label>
              </div>
            </label>
            <label class="radio-inline">
              <div class="radio radio-info col-md-12">
                <input type="radio" v-model="isInternational" name="radiointernational" id="radio4" value="0"
                  onpaste="return false" oncopy="return false" />
                <label for="radio4">Không</label>
              </div>
            </label>
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12">Danh hiệu quốc tế</label>
          <div class="col-md-12">
            <input type="text" v-model="int_crit" name="int_crit" class="form-control form-control-line"
              :placeholder="$t('nbds_lang.protected_areas.int_crit')" onpaste="return false" oncopy="return false" />
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12">
            {{ $t("nbds_lang.protected_areas.status_year") }}
          </label>
          <div class="col-md-12">
            <input type="number" v-model="status_year" min="1000" max="9999" step="1" name="status_year"
              class="form-control form-control-line" :placeholder="$t('nbds_lang.protected_areas.status_year')"
              onpaste="return false" oncopy="return false" />
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12">Thuộc kỳ quy hoạch</label>
          <div class="col-md-12">
            <input type="text" v-model="plan_in" name="description" class="form-control"
              placeholder="Thuộc kỳ quy hoạch" onpaste="return false" oncopy="return false" />
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12">Kế hoạch quản lý</label>
          <div class="col-md-12">
            <input type="text" v-model="ke_hoach_quan_ly" name="description" class="form-control"
              placeholder="Kế hoạch quản lý" onpaste="return false" oncopy="return false" />
          </div>
        </div>
        <br />
        <div class="col-md-12">
          <div class="form-group col-md-6">
            <div class="c-flex line-form">
              <div class="d-flex">
                <label style="flex: 1">Tệp đính kèm
                  <span data-toggle="tooltip" data-placement="top"
                    title="Đính kèm văn bản phê duyệt, kế hoạch quản lý, hình ảnh bản đồ ...">
                    <i class="fas fa-info-circle"></i>
                  </span>
                </label>
                <div>
                  <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                    data-bs-target="#exampleModal" data-bs-placement="top" title="Tải lên" @click="clickUpload">
                    <i class="fas fa-thumbtack"></i> Tải tệp lên
                  </button>
                </div>
                <input type="file" id="myfile" name="myfile" multiple ref="upload-files" style="display: none"
                  @change="handleUpload" />
              </div>
              <br />
              <div>
                <div class="progress" style="height: 10px" v-show="
                  tienTrinhUpload &&
                  tienTrinhUpload != 0 &&
                  tienTrinhUpload != 100
                ">
                  <div class="progress-bar" role="progressbar" style="font-weight: bold" aria-valuenow="25"
                    aria-valuemin="0" aria-valuemax="100" :style="{ width: tienTrinhUpload + 'px' }">
                    {{ tienTrinhUpload }}
                  </div>
                </div>
                <div class="c-flex">
                  <div class="d-flex col-md-12" v-for="item in fileList" :key="item.id">
                    <div @click="taiTapTin(item)" style="flex: 1; padding-bottom: 5px; cursor: pointer"
                      :title="item.name">
                      {{
                          item.name && item.name.length < 40 ? item.name : "..." + item.name.substr(-39)
                      }} </div>
                        <div>
                          <i class="fas fa-trash-alt" style="cursor: pointer" @click="xoaTapTin(item.id)"></i>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group col-md-12">
            <PointUploaded :latitude.sync="latitude" :longitude.sync="longitude" />
          </div>
          <br />

          <div class="form-group col-md-12" style="padding-top: ">
            <div class="col-sm-12 text-right">
              <button class="btn btn-inverse m-r-20" type="button" @click="cancelProtectedArea()">
                {{ $t("admin.label.cancel") }}
              </button>
              <button class="btn btn-success" @click="addProtectedArea()" type="button">
                {{ $t("admin.label.create_new") }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
import * as env from "../../../../env.js";
import Multiselect from "vue-multiselect";
import * as routes from "../../../../routes.js";
import { ValidationProvider } from "vee-validate";
import PointUploaded from "@/modules/map/edit/point-uploaded.vue";

export default {
  components: {
    Multiselect,
    ValidationProvider,
    PointUploaded,
  },
  data: function (vm) {
    return {
      typeNotification: null,
      textNotification: null,
      name_en: null,
      name_vn: null,
      loading: false,
      name: null,
      orig_name: null,
      sub_loc: null,
      region: null,
      description: null,
      desig: null,
      desig_eng: null,
      desig_type: null,
      desig_types: [],
      iucncat: null,
      rep_m_area: null,
      rep_area: null,
      status: null,
      statuses: [],
      status_year: null,
      gov_type: null,
      gov_types: [],
      mang_auth: null,
      mang_auths: [],
      int_crit: null,
      mang_plan: null,
      legislation: null,
      geometry: null,
      biodiversity_level: null,
      international_criteria: null,
      marine: 1,
      m_area: 0,
      dien_tich_vung_dem: null,
      dien_tich_vung_loi: null,
      isInternational: 0,
      select_desig: [
        "Vườn quốc gia",
        "Khu dự trữ thiên nhiên",
        "Khu bảo tồn loài và sinh cảnh",
        "Khu bảo vệ cảnh quan",
      ],
      select_biodiversity_level: [
        vm.$i18n.t("nbds_lang.protected_areas.biodiversity_level.national"),
        vm.$i18n.t("nbds_lang.protected_areas.biodiversity_level.provincial"),
      ],
      select_international_criteria: [
        "UNESCO-MAB Biosphere Reserve",
        "Wetlands of International Importance (Ramsar)",
        "ASEAN Heritage",
        "World Heritage Site",
      ],
      selectTrangThai: ['Được công nhận', 'Đang đề xuất'],
      status_no: null,
      plan_in: null,
      change: null,
      province_plan: null,
      purpose: null,
      management_proposal: null,
      international_criteria: null,
      habitat_types: null,
      unique_flora: null,
      unique_fauna: null,
      biological_richness: null,
      select_province: [],
      select_region: [],
      purposeData: ["Thành lập mới", "Tách", "Sáp nhập"],
      quanHuyenSelect: [],
      dia_chi: "",
      ngay_ban_hanh: null,
      ke_hoach_quan_ly: null,
      quanHuyens: [],
      geom: null,
      longitude: null,
      latitude: null,
      co_quan_quan_ly: null,
      co_quan_ban_hanh: null,
      fileList: [],
      files: [],
      tienTrinhUpload: null,
      trangThai: 'Đang đề xuất'
    };
  },

  mounted() {
    this.getRegion();
    //this.getSelectItemDesig();
    this.getDesigType();
    this.getStatus();
    this.getGovType();
    this.getMangAuth();
    this.getQuanHuyen();
  },
  watch: {
    marine: function (value) {
      if (value == 1) {
        this.rep_m_area = undefined;
      }
    },
    isInternational: function (value) {
      if (value == 1) {
        this.international_criteria = null;
      }
    },
  },
  methods: {
    customeLabelStatus({ status }) {
      return this.$t("nbds_lang.protected_areas." + status) + "-" + status;
    },
    addTagInterCriter(newTag) {
      this.international_criteria = newTag;
    },
    getAddress(latlng) {
      if (latlng && latlng.lat && latlng.lng) {
        this.longitude = latlng.lng;
        this.latitude = latlng.lat;
      }
    },
    clickUpload() {
      this.$refs["upload-files"].click();
    },
    async handleUpload(e) {
      var isValidate = true;
      let files = e.target.files;
      let data = new FormData();
      if (!files || files.length == 0) {
        return;
      }
      for (let el of files) {
        const isLt2M = el.size / 1024 / 1024 < 50;
        if (!isLt2M) {
          this.typeNotification = 1;
          this.textNotification = "File phải nhỏ hơn 50Mb";
          isValidate = false;
          this.files = [];
          break;
        }
        data.append("file[]", el);
      }

      if (!isValidate) return;

      try {
        this.tienTrinhUpload = 1;
        let File_id = await axios.post("/admin/uploadfiles", data, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
          onUploadProgress: (progressEvent) => {
            var percentCompleted = Math.round(
              (progressEvent.loaded * 100) / progressEvent.total
            );
            this.tienTrinhUpload = percentCompleted;
          },
        });
        this.fileList = [...this.fileList, ...File_id.data.files];
        this.files = this.fileList.map((el) => el.id);
      } catch (error) {
        console.log(error);
      }
      this.$refs["upload-files"].value = null;
    },
    xoaTapTin(id) {
      this.fileList = this.fileList.filter((el) => el.id != id)
        ? this.fileList.filter((el) => el.id != id)
        : [];
      this.form.files = this.fileList.map((el) => el.id)
        ? this.fileList.map((el) => el.id)
        : [];
    },
    taiTapTin(data) {
      window.open("/" + data.disk, "_blank");
    },
    getProvince() {
      if (this.region) {
        axios
          .get(env.endpointhttp + "getprovince", {
            params: {
              region_id: this.region.id,
            },
          })
          .then((response) => {
            this.select_province = response.data.result.map(
              (x) => x.name_vietnamese
            );
          });
      }
    },
    getRegion() {
      axios.get(env.endpointhttp + "getregion").then((response) => {
        this.select_region = response.data.result.map((x) => x);
      });
    },
    getSelectItemDesig() {
      axios.get(env.endpointhttp + routes.gettopdesig).then((response) => {
        this.select_desig = response.data.result;
      });
    },
    getDesigType() {
      axios
        .get(env.endpointhttp + "desigtypes")
        .then((response) => {
          this.desig_types = response.data.result;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getStatus() {
      axios
        .get(env.endpointhttp + "protectedareas/topstatuses", {
          params: {
            getTop: false,
          },
        })
        .then((response) => {
          this.statuses = response.data.result;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getGovType() {
      axios
        .get(env.endpointhttp + "protectedareas/topgovtypes", {
          params: {
            getTop: false,
          },
        })
        .then((response) => {
          this.gov_types = response.data.result;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getMangAuth() {
      axios
        .get(env.endpointhttp + "mangauths")
        .then((response) => {
          this.mang_auths = response.data.result.filter(
            (x) => x.mang_auth != null
          );
        })
        .catch((error) => {
          console.log(error);
        });
    },
    onChangeRegion() {
      this.select_province = [];
      this.sub_loc = null;
      this.getProvince();
    },
    addProtectedArea() {
      this.$validator.validateAll();
      axios
        .post(env.endpointhttp + "admin/protectedareas/add", {
          name: this.name,
          status_no: this.status_no,
          orig_name: this.orig_name,
          plan_in: this.plan_in,
          change: this.change,
          province_plan: this.province_plan,
          purpose: this.purpose,
          management_proposal: this.management_proposal,
          international_criteria: this.international_criteria,
          habitat_types: this.habitat_types,
          unique_flora: this.unique_flora,
          unique_fauna: this.unique_fauna,
          biological_richness: this.biological_richness,
          m_area: this.marine ? this.m_area : 0,
          sub_loc: this.sub_loc,
          region_id: this.region ? this.region.id : null,
          desig: this.desig,
          desig_eng: this.desig_eng,
          desig_type: this.desig_type ? this.desig_type.desig_type : null,
          iucncat: this.iucncat,
          rep_m_area: this.rep_m_area,
          rep_area: this.rep_area,
          status: this.status ? this.status.status : "Designated",
          status_year: this.status_year,
          gov_type: this.gov_type ? this.gov_type.gov_type : null,
          mang_auth: this.mang_auth ? this.mang_auth.mang_auth : null,
          int_crit: this.int_crit,
          mang_plan: this.mang_plan,
          legislation: this.legislation,
          description: this.description,
          marine: this.marine,
          geometry: this.geometry,
          isInternational: this.isInternational,
          name_en: this.name_en,
          name_vn: this.name_vn,
          biodiversity_level: this.biodiversity_level,
          international_criteria: this.international_criteria,
          longitude: this.longitude,
          latitude: this.latitude,
          co_quan_quan_ly: this.co_quan_quan_ly,
          dia_chi: this.dia_chi,
          quan_huyen: this.quanHuyenSelect
            ? this.quanHuyenSelect.map((el) => el.id)
            : null,
          co_quan_ban_hanh: this.co_quan_ban_hanh,
          ke_hoach_quan_ly: this.ke_hoach_quan_ly,
          files: this.files,
          ngay_ban_hanh: this.ngay_ban_hanh,
          dien_tich_vung_loi: this.dien_tich_vung_loi,
          dien_tich_vung_dem: this.dien_tich_vung_dem,
          status: (this.trangThai == 'Đang đề xuất') ? 'Proposed' : 'Designated'
        })
        .then((response) => {
          if (response.data.message == "message.success") {
            this.action = true;
            this.typeNotification = 2;
            this.textNotification = "Thêm mới thành công";
            window.location.href = "/admin/protectedareas";
          }
          else {
            this.action = true;
            this.typeNotification = 1;
            this.textNotification = response.data.error[Object.keys(response.data.error)[0]];
          }
        })
        .catch((error) => {
          this.action = true;
          this.typeNotification = 1;
          this.textNotification = "Thêm mới không thành công";
        })
        .finally(() => {
          this.loading = false;
          this.typeNotification = null;
          this.textNotification = null;
        });
    },
    cancelProtectedArea() {
      window.location.href = "/admin/protectedareas";
    },
    getQuanHuyen() {
      axios.get(env.endpointhttp + "quanhuyensearch").then((response) => {
        this.quanHuyens = response.data.result;
      });
    },
  },
};
</script>

<style scoped>
.form-group {
  margin-bottom: 10px;
}
</style>
