<template>
  <div class="white-box clearfix" style="min-height: 100vh">
    <components-notifications
      :typeInput="typeNotification"
      :textInput="textNotification"
    ></components-notifications>
    <div class="loading-roles" v-if="loading">
      <components-loading-page></components-loading-page>
    </div>
    <p>THÔNG TIN CHUNG</p>
    <hr />
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-6">
          <div class="form-material">
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">
                Tiêu đề
                <span style="color: red; margin-left: 5px">*</span>
              </label>
              <div class="col-md-12">
                <input
                  type="text"
                  v-model="title"
                  class="form-control form-control-line"
                  name="title"
                  placeholder="Mời bạn nhập tiêu đề"
                  id="example-code"
                  v-validate="'required'"
                />
                <span
                  v-show="errors.has('title')"
                  class="help is-danger"
                  style="color: red"
                >
                  Trường tên tiêu đề là trường bắt buộc
                </span>
              </div>
            </div>
            <div
              class="form-group col-md-12 no-padding"
              style="overflow: unset"
            >
              <label class="col-md-12">
                {{ $t("nbds_lang.dataset_resources.publication_date") }}
                <span style="color: red; margin-left: 5px">*</span>
              </label>
              <div class="col-md-12">
                <date-picker
                  v-model="publication_date"
                  :config="optionsTime"
                  placeholder="Mời bạn chọn ngày công bố"
                  name="publication_date"
                  v-validate="'required'"
                ></date-picker>
                <span
                  v-show="errors.has('publication_date')"
                  class="help is-danger"
                  style="color: red"
                >
                  Trường ngày công bố là trường bắt buộc
                </span>
              </div>
            </div>
            <div class="col-md-12 no-padding">
              <label class="col-md-12">Ngôn ngữ</label>
              <div class="col-md-12">
                <multiselect
                  v-model="language"
                  :options="languages"
                  track-by="name"
                  label="name"
                  placeholder="Mời bạn chọn ngôn ngữ"
                  v-validate="'required'"
                  name="language"
                ></multiselect>
                <span
                  v-show="errors.has('language')"
                  class="help is-danger"
                  style="color: red"
                >
                  Trường ngôn ngữ là trường bắt buộc
                </span>
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">Series</label>
              <div class="col-md-12">
                <input
                  type="text"
                  v-model="series"
                  class="form-control form-control-line"
                  name="series"
                  placeholder="Mời bạn nhập series"
                />
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">Tóm lược</label>
              <div class="col-md-12">
                <input
                  type="text"
                  v-model="abstract"
                  class="form-control form-control-line"
                  name="abstract"
                  placeholder="Mời bạn nhập tóm lược"
                />
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">Thông tin thêm</label>
              <div class="col-md-12">
                <input
                  type="text"
                  v-model="additional_info"
                  class="form-control form-control-line"
                  name="additional_info"
                  placeholder="Mời bạn nhập thông tin thêm"
                />
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12"> Quyền sở hữu trí tuệ </label>
              <div class="col-md-12">
                <input
                  type="text"
                  v-model="intellectual_rights"
                  class="form-control form-control-line"
                  name="intellectual_rights"
                  placeholder="Mời bạn nhập quyền sở hữu trí tuệ"
                />
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">Phân bố</label>
              <div class="col-md-12">
                <input
                  type="text"
                  v-model="distribution"
                  class="form-control form-control-line"
                  name="distribution"
                  placeholder="Mời bạn nhập phân bố"
                />
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">Độ phủ</label>
              <div class="col-md-12">
                <input
                  type="text"
                  v-model="coverage"
                  class="form-control form-control-line"
                  name="coverage"
                  placeholder="Mời bạn nhập độ phủ"
                />
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">Địa chỉ website</label>
              <div class="col-md-12">
                <input
                  type="text"
                  v-model="website_url"
                  class="form-control form-control-line"
                  name="website_url"
                  placeholder="Mời bạn nhập địa chỉ website"
                />
                <span class="help is-danger" style="color: red" v-if="website_url">
                {{isValidUrl(website_url) ? null : 'Sai định dạng' }}
                </span>
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">Địa chỉ logo</label>
              <div class="col-md-12">
                <input
                  type="text"
                  v-model="logo_url"
                  class="form-control form-control-line"
                  name="logo_url"
                  placeholder="Mời bạn nhập địa chỉ logo"
                />
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">Trích dẫn</label>
              <div class="col-md-12">
                <input
                  type="text"
                  v-model="citation"
                  class="form-control form-control-line"
                  name="citation"
                  placeholder="Mời bạn nhập trích dẫn"
                />
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">Mô tả về địa lý</label>
              <div class="col-md-12">
                <input
                  type="text"
                  v-model="geographic_description"
                  class="form-control form-control-line"
                  name="geographic_description"
                  placeholder="Mời bạn nhập mô tả về địa lý"
                />
              </div>
            </div>
            <div
              class="col-md-12 no-padding"
              style="padding: 15px; position: relative; margin-right: 30px"
            >
              <label class="typo__label">
                Loại
                <span style="color: red; margin-left: 5px">*</span></label
              >
              <multiselect
                v-model="resource"
                :options="listResource"
                track-by="id"
                label="name"
                :disabled="loadingResource"
                :show-labels="false"
                placeholder="Mời bạn nhập loại"
                v-validate="'required'"
                name="resource"
                :allow-empty="false"
              >
              </multiselect>
              <span
                v-show="errors.has('resource')"
                class="help is-danger"
                style="color: red"
                >Trường loại là trường bắt buộc</span
              >
            </div>

            <div
              class="col-md-12 no-padding"
              style="padding: 15px; position: relative; margin-right: 30px"
            >
              <label class="typo__label">
                {{ $t("nbds_lang.dataset_resources.status") }}
                <span style="color: red; margin-left: 5px">*</span>
              </label>
              <multiselect
                v-model="status"
                :options="optionStatus"
                :searchable="true"
                :show-labels="false"
                placeholder="Mời bạn chọn trạng thái"
                v-validate="'required'"
                name="status"
              ></multiselect>
              <span
                v-show="errors.has('org_defined_format')"
                class="help is-danger"
                style="color: red"
              >
                Trường định dạng trạng thái được xác định là trường bắt buộc
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-material">
            <div
              class="form-group col-md-12 no-padding"
              style="overflow: unset"
            >
              <label class="col-md-12">{{
                $t("nbds_lang.dataset_resources.begin_or_single_date")
              }}</label>
              <div class="col-md-12">
                <date-picker
                  v-model="begin_or_single_date"
                  :config="optionsTime"
                  placeholder="Mời bạn chọn ngày bắt đầu"
                ></date-picker>
              </div>
            </div>
            <div
              class="form-group col-md-12 no-padding"
              style="overflow: unset"
            >
              <label class="col-md-12">{{
                $t("nbds_lang.dataset_resources.end_date")
              }}</label>
              <div class="col-md-12">
                <date-picker
                  v-model="end_date"
                  :config="optionsTime"
                  placeholder="Mời bạn chọn ngày kết thúc"
                ></date-picker>
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">{{
                $t("nbds_lang.dataset_resources.taxonomic_coverage")
              }}</label>
              <div class="col-md-12">
                <input
                  type="text"
                  v-model="taxonomic_coverage"
                  class="form-control form-control-line"
                  name="taxonomic_coverage"
                  placeholder="Mời bạn nhập độ phủ về phân loại"
                />
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">{{
                $t("nbds_lang.dataset_resources.west_bounding_coordinate")
              }}</label>
              <div class="col-md-12">
                <input
                  type="number"
                  v-model="west_bounding_coordinate"
                  class="form-control form-control-line"
                  name="west_bounding_coordinate"
                  placeholder="Mời bạn nhập tọa độ giới hạn tây"
                />
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">{{
                $t("nbds_lang.dataset_resources.east_bounding_coordinate")
              }}</label>
              <div class="col-md-12">
                <input
                  type="number"
                  v-model="east_bounding_coordinate"
                  class="form-control form-control-line"
                  name="east_bounding_coordinate"
                  placeholder="Mời bạn nhập tọa độ giới hạn đông"
                />
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">{{
                $t("nbds_lang.dataset_resources.north_bounding_coordinate")
              }}</label>
              <div class="col-md-12">
                <input
                  type="number"
                  v-model="north_bounding_coordinate"
                  class="form-control form-control-line"
                  name="north_bounding_coordinate"
                  placeholder="Mời bạn nhập tọa độ giới hạn bắc"
                />
              </div>
            </div>
            <div class="form-group col-md-12 no-padding">
              <label class="col-md-12">{{
                $t("nbds_lang.dataset_resources.south_bounding_coordinate")
              }}</label>
              <div class="col-md-12">
                <input
                  type="number"
                  v-model="south_bounding_coordinate"
                  class="form-control form-control-line"
                  name="south_bounding_coordinate"
                  placeholder="Mời bạn nhập tọa độ giới hạn nam"
                />
              </div>
            </div>
            <!--<div class="form-group col-md-12 no-padding">
                            <label  class="col-md-12">{{ $t("nbds_lang.dataset_resources.original_filename") }}</label>
                            <div class="col-md-12">
                                <input type="text" v-model="original_filename" class="form-control form-control-line" name="original_filename"
                                       placeholder="Mời bạn nhập tên của tệp bộ dữ liệu (dưới dạng Excel)">
                            </div>
                        </div>
                        <div class="form-group col-md-12 no-padding">
                            <label  class="col-md-12">{{ $t("nbds_lang.dataset_resources.stored_filename") }}</label>
                            <div class="col-md-12">
                                <input type="text" v-model="stored_filename" class="form-control form-control-line" name="stored_filename"
                                       placeholder="Mời bạn nhập tên tệp lưu trữ trong máy chủ">
                            </div>
            </div>-->
            <div
              class="col-md-12 no-padding"
              style="padding: 15px; position: relative; margin-right: 30px"
            >
              <label class="typo__label"> Quận huyện </label>
              <multiselect
                v-model="quanHuyenSelect"
                :options="quanHuyens"
                :searchable="true"
                :multiple="true"
                track-by="id"
                label="name_vietnamese"
                placeholder="Quận huyện"
                :show-labels="false"
              />
            </div>
            <div
              class="col-md-12 no-padding"
              style="padding: 15px; position: relative; margin-right: 30px"
            >
              <label for="ngayhieuluc" class="form-label"
                >Đối tượng bảo tồn</label
              >
              <multiselect
                v-model="loaiDoiTuongSelect"
                :options="doiTuongHeSinhThai"
                :searchable="true"
                track-by="name"
                label="name"
                :show-labels="false"
                placeholder="Chọn một đối tượng bảo tồn"
                @input="changeDoiTuong()"
              ></multiselect>
            </div>

            <div
              class="col-md-12 no-padding"
              style="padding: 15px; position: relative; margin-right: 30px"
            >
              <div
                v-if="
                  loaiDoiTuongSelect && loaiDoiTuongSelect.code == 'khu_bao_ton'
                "
              >
                <label class="form-label">
                  Khu bảo tồn
                  <span style="color: red; margin-left: 5px">*</span>
                </label>
                <multiselect
                  v-model="doiTuongSelect"
                  :options="dataDoiTuong.khu_bao_ton"
                  :searchable="true"
                  track-by="id"
                  label="name"
                  :show-labels="false"
                  placeholder="Mời bạn nhập khu bảo tồn"
                  v-validate="'required'"
                  name="protectedArea"
                ></multiselect>
                <span
                  v-show="errors.has('protectedArea')"
                  class="help is-danger"
                  style="color: red"
                >
                  Trường khu bảo tồn là trường bắt buộc
                </span>
              </div>
              <div
                v-if="
                  loaiDoiTuongSelect &&
                  loaiDoiTuongSelect.code == 'co_so_bao_ton'
                "
              >
                <label class="form-label">
                  Cơ sở bảo tồn
                  <span style="color: red; margin-left: 5px">*</span>
                </label>
                <multiselect
                  v-model="doiTuongSelect"
                  :options="dataDoiTuong.co_so_bao_ton"
                  :searchable="true"
                  track-by="id"
                  label="ten"
                  :show-labels="false"
                  placeholder="Mời bạn nhập cơ sở bảo tồn"
                  v-validate="'required'"
                  name="protectedArea"
                ></multiselect>
                <span
                  v-show="errors.has('protectedArea')"
                  class="help is-danger"
                  style="color: red"
                >
                  Trường co sở bảo tồn là trường bắt buộc
                </span>
              </div>
              <div
                v-if="
                  loaiDoiTuongSelect &&
                  loaiDoiTuongSelect.code == 'he_sinh_thai'
                "
              >
                <label class="form-label">
                  Hệ sinh thái
                  <span style="color: red; margin-left: 5px">*</span>
                </label>
                <multiselect
                  v-model="doiTuongSelect"
                  :options="dataDoiTuong.he_sinh_thai"
                  :searchable="true"
                  track-by="id"
                  label="name"
                  :show-labels="false"
                  placeholder="Mời bạn nhập hệ sinh thái"
                  v-validate="'required'"
                  name="protectedArea"
                ></multiselect>
                <span
                  v-show="errors.has('protectedArea')"
                  class="help is-danger"
                  style="color: red"
                >
                  Trường Hệ sinh thái là trường bắt buộc
                </span>
              </div>
            </div>
            <div
              class="col-md-12 no-padding"
              style="padding: 15px; position: relative; margin-right: 30px"
            >
              <label class="typo__label">
                {{ $t("nbds_lang.dataset_resources.organization") }}
                <span style="color: red; margin-left: 5px">*</span>
              </label>
              <multiselect
                v-model="organization"
                :options="listOrganization"
                :searchable="true"
                track-by="id"
                label="name"
                :show-labels="false"
                placeholder="Mời bạn nhập cơ quan công bố"
                v-validate="'required'"
                name="organization"
              ></multiselect>
              <span
                v-show="errors.has('organization')"
                class="help is-danger"
                style="color: red"
              >
                Trường cơ quan công bố là trường bắt buộc
              </span>
            </div>
            <div
              class="col-md-12 no-padding"
              style="padding: 15px; position: relative; margin-right: 30px"
            >
              <label class="typo__label">
                {{ $t("nbds_lang.dataset_resources.org_defined_format_id") }}
                <span style="color: red; margin-left: 5px">*</span>
              </label>
              <multiselect
                v-model="org_defined_format"
                :options="listOrgDefinedFormat"
                :searchable="true"
                track-by="id"
                label="name"
                :show-labels="false"
                placeholder="Mời bạn nhập định dạng tổ chức được xác định"
                v-validate="'required'"
                name="org_defined_format"
              ></multiselect>
              <span
                v-show="errors.has('org_defined_format')"
                class="help is-danger"
                style="color: red"
              >
                Trường định dạng tổ chức được xác định là trường bắt buộc
              </span>
            </div>
            <div
              class="col-md-12 no-padding"
              style="padding: 15px; position: relative; margin-right: 30px"
            >
              <label class="typo__label">
                {{ $t("nbds_lang.dataset_resources.dataset_type") }}
                <span style="color: red; margin-left: 5px">*</span>
              </label>
              <multiselect
                v-model="dataset_type"
                :options="listdatasetType"
                :searchable="true"
                track-by="id"
                label="name"
                :show-labels="false"
                placeholder="Mời bạn nhập cơ quan công bố"
                v-validate="'required'"
                name="dataset_type"
              ></multiselect>
              <span
                v-show="errors.has('dataset_type')"
                class="help is-danger"
                style="color: red"
                >Trường loại bộ dữ liệu</span
              >
            </div>
            <div
              class="col-md-12 no-padding"
              style="padding: 15px; position: relative; margin-right: 30px"
            ></div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-12 no-padding">
          <div class="col-sm-12 text-right">
            <button
              class="btn btn-inverse m-r-20"
              type="button"
              @click="cancelAdd()"
            >
              {{ $t("admin.label.cancel") }}
            </button>
            <button
              class="btn btn-success"
              @click="updateDataset()"
              type="button"
            >
              {{ $t("admin.label.update") }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import * as env from "../../../../env.js";
import { endpoint, endpointhttp } from "../../../../env.js";
import Multiselect from "vue-multiselect";
import { ValidationProvider } from "vee-validate";
import * as routes from "../../../../routes.js";
import { doiTuongHeSinhThai } from "../../../../doituongbaoton.js";

export default {
  components: {
    Multiselect,
    ValidationProvider,
  },
  props: {
    value: { type: String },
  },
  data: function () {
    return {
      title: null,
      publication_date: null,
      optionsTime: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
      language: null,
      languages: [
        {
          name: "Vietnamese",
        },
        {
          name: "English",
        },
      ],
      resource: null,
      series: null,
      abstract: null,
      additional_info: null,
      intellectual_rights: null,
      distribution: null,
      coverage: null,
      website_url: null,
      logo_url: null,
      citation: null,
      geographic_description: null,
      keyword: null,
      keyword_thesaurus: null,
      begin_or_single_date: null,
      end_date: null,
      taxonomic_coverage: null,
      west_bounding_coordinate: null,
      east_bounding_coordinate: null,
      north_bounding_coordinate: null,
      south_bounding_coordinate: null,
      original_filename: null,
      stored_filename: null,
      organization: null,
      listOrganization: [],
      protectedArea: null,
      listProtectedArea: [],
      org_defined_format: null,
      listOrgDefinedFormat: [],
      dataset_type: null,
      listdatasetType: [
        { id: 0, name: "Occurrence" },
        { id: 1, name: "Taxon" },
      ],
      id: null,
      loading: false,
      typeNotification: null,
      textNotification: null,
      action: true,
      status: null,
      optionStatus: ["Public", "Draft"],
      listResource: [],
      loadingResource: false,
      quanHuyenSelect: null,
      quanHuyens: [],
      loaiDoiTuongSelect: null,
      doiTuongSelect: null,
      doiTuongHeSinhThai,
      dataDoiTuong: {
        co_so_bao_ton: [],
        khu_bao_ton: [],
      },
    };
  },
  created() {
    this.getlistOrganization();
    this.getlistProtectedArea();
    this.getlistOrgDefinedFormat();
    this.getListResource();
    this.getQuanHuyen();
    this.getData();
  },
  methods: {
    async getDoiTuongBaoTon() {
      let response = await axios.get(env.endpointhttp + "admin/doituongbaoton");
      this.dataDoiTuong = response.data;
    },
    changeDoiTuong() {
      this.doiTuongSelect = null;
    },
    async getData() {
      await this.getDoiTuongBaoTon();
      if (this.value) {
        let data = JSON.parse(this.value);
        this.title = data.title;
        this.publication_date = data.publication_date;
        this.language = { name: data.language };
        this.series = data.series;
        this.abstract = data.abstract;
        this.additional_info = data.additional_info;
        this.intellectual_rights = data.intellectual_rights;
        this.distribution = data.distribution;
        this.coverage = data.coverage;
        this.website_url = data.website_url;
        this.logo_url = data.logo_url;
        this.citation = data.citation;
        this.geographic_description = data.geographic_description;
        this.keyword = data.keyword;
        this.keyword_thesaurus = data.keyword_thesaurus;
        this.begin_or_single_date = data.begin_or_single_date;
        this.end_date = data.end_date;
        this.taxonomic_coverage = data.taxonomic_coverage;
        this.west_bounding_coordinate = data.west_bounding_coordinate;
        this.east_bounding_coordinate = data.east_bounding_coordinate;
        this.north_bounding_coordinate = data.north_bounding_coordinate;
        this.south_bounding_coordinate = data.south_bounding_coordinate;
        this.original_filename = data.original_filename;
        this.stored_filename = data.stored_filename;
        this.status = data.status
          ? data.status[0].toUpperCase() + data.status.slice(1)
          : new Date(data.publication_date) >= new Date(2019, 1, 1)
          ? "Public"
          : "Draft";
        this.id = data.id;
        for (let i in this.listOrganization) {
          if (this.listOrganization[i].id == data.organization_id) {
            this.organization = this.listOrganization[i];
          }
        }
        if (data.protected_area_id) {
          for (let i in this.listProtectedArea) {
            if (
              this.listProtectedArea[i].id ==
              data.dataset_resource_area.protected_area_id
            ) {
              this.protectedArea = this.listProtectedArea[i];
            }
          }
        }

        for (let i in this.listOrgDefinedFormat) {
          if (this.listOrgDefinedFormat[i].id == data.org_defined_format_id) {
            this.org_defined_format = this.listOrgDefinedFormat[i];
          }
        }
        for (let i in this.listdatasetType) {
          if (this.listdatasetType[i].name == data.dataset_type) {
            this.dataset_type = this.listdatasetType[i];
          }
        }
        this.resource = data.resource;
        this.quanHuyenSelect = data.quan_huyen
          ? this.quanHuyens.filter((el) =>
              JSON.parse(data.quan_huyen).includes(el.id)
            )
          : null;

        this.loaiDoiTuongSelect = data.loai_doi_tuong
          ? this.doiTuongHeSinhThai.find((el) => el.code == data.loai_doi_tuong)
          : null;
        this.doiTuongSelect =
          data.loai_doi_tuong && data.doi_tuong_id
            ? this.dataDoiTuong[data.loai_doi_tuong].find(
                (el) => el.id == data.doi_tuong_id
              )
            : null;
      }
    },
    isValidUrl(website_url) {
      try {
        new URL(website_url);
        return true;
      } catch (err) {
        return false;
      };
    },
    updateDataset() {
      this.$validator.validate().then((valid) => {
        if (valid && (this.website_url ? this.isValidUrl(this.website_url) : true )) {
          if (this.action == true) {
            this.loading = true;
            this.action = false;
            axios
              .post(endpointhttp + "admin/dataset/update/" + this.id, {
                title: this.title,
                publication_date: this.publication_date,
                language: this.language && this.language.name,
                series: this.series,
                abstract: this.abstract,
                additional_info: this.additional_info,
                intellectual_rights: this.intellectual_rights,
                distribution: this.distribution,
                coverage: this.coverage,
                website_url: this.website_url,
                logo_url: this.logo_url,
                citation: this.citation,
                geographic_description: this.geographic_description,
                keyword: this.keyword,
                keyword_thesaurus: this.keyword_thesaurus,
                begin_or_single_date: this.begin_or_single_date,
                end_date: this.end_date,
                taxonomic_coverage: this.taxonomic_coverage,
                west_bounding_coordinate: this.west_bounding_coordinate,
                east_bounding_coordinate: this.east_bounding_coordinate,
                north_bounding_coordinate: this.north_bounding_coordinate,
                south_bounding_coordinate: this.south_bounding_coordinate,
                original_filename: this.original_filename,
                stored_filename: this.stored_filename,
                protected_area_id: this.protectedArea
                  ? this.protectedArea.id
                  : "",
                organization_id: this.organization ? this.organization.id : "",
                org_defined_format_id: this.org_defined_format
                  ? this.org_defined_format.id
                  : "",
                dataset_type: this.dataset_type ? this.dataset_type.name : "",
                status: this.status.toLowerCase(),
                resource_id: this.resource ? this.resource.id : "",
                quan_huyen: this.quanHuyenSelect
                  ? this.quanHuyenSelect.map((el) => el.id)
                  : null,
                loai_doi_tuong: this.loaiDoiTuongSelect
                  ? this.loaiDoiTuongSelect.code
                  : null,
                doi_tuong_id: this.doiTuongSelect
                  ? this.doiTuongSelect.id
                  : null,
              })
              .then((response) => {
                if (response.data.message == "message.success") {
                  this.action = true;
                  this.typeNotification = 2;
                  this.textNotification = "Cập nhật thành công.";
                  window.location.href = "/admin/dataset";
                } 
                else if(response.data.message=='datetime'){
                  this.action = true;
                  this.typeNotification = 1;
                  this.textNotification = "Ngày bắt đầu phải nhỏ hơn ngày kết thúc";
                }
                else {
                  this.action = true;
                  this.typeNotification = 1;
                  this.textNotification = "Cập nhật không thành công.";
                }
              })
              .catch((error) => {
                this.action = true;
                this.typeNotification = 1;
                this.textNotification = "Cập nhật không thành công.";
              })
              .finally(() => {
                this.loading = false;
                this.typeNotification = null;
                this.textNotification = null;
              });
          }
        } else {
          console.log(valid);
        }
      });
    },
    cancelAdd() {
      window.location.href = "/admin/dataset";
    },
    getlistOrganization() {
      axios
        .get(env.endpointhttp + "admin/dataset/organizations/info")
        .then((response) => {
          this.listOrganization = response.data.result;
          this.getData();
        });
    },
    getListResource() {
      this.loadingResource = true;
      axios
        .get(env.endpointhttp + routes.getresource_type)
        .then((response) => {
          this.listResource = response.data.result;
        })
        .finally(() => {
          this.loadingResource = false;
        });
    },
    getlistProtectedArea() {
      axios
        .get(env.endpointhttp + "admin/dataset/protectedarea/info")
        .then((response) => {
          this.listProtectedArea = response.data.result;
          this.getData();
        });
    },
    getlistOrgDefinedFormat() {
      axios
        .get(env.endpointhttp + "admin/org-defined-format/info")
        .then((response) => {
          this.listOrgDefinedFormat = response.data.result;
          this.getData();
        });
    },
    getQuanHuyen() {
      axios.get(env.endpointhttp + "quanhuyensearch").then((response) => {
        this.quanHuyens = response.data.result;
        this.getData();
      });
    },
  },
  computed: {},
  watch: {},
};
</script>
