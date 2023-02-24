<?php

use App\Lookup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LookupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::statement('TRUNCATE lookups CASCADE');
        $data = [
            [
                'name' => 'Luật',
                'code' => 'luat',
                'group' => 'LoaiVanBanPhapLuat',
            ],
            [
                'name' => 'Thông tư',
                'code' => 'thong_tu',
                'group' => 'LoaiVanBanPhapLuat',
            ],
            [
                'name' => 'Nghị Định',
                'code' => 'nghi_dinh',
                'group' => 'LoaiVanBanPhapLuat',
            ],
            [
                'name' => 'Quyết Định',
                'code' => 'quyet_dinh',
                'group' => 'LoaiVanBanPhapLuat',
            ],
            [
                'name' => 'Lệnh/ Pháp lệnh',
                'code' => 'phap_lenh',
                'group' => 'LoaiVanBanPhapLuat',
            ],
            [
                'name' => 'Nghị quyết',
                'code' => 'nghi_quyet',
                'group' => 'LoaiVanBanPhapLuat',
            ],
            [
                'name' => 'Hướng dẫn',
                'code' => 'huong_dan',
                'group' => 'LoaiVanBanPhapLuat',
            ],
            [
                'name' => 'Chỉ thị',
                'code' => 'chi_thi',
                'group' => 'LoaiVanBanPhapLuat',
            ],
            [
                'name' => 'Kết luận',
                'code' => 'ket_luan',
                'group' => 'LoaiVanBanPhapLuat',
            ],
            [
                'name' => 'Quy định',
                'code' => 'quy_dinh',
                'group' => 'LoaiVanBanPhapLuat',
            ],
            [
                'name' => 'Văn bản khác',
                'code' => 'khac',
                'group' => 'LoaiVanBanPhapLuat',
            ],
            [
                'name' => 'Quốc hội',
                'code' => 'quoc_hoi',
                'group' => 'CoQuanBanHanhVanBanPhapLuat',
            ],
            [
                'name' => 'Thủ tướng chính phủ',
                'code' => 'thu_tuong',
                'group' => 'CoQuanBanHanhVanBanPhapLuat',
            ],
            [
                'name' => 'Bộ Tài Nguyên Môi Trường',
                'code' => 'TNMT',
                'group' => 'CoQuanBanHanhVanBanPhapLuat',
            ],
            [
                'name' => 'Ban chấp hành trung ương',
                'code' => 'ban_chap_hanh_trung_uong',
                'group' => 'CoQuanBanHanhVanBanPhapLuat',
            ],
            [
                'name' => 'Văn phòng quốc hội',
                'code' => 'van_phong_quoc_hoi',
                'group' => 'CoQuanBanHanhVanBanPhapLuat',
            ],
            [
                'name' => 'Bộ Tài nguyên và Môi trường -  Bộ Nông nghiệp và Phát triển Nông thôn',
                'code' => 'bo_tnmt_ptnt',
                'group' => 'CoQuanBanHanhVanBanPhapLuat',
            ],
            [
                'name' => 'Bộ Tài chính - Bộ Tài nguyên và Môi trường',
                'code' => 'bo_tc_tnmt',
                'group' => 'CoQuanBanHanhVanBanPhapLuat',
            ],
            [
                'name' => 'Chính phủ',
                'code' => 'chinh_phu',
                'group' => 'CoQuanBanHanhVanBanPhapLuat',
            ],
            [
                'name' => 'Ban bí thư',
                'code' => 'ban_bi_thu',
                'group' => 'CoQuanBanHanhVanBanPhapLuat',
            ],
            [
                'name' => 'Chủ tịch nước',
                'code' => 'chu_tich_nuoc',
                'group' => 'CoQuanBanHanhVanBanPhapLuat',
            ],
            [
                'name' => 'Bộ ngành khác',
                'code' => 'khac',
                'group' => 'CoQuanBanHanhVanBanPhapLuat',
            ],
            [
                'name' => 'Đa dạng sinh học chung',
                'code' => 'DDSH_chung',
                'group' => 'LinhVucVanBanPhapLuat',
            ],
            [
                'name' => 'Nguồn gen',
                'code' => 'nguon_gen',
                'group' => 'LinhVucVanBanPhapLuat',
            ],
            [
                'name' => 'Thủy sản',
                'code' => 'thuy_san',
                'group' => 'LinhVucVanBanPhapLuat',
            ],
            [
                'name' => 'Nông nghiệp',
                'code' => 'nong_nghiep',
                'group' => 'LinhVucVanBanPhapLuat',
            ],
            [
                'name' => 'Lâm Nghiệp',
                'code' => 'lam_nghiep',
                'group' => 'LinhVucVanBanPhapLuat',
            ],
            [
                'name' => 'Giấy phép tiếp cận nguồn gen',
                'code' => 'tiep_can_nguon_gen',
                'group' => 'GiayPhepDDSH',
            ],
            [
                'name' => 'Giấy chứng nhận cơ sở bảo tồn ĐDSH',
                'code' => 'chung_nhan_co_so_bao_ton',
                'group' => 'GiayPhepDDSH',
            ],
            [
                'name' => 'Giấy chứng nhận bản quyền tri thức truyền thống về nguồn gen',
                'code' => 'chung_nhan_ban_quyen_tri_thuc',
                'group' => 'GiayPhepDDSH',
            ],
            [
                'name' => 'Giấy chứng nhận an toàn của sinh vật biến đổi gen, mẫu vật di truyền của sinh vật biến đổi gen với ĐDSH',
                'code' => 'chung_nhan_ban_quyen_tri_thuc',
                'group' => 'GiayPhepDDSH',
            ],
            [
                'name' => 'Gia hạn',
                'code' => 'gia_han',
                'group' => 'LoaiGiayPhep',
            ],
            [
                'name' => 'Thu hồi',
                'code' => 'thu_hoi',
                'group' => 'LoaiGiayPhep',
            ],
            [
                'name' => 'Cấp mới',
                'code' => 'cap_moi',
                'group' => 'LoaiGiayPhep',
            ],
            [
                'name' => 'Cấp chính phủ',
                'code' => 'cap_chinh_phu',
                'group' => 'DanhNghia',
            ],
            [
                'name' => 'Cấp bộ',
                'code' => 'cap_bo',
                'group' => 'DanhNghia',
            ],
            [
                'name' => 'Cấp tổng cục',
                'code' => 'cap_tong_cuc',
                'group' => 'DanhNghia',
            ],
            [
                'name' => 'UBND Tỉnh',
                'code' => 'ubnd_tinh',
                'group' => 'DanhNghia',
            ],
            [
                'name' => 'UBND Huyện',
                'code' => 'ubnd_huyen',
                'group' => 'DanhNghia',
            ],
            [
                'name' => 'UBND Xã',
                'code' => 'ubnd_xa',
                'group' => 'DanhNghia',
            ],
            [
                'name' => 'Khác',
                'code' => 'khac',
                'group' => 'DanhNghia',
            ],
            [
                'name' => 'Trung Ương/Quốc gia',
                'code' => 'quoc_gia',
                'group' => 'phanCap',
            ],
            [
                'name' => 'Địa phương',
                'code' => 'dia_phuong',
                'group' => 'phanCap',
            ],
            [
                'name' => 'Giấy phép tiếp cận nguồn gen',
                'code' => 'giay_phep_tiep_can',
                'group' => 'loaiGiayPhepDaDangSinhHoc',
            ],
            [
                'name' => 'Cho phép đưa nguồn gen ra nước ngoài phục vụ nghiên cứu không vì mục đích thương mại',
                'code' => 'cho_phep_dua_ra_nuoc_ngoai',
                'group' => 'loaiGiayPhepDaDangSinhHoc',
            ],
            [
                'name' => 'Giấy chứng nhận an toàn sinh học đối với cây trồng biến đổi gen',
                'code' => 'chung_nhan_an_toan',
                'group' => 'loaiGiayPhepDaDangSinhHoc',
            ],
            [
                'name' => 'Giấy phép khai thác loài thuộc Danh mục loài được ưu tiên bảo vệ',
                'code' => 'chung_nhan_uu_tien',
                'group' => 'loaiGiayPhepDaDangSinhHoc',
            ],

            [
                'name' => 'Tổ chức trong nước',
                'code' => 'to_chuc_trong_nuoc',
                'group' => 'doiTuongCapGiayDdsh',
            ],
            [
                'name' => 'Cá nhân trong nước',
                'code' => 'ca_nhan_trong_nuoc',
                'group' => 'doiTuongCapGiayDdsh',
            ],
            [
                'name' => 'Tổ chức nước ngoài',
                'code' => 'to_chuc_nuoc_ngoai',
                'group' => 'doiTuongCapGiayDdsh',
            ],
            [
                'name' => 'Cá nhân nước ngoài',
                'code' => 'ca_nhan_nuoc_ngoai',
                'group' => 'doiTuongCapGiayDdsh',
            ],
            [
                'name' => 'Nghiên cứu không vì mục đích thương mại',
                'code' => 'nghien_cuu_khong_thuong_mai',
                'group' => 'mucDichCapGiayDdsh',
            ],
            [
                'name' => 'Học tập',
                'code' => 'hoc_tap',
                'group' => 'mucDichCapGiayDdsh',
            ],
            [
                'name' => 'Nghiên cứu vì mục đích thương mại',
                'code' => 'nghien_cuu_thuong_mai',
                'group' => 'mucDichCapGiayDdsh',
            ],
            [
                'name' => 'Phát triển sản phẩm thương mại',
                'code' => 'phat_trien_san_pham',
                'group' => 'mucDichCapGiayDdsh',
            ],
            [
                'name' => 'Thỏa thuận',
                'code' => 'thoa_thuan_htqt',
                'group' => 'phanLoaiHopTacQuocTe',
            ],
            [
                'name' => 'Chương trình',
                'code' => 'chuong_trinh_htqt',
                'group' => 'phanLoaiHopTacQuocTe',
            ],
            [
                'name' => 'Biên bản ghi nhớ',
                'code' => 'bien_ban_ghi_nho',
                'group' => 'phanLoaiHopTacQuocTe',
            ],
            [
                'name' => 'Dự án',
                'code' => 'du_an_htqt',
                'group' => 'phanLoaiHopTacQuocTe',
            ],
            [
                'name' => 'Khác',
                'code' => 'htqt_khac',
                'group' => 'phanLoaiHopTacQuocTe',
            ],
            [
                'name' => 'Thực hiện chính sách',
                'code' => 'thuc_hien_chinh_sach',
                'group' => 'hinhthucsangkien',
            ],
            [
                'name' => 'Hợp tác quốc tế',
                'code' => 'hop_tac_quoc_te',
                'group' => 'hinhthucsangkien',
            ],
            [
                'name' => 'Hoạt động tình nguyện',
                'code' => 'tinh_nguyen',
                'group' => 'hinhthucsangkien',
            ],
            [
                'name' => 'Hoạt động khác',
                'code' => 'hoat_dong_khac',
                'group' => 'hinhthucsangkien',
            ],
            [
                'name' => 'Ngân sách nhà nước',
                'code' => 'ngan_sach',
                'group' => 'nguonKinhPhiHangNam',
            ],
            [
                'name' => 'Đầu tư, đóng góp',
                'code' => 'dau_tu_dong_gop',
                'group' => 'nguonKinhPhiHangNam',
            ],
            [
                'name' => 'Khác',
                'code' => 'khac',
                'group' => 'nguonKinhPhiHangNam',
            ],
            [
                'name' => 'Giống cây trồng',
                'code' => 'giong_cay_trong',
                'group' => 'nhomnguongen',
            ],
            [
                'name' => 'Giống vật nuôi',
                'code' => 'giong_vat_nuoi',
                'group' => 'nhomnguongen',
            ],
            [
                'name' => 'Động vật hoang dã',
                'code' => 'dong_vat_hoang_da',
                'group' => 'nhomnguongen',
            ],
            [
                'name' => 'Thực vật hoang dã',
                'code' => 'thuc_vat_hoang_da',
                'group' => 'nhomnguongen',
            ],

            [
                'name' => 'Thủy sản',
                'code' => 'thuy_san',
                'group' => 'nhomnguongen',
            ],
            [
                'name' => 'Nấm',
                'code' => 'nam',
                'group' => 'nhomnguongen',
            ],
            [
                'name' => 'Vi sinh vật',
                'code' => 'vi_sinh_vat',
                'group' => 'nhomnguongen',
            ],
            [
                'name' => 'Thực phẩm',
                'code' => 'thuc_pham',
                'group' => 'sudungnguongen',
            ],
            [
                'name' => 'Chữa bệnh',
                'code' => 'chua_benh',
                'group' => 'sudungnguongen',
            ],
            [
                'name' => 'Nguyên liệu sản xuất',
                'code' => 'nguyen_lieu_san_xuat',
                'group' => 'sudungnguongen',
            ],
            [
                'name' => 'Khác',
                'code' => 'su_dung_khac',
                'group' => 'sudungnguongen',
            ],
            [
                'name' => 'Gen quý hiếm',
                'code' => 'gen_quy_hiem',
                'group' => 'genquyhiem',
            ],
            [
                'name' => 'Gen đặc sản, đặc hữu',
                'code' => 'gen_dac_san',
                'group' => 'genquyhiem',
            ],
            [
                'name' => 'Gen bản địa',
                'code' => 'gen_ban_dia',
                'group' => 'genquyhiem',
            ],
            [
                'name' => 'Gen có giá trị kinh tế',
                'code' => 'gen_co_gia_tri_kinh_te',
                'group' => 'genquyhiem',
            ],
            [
                'name' => 'Khác',
                'code' => 'gen_khac',
                'group' => 'genquyhiem',
            ],

            [
                'name' => 'Nguồn gen được thu thập và lưu trữ',
                'code' => 'duoc_luu_tru',
                'group' => 'tinhtrangluugiu',
            ],
            [
                'name' => 'Chưa được lưu trữ',
                'code' => 'chua_duoc_luu_tru',
                'group' => 'tinhtrangluugiu',
            ],
            [
                'name' => 'Gen đã được đánh gia ban đầu',
                'code' => 'gen_duoc_danh_gia_ban_dau',
                'group' => 'tinhtrangnghiencuu',
            ],
            [
                'name' => 'Đánh giá chi tiết',
                'code' => 'danh_gia_chi_tiet',
                'group' => 'tinhtrangnghiencuu',
            ],
            [
                'name' => 'Đánh giá đặc điểm di truyền',
                'code' => 'danh_gia_dac_diem_di_truyen',
                'group' => 'tinhtrangnghiencuu',
            ],
            [
                'name' => 'Đánh giá đày đủ',
                'code' => 'danh_gia_day_du',
                'group' => 'tinhtrangnghiencuu',
            ],
            [
                'name' => 'Chưa được đánh giá',
                'code' => 'chua_danh_gia',
                'group' => 'tinhtrangnghiencuu',
            ],
            [
                'name' => 'Gen được sử dụng, chia sẻ và thương mại hóa',
                'code' => 'su_dung_chia_se',
                'group' => 'tinhtrangsudung',
            ],
            [
                'name' => 'Gen chưa được sử dụng, chia sẻ và thương mại hóa',
                'code' => 'chua_su_dung_chia_se',
                'group' => 'tinhtrangsudung',
            ],
            [
                'name' => 'Bản địa',
                'code' => 'ban_dia',
                'group' => 'nguongocgen',
            ],
            [
                'name' => 'Tự nhiên hoang dã',
                'code' => 'tu_nhien_hoang_da',
                'group' => 'nguongocgen',
            ],
            [
                'name' => 'Bán hoang dã',
                'code' => 'ban_hoang_da',
                'group' => 'nguongocgen',
            ],
            [
                'name' => 'Cư trú',
                'code' => 'cu_tru',
                'group' => 'nguongocgen',
            ],
            [
                'name' => 'Du nhập',
                'code' => 'du_nhap',
                'group' => 'nguongocgen',
            ],
            [
                'name' => 'Xâm lấn',
                'code' => 'xam_lan',
                'group' => 'nguongocgen',
            ],
            [
                'name' => 'Di cư',
                'code' => 'di_cu',
                'group' => 'nguongocgen',
            ],
            [
                'name' => 'Nhập nội',
                'code' => 'nhap_noi',
                'group' => 'nguongocgen',
            ],
            [
                'name' => 'Lai',
                'code' => 'lai',
                'group' => 'nguongocgen',
            ],
            [
                'name' => 'Chưa biết',
                'code' => 'chua_biet',
                'group' => 'nguongocgen',
            ],
            [
                'name' => 'Khai thác tự nhiên',
                'code' => 'khai_thac_tu_nhien',
                'group' => 'nguongoccoso',
            ],
            [
                'name' => 'Tiếp nhận từ tổ chức cá nhân',
                'code' => 'tiep_nhan_tu_to_chuc_ca_nhan',
                'group' => 'nguongoccoso',
            ],
            [
                'name' => 'Gây nuôi',
                'code' => 'gay_nuoi',
                'group' => 'nguongoccoso',
            ],
            [
                'name' => 'Tặng cho',
                'code' => 'tang_cho',
                'group' => 'nguongoccoso',
            ],
            [
                'name' => 'Nhập khẩu',
                'code' => 'nhap_khau',
                'group' => 'nguongoccoso',
            ],
            [
                'name' => 'InSitu (Bảo tồn tại chỗ)',
                'code' => 'insitu',
                'group' => 'phuongthucluugiu',
            ],
            [
                'name' => 'ExSitu (Bảo tồn chuyển chỗ)',
                'code' => 'exsitu',
                'group' => 'phuongthucluugiu',
            ],
            [
                'name' => 'Nguy cấp',
                'code' => 'nguycap',
                'group' => 'tinhtrangnguongen',
            ],
            [
                'name' => 'Đã được khôi phục và thoát khỏi tình trạng nguy cấp',
                'code' => 'thoatkhoinguycap',
                'group' => 'tinhtrangnguongen',
            ],
            [
                'name' => 'Chưa có thông tin',
                'code' => 'kothongtin',
                'group' => 'tinhtrangnguongen',
            ],
            [
                'name' => 'Có',
                'code' => 'co',
                'group' => 'gencodk',
            ],
            [
                'name' => 'Không',
                'code' => 'ko',
                'group' => 'gencodk',
            ],
            [
                'name' => 'Chưa biết',
                'code' => 'chuabiet',
                'group' => 'gencodk',
            ],

            [
                'name' => 'Nguồn gen làm lương thực, thực phẩm',
                'code' => 'luongthucthucpham',
                'group' => 'nhomtrithuctruyenthong',
            ],
            [
                'name' => 'Nguồn gen làm dược phẩm/dược liệu/bài thuốc/mỹ phẩm',
                'code' => 'duocpham',
                'group' => 'nhomtrithuctruyenthong',
            ],
            [
                'name' => 'Nguồn gen mang ý nghĩa văn hóa/tâm linh',
                'code' => 'tamlinh',
                'group' => 'nhomtrithuctruyenthong',
            ],
            [
                'name' => 'Nguồn gen khác',
                'code' => 'nguongenkhac',
                'group' => 'nhomtrithuctruyenthong',
            ],
            [
                'name' => 'Hệ sinh thái rừng trên cạn',
                'code' => 'hst_rung_tren_can',
                'group' => 'hesinhthai',
            ],
            [
                'name' => 'Hệ sinh thái nông nghiệp',
                'code' => 'hst_nong_nghiep',
                'group' => 'hesinhthai',
            ],
            [
                'name' => 'Hệ sinh thái dân cư',
                'code' => 'hst_dan_cu',
                'group' => 'hesinhthai',
            ],
            [
                'name' => 'Hệ sinh thái đất ngập nước',
                'code' => 'hst_dat_ngap_nuoc',
                'group' => 'hesinhthai',
            ],
            [
                'name' => 'Hệ sinh thái cỏ cây tráng bụi',
                'code' => 'hst_dat_co_cay_trang_bui',
                'group' => 'hesinhthai',
            ],

            [
                'name' => 'Rừng đặc dụng',
                'code' => 'rung_dac_dung',
                'group' => 'phanloairunghesinhthai',
            ],
            [
                'name' => 'Rừng phòng hộ',
                'code' => 'rung_phong_ho',
                'group' => 'phanloairunghesinhthai',
            ],
            [
                'name' => 'Rừng sản xuất',
                'code' => 'rung_san_xuat',
                'group' => 'phanloairunghesinhthai',
            ],
            [
                'name' => 'Rừng kín thường xanh mưa ẩm lá nhiệt đới chủ yếu là cây lá rộng trên núi đất',
                'code' => 'rung_kin_thuong_xanh',
                'group' => 'phanloairunghesinhthaican',
            ],
            [
                'name' => 'Rừng tre nứa xen cây gỗ',
                'code' => 'rung_tre_nua',
                'group' => 'phanloairunghesinhthaican',
            ],
            [
                'name' => 'Rừng trên núi đá vôi',
                'code' => 'rung_nui_da_voi',
                'group' => 'phanloairunghesinhthaican',
            ],
            [
                'name' => 'Hệ sinh thái rừng trồng',
                'code' => 'rung_trong',
                'group' => 'phanloairunghesinhthaican',
            ],
            [
                'name' => 'Hệ sinh thái nước chảy',
                'code' => 'hst_nuoc_chay',
                'group' => 'phanloaihesinhthainuoc',
            ],
            [
                'name' => 'Hệ sinh thái nước đứng',
                'code' => 'hst_nuoc_dung',
                'group' => 'phanloaihesinhthainuoc',
            ],
            [
                'name' => 'Hệ sinh thái dân cư nông thôn',
                'code' => 'hst_dan_cu_nong_thon',
                'group' => 'phanloaihesinhthaidancu',
            ],
            [
                'name' => 'Hệ sinh thái dân cư đô thị',
                'code' => 'hst_dan_cu_do_thi',
                'group' => 'phanloaihesinhthaidancu',
            ],
            [
                'name' => 'Dịch vụ cung cấp',
                'code' => 'dich_vu_cung_cap',
                'group' => 'phanloaidichvuhesinhthai',
            ],
            [
                'name' => 'Dịch vụ điều tiết',
                'code' => 'dich_vu_dieu_tiet',
                'group' => 'phanloaidichvuhesinhthai',
            ],
            [
                'name' => 'Dịch vụ văn hóa',
                'code' => 'dich_vu_van_hoa',
                'group' => 'phanloaidichvuhesinhthai',
            ],
            [
                'name' => 'Dịch vụ hỗ trợ',
                'code' => 'dich_vu_ho_tro',
                'group' => 'phanloaidichvuhesinhthai',
            ],

            [
                'name' => 'Cơ sở nuôi, trồng loài thuộc Danh mục loài nguy cấp, quý, hiếm được ưu tiên bảo vệ',
                'code' => 'co_so_nuoi_trong',
                'group' => 'loaiHinhCoSoBaoTon',
            ],
            [
                'name' => 'Cơ sở cứu hộ loài ',
                'code' => 'co_so_cuu_ho',
                'group' => 'loaiHinhCoSoBaoTon',
            ],
            [
                'name' => 'Cơ sở cứu hộ loài hoang dã',
                'code' => 'co_so_cuu_ho',
                'group' => 'loaiHinhCoSoBaoTon',
            ],
            [
                'name' => 'Cơ sở lưu giữ giống cây trồng',
                'code' => 'co_so_luu_giu_cay_trong',
                'group' => 'loaiHinhCoSoBaoTon',
            ],
            [
                'name' => 'Cơ sở lưu giữ giống vật nuôi',
                'code' => 'co_so_luu_giu_giong_vat_nuoi',
                'group' => 'loaiHinhCoSoBaoTon',
            ],
            [
                'name' => 'Cơ sở lưu giữ vi sinh vật và nấm',
                'code' => 'co_so_luu_giu_nam',
                'group' => 'loaiHinhCoSoBaoTon',
            ],
            [
                'name' => 'Cơ sở lưu giữ bảo quản nguồn gen và mẫu vật di truyền',
                'code' => 'co_so_luu_giu_gen',
                'group' => 'loaiHinhCoSoBaoTon',
            ],
            [
                'name' => 'Cơ sở lưu giữ khác',
                'code' => 'co_so_luu_giu_khac',
                'group' => 'loaiHinhCoSoBaoTon',
            ],
            [
                'name' => 'Công nhận',
                'code' => 'cong_nhan',
                'group' => 'tinhtranghanhlang',
            ],
            [
                'name' => 'Đề xuất',
                'code' => 'de_xuat',
                'group' => 'tinhtranghanhlang',
            ],

            [
                'name' => 'Cấp địa phương',
                'code' => 'cap_dia_phuong',
                'group' => 'doQuanTrongDatNgapNuoc',
            ],
            [
                'name' => 'Cấp quốc gia',
                'code' => 'cap_quoc_gia',
                'group' => 'doQuanTrongDatNgapNuoc',
            ],
            [
                'name' => 'Cấp quốc tế',
                'code' => 'cap_quoc_te',
                'group' => 'doQuanTrongDatNgapNuoc',
            ],
            [
                'name' => 'Vi sinh vật',
                'code' => 'vi_sinh_vat',
                'group' => 'phanLoaiSinhVatNgoaiLai',
            ],
            [
                'name' => 'Động vật không xương sống',
                'code' => 'dong_vat_khong_xuong_song',
                'group' => 'phanLoaiSinhVatNgoaiLai',
            ],
            [
                'name' => 'Cá',
                'code' => 'ca',
                'group' => 'phanLoaiSinhVatNgoaiLai',
            ],
            [
                'name' => 'Lưỡng cư bò sát',
                'code' => 'luong_cu_bo_sat',
                'group' => 'phanLoaiSinhVatNgoaiLai',
            ],
            [
                'name' => 'Chim thú',
                'code' => 'chim_thu',
                'group' => 'phanLoaiSinhVatNgoaiLai',
            ],
            [
                'name' => 'Thực vật',
                'code' => 'thuc_vat',
                'group' => 'phanLoaiSinhVatNgoaiLai',
            ],

            [
                'name' => 'Loài ngoại lai xâm hại',
                'code' => 'loai_ngoai_lai_xam_hai',
                'group' => 'nguyCoXamHai',
            ],
            [
                'name' => 'Loài ngoại lai có nguy cơ xâm hại',
                'code' => 'loai_ngoai_lai_co_nguy_co_xam_hai',
                'group' => 'nguyCoXamHai',
            ],

            [
                'name' => 'Tỉnh',
                'code' => 'tinh',
                'group' => 'phanCapBangTinTongHop',
            ],
            [
                'name' => 'Huyện',
                'code' => 'huyen',
                'group' => 'phanCapBangTinTongHop',
            ],
            [
                'name' => 'Xã',
                'code' => 'xa',
                'group' => 'phanCapBangTinTongHop',
            ],
            [
                'name' => 'Cơ sở (VQG, KBT)',
                'code' => 'co_so',
                'group' => 'phanCapBangTinTongHop',
            ],
            [
                'name' => 'Thanh tra',
                'code' => 'thanh_tra',
                'group' => 'loai_hinh_doan_thanh_tra',
            ],
            [
                'name' => 'Kiểm tra',
                'code' => 'kiem_tra',
                'group' => 'loai_hinh_doan_thanh_tra',
            ],
            [
                'name' => 'Theo kế hoạch',
                'code' => 'theo_ke_hoach',
                'group' => 'che_do_thanh_tra',
            ],
            [
                'name' => 'Đột xuất',
                'code' => 'dot_xuat',
                'group' => 'che_do_thanh_tra',
            ],
        ];
        DB::beginTransaction();
        foreach ($data as $item) {
            $checkAvaliable = Lookup::where('name', $item['name'])->where('code', $item['code'])->where('group', $item['group'])->first();
            if (!$checkAvaliable) {
                Lookup::create($item);
            }
        }
        DB::commit();
        echo 'Seeder Done';
        return;
    }
}
