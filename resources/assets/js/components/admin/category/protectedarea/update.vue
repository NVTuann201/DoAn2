<template>
  <div class="white-box clearfix" style="min-height: 100vh">
    <components-notifications :typeInput="typeNotification" :textInput="textNotification"></components-notifications>
    <div class="loading-roles" v-if="loading">
      <components-loading-page></components-loading-page>
    </div>
    <div class="col-md-12">
      <label class="col-md-12 uppercase" style="margin-bottom: 25px">Thông tin chung</label>
      <div class="form-material">
        <div class="col-md-6">
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
              placeholder="Tên gọi" />
            <span v-show="errors.has('name')" class="help is-danger" style="color: red">{{
                $t("nbds_lang.protected_areas.error.name")
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
              placeholder="Tên tiếng anh" />
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
              placeholder="Mô tả địa chỉ" />
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
              placeholder="Số quyết định thành lập" />
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
              placeholder="Cơ quan quản lý" />
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12">
            {{ $t("nbds_lang.protected_areas.rep_area") }}
            <span style="color: red; margin-left: 5px">*</span>
          </label>
          <div class="col-md-12">
            <input type="number" v-model="rep_area" v-validate="'required'" name="rep_area"
              class="form-control form-control-line" :placeholder="$t('nbds_lang.protected_areas.rep_area')" />
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
              placeholder="Diện tích vùng lõi" />
          </div>
        </div>

        <div class="form-group col-md-6">
          <label class="col-md-12">
            Diện tích vùng đệm
            <span style="color: red; margin-left: 5px"></span>
          </label>
          <div class="col-md-12">
            <input type="number" v-model="dien_tich_vung_dem" class="form-control form-control-line"
              placeholder="Diện tích vùng đệm" />
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
                <input type="radio" v-model="isInternational" name="radiointernational" id="radio3" value="1" />
                <label for="radio3">Có</label>
              </div>
            </label>
            <label class="radio-inline">
              <div class="radio radio-info col-md-12">
                <input type="radio" v-model="isInternational" name="radiointernational" id="radio4" value="0" />
                <label for="radio4">Không</label>
              </div>
            </label>
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12">Danh hiệu quốc tế</label>
          <div class="col-md-12">
            <input type="text" v-model="int_crit" name="int_crit" class="form-control form-control-line"
              :placeholder="$t('nbds_lang.protected_areas.int_crit')" />
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12">
            {{ $t("nbds_lang.protected_areas.status_year") }}
          </label>
          <div class="col-md-12">
            <input type="number" v-model="status_year" min="1000" max="9999" step="1" name="status_year"
              class="form-control form-control-line" :placeholder="$t('nbds_lang.protected_areas.status_year')" />
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12">Thuộc kỳ quy hoạch</label>
          <div class="col-md-12">
            <input type="text" v-model="plan_in" name="description" class="form-control"
              placeholder="Thuộc kỳ quy hoạch" />
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="col-md-12">Kế hoạch quản lý</label>
          <div class="col-md-12">
            <input type="text" v-model="ke_hoach_quan_ly" name="description" class="form-control"
              placeholder="Kế hoạch quản lý" />
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
          <br />
          <div class="form-group col-md-12">
            <PointUploaded :latitude.sync="latitude" :longitude.sync="longitude" />
          </div>
          <br />

          <div class="form-group col-md-12" style="padding-top: 20px">
            <div class="col-sm-12 text-right">
              <button class="btn btn-inverse m-r-20" type="button" @click="cancelProtectedArea()">
                {{ $t("admin.label.cancel") }}
              </button>
              <button class="btn btn-success" @click="updateProtectedArea()" type="button">
                Cập nhật
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
import { ValidationProvider } from "vee-validate";
import * as routes from "../../../../routes.js";
import PointUploaded from "@/modules/map/edit/point-uploaded.vue";

export default {
  components: { Multiselect, ValidationProvider, PointUploaded },
  props: ["value"],
  data: function (vm) {
    return {
      urlUpload: "",
      urlDelete: env.endpointhttp + "admin/medias",
      form: {},
      typeNotification: null,
      textNotification: null,
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
      marine: 1,
      id: null,
      isInternational: 0,
      name_en: null,
      name_vn: null,
      biodiversity_level: null,
      international_criteria: null,
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
      select_desig: [
        "Vườn quốc gia",
        "Khu dự trữ thiên nhiên",
        "Khu bảo tồn loài và sinh cảnh",
        "Khu bảo vệ cảnh quan",
      ],
      selectTrangThai: ['Được công nhận', 'Đang đề xuất'],
      dien_tich_vung_dem: null,
      dien_tich_vung_loi: null,
      select_province: [],
      select_region: [],
      medias: [],
      urlGet: "",
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
      latlng: null,
      trangThai: "Đang đề xuất"
    };
  },

  mounted() {
    this.getRegion();
    this.getDefaultValue();
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
  },
  methods: {
    reload() {
      if (this.$refs.gallery) this.$refs.gallery.fetchData();
    },
    addTagInterCriter(newTag) {
      this.international_criteria = newTag;
    },
    customeLabelStatus({ status }) {
      return this.$t("nbds_lang.protected_areas." + status) + "-" + status;
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
    onChangeRegion() {
      this.select_province = [];
      this.sub_loc = null;
      this.getProvince();
    },

    getDefaultValue() {
      let data = JSON.parse(this.value);
      if (this.value) {
        this.urlUpload =
          env.endpointhttp + "admin/protectedareas/uploadImage/" + data.id;
        this.urlGet =
          env.endpointhttp + "api/protectedareas/" + data.id + "/images";
        this.id = data.id;
        this.name = data.name;
        this.region = data.region;
        this.orig_name = data.orig_name;
        this.sub_loc = data.sub_loc;
        this.description = data.description;
        this.desig_eng = data.desig_eng;
        this.desig_type = { desig_type: data.desig_type };
        this.iucncat = data.iucncat;
        this.rep_m_area = data.rep_m_area;
        this.rep_area = data.rep_area;
        this.status = { status: data.status };
        this.status_year = data.status_year;
        this.gov_type = { gov_type: data.gov_type };
        this.mang_auth = { mang_auth: data.mang_auth };
        this.int_crit = data.int_crit;
        this.mang_plan = data.mang_plan;
        this.legislation = data.legislation;
        this.geometry = data.geometry;
        this.marine = data.marine;
        this.isInternational = data.isInternational ? 1 : 0;
        this.name_en = data.name_en;
        this.name_vn = data.name_vn;
        this.biodiversity_level = data.biodiversity_level;
        this.international_criteria = data.international_criteria;
        this.medias = data.media;

        this.status_no = data.status_no;
        this.orig_name = data.orig_name;
        this.plan_in = data.plan_in;
        this.change = data.change;
        this.province_plan = data.province_plan;
        this.purpose = data.purpose;
        this.management_proposal = data.management_proposal;
        this.international_criteria = data.international_criteria;
        this.habitat_types = data.habitat_types;
        this.unique_flora = data.unique_flora;
        this.unique_fauna = data.unique_fauna;
        this.biological_richness = data.biological_richness;

        this.longitude = data.longitude;
        this.latitude = data.latitude;
        this.co_quan_quan_ly = data.co_quan_quan_ly;
        this.dia_chi = data.dia_chi;
        this.quanHuyenSelect = data.quan_huyen
          ? this.quanHuyens.filter((el) =>
            JSON.parse(data.quan_huyen).includes(el.id)
          )
          : null;
        this.co_quan_ban_hanh = data.co_quan_ban_hanh;
        this.ke_hoach_quan_ly = data.ke_hoach_quan_ly;

        if (data.files) {
          this.files = [...JSON.parse(data.files)];
        }
        this.fileList = [...data.fileList];
        this.latlng = [this.latitude, this.longitude];
        this.ngay_ban_hanh = data.ngay_ban_hanh;
        this.desig = data.desig;
        this.dien_tich_vung_dem = data.dien_tich_vung_dem;
        this.dien_tich_vung_loi = data.dien_tich_vung_loi;
        this.trangThai=(data.status=="Proposed") ? 'Đang đề xuất' : "Được công nhận" 
      }
    },
    getAddress(latlng) {
      if (latlng && latlng.lat && latlng.lng) {
        this.longitude = latlng.lng;
        this.latitude = latlng.lat;
      }
    },
    updateProtectedArea() {
      this.$validator.validateAll();
      axios
        .post(env.endpointhttp + "admin/protectedareas/update/" + this.id, {
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
          region_id: this.region && this.region.id,
          orig_name: this.orig_name,
          sub_loc: this.sub_loc,
          desig: this.desig,
          desig_eng: this.desig_eng,
          desig_type: this.desig_type ? this.desig_type.desig_type : "",
          iucncat: this.iucncat,
          rep_m_area: this.rep_m_area,
          rep_area: this.rep_area,
          status: this.status ? this.status.status : "",
          status_year: this.status_year,
          gov_type: this.gov_type ? this.gov_type.gov_type : "",
          mang_auth: this.mang_auth ? this.mang_auth.mang_auth : "",
          int_crit: this.int_crit,
          mang_plan: this.mang_plan,
          legislation: this.legislation,
          description: this.description,
          geometry: this.geometry,
          marine: this.marine,
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
            this.textNotification = "Cập nhật thành công";
            window.location.href = "/admin/protectedareas";
          } else {
            console.log(response.data)
            this.action = true;
            this.typeNotification = 1;
            this.textNotification = response.data.message;
          }
        })
        .catch((error) => {
          this.action = true;
          this.typeNotification = 1;
          this.textNotification = "Cập nhật không thành công";
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
        this.getDefaultValue();
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
