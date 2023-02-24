<template>
  <div class="white-box p-0 addgen">
    <tabs>
      <tab name="Thêm nguồn gen">
        <div class="white-box p-0">
          <div>
            <components-notifications :typeInput="typeNotification" :textInput="textNotification">
            </components-notifications>
            <div class="loading-roles" v-if="loading">
              <components-loading-page></components-loading-page>
            </div>
          </div>
          <div style="padding: 25px">
            <div class="c-flex">
              <div class="d-flex container-line">
                <div class="line-form">
                  <label class="form-label">Nhóm nguồn gen <span class="red-dot">*</span></label>
                  <multiselect v-model="nhomGenSelect" :options="danhmucs.nhom_gen" :searchable="true" track-by="id"
                    name="selectNhom" label="ten" placeholder="Chọn một nhóm gen" v-validate="'required'"
                    :show-labels="false" @input="changNhomGen()">
                  </multiselect>
                  <span v-show="errors.has('selectNhom')" class="help is-danger" style="color: red">Nhóm nguồn gen không
                    thể bỏ trống</span>
                </div>
                <div class="line-form">
                  <label class="form-label">Loại nguồn gen <span class="red-dot"></span></label>
                  <multiselect v-model="loaiGenSelect" :options="loaiGens" :searchable="true" track-by="name"
                    label="ten" placeholder="Chọn một loại nguồn gen" :show-labels="false" @input="changLoaiGen()">
                  </multiselect>
                </div>
                <div class="line-form">
                  <label class="form-label">Tên nguồn gen <span class="red-dot"></span></label>
                  <input class="form-control" v-model="form.ten" placeholder="Nhập tên nguồn gen" name="ten"
                    v-validate="'max:255'" />
                  <span v-show="errors.has('ten')" class="help is-danger" style="color: red">Tên nguồn gen không vượt
                    quá 255 kí tự</span>
                </div>
              </div>
              <div class="d-flex container-line">
                <div class="line-form">
                  <label class="form-label">Tên thông thường</label>
                  <input class="form-control" v-model="form.ten_thong_thuong"
                    placeholder="Tên gọi theo Tiếng Việt phổ thông" name="tenthongthuong" v-validate="'max:255'" />
                  <span v-show="errors.has('tenthongthuong')" class="help is-danger" style="color: red">Tên thông thường
                    không vượt quá 255 kí tự</span>
                </div>
                <div class="line-form">
                  <label class="form-label">Tên dân tộc</label>
                  <input v-model="form.ten_dan_toc" class="form-control" placeholder="Tên gọi theo phiên âm dân tộc"
                    name="tendantoc" v-validate="'max:255'" />
                  <span v-show="errors.has('tendantoc')" class="help is-danger" style="color: red">Tên dân tộc không
                    vượt quá 255 kí tự</span>
                </div>
                <div class="line-form">
                  <label class="form-label">Tên khoa học</label>
                  <input v-model="form.ten_khoa_hoc" class="form-control" placeholder="Tên khoa học" name="tenkhoahoc"
                    v-validate="'max:255'" />
                  <span v-show="errors.has('tenkhoahoc')" class="help is-danger" style="color: red">Tên khoa học không
                    vượt quá 255 kí tự</span>
                </div>
              </div>
              <div class="d-flex container-line">
                <div class="line-form">
                  <label class="form-label">Sử dụng <span class="red-dot"></span></label>
                  <multiselect v-model="suDungSelect" :options="danhmucs.su_dung" :searchable="true" track-by="name"
                    label="name" placeholder="Chức năng sử dụng" :show-labels="false">
                  </multiselect>
                </div>
                <div class="line-form">
                  <label class="form-label">Gen quý hiếm <span class="red-dot"></span></label>
                  <multiselect v-model="genQuyHiemSelect" :options="danhmucs.gen_quy_hiem" :searchable="true"
                    track-by="name" label="name" placeholder="Thuộc gen quý hiếm" :show-labels="false">
                  </multiselect>
                </div>
                <div class="line-form">
                  <label class="form-label">Nơi lưu giữ <span class="red-dot"></span></label>
                  <multiselect v-model="noiLuuGiuSelect" :options="noiLuuTrus" :multiple="true" :searchable="true"
                    :internal-search="false" track-by="ten" label="ten" placeholder="Chọn nơi lưu giữ"
                    :show-labels="false" @search-change="getNoiLuuGiu">
                  </multiselect>
                </div>
              </div>

              <!-- <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label"
              >Sách đỏ Việt Nam <span class="red-dot"></span
            ></label>
            <multiselect
              v-model="sachDoSelect"
              :options="[]"
              :searchable="true"
              track-by="name"
              label="ten"
              placeholder="Đe dọa theo sách đỏ Việt Nam"
              :show-labels="false"
            >
            </multiselect>
          </div>
          <div class="line-form">
            <label class="form-label">Cites<span class="red-dot"></span></label>
            <multiselect
              v-model="citesSelect"
              :options="[]"
              :searchable="true"
              track-by="name"
              name="tinhChat"
              label="ten"
              placeholder="Phụ lục Cites"
              :show-labels="false"
            >
            </multiselect>
          </div>
          <div class="line-form">
            <label class="form-label"
              >Nghị định<span class="red-dot"></span
            ></label>
            <multiselect
              v-model="nghiDinhSelect"
              :options="[]"
              :searchable="true"
              track-by="name"
              label="ten"
              placeholder="Đe dọa theo Nghị Định"
              :show-labels="false"
            >
            </multiselect>
          </div>
        </div> -->

              <div class="d-flex container-line">
                <div class="line-form">
                  <label class="form-label">Mã số Quốc Gia <span class="red-dot"></span></label>
                  <input class="form-control" v-model="form.ma_so_quoc_gia" placeholder="Mã số nguồn gen quốc gia"
                    name="maqg" v-validate="'max:255'" />
                  <span v-show="errors.has('maqg')" class="help is-danger" style="color: red">Mã số Quốc gia không vượt
                    quá 255 kí tự</span>
                </div>
                <div class="line-form">
                  <label class="form-label">Mã số Tỉnh <span class="red-dot"></span></label>
                  <input class="form-control" v-model="form.ma_so_tinh" placeholder="Mã số nguồn gen tỉnh" name="matinh"
                    v-validate="'max:255'" />
                  <span v-show="errors.has('matinh')" class="help is-danger" style="color: red">Ma số tỉnh không vượt
                    quá 255 kí tự</span>
                </div>
                <div class="line-form">
                  <label class="form-label">Tình trạng lưu trữ<span class="red-dot"></span></label>
                  <multiselect v-model="tinhTrangLuuGiuSelect" :options="danhmucs.tinh_trang_luu_giu" :searchable="true"
                    track-by="name" label="name" placeholder="Tình trạng lưu trữ nguồn gen" :show-labels="false">
                  </multiselect>
                </div>
              </div>
              <div class="d-flex container-line">
                <div class="line-form">
                  <label class="form-label">Gen có điều kiện <span class="red-dot"></span></label>
                  <multiselect v-model="genCoDieuKienSelect" :options="danhmucs.gen_co_dieu_kien" :searchable="true"
                    track-by="name" label="name" placeholder="Gen có điều kiện" :show-labels="false">
                  </multiselect>
                </div>
                <div class="line-form">
                  <label class="form-label">Tình trạng nghiên cứu<span class="red-dot"></span></label>
                  <multiselect v-model="tinhTrangNghienCuuSelect" :options="danhmucs.tinh_trang_nghien_cuu"
                    :searchable="true" track-by="name" label="name" placeholder="Tình trạng nghiên cứu"
                    :show-labels="false">
                  </multiselect>
                </div>
                <div class="line-form">
                  <label class="form-label">Tình trạng sử dụng<span class="red-dot"></span></label>
                  <multiselect v-model="tinhTrangSuDungSelect" :options="danhmucs.tinh_trang_su_dung" :searchable="true"
                    track-by="name" label="name" placeholder="Tình trạng sử dụng" :show-labels="false">
                  </multiselect>
                </div>
              </div>

              <div class="d-flex container-line">
                <!-- <div class="line-form">
                  <label class="form-label"
                    >Loài<span class="red-dot"></span
                  ></label>
                  <multiselect
                    v-model="loaiSelect"
                    :options="species"
                    :searchable="true"
                    :internal-search="false"
                    track-by="name"
                    label="name"
                    placeholder="Loài"
                    :loading="isLoadingSpecies"
                    :show-labels="false"
                    @search-change="getSpecies"
                  >
                    <template slot-scope="props" slot="option">
                      <div>
                        <span>{{ props.option.name }}</span>
                        <span
                          style="color: gray; font-size: 14px; float: right"
                          >{{ props.option.name_vietnamese }}</span
                        >
                      </div>
                    </template>
                  </multiselect>
                </div> -->
                <div class="line-form">
                  <label class="form-label">Tri thức truyền thống<span class="red-dot"></span></label>
                  <multiselect v-model="triThucSelect" :multiple="true" :options="danhmucs.tri_thuc_truyen_thong"
                    :searchable="true" :internal-search="false" track-by="name" label="name"
                    placeholder="Tri thức truyền thống">
                  </multiselect>
                </div>
                <div class="line-form">
                  <label class="form-label">Nguồn gốc Việt Nam<span class="red-dot"></span></label>
                  <multiselect v-model="nguonGocVietNamSelect" :options="danhmucs.nguon_goc" :searchable="true"
                    track-by="name" label="name" placeholder="Nguồn gốc Việt Nam" :show-labels="false">
                  </multiselect>
                </div>
                <div class="line-form">
                  <label class="form-label">Nguồn gốc địa phương<span class="red-dot"></span></label>
                  <multiselect v-model="nguonGocDiaPhuongSelect" :options="danhmucs.nguon_goc" :searchable="true"
                    track-by="name" label="name" placeholder="Nguồn gốc địa phương" :show-labels="false">
                  </multiselect>
                </div>
              </div>

              <div class="d-flex container-line">
                <div class="line-form">
                  <label class="form-label">Nguồn gốc cơ sở<span class="red-dot"></span></label>
                  <multiselect v-model="nguonGocCoSoSelect" :options="danhmucs.nguon_goc_co_so" :searchable="true"
                    track-by="name" label="name" placeholder="Nguồn gốc cơ sở" :show-labels="false">
                  </multiselect>
                </div>
                <div class="line-form">
                  <label class="form-label">Xuất xứ<span class="red-dot"></span></label>
                  <input class="form-control" v-model="form.xuat_xu" placeholder="Xuất xứ nguồn gen" />
                </div>
                <div class="line-form">
                  <label class="form-label">Phương thức lưu trữ<span class="red-dot"></span></label>
                  <multiselect v-model="phuongThucLuuTruSelect" :options="danhmucs.phuong_thuc_luu_giu"
                    :searchable="true" track-by="name" label="name" placeholder="Phương thức lưu trữ"
                    :show-labels="false">
                  </multiselect>
                </div>
              </div>
              <div class="d-flex container-line">
                <div class="line-form">
                  <label class="form-label">Diện tích lưu trữ <span class="red-dot"></span></label>
                  <input class="form-control" v-model="form.dien_tich_luu_giu" placeholder="Diện tích lưu trữ"
                    type="number" />
                </div>
                <div class="line-form">
                  <label class="form-label">Vật liệu di truyền lưu giữ <span class="red-dot"></span></label>
                  <input class="form-control" v-model="form.vat_lieu_di_truyen_luu_giu"
                    placeholder="Vật liệu di truyền lưu giữ" />
                </div>
                <div class="line-form">
                  <label class="form-label">Số lượng vật liệu di truyền<span class="red-dot"></span></label>
                  <input class="form-control" v-model="form.so_luong_vat_lieu_di_truyen_luu_giu"
                    placeholder="Số lượng Vật liệu di truyền lưu giữ" type="number"/>
                </div>
              </div>

              <div class="d-flex container-line">
                <div class="line-form">
                  <label class="form-label">Năm bắt đầu lưu trữ <span class="red-dot"></span></label>
                  <input class="form-control" type="number" v-model="form.nam_bat_dau_luu_tru"
                    placeholder="Năm bắt đầu lưu trữ" />
                </div>
                <div class="line-form">
                  <label class="form-label">Hình thức bảo quản <span class="red-dot"></span></label>
                  <input class="form-control" v-model="form.hinh_thuc_bao_quan" placeholder="Hình thức bảo quản" />
                </div>
                <div class="line-form">
                  <label class="form-label">Tình trạng<span class="red-dot"></span></label>
                  <multiselect v-model="tinhTrangSelect" :options="danhmucs.tinh_trang_nguon_gen" :searchable="true"
                    track-by="name" label="name" placeholder="Tình trạng" :show-labels="false">
                  </multiselect>
                </div>
              </div>

              <div class="d-flex container-line">
                <div class="line-form">
                  <label class="form-label">Biện pháp bảo tồn <span class="red-dot"></span></label>
                  <input class="form-control" v-model="form.bien_phap_bao_ton" placeholder="Biện pháp bảo tồn" />
                </div>
                <div class="line-form">
                  <label class="form-label">Khả năng tiếp cận <span class="red-dot"></span></label>
                  <input class="form-control" v-model="form.kha_nang_tiep_can" placeholder="Khả năng tiếp cận" />
                </div>
                <div class="line-form">
                  <label class="form-label">Quy trình tiếp cận <span class="red-dot"></span></label>
                  <input class="form-control" v-model="form.quy_trinh_tiep_can" placeholder="Quy trình tiếp cận" />
                </div>
              </div>

              <div class="d-flex container-line">
                <div class="line-form" style="width: 65%">
                  <label class="form-label">Đặc điểm <span class="red-dot"></span></label>
                  <textarea rows="3" class="form-control" v-model="form.dac_diem" placeholder="Đặc điểm" />
                </div>
                <div class="line-form">
                  <label class="form-label">Ghi chú <span class="red-dot"></span></label>
                  <textarea rows="3" class="form-control" v-model="form.ghi_chu" placeholder="Ghi chú" />
                </div>
              </div>
              <label class="form-label">Hình ảnh </label>
              <b-card-body>
                <TaxonUpload class="p-t-20 full-width" v-model="documents" />
              </b-card-body>
            </div>
            <div class="d-flex" style="justify-content: space-between">
              <div>
                <button type="button" class="btn btn-inverse" data-bs-toggle="modal" data-bs-target="#exampleModal"
                  data-bs-placement="top" @click="back">
                  {{ $t("admin.buttons.cancel") }}
                </button>
              </div>
              <div>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal"
                  data-bs-placement="top" @click="add" :disabled="disableButton">
                  {{ $t("admin.buttons.add") }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </tab>
      <tab name="Nơi lưu trữ">
        <NoiLuuGiu />
      </tab>
    </tabs>
  </div>
</template>

<script>
import { ValidationProvider } from "vee-validate";
import * as env from "../../../../env.js";
import Multiselect from "vue-multiselect";
import NoiLuuGiu from "./noiluugiu.vue";
import TaxonUpload from "./file/taxonUpload.vue";

export default {
  components: { ValidationProvider, Multiselect, NoiLuuGiu, TaxonUpload },
  props: ["danhmucs"],
  data: () => ({
    typeNotification: null,
    textNotification: null,
    loading: false,
    isLoadingSpecies: false,
    nhomGenSelect: null,
    loaiGenSelect: null,
    suDungSelect: null,
    genQuyHiemSelect: null,
    iucnSelect: null,
    sachDoSelect: null,
    citesSelect: null,
    nghiDinhSelect: null,
    isLoadingNoiLuuGiu: false,
    tinhTrangLuuGiuSelect: null,
    triThucSelect: null,
    nguonGocVietNamSelect: null,
    nguonGocDiaPhuongSelect: null,
    nguonGocCoSoSelect: null,
    genCoDieuKienSelect: null,
    phuongThucLuuTruSelect: null,
    tinhTrangNghienCuuSelect: null,
    tinhTrangSuDungSelect: null,
    noiLuuGiuSelect: null,
    tinhTrangSelect: null,
    tienTrinhUpload: null,
    fileList: [],
    disableButton: false,
    loaiGens: [],
    species: [],
    noiLuuTrus: [],
    documents: [],
    form: {
      loai_gen_id: null,
      nhom_gen_id: null,
      ten: null,
      ten_thong_thuong: null,
      ten_dan_toc: null,
      ten_khoa_hoc: null,
      dac_diem: null,
      su_dung_id: null,
      gen_quy_hiem_id: null,
      ma_so_quoc_gia: null,
      ma_so_tinh: null,
      tinh_trang_luu_giu_id: null,
      tinh_trang_nghien_cuu_id: null,
      tinh_trang_su_dung_id: null,
      loai_id: null,
      nguon_goc_viet_nam_id: null,
      nguon_goc_dia_phuong_id: null,
      nguon_goc_co_so_id: null,
      xuat_xu: null,
      phuong_thuc_luu_giu_id: null,
      dien_tich_luu_giu: null,
      vat_lieu_di_truyen_luu_giu: null,
      so_luong_vat_lieu_di_truyen_luu_giu: null,
      nam_bat_dau_luu_tru: null,
      hinh_thuc_bao_quan: null,
      tinh_trang_id: null,
      bien_phap_bao_ton: null,
      kha_nang_tiep_can: null,
      quy_trinh_tiep_can: null,
      gen_co_dieu_kien_id: null,
      ghi_chu: null,
      noi_luu_giu: [],
    },
  }),
  methods: {
    back() {
      window.location.href = "/admin/genetic";
    },
    changNhomGen() {
      this.form.loai_gen_id = null;
      this.loaiGenSelect = null;
      if (this.nhomGenSelect && this.nhomGenSelect.id) {
        this.loaiGens = this.danhmucs.loai_gen.filter(
          (el) => el.nhom_gen_id == this.nhomGenSelect.id
        );
      } else this.loaiGens = this.danhmucs.loai_gen;
    },
    changLoaiGen() {
      if (this.loaiGenSelect && this.loaiGenSelect.id) {
        this.nhomGenSelect = this.danhmucs.nhom_gen.find(
          (el) => el.id == this.loaiGenSelect.nhom_gen_id
        );
      } else this.nhomGenSelect = null;
    },
    resetForm() {
      this.nhomGenSelect = null;
      this.loaiGenSelect = null;
      this.suDungSelect = null;
      this.genQuyHiemSelect = null;
      this.iucnSelect = null;
      this.sachDoSelect = null;
      this.citesSelect = null;
      this.nghiDinhSelect = null;
      this.tinhTrangLuuGiuSelect = null;
      this.triThucSelect = null;
      this.nguonGocVietNamSelect = null;
      this.nguonGocDiaPhuongSelect = null;
      this.nguonGocCoSoSelect = null;
      this.genCoDieuKienSelect = null;
      this.phuongThucLuuTruSelect = null;
      this.tinhTrangNghienCuuSelect = null;
      this.tinhTrangSuDungSelect = null;
      this.tinhTrangSelect = null;
      this.noiLuuGiuSelect = null;
      this.form = {
        loai_gen_id: null,
        nhom_gen_id: null,
        ten: null,
        ten_thong_thuong: null,
        ten_dan_toc: null,
        ten_khoa_hoc: null,
        dac_diem: null,
        su_dung_id: null,
        gen_quy_hiem_id: null,
        ma_so_quoc_gia: null,
        ma_so_tinh: null,
        tinh_trang_luu_giu_id: null,
        tinh_trang_nghien_cuu_id: null,
        tinh_trang_su_dung_id: null,
        loai_id: null,
        nguon_goc_viet_nam_id: null,
        nguon_goc_dia_phuong_id: null,
        nguon_goc_co_so_id: null,
        xuat_xu: null,
        phuong_thuc_luu_giu_id: null,
        dien_tich_luu_giu: null,
        vat_lieu_di_truyen_luu_giu: null,
        so_luong_vat_lieu_di_truyen_luu_giu: null,
        nam_bat_dau_luu_tru: null,
        hinh_thuc_bao_quan: null,
        tinh_trang_id: null,
        bien_phap_bao_ton: null,
        kha_nang_tiep_can: null,
        quy_trinh_tiep_can: null,
        gen_co_dieu_kien_id: null,
        ghi_chu: null,
        noi_luu_giu: [],
      };
    },
    setSeletFrom() {
      this.form.su_dung_id = this.suDungSelect ? this.suDungSelect.id : null;
      this.form.loai_gen_id = this.loaiGenSelect ? this.loaiGenSelect.id : null;
      this.form.nhom_gen_id = this.nhomGenSelect ? this.nhomGenSelect.id : null;
      this.form.gen_quy_hiem_id = this.genQuyHiemSelect
        ? this.genQuyHiemSelect.id
        : null;
      this.form.tinh_trang_luu_giu_id = this.tinhTrangLuuGiuSelect
        ? this.tinhTrangLuuGiuSelect.id
        : null;
      this.form.tinh_trang_nghien_cuu_id = this.tinhTrangNghienCuuSelect
        ? this.tinhTrangNghienCuuSelect.id
        : null;
      this.form.tinh_trang_su_dung_id = this.tinhTrangSuDungSelect
        ? this.tinhTrangSuDungSelect.id
        : null;
      this.form.tri_thuc_id = this.triThucSelect
        ? this.triThucSelect.map((el) => el.id)
        : null;
      this.form.nguon_goc_viet_nam_id = this.nguonGocVietNamSelect
        ? this.nguonGocVietNamSelect.id
        : null;
      this.form.nguon_goc_dia_phuong_id = this.nguonGocDiaPhuongSelect
        ? this.nguonGocDiaPhuongSelect.id
        : null;
      this.form.nguon_goc_co_so_id = this.nguonGocCoSoSelect
        ? this.nguonGocCoSoSelect.id
        : null;
      this.form.phuong_thuc_luu_giu_id = this.phuongThucLuuTruSelect
        ? this.phuongThucLuuTruSelect.id
        : null;
      this.form.tinh_trang_id = this.tinhTrangSelect
        ? this.tinhTrangSelect.id
        : null;
      this.form.gen_co_dieu_kien_id = this.genCoDieuKienSelect
        ? this.genCoDieuKienSelect.id
        : null;
      this.form.noi_luu_giu = [];
      if (this.noiLuuGiuSelect && this.noiLuuGiuSelect.length) {
        this.noiLuuGiuSelect.forEach((el) => {
          this.form.noi_luu_giu.push(el.id);
        });
      }
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        this.setSeletFrom();
        this.disableButton = true;
        if (validate) {
          this.form.ten=this.form.ten.replace(/\s+/g, ' ').trim()
          axios
            .post(env.endpointhttp + "admin/genetic/add", this.form)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Thêm mới thành công";
                this.resetForm();
              }
              else {
                this.typeNotification = 1;
                this.textNotification = response.data.error[Object.keys(response.data.error)[0]];
              }
            })
            .catch(() => {
              this.typeNotification = 1;
              this.textNotification = "Thêm mới thất bại";
            })
            .finally(() => {
              this.disableButton = false;
              this.loading = false;
              this.showForm = false;
              this.typeNotification = null;
              this.textNotification = null;
            });
        }
      });
    },
    clickUpload() {
      this.$refs["upload-files"].click();
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
    getSpecies(query = "") {
      if (typeof window.LIT !== "undefined") {
        clearTimeout(window.LIT);
      }
      window.LIT = setTimeout(() => {
        this.isLoadingSpecies = true;
        let loai_id = this.form.loai_id ? this.form.loai_id : "";
        axios
          .get(
            env.endpointhttp +
            "admin/genetic/searchspecies?search=" +
            query +
            "&loai_id=" +
            loai_id
          )
          .then((response) => {
            this.species = response.data;
            this.isLoadingSpecies = false;
          })
          .catch(() => {
            this.isLoadingSpecies = false;
          });
      }, 500);
    },
    getNoiLuuGiu(query = "") {
      this.isLoadingNoiLuuGiu = true;
      axios
        .get(env.endpointhttp + "admin/genetic/searchnoiluugiu?search=" + query)
        .then((response) => {
          this.noiLuuTrus = response.data;
          this.isLoadingNoiLuuGiu = false;
        })
        .catch(() => {
          this.isLoadingNoiLuuGiu = false;
        });
    },
  },
  mounted() {
    this.loaiGens = [...this.danhmucs.loai_gen];
    this.getSpecies();
    this.getNoiLuuGiu();
  },
};
</script>

<style scoped>
.line-form {
  width: 30%;
}

.container-line {
  justify-content: space-between;
  margin-bottom: 20px;
}
</style>
<style >
/* .addgen .tabs-component {
  margin: 4em 0;
} */

.addgen .tabs-component-tabs {
  border: solid 1px #ddd;
  border-radius: 6px;
  margin-bottom: 5px;
}

@media (min-width: 700px) {
  .addgen .tabs-component-tabs {
    border: 0;
    align-items: stretch;
    display: flex;
    justify-content: flex-start;
    margin-bottom: -1px;
  }
}

.addgen .tabs-component-tab {
  color: #999;
  font-size: 14px;
  font-weight: 600;
  margin-right: 0;
  list-style: none;
}

.addgen .tabs-component-tab:not(:last-child) {
  border-bottom: dotted 1px #ddd;
}

.tabs-component-tab:hover {
  color: #666;
}

.addgen .tabs-component-tab.is-active {
  color: #000;
}

.addgen .tabs-component-tab.is-disabled * {
  color: #cdcdcd;
  cursor: not-allowed !important;
}

@media (min-width: 700px) {
  .addgen .tabs-component-tab {
    background-color: #fff;
    border: solid 1px #ddd;
    border-radius: 3px 3px 0 0;
    margin-right: 0.5em;
    transform: translateY(2px);
    transition: transform 0.3s ease;
  }

  .addgen .tabs-component-tab.is-active {
    border-bottom: solid 1px #fff;
    z-index: 2;
    transform: translateY(0);
  }
}

.addgen .tabs-component-tab-a {
  align-items: center;
  color: inherit;
  display: flex;
  padding: 0.75em 1em;
  text-decoration: none;
}

.addgen .tabs-component-panels {
  padding: 4em 0;
}

@media (min-width: 700px) {
  .addgen .tabs-component-panels {
    border-top-left-radius: 0;
    background-color: #fff;
    border: solid 1px #ddd;
    border-radius: 0 6px 6px 6px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    padding: 2em 2em;
  }
}
</style>